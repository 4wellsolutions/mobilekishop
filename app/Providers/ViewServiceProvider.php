<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use App\Category;
use App\Brand;
use App\Filter;
use App\CategoryPriceRange;
use App\Country;
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
                // /ae/category/{slug} -> segment 3
                // /category/{slug} -> segment 2
                // /{brand}/{category_slug} (Legacy) -> segment 2 or 3

                $categorySlug = null;
                $segments = Request::segments();
                $count = count($segments);

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

                self::$requestCategory = Category::where('slug', $categorySlug)->first()
                    ?? (isset($view->category) ? $view->category : Category::where('slug', 'mobile-phones')->first());

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

            $categories = Cache::remember($categoriesCacheKey, 3600, function () {
                return Category::has('products')->where("is_active", 1)->get();
            });

            $brands = Cache::remember($brandsCacheKey, 3600, function () use ($category, $country) {
                return Brand::whereHas('products', function ($query) use ($category, $country) {
                    $query->where('category_id', $category->id)
                        ->whereHas('variants', function ($query) use ($country) {
                            $query->where('country_id', $country->id)
                                ->where('price', '>', 0);
                        });
                })->get();
            });

            $filters = Cache::remember($filtersCacheKey, 3600, function () {
                return Filter::where("page_url", Request::url())->get();
            });

            $priceRanges = Cache::remember($priceRangesCacheKey, 3600, function () use ($country, $category) {
                $priceRangesRecord = CategoryPriceRange::where('country_id', $country->id)
                    ->where('category_id', $category->id)
                    ->first();
                return $priceRangesRecord ? json_decode($priceRangesRecord->price_range_json) : [];
            });

            $data = compact('categories', 'brands', 'filters', 'priceRanges', 'country', 'category');

            foreach ($data as $key => $value) {
                if (!isset($view->{$key})) {
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