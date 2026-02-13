<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Filter;
use App\Models\CategoryPriceRange;
use App\Models\Country;
use Request;

class ViewServiceProvider extends ServiceProvider
{
    // Static properties to hold data for the current request
    protected static $requestCountry = null;
    protected static $requestCategory = null;
    protected static $requestDataLoaded = false;

    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer(['includes.*', 'frontend.*', 'layouts.*'], function ($view) {
            // Use static caching to ensure DB queries run only ONCE per request
            if (!self::$requestDataLoaded) {
                // Fetch country from URL or fallback
                $segment1 = Request::segment(1);
                $countryQuery = Country::where('country_code', $segment1)->where('is_active', 1)->first();
                $isRegional = (bool) $countryQuery;

                self::$requestCountry = $countryQuery ?? Country::where('country_code', 'pk')->where('is_active', 1)->first();

                // Detect category slug based on URL structure
                $categorySlug = null;
                $segments = Request::segments();
                $count = count($segments);

                // Check if we are on the "all brands" page to avoid setting a default category
                $isAllBrandsPage = (count($segments) >= 1 && $segments[0] === 'brands' && ($segments[1] ?? 'all') === 'all');

                if ($isRegional) {
                    if ($count >= 3 && $segments[1] === 'category') {
                        $categorySlug = $segments[2];
                    } elseif ($count >= 3 && $segments[2] !== 'product') { // brand/category case
                        $categorySlug = $segments[2];
                    }
                } else {
                    if ($count >= 2 && $segments[0] === 'category') {
                        $categorySlug = $segments[1];
                    } elseif ($count >= 2 && $segments[0] !== 'product') { // brand/category case
                        $categorySlug = $segments[1];
                    }
                }

                if ($isAllBrandsPage) {
                    self::$requestCategory = null;
                } else {
                    self::$requestCategory = Category::where('slug', $categorySlug)->first()
                        ?? (isset($view->category) ? $view->category : Category::where('slug', 'mobile-phones')->first());
                }

                self::$requestDataLoaded = true;
            }

            $country = self::$requestCountry;
            $category = self::$requestCategory;

            // Final safety check
            if (!$country) {
                $country = Country::where('country_code', 'pk')->first();
            }
            if (!$category) {
                $category = Category::where('slug', 'mobile-phones')->first();
            }

            $categoriesCacheKey = "categories_sidebar_{$country->id}";
            $brandsCacheKey = "brands_sidebar_{$country->id}_{$category->id}";
            $filtersCacheKey = 'filters_sidebar_' . md5(Request::url());
            $priceRangesCacheKey = "prices_sidebar_{$country->id}_{$category->id}";

            $categories = Cache::remember($categoriesCacheKey, now()->addDay(), function () {
                return Category::has('products')->where("is_active", 1)->get();
            });

            $brands = Cache::remember($brandsCacheKey, now()->addDay(), function () use ($category, $country) {
                return Brand::whereHas('products', function ($query) use ($category, $country) {
                    $query->where('category_id', $category->id)
                        ->whereHas('variants', function ($query) use ($country) {
                            $query->where('country_id', $country->id)
                                ->where('price', '>', 0);
                        });
                })->get();
            });

            $filters = Cache::remember($filtersCacheKey, now()->addDay(), function () {
                return Filter::where("page_url", Request::url())->get();
            });

            $priceRanges = Cache::remember($priceRangesCacheKey, now()->addDay(), function () use ($country, $category) {
                if (!\Illuminate\Support\Facades\Schema::hasTable('category_price_ranges')) {
                    return [15000, 20000, 25000, 30000, 35000, 40000, 45000, 50000, 60000, 70000, 80000, 90000, 100000, 150000, 200000, 300000, 400000, 500000, 600000, 700000];
                }

                $priceRangesRecord = CategoryPriceRange::where('country_id', $country->id)
                    ->where('category_id', $category->id)
                    ->first();
                return $priceRangesRecord ? json_decode($priceRangesRecord->price_range_json) : [15000, 20000, 25000, 30000, 35000, 40000, 45000, 50000, 60000, 70000, 80000, 90000, 100000, 150000, 200000, 300000, 400000, 500000, 600000, 700000];
            });

            $data = compact('categories', 'brands', 'filters', 'priceRanges', 'country', 'category');

            foreach ($data as $key => $value) {
                // For category, we want to allow null if it was explicitly set to null (like in all brands page)
                if ($key === 'category') {
                    if (!array_key_exists('category', $view->getData())) {
                        $view->with($key, $value);
                    }
                } elseif (!isset($view->{$key})) {
                    $view->with($key, $value);
                }
            }
        });
    }

    /**
     * Reset static properties. Called at the end of a request or for testing.
     */
    public static function resetRequestCache()
    {
        self::$requestCountry = null;
        self::$requestCategory = null;
        self::$requestDataLoaded = false;
    }
}