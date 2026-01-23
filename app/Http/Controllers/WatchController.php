<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Page;
use App\Product;
use App\Brand;
use App\Category;
use DB;

class WatchController extends Controller
{
    public function underAmountByBrand($brand,$amount){
        $productsPerPage = 32;      
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $brand = Str::title($brand);
        $metas = (object)[
            "title" => "Latest $brand Smart Watches Under {$country->currency} $amount in {$country->currency}",
            "description" => "Find the latest $brand Smart Watches under {$country->currency} $amount on the MKS with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "$brand Smart Watches Under $amount in {$country->country_name}",
            "name" => "$brand Smart Watches Under $amount"
        ];
        $brand = Brand::where("slug",$brand)->first();
        $category = Category::find(2);
        $products = $category->products();
        if($brand){
            $products = $products->where("brand_id",$brand->id);
        }

        $products = $products->whereRaw("CAST(price_in_pkr AS DECIMAL(10,2)) <= ?", [$amount]);
        if(\Request::has('filter')){
            $products = app('App\Http\Controllers\HomeController')->sortFilter($products, \Request::all(),$country);
        }
        if (\Request::ajax()) {
            $products = $products->paginate($productsPerPage);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products','country'))->render();
        }

        $products = $products->simplepaginate($productsPerPage);
        return view("frontend.filter",compact('products','brand','metas','category','country'));
    }
    
    public function underAmount($amount){
        $productsPerPage = 32;
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = Page::whereSlug(url()->current())->where("is_active",1)->first();
        if(!$metas){
            $metas = (object)[
                "title" => "Latest Smart Watches Under {$country->currency} {$amount} Price in {$country->country_name}",
                "description" => "Find the latest Smart Watches under {$country->currency} {$amount} on the MKS with specifications, features, reviews, comparison, and price in {$country->country_name}.",
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
        $products = $products->simplepaginate($productsPerPage);
        
        return view("frontend.filter",compact('products','metas','category','country'));
    }
}
