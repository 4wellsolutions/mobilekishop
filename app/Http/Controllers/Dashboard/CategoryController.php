<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view("dashboard.categories.index",compact("categories"));
    }

    public function create()
    {
        return view("dashboard.categories.create");
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            "category_name" => "required|unique:categories",
            "title"         => "required|unique:categories",
            "description"   => "required|unique:categories"
        ]);
        $category = new Category();
        $category->category_name    = $request->category_name;
        $category->slug             = Str::slug($request->category_name);
        $category->title            = $request->title;
        $category->description      = $request->description;
        $category->save();

        return redirect()->route('dashboard.categories.index')->with("success","Category Save!");
    }
    
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        return view("dashboard.categories.edit",compact("category"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            "category_name" => "required|unique:categories,category_name,".$id,
            "title"         => "required",
            "description"   => "required",
        ]);

        $category = Category::find($id);
        
        if($category)
        {
            $category->category_name = $request->category_name;
            $category->slug = Str::slug($request->category_name);
            $category->title = $request->title;
            $category->description = $request->description;
            $category->save();

            return redirect()->route('dashboard.categories.index')->with("success","Category updated!");
        }
        else
        {
            return redirect()->route('dashboard.categories.index')->with("error","Category not found!");
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
