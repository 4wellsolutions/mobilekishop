<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Compare;
use App\Product;
use Str;
use URL;
use Auth;
use Response;
class CompareController extends Controller
{
    public function index(Request $request){
        if($request->has("search")){
            $query = $request->get("search");
            $query = explode(" ", $query);
            $compares = Compare::limit(15);
            // dd($query);
            foreach($query as $q){
                $q = str_replace(' ', '-', $q);
                $compares = $compares->where('link', 'LIKE', '%'. $q. '%');
            }
            $compares = $compares->paginate(50);
            return view("dashboard.compares.index",compact('compares'));
        }
        $compares = Compare::orderBy("id","DESC")->paginate(50);
        return view("dashboard.compares.index",compact('compares'));
    }
    public function create(Request $request){

        $image = null;
        if($request->has("search1") && $request->has("search2")){
            $product1 = Product::whereSlug($request->get("search1"))->first();
            $product2 = Product::whereSlug($request->get("search2"))->first();
            if($request->has("search3")){
                $product3 = Product::whereSlug($request->get("search3"))->first();
            }
            $image = $this->mergeImages($product1,$product2,$product3);
        }
    	$compares = Compare::limit("10")->orderBy("id","DESC")->get();
    	return view("dashboard.compares.create",compact('compares',"image"));
    }
    public function edit($id){
        $compare = Compare::find($id);
        return view("dashboard.compares.edit",compact('compare'));
    }
    public function update(Request $request, $id){
        $compare = Compare::find($id);
        // dd($request->all());
        if($request->thumbnail){
            $thumbnail = $this->saveImage($request->thumbnail, $compare->alt.".jpg");
            $compare->thumbnail = $thumbnail;
            $compare->created_at = $compare->created_at;
            $compare->update();
        }

        // dd($thumbnail);
        
        return redirect()->route('dashboard.compares.index')->with("success","Compare updated.");
    }
    public function store(Request $request){

    	$this->validate($request,[
    		"url" => "required|unique:App\Compare,link",
            "thumbnail" => "nullable",
    		"thumbnail1" => "nullable|image|mimes:jpeg,png,jpg|max:2048|dimensions:width=640,height=360',"
    	]);
        
        $exists = Compare::where(function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                // Product1 vs Product2
                $q->whereProduct1($request->product1)
                  ->whereProduct2($request->product2);
            })->orWhere(function ($q) use ($request) {
                // Product2 vs Product1
                $q->whereProduct1($request->product2)
                  ->whereProduct2($request->product1);
            });
        })->when($request->filled('product3'), function ($query) use ($request) {
            // If product3 is provided, include it in the check; otherwise, ensure it's null
            return $request->product3 ? $query->whereProduct3($request->product3) : $query->whereNull('product3');
        })->exists();

        if ($exists) {
            return redirect()->back()->with("fail", "Compare already exist.");
        }

    	$name = $request->name;
        $thumbnail = $request->thumbnail;
        if($request->thumbnail1){
    	   $thumbnail = $this->saveImage($request->thumbnail1, $name);
        }
        
    	$compare            = new Compare();
    	$compare->link      = $request->url;
        $compare->alt       = $request->alt;
        $compare->product1  = $request->product1;
        $compare->product2  = $request->product2;
        $compare->product3  = $request->product3;
    	$compare->thumbnail = $thumbnail;
    	$compare->user_id   = Auth::User()->id;
    	$compare->save();

    	return redirect()->route('dashboard.compares.create')->with('success',"URL added succesfully");
    }
    
    public function autocompleteSearch(Request $request)
    {
        
        $query = $request->get("query");
        
        $query = explode(" ", $query);
        $results = Compare::limit(15);
        // dd($query);
        foreach($query as $q){
            $q = str_replace(' ', '-', $q);
            $results = $results->where('link', 'LIKE', '%'. $q. '%');
        }
        $result = json_decode($results->get());
          return Response::make($result);
    } 
    public function saveImage($image,$name) {

        $path = $_SERVER['DOCUMENT_ROOT']."/compare/$name";
        $mainImage = Image::make($image);
        $mainImage->save($path);
        return URL::to('/')."/compare/$name";
    }
    public function mergeImages($product1,$product2=null,$product3=null){
        $vs = $_SERVER['DOCUMENT_ROOT']."/images/vs.jpg";
		$img = Image::canvas(640, 360);
		if($product1 && $product2 && !$product3){
    		$img->insert($vs, 'center');
    		$img->insert($product1->thumbnail, 'left',50);
    		$img->insert($product2->thumbnail, 'right',50);
    		$img->insert($_SERVER['DOCUMENT_ROOT']."/images/logo-small.jpg", 'center-bottom',10,10);
            $img->text(Str::title($product1->name), 140, 40, function($font) { 
                $font->file(public_path('fonts/Staatliches-Regular.ttf'));
                $font->size(25);  
                $font->color('#202124');  
                $font->align('center');  
                $font->valign('top');  
                $font->angle(0);  
            });
            $img->text(Str::title($product2->name), 510, 40, function($font) { 
                $font->file(public_path('fonts/Staatliches-Regular.ttf'));
                $font->size(25);  
                $font->color('#202124');  
                $font->align('center');  
                $font->valign('top');  
                $font->angle(0);  
            });
    		$img->save($_SERVER['DOCUMENT_ROOT']."/compare/$product1->slug-vs-$product2->slug.jpg", 100);
    		$path = URL::to('/')."/compare/$product1->slug-vs-$product2->slug.jpg";
        }else{
            $img->insert($vs, 'left',140);
            $img->insert($vs, 'right',140);
            $img1 = Image::make($product1->thumbnail);
            $img2 = Image::make($product2->thumbnail);
            $img3 = Image::make($product3->thumbnail);
            $img1->resize(120, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img2->resize(120, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img3->resize(120, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->insert($img1, 'left',50);
            $img->insert($img2, 'center');
            $img->insert($img3, 'right',50);
            $img->insert($_SERVER['DOCUMENT_ROOT']."/images/logo.png", 'center-bottom',10,10);
            $img->text(Str::title($product1->name), 110, 60, function($font) { 
                $font->file(public_path('fonts/Staatliches-Regular.ttf'));
                $font->size(20);  
                $font->color('#202124');  
                $font->align('center');  
                $font->valign('top');  
                $font->angle(0);  
            });
            $img->text(Str::title($product2->name), 320, 60, function($font) { 
                $font->file(public_path('fonts/Staatliches-Regular.ttf'));
                $font->size(20);  
                $font->color('#202124');  
                $font->align('center');  
                $font->valign('top');  
                $font->angle(0);  
            });
            $img->text(Str::title($product3->name), 530, 60, function($font) { 
                $font->file(public_path('fonts/Staatliches-Regular.ttf'));
                $font->size(20);  
                $font->color('#202124');  
                $font->align('center');  
                $font->valign('top');  
                $font->angle(0);  
            });
            $img->save($_SERVER['DOCUMENT_ROOT']."/compare/$product1->slug-vs-$product2->slug.jpg", 100);
            $path = URL::to('/')."/compare/$product1->slug-vs-$product2->slug.jpg";
        }
		return $path;
    }
}
