<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;
use App\Brand;

class CableController extends Controller
{
    public function typeCToC(){
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object)[
            "title" => "USB C to USB C cables in {$country->country_name} - Mobilekishop",
            "description" => "Discover best USB C to USB C cables. Get fast charging & data transfer cables designed for durability and compatibility with all devices in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "USB C to USB C Cables in {$country->country_name}",
            "name" => "USB C to USB C Cables "
        ];
        
        $category = Category::find(11);
        $products = $category->products()->whereHas('attributes', function ($query){
        	$query->where('attribute_id', 329)
      			->where('value','like', "%USB C to USB C%");

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
    public function typeAToC(){
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object)[
            "title" => "USB A to USB C cables in {$country->country_name} - Mobilekishop",
            "description" => "Discover best USB A to USB C cables. Get fast charging & data transfer cables designed for durability and compatibility with all devices in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "USB A to USB C Cables in {$country->country_name}",
            "name" => "USB A to USB C Cables "
        ];
        
        $category = Category::find(11);
        $products = $category->products()->whereHas('attributes', function ($query){
        	$query->where('attribute_id', 329)
      			->where('value','like', "%USB A to USB C%");

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
    public function brandWatt($brand,$watt){
        $country 	= app('App\Http\Controllers\CountryController')->getCountry();
        $brand 		= ucwords(strtolower($brand)); // Ensure title case
		$watt 		= strtoupper($watt); // Ensure uppercase WATT

		$metas 		= (object)[
		    "title" => "{$brand} {$watt} Cables in {$country->country_name} - Mobilekishop",
		    "description" => "Discover best {$brand} {$watt} cables. Get fast charging & data transfer cables designed for durability and compatibility with all devices in {$country->country_name}.",
		    "canonical" => \Request::fullUrl(),
		    "h1" => "{$brand} {$watt} Cables in {$country->country_name}",
		    "name" => "{$brand} {$watt} Cables"
		];

        
        $brand = Brand::where("slug",$brand)->first();
        if(!$brand){
        	abort(404);
        }

        $category = Category::find(11);
        $products = $category->products()
        			->where('brand_id', $brand->id)
        			->whereHas('attributes', function ($query) use ($watt){
        				$query->where('attribute_id', 337)
      					->where('value','like', "%". $watt ."%");
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
}
