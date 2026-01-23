<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Attribute;

class AttributeController extends Controller
{

    public function index(Request $request){
        $search = $request->input('search');
        $categoryId = $request->input('category_id');

        // Query the attributes
        $query = Attribute::query();

        // Apply search filter
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('label', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($categoryId) {
            $query->where('category_id', $categoryId); // Adjust 'category_id' to your actual field
        }

        // Fetch the results with pagination
        $attributes = $query->orderBy('id', 'DESC')->paginate(100);

        // Return the view with attributes
        return view('dashboard.attributes.index', compact('attributes'));
    }


    public function create()
    {
        return view("dashboard.attributes.create");
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            "name"          => "required",
            "label"         => "required",
            "category_id"   => "required"
        ]);
        if(Attribute::where("name",$request->name)->where("category_id",$request->category_id)->first()){
            return redirect()->back()->with("fail","Attribute Already Exists!");            
        }
        $attribute = new Attribute();
        $attribute->name        = $request->name;
        $attribute->label       = $request->label;
        $attribute->category_id = $request->category_id;
        $attribute->save();

        return redirect()->route('dashboard.attributes.index')->with("success","Category Saved!");
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $attribute = Attribute::find($id);
        return view("dashboard.attributes.edit",compact("attribute"));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            "name"          => "required",
            "label"         => "required",
            "category_id"   => "required",
            "sequence"      => "required",
            "type"          => "required"
        ]);
        
        if(Attribute::where("name",$request->name)->where("category_id",$request->category_id)->where("id","!=",$id)->first()){
            return redirect()->back()->with("fail","Attribute Already Exists!");            
        }
        $attribute = Attribute::find($id);
        $attribute->name        = $request->name;
        $attribute->label       = $request->label;
        $attribute->category_id = $request->category_id;
        $attribute->sequence    = $request->sequence;
        $attribute->type        = $request->type;
        $attribute->update();

        return redirect()->route('dashboard.attributes.index')->with("success","Category Updated!");
    }
    
    public function destroy($id)
    {
        //
    }
}
