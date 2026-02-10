<?php

namespace App\Services;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Filter;
use App\Models\Country;
use Session;
use Illuminate\Support\Facades\File;

class SitemapService
{
    /**
     * Maximum URLs per sitemap file.
     */
    protected $maxUrlsPerSitemap = 1000;

    /**
     * Base directory for storing sitemaps.
     */
    protected $baseSitemapPath;

    public function __construct()
    {
        // Define the base path for sitemaps
        $this->baseSitemapPath = public_path('sitemaps');

        // Ensure the base sitemap directory exists
        if (!File::exists($this->baseSitemapPath)) {
            File::makeDirectory($this->baseSitemapPath, 0755, true);
        }
    }

    /**
     * Generate sitemaps for a specific country.
     *
     * @param \App\Models\Country $country
     * @return void
     */
    public function generateSitemapsForCountry($country)
    {
        $countryCode = $country->country_code;
        $domain = $country->domain;

        // Use main domain with path prefix instead of subdomain
        $mainDomain = 'https://mobilekishop.net';

        if ($countryCode === 'pk') {
            $baseUrl = $mainDomain;
        } else {
            $baseUrl = "{$mainDomain}/{$countryCode}";
        }

        // Directory to store sitemaps for this country
        $sitemapDir = "{$this->baseSitemapPath}/{$countryCode}";
        if (!File::exists($sitemapDir)) {
            File::makeDirectory($sitemapDir, 0755, true);
        }

        // 1. Generate Categories Sitemap
        $this->generateCategoriesSitemap($baseUrl, $sitemapDir);

        // 2. Generate Brands Sitemap
        // Inside generateSitemapsForCountry method
        $this->generateBrandsSitemap($baseUrl, $sitemapDir, $country);


        // 3. Generate Filters Sitemap
        $this->generateFiltersSitemap($baseUrl, $sitemapDir, $domain);

        // 4. Generate Products Sitemaps with Pagination
        $this->generateProductsSitemaps($baseUrl, $sitemapDir, $country);


        // 5. Generate Sitemap Index for the Country
        $this->generateSitemapIndex($baseUrl, $sitemapDir);

        // 6. Ping Search Engines
        $this->pingSearchEngines($baseUrl, $sitemapDir);
    }


    /**
     * Generate Categories Sitemap.
     *
     * @param string $baseUrl
     * @param string $sitemapDir
     * @return void
     */
    protected function generateCategoriesSitemap(string $baseUrl, string $sitemapDir)
    {
        $categories = Category::select('slug', 'updated_at')->get();

        $sitemap = Sitemap::create();

        foreach ($categories as $category) {
            $url = Url::create("{$baseUrl}/category/{$category->slug}");

            // Set last modification date
            if ($category->updated_at instanceof \DateTimeInterface) {
                $url->setLastModificationDate($category->updated_at);
            } elseif ($category->created_at instanceof \DateTimeInterface) {
                $url->setLastModificationDate($category->created_at);
            } else {
                $url->setLastModificationDate(now());
            }

            $sitemap->add($url);
        }

        $filePath = "{$sitemapDir}/sitemap-categories.xml";
        File::put($filePath, $sitemap->render());
    }

    /**
     * Generate Brands Sitemap.
     *
     * @param string $baseUrl
     * @param string $sitemapDir
     * @param \App\Models\Country $country
     * @return void
     */
    protected function generateBrandsSitemap(string $baseUrl, string $sitemapDir, Country $country)
    {

        // Fetch all categories to check brands for each category and country
        $categories = Category::where("is_active", 1)->get();

        $sitemap = Sitemap::create();

        // Loop through each category
        foreach ($categories as $category) {

            // Get brands that have at least one product in the specified category and country
            $brands = Brand::whereHas('products', function ($query) use ($category, $country) {
                $query->where('category_id', $category->id)
                    ->whereHas('variants', function ($query) use ($country) {
                        $query->where('country_id', $country->id)
                            ->where('price', '>', 0);
                    });
            })->select('slug', 'updated_at')->get();

            // Add each brand for this category to the sitemap
            foreach ($brands as $brand) {
                $url = Url::create("{$baseUrl}/brand/{$brand->slug}/{$category->slug}");

                // Set last modification date
                if ($brand->updated_at instanceof \DateTimeInterface) {
                    $url->setLastModificationDate($brand->updated_at);
                } elseif ($brand->created_at instanceof \DateTimeInterface) {
                    $url->setLastModificationDate($brand->created_at);
                } else {
                    $url->setLastModificationDate(now());
                }

                $sitemap->add($url);
            }
        }

        $filePath = "{$sitemapDir}/sitemap-brands.xml";
        File::put($filePath, $sitemap->render());
    }


    /**
     * Generate Filters Sitemap.
     *
     * @param string $baseUrl
     * @param string $sitemapDir
     * @param string $domain
     * @return void
     */
    protected function generateFiltersSitemap(string $baseUrl, string $sitemapDir, string $domain)
    {
        // Remove trailing slash if it exists
        $domain = rtrim($domain, '/');
        // Filter filters where page_url contains the current domain
        $filters = Filter::where('page_url', 'like', "%{$domain}%")
            ->select('url', 'updated_at')
            ->get();

        $sitemap = Sitemap::create();

        foreach ($filters as $filter) {
            // Assuming 'url' does not include the base URL
            $url = Url::create("{$filter->url}");

            // Set last modification date
            if ($filter->updated_at instanceof \DateTimeInterface) {
                $url->setLastModificationDate($filter->updated_at);
            } elseif ($filter->created_at instanceof \DateTimeInterface) {
                $url->setLastModificationDate($filter->created_at);
            } else {
                $url->setLastModificationDate(now());
            }

            $sitemap->add($url);
        }

        $filePath = "{$sitemapDir}/sitemap-filters.xml";
        File::put($filePath, $sitemap->render());
    }

    /**
     * Generate Products Sitemaps with Pagination and Country-Specific Filtering.
     *
     * @param string $baseUrl
     * @param string $sitemapDir
     * @param \App\Models\Country $country
     * @return void
     */
    protected function generateProductsSitemaps(string $baseUrl, string $sitemapDir, Country $country)
    {

        // Count total products that meet the criteria
        $totalProducts = Product::whereHas('variants', function ($query) use ($country) {
            $query->where('country_id', $country->id)
                ->where('price', '>', 0);
        })->count();

        $perPage = $this->maxUrlsPerSitemap;
        $totalPages = ceil($totalProducts / $perPage);

        // Initialize page counter
        $currentPage = 1;

        // Use chunking to process large datasets efficiently
        Product::with('brand') // Eager load the brand relationship
            ->whereHas('variants', function ($query) use ($country) {
                $query->where('country_id', $country->id)
                    ->where('price', '>', 0);
            })
            ->select('slug', 'updated_at', 'brand_id') // Select necessary fields
            ->orderBy('id') // Ensure consistent ordering
            ->chunk($perPage, function ($products) use ($baseUrl, $sitemapDir, $country, &$currentPage) {

                $sitemap = Sitemap::create();

                foreach ($products as $product) {
                    // Ensure the product has an associated brand
                    if (!$product->brand) {
                        continue; // Skip products without a brand
                    }

                    // Construct the URL using brand slug and product slug
                    $url = Url::create("{$baseUrl}/product/{$product->slug}");

                    // Set last modification date
                    if ($product->updated_at instanceof \DateTimeInterface) {
                        $url->setLastModificationDate($product->updated_at);
                    } elseif ($product->created_at instanceof \DateTimeInterface) {
                        $url->setLastModificationDate($product->created_at);
                    } else {
                        $url->setLastModificationDate(now());
                    }

                    $sitemap->add($url);
                }

                // Define the filename with the current page number
                $filePath = "{$sitemapDir}/sitemap-products-{$currentPage}.xml";
                File::put($filePath, $sitemap->render());

                $currentPage++;
            });
    }


    /**
     * Generate Sitemap Index for the Country.
     *
     * @param string $baseUrl
     * @param string $sitemapDir
     * @return void
     */
    protected function generateSitemapIndex(string $baseUrl, string $sitemapDir)
    {

        $sitemapIndex = SitemapIndex::create();

        // Define the path prefix for sitemaps
        $sitemapPathPrefix = "{$baseUrl}/sitemap";

        // List of sitemap files to include
        $sections = ['categories', 'brands', 'filters', 'news'];

        foreach ($sections as $section) {
            $sitemapUrl = "{$sitemapPathPrefix}-{$section}.xml";
            $sitemapIndex->add($sitemapUrl, now());
        }

        // Add Products sitemaps
        $files = File::files($sitemapDir);
        foreach ($files as $file) {
            $filename = $file->getFilename();

            if (preg_match('/sitemap-products-\d+\.xml$/', $filename)) {
                // Corrected: Use the filename as-is without adding 'sitemap-'
                $sitemapUrl = "{$baseUrl}/{$filename}";
                $sitemapIndex->add($sitemapUrl, now());
            }
        }

        // Save Sitemap Index
        $filePath = "{$sitemapDir}/sitemap.xml";
        File::put($filePath, $sitemapIndex->render());
    }

    /**
     * Build one master sitemap index at public/sitemap.xml
     * that references every country's sitemap.xml.
     *
     * @return array List of country sitemaps included
     */
    public function buildMasterIndex(): array
    {
        $countries = \App\Models\Country::where('is_active', 1)->get();
        $sitemapIndex = SitemapIndex::create();
        $included = [];

        foreach ($countries as $country) {
            $dir = "{$this->baseSitemapPath}/{$country->country_code}";
            $indexFile = "{$dir}/sitemap.xml";

            if (File::exists($indexFile)) {
                $baseUrl = rtrim($country->domain, '/');
                $sitemapIndex->add("{$baseUrl}/sitemaps/{$country->country_code}/sitemap.xml", now());
                $included[] = $country->country_name;
            }
        }

        // Write to public/sitemap.xml
        File::put(public_path('sitemap.xml'), $sitemapIndex->render());

        return $included;
    }


    /**
     * Ping Search Engines to notify about the updated sitemap.
     *
     * @param string $baseUrl
     * @param string $sitemapDir
     * @return void
     */
    protected function pingSearchEngines(string $baseUrl, string $sitemapDir)
    {
        \Log::info("  - Pinging Search Engines...");

        $sitemapIndexUrl = "{$baseUrl}/sitemap.xml";

        $pingUrls = [
            "https://www.google.com/ping?sitemap=" . urlencode($sitemapIndexUrl),
            "https://www.bing.com/ping?sitemap=" . urlencode($sitemapIndexUrl),
        ];

        foreach ($pingUrls as $pingUrl) {
            try {
                Http::get($pingUrl);
                \Log::info("    * Pinged: {$pingUrl}");
            } catch (\Exception $e) {
                \Log::error("    * Failed to ping: {$pingUrl} | Error: {$e->getMessage()}");
            }
        }
    }
}
