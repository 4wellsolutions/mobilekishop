<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Carbon\Carbon;
use App\Page;
use App\Category;
use App\Country;
use App\Compare;
use App\Brand;
use App\Product;
use Validator;
use DB;
use URL;
use Str;
use Session;


class CountryController extends Controller
{
    public function getCountry(){
        $countryCode = session('country_code');

        if (!$countryCode) {
            // If neither session nor parameter has the country code, set it to default 'pk'
            $countryCode = 'pk';
            session(['country_code' => $countryCode]);
        }

        $country = Country::where("country_code", $countryCode)->first();
        return $country;
    }
    public function showOld($country_code,$brand,$slug){
        $brand = Brand::firstWhere("slug",$brand);
        if(!$brand){
            return abort(404);
        }
        $product = Product::where("brand_id",$brand->id)->firstWhere("slug",$slug);
        if(!$product){
            return abort(404);
        }
        return redirect('/product/'.$slug)->setStatusCode(301);
    }
	public function showProduct($country_code,$slug)
    {
        $country = $this->getCountry();

        $agent = new Agent();
        
        $product = Product::firstWhere("slug",$slug);
        $product->load(['variants' => function ($query) use ($country) {
            $query->where('country_id', $country->id)
                  ->withPivot('price');
        }]);

        if(!$product){
            return abort(404);
        }
        
        $product->views++;
        $product->save();
        
        $products = $product->brand->products()
            ->whereHas('variants', function ($query) use ($country) {
                $query->where('price', '>', 0);
                $query->where('country_id', $country->id);
            })
            ->where("category_id",$product->category->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();
        $view       = $product->category->slug;
        $category   = $product->category;
        $compares   = Compare::where('product1', $product->slug)
                      ->orWhere('product2', $product->slug)
                      ->orWhere('product3', $product->slug)
                      ->inRandomOrder()
                      ->limit(3)
                      ->get(); 
        $amount = null;

        return view("frontend.product.$view", compact('product','products','agent','category','compares','amount','country'));
    }
    public function FilterPhoneCoverProducts($country_code, $slug){
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = Page::whereSlug(url()->current())->where("is_active",1)->first();
        $title = ucwords(str_replace('-', ' ', $slug));
        if(!$metas){
            $metas = (object)[
                "title" => "Best $title Phone Cases in {$country->country_name}",
                "description" => "Protect your $title with premium phone cases designed for durability, style, and functionality. Buy now in {$country->country_name}.",
                "canonical" => \Request::fullUrl(),
                "h1" => "$title Phone Cases {$country->country_name}",
                "name" => "$title Phone Cases"
            ];
        }
        $products = Product::whereHas('attributes', function ($query) use ($slug) {
            $query->where('attribute_id', 312)
                  ->where('value', $slug);
        });

        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products','country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(8);
        return view("frontend.filter",compact('products','metas','category','country')); 
    }
    public function FilterPhoneCoverByBrandProducts($country_code, $brand, $slug){
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = Page::whereSlug(url()->current())->where("is_active",1)->first();
        $title = ucwords(str_replace('-', ' ', $slug));
        if(!$metas){
            $metas = (object)[
                "title" => "Best ".Str::title($brand)." $title Phone Cases in {$country->country_name}",
                "description" => "Protect your ".Str::title($brand)." $title with premium phone cases designed for durability, style, and functionality. Buy now in {$country->country_name}.",
                "canonical" => \Request::fullUrl(),
                "h1" => Str::title($brand)." $title Phone Cases {$country->country_name}",
                "name" => Str::title($brand)." $title Phone Cases"
            ];
        }
        $products = Product::whereHas('attributes', function ($query) use ($slug) {
            $query->where('attribute_id', 312)
                  ->where('value', $slug);
        });

        // Filter by brand slug
        $products = $products->whereHas('brand', function ($query) use ($brand) {
            $query->where('slug', $brand);
        });

        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products','country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(8);
        return view("frontend.filter",compact('products','metas','category','country')); 
    }
    public function FilterPowerBankAttributeProduct($country_code, $mah){
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = Page::whereSlug(url()->current())->where("is_active",1)->first();
        if(!$metas){
            $metas = (object)[
                "title" => "Best Power Banks with {$mah}mAh in {$country->country_name}",
                "description" => "Explore our range of {$mah}mAh power banks for reliable charging on the go. Ideal for smartphones, tablets, and laptops with high-capacity needs in {$country->country_name}.",
                "canonical" => \Request::fullUrl(),
                "h1" => "Power Banks with {$mah}mAh in {$country->country_name}",
                "name" => "Power Banks with {$mah}mAh"
            ];
        }
        $products = Product::whereHas('attributes', function ($query) use ($mah) {
            $query->where('attribute_id', 302)
                  ->where('value', 'like', "%" . $mah . "%");
        });

        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products','country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(9);
        return view("frontend.filter",compact('products','metas','category','country')); 
    }
	public function brandShow($country_code,$slug,$category_slug){
        $category = null;
        $country = $this->getCountry();
        $countryId = $country->id;
        if(isset($category_slug)){
            $category = Category::whereSlug($category_slug)->first();
            if(!$category){
                abort(404);
            }
            $products = $category->products()->whereHas('variants', function($query) use ($countryId) {
                        $query->where('country_id', $countryId)->where('price', '>', 0);
                    });
        }else{
            $products = Product::whereHas('variants', function($query) use ($countryId) {
                        $query->where('country_id', $countryId)->where('price', '>', 0);
                    });    
        }

        $brand = Brand::whereSlug($slug)->first();
        if(!$brand){
            return abort(404);
        }
        $products = $products->where("products.brand_id",$brand->id);
        
        
        
        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country->id);
        }

        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            return view('includes.products-partial', compact('products','country'))->render();
        }

        $products = $products->simplepaginate(24);
        $name = isset($category) ? $category->category_name : 'Products';
        $metas = app('App\Http\Controllers\HomeController')->getMeta(\Request::url(),$brand,$name,$country);
        $currentMonthYear = Carbon::now()->format('F Y');
        $metas->title = "{$brand->name} {$category->category_name} in {$country->country_name} - Reviews, Prices - {$currentMonthYear}";
        $metas->description = "Discover the latest {$brand->name} {$category->category_name} in {$country->country_name} with Mobilekishop: Get detailed specifications, features, reviews, and price comparisons. Updated for {$currentMonthYear}.";

        return view("frontend.brand",compact('products','brand','metas','category','country'));
    }
    public function categoryShow($country_code,$slug){
        $category = Category::whereSlug($slug)->first();
        if(!$category){
            return abort(404);
        }
       $country = $this->getCountry();
        $countryId = $country->id;
        $products = $category->products()->whereHas('variants', function($query) use ($countryId) {
                        $query->where('country_id', $countryId) ->where('price', '>', 0);;
                    });
        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country);
        }

        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            return view('includes.products-partial', compact('products','country'))->render();
        }
        
        $products = $products->simplepaginate(24);
        $metas = (object)[
            "title" => "Latest $category->category_name Reviews, Features, Price in {$country->country_name}",
            "description" => "Mobilekishop is the best $category->category_name website that provides the latest $category->category_name phone prices in {$country->country_name} with specifications, features, reviews and comparison.",
            "canonical" => \Request::fullUrl(),
            "h1" => "$category->category_name Price in {$country->country_name}",
            "name" => "$category->category_name"
        ];
        
        return view("frontend.category",compact('products','metas','category','country'));
    }
    
    public function productUnderMobileAmount($country_code, $brand, $amount)
    {
        $productsPerPage = 32;
        $country = $this->getCountry();
        $countryId = $country->id;
        $priceSlabs = [
            2000,3000,4000,5000,7000,8000,10000,12000,15000, 20000, 25000, 30000, 35000, 40000, 45000, 50000,60000, 70000, 80000, 90000, 100000, 150000,
            200000, 250000, 300000, 350000, 400000, 500000, 600000, 700000
        ];
        $lowerAmount = 0;

        // Determine lower and upper price bounds
        for ($i = 0; $i < count($priceSlabs); $i++) {
            if ($amount == $priceSlabs[$i]) {
                $lowerAmount = $i > 0 ? $priceSlabs[$i - 1] : 0;
                $upperBound = $priceSlabs[$i];
                break;
            }
        }

        // Get brand object
        $brand = Brand::where("slug", $brand)->first();
        if ($brand) {
            // Initialize products with category products
            $category = Category::find(1); // Ensure category exists, or add error handling
            $products = $category->products()->whereHas('variants', function ($query) use ($countryId, $lowerAmount, $amount) {
                $query->where('country_id', $countryId)
                    ->where('price', '>=', $lowerAmount)
                    ->where('price', '<=', $amount);
            });

            // Filter by brand if available
            $products = $products->where('products.brand_id', $brand->id);
        }

        // Set meta data for SEO
        $metas = (object)[
            "title" => "Latest $brand->name Mobile Phones Under {$country->currency} $amount Price in {$country->country_name}",
            "description" => "Find the latest $brand->name smart phones under {$country->currency} $amount on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "$brand->name Mobile Phones Under $amount in {$country->country_name}",
            "name" => "$brand->name Mobile phones Under $amount"
        ];

        // Apply additional sorting and filtering if necessary
        $filter = \Request::all();
        $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(), $lowerAmount, $amount);

        // Handle AJAX request
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }

        // Default pagination
        $products = $products->simplePaginate($productsPerPage);

        return view("frontend.brand", compact('products', 'brand', 'metas', 'category', 'amount', 'lowerAmount', 'country'));
    }

    public function underPrice($country_code,$amount){
        $productsPerPage = 32;
    	$country = app('App\Http\Controllers\CountryController')->getCountry();
        
        $metas = Page::whereSlug(url()->current())->where("is_active",1)->first();
        if(!$metas){
            $metas = (object)[
                "title" => "Latest Mobile Phones Under {$country->currency} $amount Price in {$country->country_name}",
                "description" => "Find the latest mobile phones under {$country->currency} $amount on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
                "canonical" => \Request::fullUrl(),
                "h1" => "Mobile Phones Under {$country->currency} $amount in {$country->country_name}",
                "name" => "Mobile phones under $amount"
            ];
        }
        if($country->country_code == "in"){
            $priceSlabs = ['1000', '2000', '3000', '4000', '5000', '6000', '7000', '8000', '9000', '10000', '11000', '12000', '13000', '14000', '15000', '20000', '25000', '30000', '35000', '40000', '45000', '50000', '60000', '70000', '80000', '90000', '100000', '120000', '150000', '200000']; 
        }elseif($country->country_code == "au"){
            $priceSlabs = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '1000', '1200', '1500', '2000']; 
        }elseif($country->country_code == "us"){
            $priceSlabs = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '1000', '1200', '1500', '2000']; 
        }elseif($country->country_code == "uk"){
            $priceSlabs = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '1000', '1200', '1500', '2000']; 
        }elseif($country->country_code == "ae"){
            $priceSlabs = ['100', '200', '300', '400', '500', '600', '700', '1000', '1500', '2000', '2500', '3000', '4000','5000','6000','7000']; 
        }elseif($country->country_code == "sa"){
            $priceSlabs = ['100', '200', '300', '400', '500', '600', '700', '1000', '1500', '2000', '2500', '3000', '4000','5000','6000','7000']; 
        }elseif($country->country_code == "qa"){
            $priceSlabs = ['100', '200', '300', '400', '500', '600', '700', '1000', '1500', '2000', '2500', '3000', '4000','5000','6000','7000']; 
        }elseif($country->country_code == "es"){
            $priceSlabs = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '1000', '1200', '1500', '2000']; 
        }elseif($country->country_code == "be" || $country->country_code == "mt" || $country->country_code == "ie" || $country->country_code == "fi" || $country->country_code == "iu" || $country->country_code == "at" || $country->country_code == "hr" || $country->country_code == "cy" || $country->country_code == "ee" || $country->country_code == "gr" || $country->country_code == "pt"){
            $priceSlabs = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '1000', '1200', '1500', '2000']; 
        }elseif($country->country_code == "ca"){
            $priceSlabs = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '1000', '1200', '1500', '2000', '2500']; 
        }elseif($country->country_code == "mx"){
            $priceSlabs = ['2500', '3000','3500','4000','5000','6000' ,'7000','10000', '15000', '20000', '30000', '40000', '50000', '60000']; 
        }elseif($country->country_code == "br"){
            $priceSlabs = ['500','1000', '2000','3000','4000','5000','6000','7000','8000','9000','10000','12000']; 
        }elseif($country->country_code == "de"){
            $priceSlabs = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '1000', '1200', '1500', '2000']; 
        }elseif($country->country_code == "fr"){
            $priceSlabs = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '1000', '1200', '1500', '2000']; 
        }elseif($country->country_code == "it"){
            $priceSlabs = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '1000', '1200', '1500', '2000']; 
        }elseif($country->country_code == "nl"){
            $priceSlabs = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '1000', '1200', '1500', '2000']; 
        }elseif($country->country_code == "jp"){
            $priceSlabs = ['1000', '2000', '3000', '4000', '5000', '6000', '7000', '8000', '9000', '10000', '11000', '12000', '13000', '14000', '15000', '20000', '25000', '30000', '35000', '40000', '45000', '50000', '60000', '70000', '80000', '90000', '100000', '120000', '150000', '200000','250000']; 
        }elseif($country->country_code == "sg"){
            $priceSlabs = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '1000', '1200', '1500', '2000'];  
        }elseif($country->country_code == "se"){
            $priceSlabs = ['1000','2000','2500', '3000','3500','4000','5000','6000' ,'7000','10000', '12000', '15000', '20000'];  
        }elseif($country->country_code == "pl"){
            $priceSlabs = ['500','1000','2000','2500', '3000','3500','4000','5000','6000' ,'7000','10000', '12000'];  
        }else{
            $priceSlabs = ['500','1000','2000','2500', '3000','3500','4000','5000','6000' ,'7000','10000', '12000'];  
        }
        $lowerAmount = 0;
        for ($i = 0; $i < count($priceSlabs); $i++) {
            if ($amount == $priceSlabs[$i]) {
                $lowerAmount = $i > 0 ? $priceSlabs[$i - 1] : 0;
                $upperBound = $priceSlabs[$i];
                break;
            }
        }
        $category = Category::find(1);

        $products = app('App\Http\Controllers\ProductController')->getProducts($country->id,$lowerAmount,$amount);
        if ($category) {
            $products = $products->where('products.category_id', $category->id);
        }
        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country->id,$lowerAmount,$amount);
        }

        $products = $products->paginate($productsPerPage);
        
        
        if (\Request::ajax()) {
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products','country'))->render();
        }else{
            return view("frontend.filter",compact('products','metas','category','amount','lowerAmount','country'));
        }
    }
    public function combinationRamRom($country_code,$ram,$rom,$brand=null){      
        
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $brand = null;
        $metas = (object)[
            "title" => "$brand Mobile Phones with ".$ram."GB RAM and ".$rom."GB storage in {$country->country_name}",
            "description" => "$brand Mobile phones with ".$ram."GB RAM and ".$rom."GB storage on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "$brand Mobile Phones with ".$ram."GB RAM and ".$rom."GB storage in {$country->country_name}",
            "name" => "$brand Mobile phones with ".$ram."GB RAM and ".$rom."GB storage"
        ];
        
        $category = Category::find(1);
        $products = $category->products();
        
        $products = Product::whereHas('attributes', function ($query) use ($ram) {
            $query->where('attribute_id', 76)->where('value', $ram."GB");
        });

        $products = Product::whereHas('attributes', function ($query) use ($rom) {
            $query->where('attribute_id', 77)->where('value', $rom."GB");
        });
        
        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products','country'))->render();
        }
        $products = $products->simplepaginate(32);
        return view("frontend.filter",compact('products','brand','metas','category','country'));
    }
    public function upComingMobiles($country_code){
       $country = $this->getCountry();
        $metas = (object)[
            "title" => Str::title("All Upcoming Mobile Phones Price in {$country->country_name}"),
            "description" => "Find the latest Upcoming on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "name" => "All Brands",
            "h1" => "Upcoming Mobile Phones in {$country->country_name}",
        ];

        $products = Product::whereHas('attributes', function ($query) {
            $query->where('attribute_id', 80)->where('value',">",Carbon::now())->orderBy("id","DESC");
        })->simplepaginate(32);
        
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products'))->render();
        }
        $category = Category::find(1);
        return view("frontend.filter",compact('products','metas','category','country')); 
    }
    public function showBrandsByCategory($country_code,$category_slug){
        $category = null;
        $country = $this->getCountry();

        if($category_slug == "all"){
            $metas = (object)[
                "title" => Str::title("Latest Brands Mobile Phones, Tablets, Smart Watches {$country->country_name}"),
                "description" => "Get all the specifications, features, reviews, comparison, and price of All Mobile Phones Brands on the Mobilekishop in {$country->country_name}.",
                "canonical" => URL::to("/brands/all"),
                "h1" => "All Brands Mobile Phones in {$country->country_name}",
                "name" => "All Brands"
                ];  
            $brands = Brand::orderBy("name","ASC")->get();
        }else{
            $category = Category::where("slug",$category_slug)->first();
            if(!$category){
                return abort(404);
            }
            $metas = (object)[
                "title" => Str::title("All $category->category_name Brands Spec, Price in {$country->country_name}"),
                "description" => "Get all the specifications, features, reviews, comparison, and price of All $category->category_name Brands on the Mobilekishop in {$country->country_name}.",
                "canonical" => URL::to("/brands/$category->slug"),
                "h1" => "All $category->category_name Brands in {$country->country_name}",
                "name" => "All Brands"
            ];  
            
            $brands = Brand::whereHas('products', function ($query) use ($category){
                    $query->where('category_id', $category->id);
                })->get();
        }
        $categories = Category::where("is_active",1)->get();
        
        return view("frontend.brands",compact('brands','metas','category','country','categories'));
    }
    public function underRam($country_code,$ram){
        $country = $this->getCountry();

        $metas = (object)[
            "title" => "Best {$ram}GB RAM Smartphones in {$country->country_name}: Prices, Specs & Deals",
            "description" => "Explore top {$ram}GB Memory smartphones on MobileKiShop. Compare specs, features, and prices in {$country->country_name}. Read user reviews and find the best deals. Shop smart today!",
            "canonical" => \Request::fullUrl(),
            "h1" => "All $ram GB RAM Mobile Phones Price in {$country->country_name}",
            "name" => "Mobile phones with $ram GB RAM"
        ];
        
        
        $products = Product::whereHas('attributes', function ($query) use ($ram) {
            $query->where('attribute_id', 76)->where('value', 'like', $ram.'GB');
        });
        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country);
        }


        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products','country'))->render();
        }
        $category = Category::find(1);
        $products = $products->simplepaginate(32);
        return view("frontend.filter",compact('products','metas','category','country')); 
    }

    public function underRom($country_code,$rom){
        $country = $this->getCountry();
        $metas = (object)[
            "title" => "Latest {$rom}GB Storage Mobile Phones Price in {$country->country_name}",
            "description" => "Discover top {$rom}GB storage smartphones on MobileKiShop. Compare specs, features, and prices in {$country->country_name}. Read expert reviews and make an informed choice. Shop now! .",
            "canonical" => \Request::fullUrl(),
            "h1" => "Best {$rom}GB Storage Smartphones in {$country->country_name}: Top Picks & Deals",
            "name" => "Mobile phones with {$rom}GB Storage"
        ];
        
        $products = Product::whereHas('attributes', function ($query) use ($rom) {
            $query->where('attribute_id', 77)->where('value', 'like', $rom.'GB');
        });
        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all());
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products','country'))->render();
        }
        $category = Category::find(1);
        $products = $products->simplepaginate(32);

        return view("frontend.filter",compact('products','metas','category','country','rom')); 
    }
    public function mobilePhonesScreen(Request $request, $country_code, $maxSize){

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
            $metas = (object)[
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
    public function mobilePhonesNumberCamera($country_code,$parameter){
        $productsPerPage = 32;
        $parameterMap = [
            'dual' => 2,
            'triple' => 3,
            'quad' => 4
        ];

        $parameter = strtolower($parameter);
        if (array_key_exists($parameter, $parameterMap)) {
            $number = $parameterMap[$parameter];
        }
        $parameter = Str::title($parameter);
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object)[
            "title" => "Latest {$parameter} Camera Mobile Phones Price in {$country->country_name}",
            "description" => "Find the latest mobile phones on the Mobilekishop with {$parameter} Camera and specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "{$parameter} Camera Mobile Phones in {$country->country_name}",
            "name" => "Mobile phones with {$parameter} Camera"
        ];
        
        $products = Product::whereHas('attributes', function ($query) use ($number) {
            $query->where('attribute_id', 74)->where('value',$number);
        });

        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country->id);
        }
        
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products','country'))->render();
        }
        $products = $products->simplepaginate($productsPerPage);
        $category = Category::find(1);
        return view("frontend.filter",compact('products','metas','category','country')); 
    }
    public function mobilePhonesUnderCamera($country_code,$camera){
        $productsPerPage = 32;
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object)[
            "title" => "Latest {$camera}MP Camera Mobile Phones Price in {$country->country_name}",
            "description" => "Find the latest mobile phones on the Mobilekishop with {$camera}MP Camera and specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "{$camera}MP Camera Mobile Phones in {$country->country_name}",
            "name" => "Mobile phones with {$camera}MP Camera"
        ];
        $category = Category::find(1);
        $products = Product::whereHas('attributes', function ($query) use ($camera) {
            $query->where('attribute_id', 73)->where('value',$camera);
        });
        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products','country'))->render();
        }
        $products = $products->simplepaginate(32);
        return view("frontend.filter",compact('products','metas','category','country')); 
    }
    public function compare($country_code, $slug,$slug1=null){
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $validator = Validator::make(['slug' => $slug], [
            'slug' => 'required|string',
        ]);

        if ($validator->fails()) {
            return abort(404);
        }

        $slugs = explode('-vs-', $slug);

        $products = Product::whereIn('slug', $slugs)
            ->orderByRaw(DB::raw("FIELD(slug, '" . implode("','", $slugs) . "')"))
            ->get();
        
        if ($products->isEmpty()) {
            return abort(404);
        }

        $product = $products->firstWhere('slug', $slugs[0]);
        $product1 = isset($slugs[1]) ? $products->firstWhere('slug', $slugs[1]) : null;
        $product2 = isset($slugs[2]) ? $products->firstWhere('slug', $slugs[2]) : null;

        if (!$product) {
            return abort(404);
        }

        $h1 = $products->pluck('name')->join(' VS ');

        $description = "Compare all the specifications, features, and price of " . $products->pluck('name')->join(', ') . " on the Mobilekishop.";
        
        $compares = Compare::whereIn('product1', $slugs)
                            ->orWhereIn('product2', $slugs)
                            ->inRandomOrder()
                            ->limit(6)
                            ->get();
        if ($product && $product1 && !$product2) {
            $title = "{$product->name} vs {$product1->name} Specs & Prices";
            $description = "Compare {$product->name} vs {$product1->name} by specs, features, and prices. Detailed reviews to find the best choice for you.";
            $h1 = "{$product->name} vs {$product1->name}  in {$country->country_name}";
        } elseif ($product && $product1 && $product2) {
            $title = "{$product->name} vs {$product1->name} vs {$product2->name} Specs & Prices";
            $description = "Compare {$product->name} vs {$product1->name} vs {$product2->name} by specs, features, and prices. Detailed reviews to find the best choice for you.";
            $h1 = "{$product->name} vs {$product1->name} vs {$product2->name} in {$country->country_name}";
        } else {
            $title = null;
            $description = null;
            $h1 = null;
        }


        $metas = (object) [
            "title" => $title,
            "description" => $description,
            "canonical" => URL::to('/compare/'.$slug),
            "h1" => $h1,
            "name" => "Compare Mobiles"
        ];
        $category = $products->first()->category->slug;
        return view("frontend.compare.$category",compact('product','product1','product2','metas','compares','country'));
    }
    public function search(Request $request){
        $country = app('App\Http\Controllers\CountryController')->getCountry();

        if(!$request->has("query")){
            return redirect()->back()->with("fail","Please try again with different query!");
        }
        $query = $request->get("query");
        $metas = (object)[
            "title" => "Search for $query Price in {$country->country_name}",
            "description" => "Search the mobile phones for $query on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => URL::route('search'),
            "h1" => "Search for $query",
            "name" => "Search for $query"
        ];
        $agent = new Agent();
        DB::table("searches")->insert([
            "query" => $query,
            "user_agent" => "User Agent: "." browser:". $agent->browser()." platform:".$agent->platform()." device:".$agent->device(),
        ]);
        $countryId = $country->id;
        $products = Product::whereHas('variants', function($query) use ($countryId) {
                        $query->where('country_id', $countryId) ->where('price', '>', 0);;
                    });
        $products = $products->search($query);
        $products = $products->simplepaginate(32);
        $category = null;
        
        return view("frontend.search",compact('products','metas','category','country'));   
    }
    public function mobilePhonesCurvedByBrand($country_code,$brand){
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = Page::whereSlug(url()->current())->where("is_active",1)->first();
        
        $brand = Brand::where("slug",$brand)->first();
        if(!$metas){
            $metas = (object)[
                "title" => "{$brand->name} Curved Display Mobile Phones Price in {$country->country_name}",
                "description" => "Find the latest {$brand->name} Curved or Edge Display mobile phones its specifications, features, reviews, comparison, and price in {$country->country_name}.",
                "canonical" => \Request::fullUrl(),
                "h1" => "{$brand->name} Curved Display Mobile Phones in {$country->country_name}",
                "name" => "{$brand->name} Curved Display Mobile phones"
            ];
        }
        
        $products = Product::whereHas('attributes', function ($query) {
            $query->where('attribute_id', 263)->where('value',1);
        });
        if ($brand) {
            $products = $products->where('products.brand_id', $brand->id);
        }
        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country->id);
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products','country'))->render();
        }
        $products = $products->simplepaginate(32);
        $category = Category::find(1);
        return view("frontend.filter",compact('products','metas','category','country')); 
    }
    // mobile phones routes

    // smart watch routes
    public function underAmountWatches($country_code,$amount){
        $productsPerPage = 32;
        $country = $this->getCountry();
        $metas = Page::whereSlug(url()->current())->where("is_active",1)->first();
        if(!$metas){
            $metas = (object)[
                "title" => "Latest Smart Watches Under {$country->currency} {$amount} Price in {$country->country_name}",
                "description" => "Find the latest Smart Watches under {$country->currency} {$amount} on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
                "canonical" => \Request::fullUrl(),
                "h1" => "Smart Watches Under {$amount} in {$country->country_name}",
                "name" => "Smart Watches under {$amount}"
            ];
        }
        $priceSlabs = [
            10000,20000, 30000, 40000, 50000, 
            60000, 70000, 80000, 90000, 100000, 150000];
        $lowerAmount = 0;
        for ($i = 0; $i < count($priceSlabs); $i++) {
            if ($amount == $priceSlabs[$i]) {
                $lowerAmount = $i > 0 ? $priceSlabs[$i - 1] : 0;
                $upperBound = $priceSlabs[$i];
                break;
            }
        }
        
        $products = app('App\Http\Controllers\ProductController')->getProducts($country->id,$lowerAmount,$upperBound);
        $category = Category::find(2);
        if ($category) {
            $products = $products->where('products.category_id', $category->id);
        }
        if(\Request::has('filter')){
            $filter = \Request::all();
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country->id,$lowerAmount,$upperBound);
        }
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products','country'))->render();
        }
        $products = $products->simplepaginate(32);
        
        return view("frontend.filter",compact('products','metas','category','country'));
    }
    // smart watch routes

    // tablets routes
    public function tabletsUnderRam($country_code,$ram){
        $productsPerPage = 32;
        $country = $this->getCountry();
        $metas = (object)[
            "title" => "Latest $ram GB RAM Tablets Price in {$country->country_name}",
            "description" => "Find the latest Tablets with $ram GB RAM on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "Latest $ram GB RAM Tablets Price in {$country->country_name}",
            "name" => "Tablets with $ram GB RAM"
        ];
        $category = Category::find(3);

        $products = $category->products()->whereHas('attributes', function ($query) use ($ram) {
            $query->where('attribute_id', 239)
                ->where('value', $ram."GB");
        });
        
        if ($category) {
            $products = $products->where('products.category_id', $category->id);
        }
        

        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country->id,$lowerAmount,$upperBound);
        }

        $products = $products->paginate($productsPerPage);
        
        
        if (\Request::ajax()) {
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products','country'))->render();
        }else{
            return view("frontend.filter",compact('products','metas','category','country'));
        }
    }
    public function tabletsUnderRom($country_code,$rom){
        $productsPerPage = 32;
        $country = $this->getCountry();
        $metas = (object)[
            "title" => "All $rom GB Storage Tablets Price in {$country->country_name}",
            "description" => "Find the latest Tablets with $rom GB storage on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "$rom GB Storage/ROM Tablets in {$country->country_name}",
            "name" => "Tablets with $rom GB Storage"
        ];
        
        $category = Category::find(3);
        
        $products = $category->products()->whereHas('attributes', function ($query) use ($rom) {
            $query->where('attribute_id', 240)
                ->where('value',$rom."GB");
        });

        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all());
        }
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products'))->render();
        }
        $products = $products->simplepaginate(32);
        return view("frontend.filter",compact('products','metas','category','country')); 
    }
    public function tabletsUnderPrice($country_code,$price){
        $productsPerPage = 32;
        $country = $this->getCountry();
        $metas = Page::whereSlug(url()->current())->where("is_active",1)->first();
        if(!$metas){
            $metas = (object)[
                "title" => "Latest Tablets Under {$country->currency} $price Price in {$country->country_name}",
                "description" => "Find the latest Tablets under {$country->currency} $price on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
                "canonical" => \Request::fullUrl(),
                "h1" => "Tablets Under $price in {$country->country_name}",
                "name" => "Tablets under $price"
            ];
        }
        $category = Category::find(3);
        $priceSlabs = [
            15000,20000, 25000, 30000, 35000, 40000, 45000, 50000, 
            60000, 70000, 80000, 90000, 100000, 150000, 
            200000, 250000, 300000, 350000, 400000, 500000, 600000, 700000,800000,900000,1000000
        ];
        
        $lowerAmount = 0;
        for ($i = 0; $i < count($priceSlabs); $i++) {
            if ($price == $priceSlabs[$i]) {
                $lowerAmount = $i > 0 ? $priceSlabs[$i - 1] : 0;
                $upperBound = $priceSlabs[$i];
                break;
            }
        }
        
        $products = app('App\Http\Controllers\ProductController')->getProducts($country->id,$lowerAmount,$upperBound);
        if ($category) {
            $products = $products->where('products.category_id', $category->id);
        }
        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country->id,$lowerAmount,$upperBound);
        }

        $products = $products->paginate($productsPerPage);
        
        
        if (\Request::ajax()) {
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products','country'))->render();
        }else{
            return view("frontend.filter",compact('products','metas','category','price','lowerAmount','country'));
        }
    }
    public function tabletsUnderScreen($country_code,$inch){
        $productsPerPage = 32;
        $country = $this->getCountry();
        $metas = (object)[
            "title" => "All $inch Inch Screen Size Tablets Price in {$country->country_name}",
            "description" => "Find the latest Tablets on the Mobilekishop with $inch inch screen size and Their specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "$inch Inch Screen Size Tablets in {$country->country_name}",
            "name" => "Tablets with $inch Inch Screen"
        ];
        
        switch ($inch) {
            case "8":
                $size = [0,9];
                break;
            case "10":
                $size = [9.1,11];
                break;
            case "12":
                $size = [11,13];
                break;
            case "14":
                $size = [13.1,14];
                break;
            case "16":
                $size = [14.1,16];
                break;
            default:
                $size = [0,8]; //default value if $mp doesn't match any case
        }

        $category = Category::find(3);
        $products = $category->products()->where("category_id",3)->whereHas('attributes', function ($query) use ($size) {
            $query->where('attribute_id', 238)->whereBetween('value',$size);
        });
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products','country'))->render();
        }
        if(\Request::has('filter')){
        $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all());
        }
        $products = $products->simplepaginate(32);
        return view("frontend.filter",compact('products','metas','category','country')); 
    }
    public function tabletsUnderCamera($country_code,$mp){
        
        $productsPerPage = 32;
        $country = $this->getCountry();
        $metas = (object)[
            "title" => "All $mp MP Camera Tablets Price in {$country->country_name}",
            "description" => "Find the latest Tablets on the Mobilekishop with $mp MP Camera and specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "$mp MP Camera Tablets in {$country->country_name}",
            "name" => "Tablets with $mp MP Camera"
        ];

        switch ($mp) {
            case "5":
                $pixels = [0,5];
                break;
            case "13":
                $pixels = [6,13];
                break;
            case "24":
                $pixels = [14,24];
                break;
            default:
                $pixels = [0,0]; //default value if $mp doesn't match any case
        }
        $category = Category::find(3);
        $products = $category->products()->where("category_id",3)->whereHas('attributes', function ($query) use ($pixels) {
            $query->where('attribute_id', 236)->whereBetween('value',$pixels);
        });
        $productsPerPage = 32;
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products'))->render();
        }
        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all());
        }
        
        $products = $products->simplepaginate(32);
        return view("frontend.filter",compact('products','metas','category','country')); 
    }
    // tablets routes
    public function htmlSitemap($country_code) {
        $country = app('App\Http\Controllers\CountryController')->getCountry();

        // Define $metas as an instance of stdClass
        $metas = new \stdClass();
        $metas->title = "HTML Sitemap - Mobilekishop";
        $metas->description = "HTML Sitemap - Mobilekishop";
        $metas->canonical = URL::full();

        // Pass variables to the view
        return view("frontend.pages.html-sitemap", compact('country', 'metas'));
    }
}
