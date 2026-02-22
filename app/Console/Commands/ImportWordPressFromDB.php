<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportWordPressFromDB extends Command
{
    protected $signature = 'blog:import-wp-db
                            {--host=127.0.0.1 : WordPress DB host}
                            {--port=3306 : WordPress DB port}
                            {--database= : WordPress DB name}
                            {--username=root : WordPress DB username}
                            {--password= : WordPress DB password}
                            {--prefix=wp_ : WordPress table prefix}
                            {--user-id=1 : The user_id to assign as the author}
                            {--download-images : Download and store featured images locally}
                            {--dry-run : Preview what would be imported without saving}
                            {--post-status=publish : WordPress post status to import (publish, draft, or all)}
                            {--limit=0 : Limit number of posts to import (0 = all)}';

    protected $description = 'Import blog posts directly from a WordPress MySQL database (one-time)';

    private int $imported = 0;
    private int $skipped = 0;
    private int $errors = 0;
    private array $categoryMap = [];
    private array $userMap = [];

    public function handle(): int
    {
        $this->info('');
        $this->info('╔════════════════════════════════════════════════╗');
        $this->info('║  WordPress DB → MobileKiShop Blog Import      ║');
        $this->info('╚════════════════════════════════════════════════╝');
        $this->info('');

        // Collect DB credentials interactively if not provided
        $dbHost = $this->option('host');
        $dbPort = $this->option('port');
        $dbName = $this->option('database') ?: $this->ask('WordPress database name');
        $dbUser = $this->option('username');
        $dbPass = $this->option('password') ?: $this->secret('WordPress database password (leave empty if none)') ?: '';
        $prefix = $this->option('prefix');

        // Set up runtime connection
        config([
            'database.connections.wordpress_import' => [
                'driver' => 'mysql',
                'host' => $dbHost,
                'port' => $dbPort,
                'database' => $dbName,
                'username' => $dbUser,
                'password' => $dbPass,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => $prefix,
                'strict' => false,
            ]
        ]);

        // Test connection
        $this->info('🔌 Connecting to WordPress database...');
        try {
            $wpDb = DB::connection('wordpress_import');
            $wpDb->getPdo();
            $this->info("  ✅ Connected to: {$dbName}@{$dbHost}");
        } catch (\Exception $e) {
            $this->error('  ❌ Failed to connect!');
            $this->error("  {$e->getMessage()}");
            return 1;
        }

        if ($this->option('dry-run')) {
            $this->warn('🔍 DRY RUN — No data will be saved.');
            $this->info('');
        }


        // Step 1: Import authors
        $this->info('👤 Importing authors...');
        $this->importAuthors($wpDb, $prefix);
        $this->info('');

        // Step 2: Import categories
        $this->info('📁 Importing categories...');
        $this->importCategories($wpDb, $prefix);
        $this->info('');

        // Step 3: Query posts
        $postStatus = $this->option('post-status');
        $query = $wpDb->table('posts')
            ->where('post_type', 'post')
            ->whereNotIn('post_status', ['auto-draft', 'inherit', 'trash']);

        if ($postStatus !== 'all') {
            $query->where('post_status', $postStatus);
        }

        $limit = (int) $this->option('limit');
        if ($limit > 0) {
            $query->limit($limit);
        }

        $posts = $query->orderBy('post_date', 'asc')->get();
        $this->info("📝 Found {$posts->count()} blog posts to import." . ($limit > 0 ? " (limited to {$limit})" : ''));
        $this->info('');

        if ($posts->isEmpty()) {
            $this->warn('No posts found matching criteria.');
            return 0;
        }

        // Step 4: Import posts
        $bar = $this->output->createProgressBar($posts->count());
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');
        $bar->start();

        foreach ($posts as $wpPost) {
            $bar->setMessage(Str::limit($wpPost->post_title, 40));
            $this->importPost($wpDb, $prefix, $wpPost);
            $bar->advance();
        }

        $bar->setMessage('Done!');
        $bar->finish();

        // Summary
        $this->info('');
        $this->info('');
        $this->info('═══════════════════════════════════════════');
        $this->info("  ✅ Imported:  {$this->imported}");
        $this->info("  ⏭️  Skipped:   {$this->skipped} (already exist)");
        if ($this->errors > 0) {
            $this->error("  ❌ Errors:    {$this->errors}");
        }
        $this->info('═══════════════════════════════════════════');
        $this->info('');

        if ($this->option('dry-run')) {
            $this->warn('This was a dry run. Run without --dry-run to import.');
        }

        return 0;
    }

    /**
     * Import WordPress authors → Laravel User.
     */
    private function importAuthors($wpDb, string $prefix): void
    {
        $authors = $wpDb->table('users')
            ->select('ID', 'user_login', 'user_email', 'display_name')
            ->get();

        $count = 0;
        foreach ($authors as $wpUser) {
            if ($this->option('dry-run')) {
                $this->userMap[$wpUser->ID] = null;
                $count++;
                continue;
            }

            // Try to find existing Laravel user by email
            $user = \App\Models\User::where('email', $wpUser->user_email)->first();

            if (!$user) {
                $user = \App\Models\User::create([
                    'name' => $wpUser->display_name ?: $wpUser->user_login,
                    'email' => $wpUser->user_email,
                    'password' => bcrypt(Str::random(16)), // random password, user can reset
                ]);
                $this->info("  + Created user: {$user->name} ({$user->email})");
            } else {
                $this->info("  ✓ Matched user: {$user->name} ({$user->email})");
            }

            $this->userMap[$wpUser->ID] = $user->id;
            $count++;
        }

        $this->info("  → {$count} authors processed.");
    }

    /**
     * Import WordPress categories → BlogCategory.
     */
    private function importCategories($wpDb, string $prefix): void
    {
        $categories = $wpDb->table('terms')
            ->join('term_taxonomy', 'terms.term_id', '=', 'term_taxonomy.term_id')
            ->where('term_taxonomy.taxonomy', 'category')
            ->where('terms.name', '!=', 'Uncategorized')
            ->select('terms.term_id', 'terms.name', 'terms.slug', 'term_taxonomy.description')
            ->get();

        $count = 0;
        foreach ($categories as $wpCat) {
            if ($this->option('dry-run')) {
                $this->categoryMap[$wpCat->term_id] = null;
                $count++;
                continue;
            }

            $category = BlogCategory::firstOrCreate(
                ['slug' => $wpCat->slug],
                [
                    'name' => $wpCat->name,
                    'slug' => $wpCat->slug,
                    'description' => $wpCat->description ?: '',
                ]
            );

            $this->categoryMap[$wpCat->term_id] = $category->id;
            $count++;
        }

        $this->info("  → {$count} categories processed.");
    }

    /**
     * Import a single WordPress post → Blog.
     */
    private function importPost($wpDb, string $prefix, object $wpPost): void
    {
        try {
            $title = trim($wpPost->post_title);
            $slug = trim($wpPost->post_name) ?: Str::slug($title);

            // Skip empty titles
            if (empty($title)) {
                $this->skipped++;
                return;
            }

            // Skip duplicates
            if (!$this->option('dry-run') && Blog::where('slug', $slug)->exists()) {
                $this->skipped++;
                return;
            }

            // Body content
            $body = $wpPost->post_content;
            $body = $this->convertWordPressContent($body);

            // Excerpt
            $excerpt = trim($wpPost->post_excerpt) ?: null;

            // Status mapping
            $status = $wpPost->post_status === 'publish' ? 'published' : 'draft';

            // Published date
            $publishedAt = null;
            if ($wpPost->post_date && $wpPost->post_date !== '0000-00-00 00:00:00') {
                $publishedAt = \Carbon\Carbon::parse($wpPost->post_date);
            }

            // Author: map WP post_author → Laravel user
            $userId = $this->userMap[$wpPost->post_author] ?? (int) $this->option('user-id');

            // Category: find via term_relationships
            $categoryId = $this->getPostCategory($wpDb, $prefix, $wpPost->ID);

            // Featured image
            $featuredImage = $this->getPostFeaturedImage($wpDb, $prefix, $wpPost->ID);

            // Download image if requested
            if ($featuredImage && $this->option('download-images') && !$this->option('dry-run')) {
                $featuredImage = $this->downloadImage($featuredImage, $slug);
            }

            // SEO meta from Yoast / RankMath / AIO SEO
            $metaFields = $this->getPostSeoMeta($wpDb, $prefix, $wpPost->ID, $title, $body);

            if ($this->option('dry-run')) {
                $this->imported++;
                return;
            }

            Blog::create([
                'title' => $title,
                'slug' => $slug,
                'excerpt' => $excerpt,
                'body' => $body,
                'featured_image' => $featuredImage,
                'meta_title' => $metaFields['meta_title'],
                'meta_description' => $metaFields['meta_description'],
                'status' => $status,
                'published_at' => $publishedAt,
                'user_id' => $userId,
                'blog_category_id' => $categoryId,
            ]);

            $this->imported++;
        } catch (\Exception $e) {
            $this->errors++;
            $this->newLine();
            $this->error("  Error importing '{$wpPost->post_title}': {$e->getMessage()}");
        }
    }

    /**
     * Find the primary category for a WordPress post.
     */
    private function getPostCategory($wpDb, string $prefix, int $postId): ?int
    {
        $termRelation = $wpDb->table('term_relationships')
            ->join('term_taxonomy', 'term_relationships.term_taxonomy_id', '=', 'term_taxonomy.term_taxonomy_id')
            ->where('term_relationships.object_id', $postId)
            ->where('term_taxonomy.taxonomy', 'category')
            ->select('term_taxonomy.term_id')
            ->first();

        if ($termRelation && isset($this->categoryMap[$termRelation->term_id])) {
            return $this->categoryMap[$termRelation->term_id];
        }

        // If category wasn't pre-imported (e.g. "Uncategorized"), try to find or create
        if ($termRelation) {
            $wpTerm = $wpDb->table('terms')->where('term_id', $termRelation->term_id)->first();
            if ($wpTerm && $wpTerm->name !== 'Uncategorized') {
                if (!$this->option('dry-run')) {
                    $cat = BlogCategory::firstOrCreate(
                        ['slug' => $wpTerm->slug],
                        ['name' => $wpTerm->name, 'slug' => $wpTerm->slug, 'description' => '']
                    );
                    $this->categoryMap[$termRelation->term_id] = $cat->id;
                    return $cat->id;
                }
            }
        }

        return null;
    }

    /**
     * Get the featured image URL from WordPress post meta.
     */
    private function getPostFeaturedImage($wpDb, string $prefix, int $postId): ?string
    {
        // Get _thumbnail_id from postmeta
        $thumbMeta = $wpDb->table('postmeta')
            ->where('post_id', $postId)
            ->where('meta_key', '_thumbnail_id')
            ->first();

        if (!$thumbMeta)
            return null;

        $attachmentId = (int) $thumbMeta->meta_value;

        // Get the attachment post → guid is the full URL
        $attachment = $wpDb->table('posts')
            ->where('ID', $attachmentId)
            ->where('post_type', 'attachment')
            ->first();

        if ($attachment) {
            return $attachment->guid;
        }

        // Fallback: check _wp_attached_file meta
        $fileMeta = $wpDb->table('postmeta')
            ->where('post_id', $attachmentId)
            ->where('meta_key', '_wp_attached_file')
            ->first();

        if ($fileMeta) {
            // This is a relative path like "2024/01/image.jpg"
            // The actual URL depends on WP upload dir — return as-is
            return $fileMeta->meta_value;
        }

        return null;
    }

    /**
     * Extract SEO meta from Yoast, RankMath, or fallback to post data.
     */
    private function getPostSeoMeta($wpDb, string $prefix, int $postId, string $title, string $body): array
    {
        $meta = $wpDb->table('postmeta')
            ->where('post_id', $postId)
            ->whereIn('meta_key', [
                // Yoast
                '_yoast_wpseo_title',
                '_yoast_wpseo_metadesc',
                // RankMath
                'rank_math_title',
                'rank_math_description',
                // All in One SEO
                '_aioseo_title',
                '_aioseo_description',
            ])
            ->pluck('meta_value', 'meta_key');

        $metaTitle = $meta->get('_yoast_wpseo_title')
            ?? $meta->get('rank_math_title')
            ?? $meta->get('_aioseo_title')
            ?? $title;

        $metaDescription = $meta->get('_yoast_wpseo_metadesc')
            ?? $meta->get('rank_math_description')
            ?? $meta->get('_aioseo_description')
            ?? Str::limit(strip_tags($body), 155);

        // Clean Yoast/RankMath variable placeholders like %%title%% %%sep%% %%sitename%%
        $metaTitle = preg_replace('/%%[a-z_]+%%/i', '', $metaTitle);
        $metaTitle = trim($metaTitle, ' -–|') ?: $title;

        return [
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
        ];
    }

    /**
     * Convert WordPress content to clean, semantic HTML.
     * Strips all plugin junk: Elementor, WPBakery, Divi, shortcodes, inline styles, etc.
     */
    private function convertWordPressContent(string $content): string
    {
        if (empty(trim($content)))
            return '';

        // ──────────────────────────────────────────────
        // Phase 1: Remove WordPress block editor comments
        // ──────────────────────────────────────────────
        $content = preg_replace('/<!--\s*\/?wp:[^>]*-->/s', '', $content);
        // Remove generic HTML comments (but keep conditional IE ones just in case)
        $content = preg_replace('/<!--(?!\[if).*?-->/s', '', $content);

        // ──────────────────────────────────────────────
        // Phase 2: Strip ALL shortcodes (plugin-generated)
        // ──────────────────────────────────────────────
        // WPBakery / Visual Composer
        $vcShortcodes = [
            'vc_row',
            'vc_row_inner',
            'vc_column',
            'vc_column_inner',
            'vc_column_text',
            'vc_section',
            'vc_separator',
            'vc_empty_space',
            'vc_single_image',
            'vc_btn',
            'vc_custom_heading',
            'vc_widget_sidebar',
            'vc_raw_html',
            'vc_toggle',
            'vc_tabs',
            'vc_tab',
            'vc_accordion',
            'vc_accordion_tab',
            'vc_video',
            'vc_gallery',
            'vc_images_carousel',
            'vc_cta',
        ];
        // Elementor (sometimes stored as shortcodes)
        $elShortcodes = [
            'elementor-template',
            'elementor_template',
        ];
        // Divi
        $diviShortcodes = [
            'et_pb_section',
            'et_pb_row',
            'et_pb_column',
            'et_pb_text',
            'et_pb_image',
            'et_pb_button',
            'et_pb_divider',
            'et_pb_sidebar',
            'et_pb_cta',
            'et_pb_slider',
            'et_pb_slide',
            'et_pb_tabs',
            'et_pb_tab',
            'et_pb_accordion',
            'et_pb_toggle',
            'et_pb_blurb',
            'et_pb_video',
            'et_pb_gallery',
            'et_pb_blog',
            'et_pb_code',
            'et_pb_fullwidth_section',
            'et_pb_fullwidth_header',
        ];
        // Beaver Builder
        $bbShortcodes = ['fl_builder_insert_layout'];
        // General WordPress
        $wpShortcodes = [
            'gallery',
            'embed',
            'audio',
            'video',
            'playlist',
            'wp_caption',
            'caption',
            'contact-form-7',
            'contact-form',
            'wpcf7',
            'gravityform',
            'gravityforms',
            'wpforms',
            'ninja_form',
            'mailchimp',
            'mc4wp_form',
            'newsletter',
            'subscribe',
            'social_icons',
            'share',
            'follow',
            'yoast-breadcrumb',
            'woocommerce',
            'products',
            'product',
            'add_to_cart',
            'su_heading',
            'su_button',
            'su_note',
            'su_box',
            'su_row',
            'su_column',
            'su_spacer',
            'su_divider',
            'su_tabs',
            'su_tab',
            'toc',
            'tableofcontents',
            'table_of_contents',
            'adinserter',
            'ad',
            'adrotate',
        ];

        $allShortcodes = array_merge($vcShortcodes, $elShortcodes, $diviShortcodes, $bbShortcodes, $wpShortcodes);

        // First pass: Strip shortcodes that wrap content (keep inner content)
        foreach ($allShortcodes as $sc) {
            $content = preg_replace("/\[{$sc}[^\]]*\](.*?)\[\/{$sc}\]/si", '$1', $content);
        }
        // Second pass: Strip self-closing shortcodes
        foreach ($allShortcodes as $sc) {
            $content = preg_replace("/\[{$sc}[^\]]*\/?\]/si", '', $content);
            $content = preg_replace("/\[\/{$sc}\]/si", '', $content);
        }

        // Catch-all: Remove ANY remaining shortcodes [something ...] or [/something]
        // Be aggressive since we're importing, not serving WP
        $content = preg_replace('/\[\/?[a-z_][a-z0-9_-]*(?:\s[^\]]+)?\]/i', '', $content);

        // ──────────────────────────────────────────────
        // Phase 3: Strip plugin-specific HTML wrappers
        // ──────────────────────────────────────────────

        // Use DOMDocument for robust HTML manipulation
        if (preg_match('/<[^>]+>/', $content)) {
            $content = $this->cleanHtmlDom($content);
        }

        // ──────────────────────────────────────────────
        // Phase 4: Handle wpautop (convert newlines to <p>)
        // ──────────────────────────────────────────────
        $content = $this->wpautop($content);

        // ──────────────────────────────────────────────
        // Phase 5: Final cleanup
        // ──────────────────────────────────────────────
        // Remove empty paragraphs and tags
        $content = preg_replace('/<p>\s*<\/p>/i', '', $content);
        $content = preg_replace('/<div>\s*<\/div>/i', '', $content);
        $content = preg_replace('/<span>\s*<\/span>/i', '', $content);

        // Remove &nbsp; only paragraphs
        $content = preg_replace('/<p>\s*(&nbsp;\s*)+<\/p>/i', '', $content);

        // Clean up excessive whitespace/newlines
        $content = preg_replace('/\n{3,}/', "\n\n", $content);
        $content = preg_replace('/(\s*<br\s*\/?>\s*){3,}/i', '<br><br>', $content);

        return trim($content);
    }

    /**
     * Use DOMDocument to strip inline styles, data attrs, plugin classes, and empty wrappers.
     */
    private function cleanHtmlDom(string $html): string
    {
        // Wrap in a root element for DOMDocument
        $wrapped = '<div id="__wp_clean_root__">' . $html . '</div>';

        $doc = new \DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $doc->loadHTML(
            '<?xml encoding="UTF-8">' . mb_convert_encoding($wrapped, 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR
        );
        libxml_clear_errors();

        // Collect all elements
        $xpath = new \DOMXPath($doc);

        // ── Remove plugin-specific elements entirely ──
        $removeSelectors = [
            // Elementor wrappers
            '//*[contains(@class,"elementor-widget-wrap")]',
            '//*[contains(@class,"elementor-background-overlay")]',
            '//*[contains(@class,"elementor-shape")]',
            // Ad / Newsletter plugin injections
            '//*[contains(@class,"adinserter")]',
            '//*[contains(@class,"adsbygoogle")]',
            '//*[contains(@class,"newsletter-signup")]',
            '//*[contains(@class,"wp-block-embed")]',
            // Social sharing injected by plugins
            '//*[contains(@class,"sharedaddy")]',
            '//*[contains(@class,"jp-relatedposts")]',
            '//*[contains(@class,"social-share")]',
            // Print / PDF buttons
            '//*[contains(@class,"print-link")]',
            // Noscript tags (tracking pixels etc)
            '//noscript',
            // Scripts and styles
            '//script',
            '//style',
            // iframes (unless YouTube/Vimeo)
            '//iframe[not(contains(@src,"youtube")) and not(contains(@src,"vimeo"))]',
        ];

        foreach ($removeSelectors as $selector) {
            $nodes = $xpath->query($selector);
            if ($nodes) {
                foreach ($nodes as $node) {
                    $node->parentNode->removeChild($node);
                }
            }
        }

        // ── Clean attributes on ALL remaining elements ──
        $allElements = $xpath->query('//*');
        $allowedAttrs = [
            'href',
            'src',
            'alt',
            'title',
            'width',
            'height',
            'colspan',
            'rowspan',
            'target',
            'rel',
            'id',
            'headers',
            'scope',
            'type',
            'start'
        ];

        foreach ($allElements as $el) {
            // Skip the root wrapper
            if ($el->getAttribute('id') === '__wp_clean_root__')
                continue;

            // Collect attributes to remove
            $attrsToRemove = [];
            foreach ($el->attributes as $attr) {
                $name = strtolower($attr->name);
                // Remove: style, data-*, class, role, aria-*, onclick, on*, wp-specific
                if (!in_array($name, $allowedAttrs)) {
                    $attrsToRemove[] = $attr->name;
                }
            }
            foreach ($attrsToRemove as $attrName) {
                $el->removeAttribute($attrName);
            }

            // Unwrap meaningless wrapper divs/sections/spans (keep content, remove tag)
            $tag = strtolower($el->tagName);
            if (in_array($tag, ['div', 'section', 'span', 'article', 'main', 'aside', 'header', 'footer'])) {
                // If the element has no meaningful attributes left, unwrap it
                if ($el->attributes->length === 0 || ($el->attributes->length === 1 && $el->hasAttribute('id') && preg_match('/^(elementor|vc_|et_pb_|fl-|fusion-)/i', $el->getAttribute('id')))) {
                    // Move children before this node, then remove
                    while ($el->firstChild) {
                        $el->parentNode->insertBefore($el->firstChild, $el);
                    }
                    $el->parentNode->removeChild($el);
                }
            }
        }

        // Extract cleaned HTML from root
        $root = $doc->getElementById('__wp_clean_root__');
        if (!$root) {
            return $html; // fallback
        }

        $cleanHtml = '';
        foreach ($root->childNodes as $child) {
            $cleanHtml .= $doc->saveHTML($child);
        }

        // Decode any HTML entities that got double-encoded
        $cleanHtml = html_entity_decode($cleanHtml, ENT_QUOTES, 'UTF-8');

        return $cleanHtml;
    }

    /**
     * WordPress wpautop equivalent — converts double newlines to <p> tags.
     */
    private function wpautop(string $text): string
    {
        if (trim($text) === '')
            return '';

        // If already has block-level HTML, don't double-wrap
        if (preg_match('/<(p|div|table|h[1-6]|ul|ol|blockquote|figure|section|article)/i', $text)) {
            return $text;
        }

        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $paragraphs = preg_split('/\n\s*\n/', $text);

        $output = '';
        foreach ($paragraphs as $p) {
            $p = trim($p);
            if ($p === '')
                continue;
            $p = nl2br($p);
            $output .= "<p>{$p}</p>\n";
        }

        return $output;
    }

    /**
     * Download and store an image locally.
     */
    private function downloadImage(string $url, string $slug): ?string
    {
        try {
            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $filename = "blog/{$slug}-featured.{$extension}";

            $context = stream_context_create([
                'http' => ['timeout' => 15, 'user_agent' => 'Mozilla/5.0 (MKS Blog Import)'],
                'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
            ]);

            $imageData = @file_get_contents($url, false, $context);
            if ($imageData === false)
                return $url;

            Storage::disk('public')->put($filename, $imageData);
            return Storage::url($filename);
        } catch (\Exception $e) {
            return $url;
        }
    }
}
