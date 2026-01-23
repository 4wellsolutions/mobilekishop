<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;
use App\Brand;

class CountryChargerController extends Controller
{
	public function capacity($country,$watt){
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object)[
            "title" => "{$watt}W Chargers – Fast & Reliable Charging Adapters in {$country->country_name}",
            "description" => "Discover {$watt} Watt chargers designed for fast and efficient power delivery. Compatible with various devices for safe and reliable charging in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "{$watt}Watt Chargers in {$country->country_name}",
            "name" => "{$watt}Watt Chargers "
        ];
        $category 		= Category::find(10);
        $watt_values 	= [0,15, 20, 25, 35, 45, 65, 75, 100, 120, 150, 180, 200, 240];
        $lowerWatt 		= $this->getLowerValue($watt, $watt_values);
        
        $products = $category->products()->whereHas('attributes', function ($query) use ($lowerWatt,$watt) {
        	$query->where('attribute_id', 323)
      			->whereBetween('value', [$lowerWatt, $watt]);

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
    public function typeACharger(){
    	$country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object)[
            "title" => "USB Type A Chargers – Fast & Reliable Charging Adapters in {$country->country_name}",
            "description" => "Discover USB Type A chargers designed for fast and efficient power delivery. Compatible with various devices for safe and reliable charging in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "USB Type A Chargers in {$country->country_name}",
            "name" => "USB Type A Chargers "
        ];
        $category 		= Category::find(10);
        
        $products = $category->products()->whereHas('attributes', function ($query) {
        	$query->where('attribute_id', 315)
      			->where('value','like', "%USB Type A%");
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
    public function typeCCharger(){
    	$country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object)[
            "title" => "USB Type C Chargers – Fast & Reliable Charging Adapters in {$country->country_name}",
            "description" => "Discover USB Type C chargers designed for fast and efficient power delivery. Compatible with various devices for safe and reliable charging in {$country->country_name}.",
            "canonical" => \Request::fullUrl(),
            "h1" => "USB Type C Chargers in {$country->country_name}",
            "name" => "USB Type C Chargers "
        ];
        $category 		= Category::find(10);
        
        $products = $category->products()->whereHas('attributes', function ($query) {
        	$query->where('attribute_id', 315)
      			->where('value','like', "%USB Type C%");
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
    public function wattTypeCCharger($country,$watt){
    	$country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object)[
		    "title" => strtoupper($watt) . " USB Type C Chargers – Fast & Reliable Charging Adapters in {$country->country_name}",
		    "description" => "Discover " . strtoupper($watt) . " USB Type C chargers designed for fast and efficient power delivery. Compatible with various devices for safe and reliable charging in {$country->country_name}.",
		    "canonical" => \Request::fullUrl(),
		    "h1" => strtoupper($watt) . " USB Type C Chargers in {$country->country_name}",
		    "name" => strtoupper($watt) . " USB Type C Chargers "
		];

        $category 		= Category::find(10);

        $watt = str_replace('w', '', $watt);
        $watt_values 	= [0,30,45,60,65,67,140];
        $lowerWatt 		= $this->getLowerValue($watt, $watt_values);
        
        
        $products = $category->products()
		    ->whereHas('attributes', function ($query) use ($watt, $lowerWatt) {
		        $query->where('attribute_id', 323)
		              ->whereBetween('value', [$lowerWatt, $watt]);
		    })
		    ->whereHas('attributes', function ($query) {
		        $query->where('attribute_id', 315)
		              ->where('value', 'like', "%USB Type C%");
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
    public function brandWattCharger($country,$brand,$watt){
    	$country = app('App\Http\Controllers\CountryController')->getCountry();
        $metas = (object)[
		    "title" => ucwords($brand) . ' ' . strtoupper($watt) . " Chargers – Fast & Reliable Charging Adapters in {$country->country_name}",
		    "description" => "Discover " . ucwords($brand) . ' ' . strtoupper($watt) . " chargers designed for fast and efficient power delivery. Compatible with various devices for safe and reliable charging in {$country->country_name}.",
		    "canonical" => \Request::fullUrl(),
		    "h1" => ucwords($brand) . ' ' . strtoupper($watt) . " Chargers in {$country->country_name}",
		    "name" => ucwords($brand) . ' ' . strtoupper($watt) . " Chargers "
		];

        $category 		= Category::find(10);

        $watt = str_replace('w', '', $watt);
        $watt_values 	= [0,15, 20, 25, 35, 45, 65, 75, 100, 120, 150, 180, 200, 240];
        $lowerWatt 		= $this->getLowerValue($watt, $watt_values);
        
        $brand = Brand::where("slug",$brand)->first();
        if(!$brand){
        	abort(404);
        }
        
        $products = $category->products()
		    ->where('brand_id', $brand->id)
		    ->whereHas('attributes', function ($query) use ($watt, $lowerWatt) {
		        $query->where('attribute_id', 323)
		              ->whereBetween('value', [$lowerWatt, $watt]);
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
    public function getLowerValue($value, $watt_values) {
	    // Sort the array to ensure it's in ascending order
	    sort($watt_values);

	    // Find the first value in the array that is greater than or equal to the passed value
	    foreach ($watt_values as $index => $watt) {
	        if ($watt >= $value) {
	            // If the value is found to be the first element (15 in your case)
	            if ($index === 0 && $watt == $value) {
	                return null; // or you can return the same value, depending on your use case
	            }

	            // If the value is greater than or equal to the input value, return the previous element
	            return $watt_values[$index - 1]+1; 
	        }
	    }

	    // If no matching value is found (the value is higher than all array values)
	    return null;
	}
}
