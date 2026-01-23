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
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('includes.*', function ($view) {
            // Fetch country from URL or session, with a fallback
            $countryCode = Request::segment(1);
            $country = Country::where('country_code', $countryCode)->first() ?? Country::where('country_code', 'pk')->first();
            
            // Fetch category from URL, with a fallback
            $categorySlug = Request::segment(2);
            $category = Category::where('slug', $categorySlug)->first() ?? Category::where('slug', 'mobile-phones')->first();
            
            // Check for null after fallbacks
            if (!$country || !$category) {
                $view->with(['categories' => [], 'brands' => [], 'filters' => [], 'priceRanges' => []]);
                return;
            }

            $categoriesCacheKey = "categories_sidebar_{$country->id}";
            $brandsCacheKey     = "brands_sidebar_{$country->id}_{$category->id}";
            $filtersCacheKey    = 'filters_sidebar_' . md5(Request::url());
            $priceRangesCacheKey = "prices_sidebar_{$country->id}_{$category->id}";

            $categories = Cache::remember($categoriesCacheKey, 3600, function () {
                return Category::has('products')->where("is_active", 1)->get();
            });

            $brands = Cache::remember($brandsCacheKey, 3600, function () use ($category, $country) {
                return Brand::whereHas('products', function ($query) use ($category,$country) {
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
            
            $priceRanges = Cache::remember($priceRangesCacheKey, 3600, function() use ($country, $category) {
                $priceRangesRecord = CategoryPriceRange::where('country_id', $country->id)
                                                         ->where('category_id', $category->id)
                                                         ->first();
                return $priceRangesRecord ? json_decode($priceRangesRecord->price_range_json) : [];
            });

            $view->with(compact('categories', 'brands', 'filters', 'priceRanges', 'country', 'category'));
        });
    }
}