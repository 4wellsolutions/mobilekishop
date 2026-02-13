<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class SeedProductReviews extends Command
{
    protected $signature = 'reviews:seed
                            {--limit= : Process only N products (for testing)}
                            {--batch=5 : Number of products per API call}
                            {--fresh : Delete ALL existing reviews before starting}';

    protected $description = 'Seed AI-generated product-specific reviews using Gemini API';

    // Models to rotate through (each has separate 20 RPD quota)
    private array $models = [
        'gemini-2.5-flash',
        'gemini-2.5-flash-lite',
    ];

    private array $apiKeys = [];
    private int $currentSlotIndex = 0;
    private array $slots = [];       // [['key' => ..., 'model' => ...], ...]
    private array $slotUsage = [];   // track usage per slot
    private int $maxPerSlot = 18;    // stay under 20 RPD per key-model combo

    public function handle(): int
    {
        // Load API keys from .env
        $envKeys = env('GEMINI_API_KEYS', '');
        if (empty($envKeys)) {
            $this->error('Set GEMINI_API_KEYS in .env (comma-separated). Example:');
            $this->line('GEMINI_API_KEYS=AIzaSy...,AIzaSy...,AIzaSy...');
            return 1;
        }
        $this->apiKeys = array_map('trim', explode(',', $envKeys));

        // Build slots: every key × every model = separate quota bucket
        foreach ($this->apiKeys as $key) {
            foreach ($this->models as $model) {
                $this->slots[] = ['key' => $key, 'model' => $model];
                $this->slotUsage[] = 0;
            }
        }

        $totalSlots = count($this->slots);
        $totalCapacity = $totalSlots * $this->maxPerSlot;
        $this->info("Loaded " . count($this->apiKeys) . " API keys × " . count($this->models) . " models = {$totalSlots} slots ({$totalCapacity} API calls available)");

        // Delete all reviews if --fresh
        if ($this->option('fresh')) {
            $count = DB::table('reviews')->count();
            $this->warn("Deleting all {$count} existing reviews...");
            DB::table('reviews')->truncate();
            $this->info("All reviews deleted.");

            // Also reset progress file
            $stateFile = storage_path('app/review_seeder_progress.json');
            if (file_exists($stateFile)) {
                unlink($stateFile);
            }
        }

        $limit = $this->option('limit');
        $batchSize = (int) $this->option('batch');

        // Resume support
        $stateFile = storage_path('app/review_seeder_progress.json');
        $processedIds = [];
        if (file_exists($stateFile)) {
            $state = json_decode(file_get_contents($stateFile), true);
            $processedIds = $state['processed_ids'] ?? [];
            if (count($processedIds) > 0) {
                $this->info('Resuming: ' . count($processedIds) . ' products already processed.');
            }
        }

        // Get products
        $query = Product::query()
            ->select('id', 'name', 'brand_id')
            ->with(['brand:id,name', 'attributes'])
            ->whereNotIn('id', $processedIds);

        if ($limit) {
            $query->limit((int) $limit);
        }

        // Count total products efficiently without loading them all
        $totalProducts = $query->count();

        if ($totalProducts === 0) {
            $this->info('No products left to process. All done!');
            return 0;
        }

        $maxProducts = $totalCapacity * $batchSize;
        $this->info("Can process ~{$maxProducts} products this run. {$totalProducts} remaining.");

        $bar = $this->output->createProgressBar($totalProducts);
        $bar->start();

        // Efficiently process products in chunks to save memory
        $query->chunkById($batchSize, function ($products) use (&$totalReviews, &$processedCount, &$processedIds, $stateFile, $bar, $limit) {
            foreach ($products as $product) {
                // Check if we hit the limit (if set)
                if ($limit && $processedCount >= $limit) {
                    return false; // Stop chunking
                }
            }

            // We need to pass a collection to generateReviewsForProducts, which chunkById provides.
            // But verify if we need to check slot exhaustion inside the chunk loop.

            if ($this->currentSlotIndex >= count($this->slots)) {
                $this->newLine();
                $this->warn('All API slots exhausted. Will continue on next run.');
                return false; // Stop chunking
            }

            $reviews = $this->generateReviewsForProducts($products);

            if ($reviews === null || empty($reviews)) {
                $this->line(' [no reviews returned for batch, skipping]');
                $bar->advance($products->count());
                $processedCount += $products->count(); // Count as processed even if skipped
                return; // Continue to next chunk
            }

            // Insert reviews
            $insertRows = [];
            foreach ($reviews as $productId => $productReviews) {
                foreach ($productReviews as $review) {
                    $insertRows[] = [
                        'stars' => max(1, min(5, (int) ($review['stars'] ?? 5))),
                        'review' => trim($review['text'] ?? ''),
                        'name' => trim($review['name'] ?? 'Anonymous'),
                        'email' => null,
                        'product_id' => $productId,
                        'user_id' => null,
                        'is_active' => 1,
                        'approved_by' => null,
                        'created_at' => now()->subDays(rand(1, 365))->subHours(rand(0, 23))->subMinutes(rand(0, 59))->format('Y-m-d H:i:s'),
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                    ];
                }
                $processedIds[] = $productId;
                $totalReviews += count($productReviews);
            }

            if (!empty($insertRows)) {
                foreach (array_chunk($insertRows, 500) as $batch) {
                    DB::table('reviews')->insert($batch);
                }
            }

            $processedCount += $products->count();
            $bar->advance($products->count());

            // Save progress
            file_put_contents($stateFile, json_encode([
                'processed_ids' => $processedIds,
                'last_run' => now()->toDateTimeString(),
                'total_reviews' => $totalReviews,
            ]));

            // Rate limit: 5 RPM = 12s minimum between calls, use 13s to be safe
            sleep(13);
        });



        $bar->finish();
        $this->newLine(2);

        $remaining = $totalProducts - $processedCount;
        $this->info("Done this run! Seeded {$totalReviews} reviews across {$processedCount} products.");
        if ($remaining > 0) {
            $this->info("{$remaining} products remaining — will auto-continue on next scheduled run.");
        } else {
            $this->info("All products have been processed!");
            // Clean up state file
            if (file_exists($stateFile)) {
                unlink($stateFile);
            }
        }

        $this->table(
            ['Metric', 'Value'],
            [
                ['Processed this run', $processedCount],
                ['Reviews created', $totalReviews],
                ['Avg per product', $processedCount > 0 ? round($totalReviews / $processedCount, 1) : 0],
                ['Remaining', $remaining],
            ]
        );

        return 0;
    }

    private function generateReviewsForProducts($products): ?array
    {
        $productList = [];
        foreach ($products as $product) {
            $specs = [];
            foreach ($product->attributes as $attr) {
                $specs[] = $attr->name . ': ' . $attr->pivot->value;
            }
            $productList[$product->id] = [
                'name' => $product->name,
                'brand' => $product->brand ? $product->brand->name : 'Unknown',
                'specs' => implode(', ', array_slice($specs, 0, 8)),
            ];
        }

        $productDescriptions = '';
        foreach ($productList as $id => $info) {
            $productDescriptions .= "PRODUCT_ID:{$id} | {$info['name']} | Brand: {$info['brand']} | {$info['specs']}\n";
        }

        $prompt = <<<PROMPT
Generate realistic product reviews for mobile phones. For each product below, write 5 to 15 unique reviews.

RULES:
1. Write as REAL HUMANS. Include natural typos, missing punctuation, casual grammar, abbreviations, emoji, ALL CAPS excitement, run-on sentences
2. Each review MUST mention SPECIFIC features of THAT product (camera MP, battery mAh, screen size, processor, price, etc.)
3. Mix languages across reviews: ~40% English, ~15% Roman Urdu, ~15% Arabic script, ~10% Roman Hindi, ~10% Spanish/French/Turkish/Portuguese, ~10% one-liner emoji reviews
4. Stars distribution: 5★ (35%), 4★ (35%), 3★ (15%), 2★ (10%), 1★ (5%)
5. Vary length: some 1-line, some 2-3 sentences, some 4-5 sentences
6. IMPORTANT: The reviewer "name" MUST match the review language:
   - English review → English/Western name (e.g. "James Wilson", "Emily Brown", "Sophie Dubois")
   - Arabic review → Arabic name (e.g. "عمر الفهد", "نورا الشمري", "خالد المنصور")
   - Roman Urdu review → Pakistani name (e.g. "Ahmad Khan", "Fatima Noor", "Bilal Qureshi")
   - Roman Hindi review → Indian name (e.g. "Rahul Sharma", "Priya Singh", "Amit Kumar")
   - Spanish review → Spanish name (e.g. "Carlos García", "María López")
   - French review → French name (e.g. "Sophie Dubois", "Pierre Moreau")
   - Turkish review → Turkish name (e.g. "Mehmet Yılmaz", "Elif Demir")
   - Portuguese review → Portuguese name (e.g. "Lucas Silva", "Ana Costa")
   - Emoji/short review → Any global name
7. Use DIFFERENT names for each review, never repeat a name

PRODUCTS:
{$productDescriptions}

OUTPUT FORMAT (strict JSON only, no markdown):
{
  "PRODUCT_ID": [
    {"stars": 5, "name": "James Wilson", "text": "review text here"},
    {"stars": 4, "name": "أحمد سعيد", "text": "review text in arabic"}
  ]
}

Return ONLY valid JSON.
PROMPT;

        $response = $this->callGeminiAPI($prompt);
        if ($response === null)
            return null;

        // Clean up response — handle various formats
        $response = trim($response);

        // Remove markdown code blocks if present
        if (preg_match('/```(?:json)?\s*([\s\S]*?)\s*```/', $response, $matches)) {
            $response = $matches[1];
        }

        // Try to extract JSON object from response if there's extra text
        if (!str_starts_with($response, '{')) {
            $jsonStart = strpos($response, '{');
            if ($jsonStart !== false) {
                $response = substr($response, $jsonStart);
            }
        }

        // Find the last closing brace
        $lastBrace = strrpos($response, '}');
        if ($lastBrace !== false) {
            $response = substr($response, 0, $lastBrace + 1);
        }

        $parsed = json_decode($response, true);
        if (!is_array($parsed)) {
            $this->warn(' [JSON parse failed: ' . json_last_error_msg() . ']');
            $this->line(' [Raw response (first 500 chars): ' . mb_substr($response, 0, 500) . ']');
            return null;
        }


        $result = [];
        foreach ($productList as $id => $info) {
            $key = (string) $id;
            if (isset($parsed[$key]) && is_array($parsed[$key])) {
                $result[$id] = [];
                foreach ($parsed[$key] as $review) {
                    if (isset($review['text']) && isset($review['stars']) && isset($review['name'])) {
                        $text = trim($review['text']);
                        $name = trim($review['name']);
                        if (!empty($text) && strlen($text) > 5 && !empty($name)) {
                            $result[$id][] = [
                                'stars' => $review['stars'],
                                'text' => $text,
                                'name' => $name,
                            ];
                        }
                    }
                }
            }
        }

        return $result;
    }

    private function callGeminiAPI(string $prompt): ?string
    {
        $maxRetries = count($this->slots);

        for ($retry = 0; $retry < $maxRetries; $retry++) {
            if ($this->currentSlotIndex >= count($this->slots)) {
                return null;
            }

            $slot = $this->slots[$this->currentSlotIndex];
            $key = $slot['key'];
            $model = $slot['model'];

            // Check if this slot is exhausted
            if ($this->slotUsage[$this->currentSlotIndex] >= $this->maxPerSlot) {
                $this->currentSlotIndex++;
                continue;
            }

            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . $key;

            $data = json_encode([
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'temperature' => 0.95,
                    'maxOutputTokens' => 8000,
                    'responseMimeType' => 'application/json',
                ],
            ]);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 120);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $json = json_decode($response, true);
                if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
                    $this->slotUsage[$this->currentSlotIndex]++;
                    $this->line(' [slot ' . ($this->currentSlotIndex + 1) . '/' . count($this->slots) . " {$model} OK]");
                    return $json['candidates'][0]['content']['parts'][0]['text'];
                }
                // 200 but no content — check finish reason
                $finishReason = $json['candidates'][0]['finishReason'] ?? 'UNKNOWN';
                $this->line(" [slot " . ($this->currentSlotIndex + 1) . " {$model} empty: {$finishReason}]");
                if ($finishReason === 'SAFETY' || $finishReason === 'RECITATION') {
                    // Content was blocked, retry with same slot
                    continue;
                }
                // Other reason (e.g. rate limit disguised as 200) — try next slot
                $this->currentSlotIndex++;
                continue;
            }

            if ($httpCode === 429) {
                $this->currentSlotIndex++;
                if ($this->currentSlotIndex < count($this->slots)) {
                    $shortModel = $this->slots[$this->currentSlotIndex]['model'] ?? 'done';
                    $this->line(" [rate limited, switching to slot " . ($this->currentSlotIndex + 1) . ": {$shortModel}]");
                }
                continue;
            }

            if ($httpCode === 403 || $httpCode === 401) {
                $this->currentSlotIndex++;
                continue;
            }

            // Other error — wait and retry same slot
            sleep(5);
        }

        return null;
    }
}
