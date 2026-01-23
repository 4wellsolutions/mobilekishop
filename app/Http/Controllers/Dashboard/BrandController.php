<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Brand;
use App\Category;
use URL;
use Str;

class BrandController extends Controller
{
    public function index(){
    	$brands = Brand::orderBy("id","DESC")->get();

    	return view("dashboard.brands.index",compact('brands'));
    }
    public function edit($id){
    	$brand = Brand::find($id);
        $categories = Category::all();
    	return view("dashboard.brands.edit",compact('brand','categories'));
    }
    public function create(){
        $categories = Category::all();
        return view("dashboard.brands.create",compact("categories"));
    }
    public function show(){
        dd("asdf");
    }
    public function store(Request $request)
    {
        // Validation
        $this->validate($request, [
            "name"          => "required|unique:brands,name",
            "slug"          => "required",
            "title"         => "required|unique:brands,title",
            "description"   => "required|unique:brands,description",
            "keywords"      => "required",
            "categories"    => "required|array|min:1",
            "body"          => "nullable",
            "is_featured"   => "nullable",
        ]);

        // Store the new brand
        $brand              = new Brand;
        $brand->name        = $request->name;
        $brand->slug        = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        $brand->title       = $request->title;
        $brand->description = $request->description;
        $brand->keywords    = $request->keywords;
        $brand->body        = $request->body;
        $brand->is_featured = ($request->is_featured) ? 1 : 0;
        $brand->save();

        $brand->categories()->sync($request->categories);

        // Save thumbnail if available
        if($request->thumbnail) {
            $this->saveImage($request->thumbnail, $brand->slug . ".jpg", $brand);
        }

        // Redirect back with success message
        return redirect()->route('dashboard.brands.index')->with('success', "Brand created successfully.");
    }

    public function update(Request $request,$id){

    	$this->validate($request,[
    		"name"        => "required",
    		"slug"        => "required",
            "categories"  => "required|array|min:1",
    		"title"       => "required",
    		"description" => "required",
    		"keywords"    => "required",
    		"body"        => "nullable",
            "is_featured" => "nullable",
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
    	]);

    	$brand                 = Brand::find($id);
    	$brand->name           = $request->name;
    	$brand->slug           = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
    	$brand->title          = $request->title;
    	$brand->description    = $request->description;
    	$brand->keywords       = $request->keywords;
    	$brand->body           = $request->body;
        $brand->is_featured    = ($request->is_featured) ? 1 : 0;
    	$brand->update();

        $brand->categories()->sync($request->categories);
        // dd($request->all());
        if($request->thumbnail){
            $this->saveImage($request->thumbnail, $brand->slug.".jpg",$brand);
        }
    	return redirect()->route('dashboard.brands.index')->with('success',"Branded updated.");
    }
    public function saveImage($image,$name,$brand){

        $path = $_SERVER['DOCUMENT_ROOT']."/brands/$name";
        $mainImage = Image::make($image);
        $mainImage->save($path);
        // dd($mainImage);
        //update brand thumbnail path.
        $brand->thumbnail = URL::to("/brands/$name");;
        $brand->update();
        //update brand thumbnail path.

        return;
    }
}
