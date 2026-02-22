<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportWordPressBlog extends Command
{
    protected $signature = 'blog:import-wordpress
                            {file : Path to the WordPress WXR export XML file}
                            {--user-id=1 : The user_id to assign as the author}
                            {--status=published : Default status for imported posts (published/draft)}
                            {--download-images : Download and store featured images locally}
                            {--dry-run : Preview what would be imported without saving}';

    protected $description = 'Import blog posts from a WordPress XML (WXR) export file';

    private int $imported = 0;
    private int $skipped = 0;
    private int $errors = 0;
    private array $categoryMap = [];

    public function handle(): int
    {
        $filePath = $this->argument('file');

        // Resolve the file path
        if (!file_exists($filePath)) {
            $filePath = storage_path('app/' . $filePath);
        }
        if (!file_exists($filePath)) {
            $this->error("File not found: {$this->argument('file')}");
            $this->line('Place your WordPress export XML in storage/app/ or provide an absolute path.');
            return 1;
        }

        $this->info('');
        $this->info('╔════════════════════════════════════════════╗');
        $this->info('║   WordPress → MobileKiShop Blog Import    ║');
        $this->info('╚════════════════════════════════════════════╝');
        $this->info('');

        if ($this->option('dry-run')) {
            $this->warn('🔍 DRY RUN MODE — No data will be saved.');
            $this->info('');
        }

        // Parse XML
        $this->info('📄 Parsing XML file...');
        $xml = $this->parseWxrFile($filePath);

        if (!$xml) {
            $this->error('Failed to parse XML file. Make sure it is a valid WordPress WXR export.');
            return 1;
        }

        // Extract namespaces
        $namespaces = $xml->getNamespaces(true);
        $wp = $namespaces['wp'] ?? 'http://wordpress.org/export/1.2/';
        $content = $namespaces['content'] ?? 'http://purl.org/rss/1.0/modules/content/';
        $excerpt_ns = $namespaces['excerpt'] ?? 'http://wordpress.org/export/1.2/excerpt/';
        $dc = $namespaces['dc'] ?? 'http://purl.org/dc/elements/1.1/';

        $channel = $xml->channel;
        $items = $channel->item;
        $totalItems = count($items);

        $this->info("📊 Found {$totalItems} items in the export file.");
        $this->info('');

        // Step 1: Import categories
        $this->info('📁 Importing categories...');
        $this->importCategories($channel, $wp);

        // Step 2: Filter to only posts (skip pages, attachments, etc.)
        $posts = [];
        foreach ($items as $item) {
            $postType = (string) $item->children($wp)->post_type;
            if ($postType === 'post') {
                $posts[] = $item;
            }
        }

        $this->info("📝 Found " . count($posts) . " blog posts to import.");
        $this->info('');

        if (count($posts) === 0) {
            $this->warn('No blog posts found in the export file.');
            return 0;
        }

        // Step 3: Import posts
        $bar = $this->output->createProgressBar(count($posts));
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');
        $bar->start();

        foreach ($posts as $item) {
            $bar->setMessage('Importing...');
            $this->importPost($item, $wp, $content, $excerpt_ns, $dc);
            $bar->advance();
        }

        $bar->setMessage('Done!');
        $bar->finish();

        // Summary
        $this->info('');
        $this->info('');
        $this->info('═══════════════════════════════════════');
        $this->info("✅ Imported:  {$this->imported}");
        $this->info("⏭️  Skipped:   {$this->skipped} (already exist)");
        if ($this->errors > 0) {
            $this->error("❌ Errors:    {$this->errors}");
        }
        $this->info('═══════════════════════════════════════');
        $this->info('');

        if ($this->option('dry-run')) {
            $this->warn('This was a dry run. Run again without --dry-run to actually import.');
        }

        return 0;
    }

    /**
     * Parse the WXR XML file.
     */
    private function parseWxrFile(string $path): ?\SimpleXMLElement
    {
        try {
            libxml_use_internal_errors(true);
            $content = file_get_contents($path);

            // Remove any BOM
            $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);

            $xml = simplexml_load_string($content);

            if ($xml === false) {
                foreach (libxml_get_errors() as $error) {
                    $this->error("  XML Error: {$error->message}");
                }
                libxml_clear_errors();
                return null;
            }

            return $xml;
        } catch (\Exception $e) {
            $this->error("Parse error: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Import WordPress categories → BlogCategory.
     */
    private function importCategories(\SimpleXMLElement $channel, string $wpNs): void
    {
        $wpChildren = $channel->children($wpNs);
        $count = 0;

        foreach ($wpChildren->category as $wpCat) {
            $catSlug = (string) $wpCat->cat_name;
            $catNicename = (string) $wpCat->category_nicename;

            // Clean up CDATA
            $catSlug = trim($catSlug);
            $catNicename = trim($catNicename);

            if (empty($catSlug))
                continue;

            $slug = $catNicename ?: Str::slug($catSlug);

            if ($this->option('dry-run')) {
                $this->categoryMap[$catSlug] = null;
                $count++;
                continue;
            }

            $category = BlogCategory::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $catSlug,
                    'slug' => $slug,
                    'description' => '',
                ]
            );

            $this->categoryMap[$catSlug] = $category->id;
            $count++;
        }

        $this->info("  → {$count} categories processed.");
    }

    /**
     * Import a single WordPress post → Blog.
     */
    private function importPost(\SimpleXMLElement $item, string $wpNs, string $contentNs, string $excerptNs, string $dcNs): void
    {
        try {
            $wp = $item->children($wpNs);
            $contentEncoded = $item->children($contentNs);
            $excerptEncoded = $item->children($excerptNs);

            $title = trim((string) $item->title);
            $slug = trim((string) $wp->post_name);
            $body = trim((string) $contentEncoded->encoded);
            $excerpt = trim((string) $excerptEncoded->encoded);
            $wpStatus = trim((string) $wp->status);
            $postDate = trim((string) $wp->post_date);

            // Skip empty titles
            if (empty($title)) {
                $this->skipped++;
                return;
            }

            // Check if slug already exists
            $slug = $slug ?: Str::slug($title);
            if (!$this->option('dry-run') && Blog::where('slug', $slug)->exists()) {
                $this->skipped++;
                return;
            }

            // Map status
            $status = $wpStatus === 'publish' ? 'published' : ($this->option('status') === 'published' ? 'draft' : $this->option('status'));

            // Parse published date
            $publishedAt = null;
            if ($postDate && $postDate !== '0000-00-00 00:00:00') {
                try {
                    $publishedAt = \Carbon\Carbon::parse($postDate);
                } catch (\Exception $e) {
                    $publishedAt = now();
                }
            }

            // Find category
            $categoryId = null;
            foreach ($item->category as $cat) {
                $domain = (string) $cat->attributes()->domain;
                if ($domain === 'category') {
                    $catName = trim((string) $cat);
                    if (isset($this->categoryMap[$catName])) {
                        $categoryId = $this->categoryMap[$catName];
                        break;
                    }
                    // Try to find by name if not in map
                    $found = BlogCategory::where('name', $catName)->first();
                    if ($found) {
                        $categoryId = $found->id;
                        $this->categoryMap[$catName] = $found->id;
                        break;
                    }
                    // Create category on the fly
                    if (!$this->option('dry-run')) {
                        $newCat = BlogCategory::create([
                            'name' => $catName,
                            'slug' => BlogCategory::generateUniqueSlug($catName),
                        ]);
                        $categoryId = $newCat->id;
                        $this->categoryMap[$catName] = $newCat->id;
                    }
                    break;
                }
            }

            // Convert WordPress content to clean HTML
            $body = $this->convertWordPressContent($body);

            // Extract featured image from wp:postmeta
            $featuredImage = $this->extractFeaturedImage($item, $wpNs);

            // Download image if requested
            if ($featuredImage && $this->option('download-images') && !$this->option('dry-run')) {
                $featuredImage = $this->downloadImage($featuredImage, $slug);
            }

            // Generate meta fields
            $metaTitle = $title;
            $metaDescription = $excerpt ?: Str::limit(strip_tags($body), 155);

            if ($this->option('dry-run')) {
                $this->imported++;
                return;
            }

            // Create the blog post
            Blog::create([
                'title' => $title,
                'slug' => $slug,
                'excerpt' => $excerpt ?: null,
                'body' => $body,
                'featured_image' => $featuredImage,
                'meta_title' => $metaTitle,
                'meta_description' => $metaDescription,
                'status' => $status,
                'published_at' => $publishedAt,
                'user_id' => (int) $this->option('user-id'),
                'blog_category_id' => $categoryId,
            ]);

            $this->imported++;
        } catch (\Exception $e) {
            $this->errors++;
            $this->newLine();
            $this->error("  Error importing '{$title}': {$e->getMessage()}");
        }
    }

    /**
     * Convert WordPress content (shortcodes, blocks) to clean HTML.
     */
    private function convertWordPressContent(string $content): string
    {
        // Convert WordPress double line breaks to proper paragraphs (wpautop behavior)
        $content = $this->wpautop($content);

        // Remove WordPress block comments <!-- wp:paragraph --> etc.
        $content = preg_replace('/<!--\s*\/?wp:[^>]*-->/s', '', $content);

        // Convert WordPress [caption] shortcode
        $content = preg_replace(
            '/\[caption[^\]]*\](.*?)\[\/caption\]/s',
            '<figure>$1</figure>',
            $content
        );

        // Remove common shortcodes that won't work outside WP
        $wpShortcodes = ['gallery', 'embed', 'audio', 'video', 'playlist', 'wp_caption', 'vc_row', 'vc_column', 'vc_column_text'];
        foreach ($wpShortcodes as $sc) {
            $content = preg_replace("/\[{$sc}[^\]]*\](.*?)\[\/{$sc}\]/s", '$1', $content);
            $content = preg_replace("/\[{$sc}[^\]]*\/?\]/s", '', $content);
        }

        // Clean up remaining shortcodes: [something attr="val"]...[/something]
        // Be conservative - only remove if they look like WordPress shortcodes
        $content = preg_replace('/\[\/?[a-z_]+(?:\s[^\]]+)?\]/i', '', $content);

        // Clean up excess whitespace
        $content = preg_replace('/\n{3,}/', "\n\n", $content);
        $content = trim($content);

        return $content;
    }

    /**
     * Replicate WordPress wpautop() — converts double line breaks to paragraphs.
     */
    private function wpautop(string $text): string
    {
        if (trim($text) === '')
            return '';

        // If content already has HTML block elements, don't double-wrap
        if (preg_match('/<(p|div|table|h[1-6]|ul|ol|blockquote|figure|section|article)/i', $text)) {
            return $text;
        }

        // Standardize line breaks
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // Split by double newlines
        $paragraphs = preg_split('/\n\s*\n/', $text);

        $output = '';
        foreach ($paragraphs as $p) {
            $p = trim($p);
            if ($p === '')
                continue;

            // Convert single newlines to <br>
            $p = nl2br($p);

            $output .= "<p>{$p}</p>\n";
        }

        return $output;
    }

    /**
     * Try to extract the featured image URL from WordPress post meta.
     */
    private function extractFeaturedImage(\SimpleXMLElement $item, string $wpNs): ?string
    {
        $wp = $item->children($wpNs);

        // Look for _thumbnail_id in postmeta
        foreach ($wp->postmeta as $meta) {
            $key = (string) $meta->meta_key;
            if ($key === '_thumbnail_id') {
                // The value is an attachment ID — we'd need to find the attachment URL
                // This is complex in WXR, so we'll try the enclosure or just skip
                break;
            }
        }

        // Check if there's an enclosure with image
        if (isset($item->enclosure)) {
            $url = (string) $item->enclosure->attributes()->url;
            if ($url && preg_match('/\.(jpg|jpeg|png|gif|webp)/i', $url)) {
                return $url;
            }
        }

        // Try to extract first image from content as fallback
        $content = '';
        $namespaces = $item->getNamespaces(true);
        $contentNs = $namespaces['content'] ?? 'http://purl.org/rss/1.0/modules/content/';
        $contentEncoded = $item->children($contentNs);
        if (isset($contentEncoded->encoded)) {
            $content = (string) $contentEncoded->encoded;
        }

        if ($content && preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', $content, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Download an image and store it locally.
     */
    private function downloadImage(string $url, string $slug): ?string
    {
        try {
            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $filename = "blog/{$slug}-featured.{$extension}";

            // Download the image
            $context = stream_context_create([
                'http' => [
                    'timeout' => 15,
                    'user_agent' => 'Mozilla/5.0 (compatible; MKS Blog Import)',
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);

            $imageData = @file_get_contents($url, false, $context);

            if ($imageData === false) {
                return $url; // Keep original URL as fallback
            }

            Storage::disk('public')->put($filename, $imageData);

            return Storage::url($filename);
        } catch (\Exception $e) {
            return $url; // Keep original URL as fallback
        }
    }
}
