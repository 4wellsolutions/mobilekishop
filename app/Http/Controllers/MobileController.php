<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use App\Product;
use App\Review;
use App\Brand;
use App\Wishlist;
use App\Compare;
use App\Category;
use App\User;
use App\Page;
use App\Ad;
use Response;
use Session;
use Auth;
use URL;
use Str;
use DB;

class MobileController extends Controller
{

    public function showProduct($brand, $slug)
    {

        $agent = new Agent();
        $product = Product::whereSlug($slug)->first();
        if (!$product) {
            return abort(404);
        }
        $product->views++;
        $product->save();
        $products = $product->brand->products()->inRandomOrder()->limit(4)->get();
        return view("frontend.product.mobile", compact('product', 'products', 'agent'));
    }

    public function productUnderMobileAmount($brand, $amount)
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $priceSlabs = [
            15000,
            20000,
            25000,
            30000,
            35000,
            40000,
            45000,
            50000,
            60000,
            70000,
            80000,
            90000,
            100000,
            150000,
            200000,
            250000,
            300000,
            350000,
            400000,
            500000,
            600000,
            700000
        ];
        $lowerAmount = 0;
        for ($i = 0; $i < count($priceSlabs); $i++) {
            if ($amount == $priceSlabs[$i]) {
                $lowerAmount = $i > 0 ? $priceSlabs[$i - 1] : 0;
                $upperBound = $priceSlabs[$i];
                break;
            }
        }

        $brand = Str::title($brand);
        $metas = (object) [
            "title" => "Latest $brand Mobile Phones Under {$country->currency} $amount Price in {$country->country_name}",
            "description" => "Find the latest $brand mobile phones under {$country->currency} $amount on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "$brand Mobile Phones $lowerAmount to $amount in {$country->country_name}",
            "name" => "$brand Mobile phones $amount to $lowerAmount"
        ];

        $brand = Brand::where("slug", $brand)->first();
        $products = app('App\Http\Controllers\ProductController')->getProducts($country->id, $lowerAmount, $amount);

        $category = Category::find(1);
        if ($category) {
            $products = $products->where('products.category_id', $category->id);
        }

        if ($brand) {
            $products = $products->where('products.brand_id', $brand->id);
        }

        $filter = \Request::all();
        $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id);

        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $products = $products->simplePaginate(32);

        return view("frontend.filter", compact('products', 'brand', 'metas', 'category', 'amount', 'lowerAmount', 'country'));
    }
    public function underPrice($amount, Request $request)
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $productsPerPage = 32;
        $priceSlabs = [
            15000,
            20000,
            25000,
            30000,
            35000,
            40000,
            45000,
            50000,
            60000,
            70000,
            80000,
            90000,
            100000,
            150000,
            200000,
            300000,
            400000,
            500000,
            600000,
            700000
        ];
        $lowerAmount = 0;
        for ($i = 0; $i < count($priceSlabs); $i++) {
            if ($amount == $priceSlabs[$i]) {
                $lowerAmount = $i > 0 ? $priceSlabs[$i - 1] : 0;
                $upperBound = $priceSlabs[$i];
                break;
            }
        }

        $metas = Page::whereSlug(url()->current())->where("is_active", 1)->first();
        if (!$metas) {
            $metas = (object) [
                "title" => "Latest Mobile Phones Under {$country->currency} $amount Price in {$country->country_name}",
                "description" => "Explore mobile phones ranging from {$country->currency} $lowerAmount to {$country->currency} {$amount} on Mobilekishop, featuring detailed specifications, reviews, comparisons, and pricing in $country->country_name.",
                "canonical" => \Request::fullUrl(),
                "h1" => "Mobile Phones Under {$country->currency} $lowerAmount to {$country->currency} $amount in $country->country_name",
                "name" => "Mobile phones under $amount"
            ];
        }

        $category = Category::find(1);
        $categoryId = $category->id;

        // Start building the query
        $products = app('App\Http\Controllers\ProductController')->getProducts($country->id, $lowerAmount, $amount);

        if ($category) {
            $products = $products->where('products.category_id', $category->id);
        }
        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id, $lowerAmount, $amount);
        }

        $products = $products->paginate($productsPerPage);


        if (\Request::ajax()) {
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        } else {
            return view("frontend.filter", compact('products', 'metas', 'category', 'amount', 'lowerAmount', 'country'));
        }
    }
    public function combinationRamRom($ram, $rom, $brand = null)
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $brand = null;
        $metas = (object) [
            "title" => "$brand Mobile Phones with " . $ram . "GB RAM and " . $rom . "GB storage in {$country->country_name}",
            "description" => "$brand Mobile phones with " . $ram . "GB RAM and " . $rom . "GB storage on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "$brand Mobile Phones with " . $ram . "GB RAM and " . $rom . "GB storage in {$country->country_name}",
            "name" => "$brand Mobile phones with " . $ram . "GB RAM and " . $rom . "GB storage"
        ];

        $category = Category::find(1);
        $products = $category->products();

        $products = Product::whereHas('attributes', function ($query) use ($ram) {
            $query->where('attribute_id', 76)->where('value', $ram . "GB");
        });

        $products = Product::whereHas('attributes', function ($query) use ($rom) {
            $query->where('attribute_id', 77)->where('value', $rom . "GB");
        });

        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $products = $products->simplepaginate(32);
        return view("frontend.filter", compact('products', 'brand', 'metas', 'category', 'country'));
    }
    public function showMobileEmbed($slug)
    {
        $product = Product::whereSlug($slug)->first();
        if (!$product) {
            return abort(404);
        }
        return view("frontend.embed.mobile", compact('product'));
    }
    public function showMobileEmbedWithButton($slug)
    {
        $product = Product::whereSlug($slug)->first();
        if (!$product) {
            return abort(404);
        }
        return view("frontend.embed.mobile_with_button", compact('product'));
    }


    public function upComingMobiles()
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object) [
            "title" => Str::title("All Upcoming Mobile Phones Price in {$country->country_name}"),
            "description" => "Find the latest Upcoming on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "name" => "All Brands",
            "h1" => "Upcoming Mobile Phones in {$country->country_name}",
        ];

        $products = Product::with('attributes')->where('release_date', '>', Carbon::now());

        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(1);
        return view("frontend.upcoming", compact('products', 'metas', 'category', 'country'));
    }
    public function mobilePhones4g()
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object) [
            "title" => "All 4G Network Mobile Phones Price in {$country->country_name}",
            "description" => "Find the latest mobile phones with 4G Network on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "4G Network Mobile Phones in {$country->country_name}",
            "name" => "4G Network"
        ];
        $products = Product::whereHas('attributes', function ($query) {
            $query->where('attribute_id', 31);
        });
        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id);
        }

        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(1);
        return view("frontend.filter", compact('products', 'metas', 'category', 'country'));
    }
    public function mobilePhones5g()
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object) [
            "title" => "All 5G Network Mobile Phones Price in {$country->country_name}",
            "description" => "Find the latest mobile phones with 5G Network on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "5G Network Mobile Phones in {$country->country_name}",
            "name" => "5G Network"
        ];
        $products = Product::whereHas('attributes', function ($query) {
            $query->where('attribute_id', 32);
        });
        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id);
        }

        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(1);
        return view("frontend.filter", compact('products', 'metas', 'category', 'country'));
    }
    public function underRam($ram)
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object) [
            "title" => "Latest {$ram}GB RAM Mobile Phones Price in {$country->country_name}",
            "description" => "Find the latest mobile phones with {$ram}GB RAM on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "Best {$ram}GB RAM Mobile Phones Price in {$country->country_name}",
            "name" => "Mobile phones with {$ram}GB RAM"
        ];


        $products = Product::whereHas('attributes', function ($query) use ($ram) {
            $query->where('attribute_id', 76)->where('value', 'like', $ram . 'GB');
        });

        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id, 0, 700000);
        }


        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $category = Category::find(1);
        $products = $products->simplepaginate($productsPerPage);
        return view("frontend.filter", compact('products', 'metas', 'category', 'country'));
    }
    public function underProcessor(Request $request)
    {
        $url = $request->fullUrl();
        $parts = explode('/', parse_url($url, PHP_URL_PATH));
        $relevantPart = end($parts); // Get the last part of the URL

        // Step 2: Remove '-mobile-phones' from the relevant part
        $relevantPart = str_replace('-mobile-phones', '', $relevantPart);

        // Step 3: Replace dashes with spaces
        $relevantPartWithSpaces = str_replace('-', ' ', $relevantPart);
        // dd($relevantPartWithSpaces);
        // Example array to check against
        $validParts = ['snapdragon 888', 'snapdragon 8 gen 1', 'snapdragon 8 gen 2', 'snapdragon 8 gen 3', 'snapdragon 8 gen 4', 'mediatek', 'exynos', 'kirin', 'google tensor'];

        if (in_array($relevantPartWithSpaces, $validParts)) {
            $processor = $relevantPartWithSpaces;
        } else {
            abort(404);
        }
        $processor = Str::title($processor);
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object) [
            "title" => "Latest {$processor} Mobile Phones Price in {$country->country_name}",
            "description" => "Find the latest mobile phones powered by {$processor} with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "Best {$processor} Mobile Phones Price in {$country->country_name}",
            "name" => "Mobile phones with {$processor}"
        ];


        $products = Product::whereHas('attributes', function ($query) use ($processor) {
            $query->where('attribute_id', 34)
                ->where('value', 'like', "%" . $processor . "%");
        })->whereHas('variants', function ($query) use ($country) {
            $query->where('country_id', $country->id);
        });


        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id, 0, 700000);
        }


        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $category = Category::find(1);
        $products = $products->simplepaginate($productsPerPage);
        return view("frontend.filter", compact('products', 'metas', 'category', 'country'));
    }
    public function filterUnderProcessorAmount(Request $request, $country_code, $amount)
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object) [
            "title" => "Snapdragon 8 Gen 3 Mobile Phones under {$amount} in {$country->country_name}",
            "description" => "Find the latest mobile phones powered by Snapdragon 8 Gen 3 under {$amount} with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "Snapdragon 8 Gen 3 Mobile Phones under {$amount} in {$country->country_name}",
            "name" => "Snapdragon 8 Gen 3 Mobile Phones under {$amount}"
        ];
        $countryId = $country->id;

        $priceSlabs = [
            10000,
            20000,
            25000,
            30000,
            40000,
            50000,
            60000,
            70000,
            80000,
            90000,
            100000,
            150000
        ];
        $lowerAmount = 0;
        for ($i = 0; $i < count($priceSlabs); $i++) {
            if ($amount == $priceSlabs[$i]) {
                $lowerAmount = $i > 0 ? $priceSlabs[$i - 1] : 0;
                $upperBound = $priceSlabs[$i];
                break;
            }
        }

        $products = Product::whereHas('attributes', function ($query) {
            $query->where('attribute_id', 34)
                ->where('value', 'like', "% Snapdragon 8 Gen 3 %");
        })->whereHas('variants', function ($query) use ($countryId, $lowerAmount, $amount) {
            $query->where('country_id', $countryId)->where('price', '>=', $lowerAmount)
                ->where('price', '<=', $amount);
        });


        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id, 0, 700000);
        }


        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $category = Category::find(1);
        $products = $products->simplepaginate($productsPerPage);
        return view("frontend.filter", compact('products', 'metas', 'category', 'country'));
    }

    public function underRom($rom)
    {

        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object) [
            "title" => "Best {$rom}GB Storage Mobile Phones Price in {$country->country_name}",
            "description" => "Find the latest mobile phones with {$rom}GB storage on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "{$rom}GB Storage/ROM Mobile Phones in {$country->country_name}",
            "name" => "Mobile phones with {$rom}GB Storage"
        ];

        $products = Product::whereHas('attributes', function ($query) use ($rom) {
            $query->where('attribute_id', 77)->where('value', 'like', $rom . 'GB');
        });
        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $category = Category::find(1);
        $products = $products->simplepaginate(32);
        return view("frontend.filter", compact('products', 'metas', 'category', 'country'));
    }

    public function mobilePhonesScreen(Request $request, $maxSize)
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $currentUrl = url()->current();
        $slug = last(explode('/', $currentUrl));

        // Define the ranges
        $ranges = [
            4 => [0, 4],
            5 => [4.1, 5],
            6 => [5.1, 6],
            7 => [6.1, 7],
            8 => [7.1, 8],
        ];

        if (!array_key_exists($maxSize, $ranges)) {
            abort(404);
        }

        $range = $ranges[$maxSize];

        $metas = Page::whereSlug($slug)->where("is_active", 1)->first();
        if (!$metas) {
            $metas = (object) [
                "title" => "All {$maxSize}-Inch Screen Size Mobile Phones Price in {$country->country_name}",
                "description" => "Find the latest mobile phones on the Mobilekishop with {$maxSize}-inch screen size and Their specifications, features, reviews, comparison, and price in {$country->country_name}.",
                "canonical" => \Request::fullUrl(),
                "h1" => "{$maxSize}-Inch Screen Size Mobile Phones in {$country->country_name}",
                "name" => "Mobile phones with {$maxSize} Inch Screen"
            ];
        }

        $products = Product::whereHas('attributes', function ($query) use ($range) {
            $query->where('attribute_id', 75)->whereBetween('value', $range);
        });

        if ($request->has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, $request->all(), $country->id);
        }

        $productsPerPage = 32;

        if ($request->ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }

        $products = $products->simplePaginate($productsPerPage);
        $category = Category::find(1);

        return view("frontend.filter", compact('products', 'metas', 'category', 'country'));
    }

    public function mobilePhonesScreen5()
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object) [
            "title" => "All 5-Inch Screen Size Mobile Phones Price in {$country->country_name}",
            "description" => "Find the latest mobile phones on the Mobilekishop with 5-inch screen size and Their specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "5-Inch Screen Size Mobile Phones in {$country->country_name}",
            "name" => "Mobile phones with 5 Inch Screen"
        ];

        $products = Product::whereHas('attributes', function ($query) {
            $query->where('attribute_id', 75)->whereBetween('value', [4, 5]);
        });
        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(1);
        return view("frontend.filter", compact('products', 'metas', 'category', 'country'));
    }
    public function mobilePhonesScreen6()
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = Page::whereSlug(url()->current())->where("is_active", 1)->first();
        if (!$metas) {
            $metas = (object) [
                "title" => "All 6-Inch Screen Size Mobile Phones Price in {$country->country_name}",
                "description" => "Find the latest mobile phones on the Mobilekishop with 6-inch screen size and Their specifications, features, reviews, comparison, and price in {$country->country_name}.",
                "canonical" => \Request::fullUrl(),
                "h1" => "6-Inch Screen Size Mobile Phones in {$country->country_name}",
                "name" => "Mobile phones with 6 Inch Screen"
            ];
        }

        $products = Product::whereHas('attributes', function ($query) {
            $query->where('attribute_id', 75)->whereBetween('value', [5, 6]);
        });
        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(1);
        return view("frontend.filter", compact('products', 'metas', 'category', 'country'));
    }
    public function mobilePhonesScreen7()
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = Page::whereSlug(url()->current())->where("is_active", 1)->first();
        if (!$metas) {
            $metas = (object) [
                "title" => "All 7-Inch Screen Size Mobile Phones Price in {$country->country_name}",
                "description" => "Find the latest mobile phones on the Mobilekishop with 7-inch screen size and Their specifications, features, reviews, comparison, and price in {$country->country_name}.",
                "canonical" => \Request::fullUrl(),
                "h1" => "7-Inch Screen Size Mobile Phones in {$country->country_name}",
                "name" => "Mobile phones with 7 Inch Screen"
            ];
        }

        $products = Product::whereHas('attributes', function ($query) {
            $query->where('attribute_id', 75)->whereBetween('value', [6, 7]);
        });
        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(1);
        return view("frontend.filter", compact('products', 'metas', 'category', 'country'));
    }
    public function mobilePhonesScreen8()
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = Page::whereSlug(url()->current())->where("is_active", 1)->first();
        if (!$metas) {
            $metas = (object) [
                "title" => "All 8-Inch Screen Size Mobile Phones Price in {$country->country_name}",
                "description" => "Find the latest mobile phones on the Mobilekishop with 8-inch screen size and Their specifications, features, reviews, comparison, and price in {$country->country_name}.",
                "canonical" => \Request::fullUrl(),
                "h1" => "8-Inch Screen Size Mobile Phones in {$country->country_name}",
                "name" => "Mobile phones with 8 Inch Screen"
            ];
        }
        $products = Product::whereHas('attributes', function ($query) {
            $query->where('attribute_id', 75)->whereBetween('value', [7.1, 24]);
        });
        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(1);
        return view("frontend.filter", compact('products', 'metas', 'category', 'country'));
    }
    public function mobilePhonesFolding()
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = Page::whereSlug(url()->current())->where("is_active", 1)->first();
        if (!$metas) {
            $metas = (object) [
                "title" => "Folding Mobile Phones Price in {$country->country_name}",
                "description" => "Find the latest Folding mobile phones its specifications, features, reviews, comparison, and price in {$country->country_name}.",
                "canonical" => \Request::fullUrl(),
                "h1" => "Folding Mobile Phones in {$country->country_name}",
                "name" => "Folding Mobile phones"
            ];
        }
        $products = Product::whereHas('attributes', function ($query) {
            $query->where('attribute_id', 265)->where('value', 1);
        });
        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(1);
        return view("frontend.filter", compact('products', 'metas', 'category', 'country'));
    }
    public function mobilePhonesFlip()
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = Page::whereSlug(url()->current())->where("is_active", 1)->first();
        if (!$metas) {
            $metas = (object) [
                "title" => "Flip Mobile Phones Price in {$country->country_name}",
                "description" => "Find the latest Flip mobile phones its specifications, features, reviews, comparison, and price in {$country->country_name}.",
                "canonical" => \Request::fullUrl(),
                "h1" => "Flip Mobile Phones in {$country->country_name}",
                "name" => "Flip Mobile phones"
            ];
        }
        $products = Product::whereHas('attributes', function ($query) {
            $query->where('attribute_id', 264)->where('value', 1);
        });
        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(1);
        return view("frontend.filter", compact('products', 'metas', 'category', 'country'));
    }
    public function mobilePhonesCurved()
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = Page::whereSlug(url()->current())->where("is_active", 1)->first();
        if (!$metas) {
            $metas = (object) [
                "title" => "Curved Display Mobile Phones Price in {$country->country_name}",
                "description" => "Find the latest Curved or Edge Display mobile phones its specifications, features, reviews, comparison, and price in {$country->country_name}.",
                "canonical" => \Request::fullUrl(),
                "h1" => "Curved Display Mobile Phones in {$country->country_name}",
                "name" => "Curved Display Mobile phones"
            ];
        }
        $products = Product::whereHas('attributes', function ($query) {
            $query->where('attribute_id', 263)->where('value', 1);
        });
        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(1);
        return view("frontend.filter", compact('products', 'metas', 'category', 'country'));
    }
    public function mobilePhonesCurvedByBrand($brand)
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = Page::whereSlug(url()->current())->where("is_active", 1)->first();

        $brand = Brand::where("slug", $brand)->first();
        if (!$metas) {
            $metas = (object) [
                "title" => "{$brand->name} Curved Display Mobile Phones Price in {$country->country_name}",
                "description" => "Find the latest {$brand->name} Curved or Edge Display mobile phones its specifications, features, reviews, comparison, and price in {$country->country_name}.",
                "canonical" => \Request::fullUrl(),
                "h1" => "{$brand->name} Curved Display Mobile Phones in {$country->country_name}",
                "name" => "{$brand->name} Curved Display Mobile phones"
            ];
        }

        $products = Product::whereHas('attributes', function ($query) {
            $query->where('attribute_id', 263)->where('value', 1);
        });
        if ($brand) {
            $products = $products->where('products.brand_id', $brand->id);
        }
        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(1);
        return view("frontend.filter", compact('products', 'metas', 'category', 'country'));
    }
    public function mobilePhonesDualCamera()
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object) [
            "title" => "All Dual Camera Mobile Phones Price in {$country->country_name}",
            "description" => "Find the latest mobile phones on the Mobilekishop with Dual Camera and specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "Dual Camera Mobile Phones in {$country->country_name}",
            "name" => "Mobile phones with Dual Camera"
        ];

        $products = Product::whereHas('attributes', function ($query) {
            $query->where('attribute_id', 74)->where('value', 2);
        });
        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(1);
        return view("frontend.filter", compact('products', 'metas', 'category', 'country'));
    }
    public function mobilePhonesTripleCamera()
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object) [
            "title" => "All Triple Camera Mobile Phones Price in {$country->country_name}",
            "description" => "Find the latest mobile phones on the Mobilekishop with Triple Camera and specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "Triple Camera Mobile Phones in {$country->country_name}",
            "name" => "Mobile phones with Triple Camera"
        ];

        $products = Product::whereHas('attributes', function ($query) {
            $query->where('attribute_id', 74)->where('value', 3);
        });

        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(1);
        return view("frontend.filter", compact('products', 'metas', 'category', 'country'));
    }
    public function mobilePhonesQuadCamera()
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object) [
            "title" => "All Quad Camera Mobile Phones Price in {$country->country_name}",
            "description" => "Find the latest mobile phones on the Mobilekishop with Quad Camera and specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "Quad Camera Mobile Phones in {$country->country_name}",
            "name" => "Mobile phones with Quad Camera"
        ];

        $products = Product::whereHas('attributes', function ($query) {
            $query->where('attribute_id', 74)->where('value', 4);
        });

        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(1);
        return view("frontend.filter", compact('products', 'metas', 'category', 'country'));
    }
    public function mobilePhonesUnderCamera($camera)
    {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object) [
            "title" => "Latest {$camera}MP Camera Mobile Phones Price in {$country->country_name}",
            "description" => "Find the latest mobile phones on the Mobilekishop with {$camera}MP Camera and specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "{$camera}MP Camera Mobile Phones in {$country->country_name}",
            "name" => "Mobile phones with {$camera}MP Camera"
        ];
        $category = Category::find(1);
        $products = Product::whereHas('attributes', function ($query) use ($camera) {
            $query->where('attribute_id', 73)->where('value', $camera);
        });
        if (\Request::has('filter')) {
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }
        $products = $products->simplepaginate(32);
        return view("frontend.filter", compact('products', 'metas', 'category', 'country'));
    }
    public function compareMobileEmbed($slug, $slug1 = null)
    {

        if (strpos($slug, "vs")) {
            $slug1 = explode('-vs-', $slug)[0];
            $slug2 = explode('-vs-', $slug)[1];
            if (isset(explode('-vs-', $slug)[2])) {
                $slug3 = explode('-vs-', $slug)[2];
            }
        } else {
            $slug1 = $slug;
        }
        // dd($slug);
        $mobile = null;
        $mobile1 = null;
        $mobile2 = null;
        $mobile = Mobile::whereSlug($slug1)->first();
        if (isset($slug2)) {
            $mobile1 = Mobile::whereSlug($slug2)->first();
        }
        if (isset($slug3)) {
            $mobile2 = Mobile::whereSlug($slug3)->first();
        }

        return view("frontend.compare_embed", compact('mobile', 'mobile1', 'mobile2'));
    }
}
