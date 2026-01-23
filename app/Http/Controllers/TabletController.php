<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Product;
use App\Category;
use App\Brand;
use App\Page;
use DB;

class TabletController extends Controller
{
    public function tablets4g(){
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object)[
            "title" => "All 4G Network Tablets Price in {$country->country_name}",
            "description" => "Find the latest Tablets with 4G Network on the MKS with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "4G Network Tablets in {$country->country_name}",
            "name" => "4G Network"
        ];
        $category = Category::find(3);
        $products = $category->products()->whereHas('attributes', function ($query) {
            $query->where('attribute_id', 199);
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
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country);
        }
        $products = $products->simplepaginate(32);
        return view("frontend.filter",compact('products','metas','category','country'));   
    }
    public function tablets5g(){
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object)[
            "title" => "All 5G Network Tablets Price in {$country->country_name}",
            "description" => "Find the latest Tablets with 5G Network on the MKS with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "5G Network Tablets in {$country->country_name}",
            "name" => "5G Network"
        ];
        $category = Category::find(3);
        $products = $category->products()->whereHas('attributes', function ($query) {
            $query->where('attribute_id', 200);
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
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country);
        }
        $products = $products->simplepaginate(32);
        return view("frontend.filter",compact('products','metas','category','country'));   
    }
    public function underPrice($price){
        $productsPerPage = 32;
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = Page::whereSlug(url()->current())->where("is_active",1)->first();
        if(!$metas){
            $metas = (object)[
                "title" => "Latest Tablets Under {$country->currency} $price Price in {$country->country_name}",
                "description" => "Find the latest Tablets under {$country->currency} $price on the MKS with specifications, features, reviews, comparison, and price in {$country->country_name}.",
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
    public function underBrandPrice($brand,$price){
        $productsPerPage = 32;
        $country = app('App\Http\Controllers\CountryController')->getCountry();
    	$category = null;
        $category = Category::find(3);
        $priceSlabs = [
            15000,20000, 25000, 30000, 40000, 50000, 
            60000, 70000, 80000, 90000, 100000, 150000, 
            200000, 250000, 300000, 350000, 400000
        ];
        $lowerAmount = 0;
        for ($i = 0; $i < count($priceSlabs); $i++) {
            if ($price == $priceSlabs[$i]) {
                $lowerAmount = $i > 0 ? $priceSlabs[$i - 1] : 0;
                $upperBound = $priceSlabs[$i];
                break;
            }
        }
        $brand = Brand::whereSlug($brand)->first();
        // $metas = Page::whereSlug(url()->current())->where("is_active",1)->first();
        $brand_title = Str::title($brand->name);
        // if(!$metas){
            $metas = (object)[
                "title" => "Latest $brand_title Tablets Under {$country->currency} {$price} price in {$country->country_name}",
                "description" => isset($lowerAmount) ? "Latest $brand_title Tablets priced between {$country->currency}. $lowerAmount and {$country->currency} $price on MKS with specifications, features, reviews, comparison, and price in {$country->country_name}." : "Latest $brand_title Tablets under {$country->currency} $price on MKS with specifications, features, reviews, comparison, and price in {$country->country_name}.",
                "canonical" => \Request::fullUrl(),
                "h1" => "$brand_title Tablets Under $price in {$country->country_name}",
                "name" => "$brand_title Tablets under $price"
            ];
        // }

        $products = app('App\Http\Controllers\ProductController')->getProducts($country->id,$lowerAmount,$price);
        
        if ($category) {
            $products = $products->where('products.category_id', $category->id);
        }
        if ($brand) {
            $products = $products->where('products.brand_id', $brand->id);
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
    public function underRam($ram){
        $productsPerPage = 32;
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object)[
            "title" => "Latest $ram GB RAM Tablets Price in {$country->country_name}",
            "description" => "Find the latest Tablets with $ram GB RAM on the MKS with specifications, features, reviews, comparison, and price in {$country->country_name}.",
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
    public function underRom($rom){
        $productsPerPage = 32;
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object)[
            "title" => "All $rom GB Storage Tablets Price in {$country->country_name}",
            "description" => "Find the latest Tablets with $rom GB storage on the MKS with specifications, features, reviews, comparison, and price in {$country->country_name}.",
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
        $products = $products->simplepaginate(32);
        return view("frontend.filter",compact('products','metas','category','country')); 
    }
    public function underScreen($inch){
        $productsPerPage = 32;
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object)[
            "title" => "All $inch Inch Screen Size Tablets Price in {$country->country_name}",
            "description" => "Find the latest Tablets on the MKS with $inch inch screen size and Their specifications, features, reviews, comparison, and price in {$country->country_name}.",
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
        $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country);
        }
        $products = $products->simplepaginate(32);
        return view("frontend.filter",compact('products','metas','category','country')); 
    }
    public function underCamera($mp){
        $productsPerPage = 32;
        $country = app('App\Http\Controllers\CountryController')->getCountry();
    	$metas = (object)[
            "title" => "All $mp MP Camera Tablets Price in {$country->country_name}",
            "description" => "Find the latest Tablets on the MKS with $mp MP Camera and specifications, features, reviews, comparison, and price in {$country->country_name}.",
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
            return view('includes.products-partial', compact('products','country'))->render();
        }
        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country);
        }
        
        $products = $products->simplepaginate(32);
        return view("frontend.filter",compact('products','metas','category','country')); 
    }
}
