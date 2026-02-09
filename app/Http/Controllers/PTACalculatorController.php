<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Tax;
use Response;
use DB;
class PTACalculatorController extends Controller
{
    public function index(){
    	$metas = (object)[
            "title" => "Phone Reviews, Features, News, Price in Pakistan - MKS",
            "description" => "MKS is the best mobile website that provides the latest mobile phone prices in Pakistan with specifications, features, reviews and comparison.",
            "canonical" => "https://mobilekishop.net/pta-calculator",
            "h1" => "Mobile Phones Specifications, Price in Pakistan"
        ];
    	return view("frontend.pages.pta_calculator",compact("metas"));
    }
    public function getProductsByBrandPTA(Request $request){
	    if(!$request->brand_id){
	    	return Response::make(["success" => false, ["error" => "Select Brand"]]);
	    }
	    // $products = Product::where("brand_id",$request->brand_id)->get();
	    $products = Product::where('brand_id', $request->brand_id)
                       ->whereExists(function ($query) {
                           $query->select(DB::raw(1))
                                 ->from('taxes')
                                 ->whereRaw('taxes.product_id = products.id');
                       })
                       ->get();
        if(!$products->isNotEmpty()){
        	return Response::make(["success" => false, ["error" => "No mobile found"]]);	
        }
	    return Response::make(["success" => true, "products" => $products]);
    }
    public function getPTATax(Request $request){

    	if(!$request->brand_id && !$request->product_id){
	    	return Response::make(["success" => false, ["error" => "Select Brand & Product"]]);
	    }

	    $tax = Tax::where("brand_id",$request->brand_id)->where("product_id",$request->product_id)->first();
	 	return Response::make(["success" => true, "tax" => $tax]);   
    }
    
}
