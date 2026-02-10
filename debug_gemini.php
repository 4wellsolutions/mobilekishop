<?php
// Debug script — upload to server and run: php debug_gemini.php
// This will show exactly what the API returns

$key = 'AIzaSyDCdTErb1VmAyM4eCasVv4_fMAGM5oV2-Q'; // Key 3

$prompt = <<<PROMPT
Generate realistic product reviews for mobile phones. For each product below, write 5 to 10 unique reviews.

RULES:
1. Write as REAL HUMANS with natural language
2. Each review MUST mention SPECIFIC features of THAT product
3. Mix languages: ~40% English, ~20% Roman Urdu, ~15% Arabic, ~10% Roman Hindi, ~15% short/emoji
4. The reviewer "name" MUST match the review language
5. Stars: mostly 4-5 stars (70%), some 3 (15%), few 1-2 (15%)

PRODUCTS:
PRODUCT_ID:1 | Samsung Galaxy Z Fold 3 | Brand: Samsung | model: Samsung Galaxy Z Fold 3

OUTPUT FORMAT (strict JSON only, no markdown):
{
  "1": [
    {"stars": 5, "name": "James Wilson", "text": "review text here"}
  ]
}

Return ONLY valid JSON.
PROMPT;

$models = ['gemini-2.5-flash', 'gemini-2.5-flash-lite'];

foreach ($models as $model) {
    echo "\n=== Testing {$model} ===\n";
    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . $key;

    $data = json_encode([
        'contents' => [['parts' => [['text' => $prompt]]]],
        'generationConfig' => [
            'temperature' => 0.95,
            'maxOutputTokens' => 4000,
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
    $curlErr = curl_error($ch);
    curl_close($ch);

    echo "HTTP: {$httpCode}\n";
    if ($curlErr)
        echo "cURL Error: {$curlErr}\n";

    if ($httpCode !== 200) {
        echo "Error response: " . substr($response, 0, 500) . "\n";
        continue;
    }

    $json = json_decode($response, true);

    // Check if we got candidates
    if (!isset($json['candidates'][0])) {
        echo "No candidates in response!\n";
        echo "Full response: " . substr($response, 0, 500) . "\n";
        continue;
    }

    $candidate = $json['candidates'][0];
    echo "Finish reason: " . ($candidate['finishReason'] ?? 'N/A') . "\n";

    if (!isset($candidate['content']['parts'][0]['text'])) {
        echo "No text in candidate!\n";
        echo "Candidate keys: " . implode(', ', array_keys($candidate)) . "\n";
        echo "Full candidate: " . json_encode($candidate) . "\n";
        continue;
    }

    $text = $candidate['content']['parts'][0]['text'];
    echo "Raw text length: " . strlen($text) . "\n";
    echo "First 1000 chars:\n" . mb_substr($text, 0, 1000) . "\n\n";

    // Try parsing
    $parsed = json_decode($text, true);
    if ($parsed) {
        echo "JSON PARSED OK!\n";
        foreach ($parsed as $pid => $reviews) {
            echo "Product {$pid}: " . count($reviews) . " reviews\n";
            foreach ($reviews as $r) {
                echo "  " . ($r['stars'] ?? '?') . "* | " . ($r['name'] ?? 'NO NAME') . " | " . mb_substr($r['text'] ?? '', 0, 50) . "\n";
            }
        }
    } else {
        echo "JSON PARSE FAILED: " . json_last_error_msg() . "\n";
    }

    break; // Stop after first working model
}
