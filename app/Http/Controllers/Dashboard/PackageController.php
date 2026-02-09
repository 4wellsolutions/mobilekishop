<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use Str;

class PackageController extends Controller
{
    public function index(Request $request){
    $query = Package::query();

    
    if ($request->get('filter_network') != null) {
        $query->where('filter_network', $request->input('filter_network'));
    }
    if ($request->get('filter_type') != null) {
        $query->where('filter_type', $request->input('filter_type'));
    }
    if ($request->get('filter_validity') != null) {
        $query->where('filter_validity', $request->input('filter_validity'));
    }
    if ($request->get('filter_data') != null) {
        $query->where('filter_data', '<=', $request->input('filter_data')); 
    }
    if ($request->get('type') != null) {
        $query->where('type', $request->input('type'));
    }
    if ($request->get('search') != null) {
        $query->where('name',"like" ,"%". $request->input('search') ."%");
    }
    $packages = $query->orderBy('id', 'DESC')->paginate(50);

    return view('dashboard.packages.index', compact('packages'));
}

    public function create()
    {
        return view("dashboard.packages.create");
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'              => 'required|unique:packages',
            'filter_network'    => 'required',
            'filter_type'       => 'required',
            'filter_data'       => 'required',
            'filter_validity'   => 'required',
            'price'             => 'required',
            'onnet'             => 'nullable',
            'offnet'            => 'nullable',
            'sms'               => 'nullable',
            'data'              => 'nullable',
            'validity'          => 'required',
            'type'              => 'required',
            'description'       => 'required|unique:packages',
            'method'            => 'required',
            'meta_title'        => 'required|unique:packages',
            'meta_description'  => 'required|unique:packages',
        ]);

        
        $package                    = new Package;
        $package->name              = $validatedData['name'];
        $package->slug              = Str::slug($validatedData['name']);
        $package->filter_network    = $validatedData['filter_network'];
        $package->type              = $validatedData['type'];
        $package->filter_type       = $validatedData['filter_type'];
        $package->filter_data       = $validatedData['filter_data'];
        $package->filter_data       = $validatedData['filter_validity'];
        $package->price             = $validatedData['price'];
        $package->onnet             = $validatedData['onnet'];
        $package->offnet            = $validatedData['offnet'];
        $package->sms               = $validatedData['sms'];
        $package->data              = $validatedData['data'];
        $package->validity          = $validatedData['validity'];
        $package->description       = $validatedData['description'];
        $package->method            = $validatedData['method'];
        $package->meta_title        = $validatedData['meta_title'];
        $package->meta_description  = $validatedData['meta_description'];
        $package->save();

        // Redirect to the dashboard or wherever you want to go
        return redirect()->route('dashboard.packages.create')->with("success","Package saved!");
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $package = Package::find($id);
        return view("dashboard.packages.edit",compact("package"));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name'              => 'required|unique:packages,name,'.$id,
            'filter_network'    => 'required',
            'filter_type'       => 'required',
            'filter_data'       => 'required',
            'filter_validity'   => 'required',
            'price'             => 'required',
            'onnet'             => 'nullable',
            'offnet'            => 'nullable',
            'sms'               => 'nullable',
            'data'              => 'nullable',
            'validity'          => 'required',
            'type'              => 'required',
            'description'       => 'required|unique:packages,description,'.$id,
            'method'            => 'required',
            'meta_title'        => 'required|unique:packages,meta_title,'.$id,
            'meta_description'  => 'required|unique:packages,meta_description,'.$id,
        ]);

        $package = Package::findOrFail($id); // Find the package with the given ID

        $package->name              = $validatedData['name'];
        $package->slug              = Str::slug($validatedData['name']);
        $package->filter_network    = $validatedData['filter_network'];
        $package->filter_type       = $validatedData['filter_type'];
        $package->type              = $validatedData['type'];
        $package->filter_data       = $validatedData['filter_data'];
        $package->filter_validity   = $validatedData['filter_validity'];
        $package->price             = $validatedData['price'];
        $package->onnet             = $validatedData['onnet'];
        $package->offnet            = $validatedData['offnet'];
        $package->sms               = $validatedData['sms'];
        $package->data              = $validatedData['data'];
        $package->validity          = $validatedData['validity'];
        $package->description       = $validatedData['description'];
        $package->method            = $validatedData['method'];
        $package->meta_title        = $validatedData['meta_title'];
        $package->meta_description  = $validatedData['meta_description'];
        $package->save();

        return redirect()->route('dashboard.packages.index')->with("success","Package updated!");
    }

    public function destroy($id)
    {
        //
    }
}
