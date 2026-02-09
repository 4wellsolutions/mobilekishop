<?php

use Illuminate\Support\Facades\Route;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

if (!function_exists('generate_hreflang_tags')) {
    function generate_hreflang_tags()
    {
        $countries = Country::where('is_active', 1)
                            ->where('is_menu', 1)
                            ->get();
        
        $currentUrl = url()->current();
        $baseUrl = url('/');
        $segments = request()->segments();

        $hreflangTags = '';

        // Define specific pages
        $specificPages = [
            'about-us' => 'about-us',
            'contact' => 'contact',
            'privacy-policy' => 'privacy-policy',
            'terms-and-conditions' => 'terms-and-conditions'
        ];

        if ($currentUrl === $baseUrl || in_array(last($segments), array_keys($specificPages))) {
            // Add the default hreflang tag for homepage and specific pages
            $mainFullUrl = $baseUrl . (isset($specificPages[last($segments)]) ? '/' . last($segments) : '');
            $hreflangTags .= '<link rel="alternate" hreflang="en" href="https://mobilekishop.net">' . PHP_EOL;
            $hreflangTags .= '<link rel="alternate" hreflang="x-default" href="https://mobilekishop.net">' . PHP_EOL;

            foreach ($countries as $country) {
                $domain = $country->country_code === 'pk' ? 'mobilekishop.net' : $country->country_code . '.mobilekishop.net';
                $url = 'https://' . $domain . (isset($specificPages[last($segments)]) ? '/' . last($segments) : '');
                $hreflangTags .= '<link rel="alternate" hreflang="' . $country->locale . '" href="' . $url . '">' . PHP_EOL;
            }
        } elseif (Route::currentRouteName() === 'product.show' || Route::currentRouteName() === 'country.product.show') {
            $segment1 = request()->segment(1);
            $segment2 = request()->segment(2);
            $product = Product::whereSlug($segment2)->first();
            if ($product) {
                // Add the default hreflang tag
                $mainUrl = route('product.show', [$segment2], false);
                $mainFullUrl = 'https://mobilekishop.net' . $mainUrl;
                $hreflangTags .= '<link rel="alternate" hreflang="en" href="' . $mainFullUrl . '">' . PHP_EOL;
                $hreflangTags .= '<link rel="alternate" hreflang="x-default" href="' . $mainFullUrl . '">' . PHP_EOL;

                foreach ($countries as $country) {
                    $domain = $country->country_code === 'pk' ? 'mobilekishop.net' : $country->country_code . '.mobilekishop.net';
                    $hasPrice = $product->variants()->where('country_id', $country->id)->where('price', '>', 0)->exists();
                    if ($hasPrice) {
                        $url = $country->country_code === 'pk' ? route('product.show', [$segment2], false) : route('country.product.show', [$country->country_code, $segment2], false);
                        $fullUrl = 'https://' . $domain . $url;
                        $hreflangTags .= '<link rel="alternate" hreflang="' . $country->locale . '" href="' . $fullUrl . '">' . PHP_EOL;
                    }
                }
            }
        } elseif (Route::currentRouteName() === 'category.show' || Route::currentRouteName() === 'country.category.show') {
            $segment1 = request()->segment(2); // Extract the category slug
            $category = Category::whereSlug($segment1)->first(); // Fetch category by slug
            if ($category) {
                // Default hreflang tag for the category page
                $mainUrl = route('category.show', [$segment1], false);
                $mainFullUrl = 'https://mobilekishop.net' . $mainUrl;
                $hreflangTags .= '<link rel="alternate" hreflang="en" href="' . $mainFullUrl . '">' . PHP_EOL;
                $hreflangTags .= '<link rel="alternate" hreflang="x-default" href="' . $mainFullUrl . '">' . PHP_EOL;

                foreach ($countries as $country) {
                    $domain = $country->country_code === 'pk' ? 'mobilekishop.net' : $country->country_code . '.mobilekishop.net';
                    $url = $country->country_code === 'pk'
                        ? route('category.show', [$segment1], false)
                        : route('country.category.show', [$country->country_code, $segment1], false);
                    $fullUrl = 'https://' . $domain . $url;
                    $hreflangTags .= '<link rel="alternate" hreflang="' . $country->locale . '" href="' . $fullUrl . '">' . PHP_EOL;
                }
            }
        }elseif (Route::currentRouteName() === 'brand.show' || Route::currentRouteName() === 'country.brand.show') {
            $segment1 = request()->segment(2);
            $segment2 = request()->segment(3); // Extract the brand slug
            $brand = Brand::whereSlug($segment1)->first(); // Fetch brand by slug
            if ($brand) {
                // Default hreflang tag for the brand page
                $mainUrl = route('brand.show', [$segment1,$segment2], false);
                $mainFullUrl = 'https://mobilekishop.net' . $mainUrl;
                $hreflangTags .= '<link rel="alternate" hreflang="en" href="' . $mainFullUrl . '">' . PHP_EOL;
                $hreflangTags .= '<link rel="alternate" hreflang="x-default" href="' . $mainFullUrl . '">' . PHP_EOL;
                
                foreach ($countries as $country) {
                    $domain = $country->country_code === 'pk' ? 'mobilekishop.net' : $country->country_code . '.mobilekishop.net';
                    $url = $country->country_code === 'pk'
                        ? route('brand.show', [$segment1, $segment2], false)
                        : route('country.brand.show', [$country->country_code, $segment1, $segment2], false);
                    $fullUrl = 'https://' . $domain . $url;
                    $hreflangTags .= '<link rel="alternate" hreflang="' . $country->locale . '" href="' . $fullUrl . '">' . PHP_EOL;
                }
            }
        } elseif (in_array(Route::currentRouteName(), ['compare', 'country.compare'])) {
            // Add the default hreflang tag for compare route
            $segment2 = request()->segment(2);
            $mainUrl = route('compare', [$segment2], false);
            $mainFullUrl = 'https://mobilekishop.net' . $mainUrl;
            $hreflangTags .= '<link rel="alternate" hreflang="en" href="' . $mainFullUrl . '">' . PHP_EOL;
            $hreflangTags .= '<link rel="alternate" hreflang="x-default" href="' . $mainFullUrl . '">' . PHP_EOL;

            foreach ($countries as $country) {
                $domain = $country->country_code === 'pk' ? 'mobilekishop.net' : $country->country_code . '.mobilekishop.net';
                $url = $country->country_code === 'pk' ? route('compare', [$segment2], false) : route('country.compare', [$country->country_code, $segment2], false);
                $fullUrl = 'https://' . $domain . $url;
                $hreflangTags .= '<link rel="alternate" hreflang="' . $country->locale . '" href="' . $fullUrl . '">' . PHP_EOL;
            }
        }

        return $hreflangTags;
    }
}
