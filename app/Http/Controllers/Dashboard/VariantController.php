<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Variant;

class VariantController extends Controller
{
    public function index()
    {
        $variants = Variant::paginate(50);
        return view("dashboard.variants.index",compact("variants"));
    }
    public function create()
    {
        return view("dashboard.variants.create");
    }
    public function store(Request $request)
    {
        $request->validate([
            'variant_name'  => 'required|unique:variants'
        ]);

        $variant = new Variant();
        $variant->variant_name          = $request->variant_name;
        $variant->save();

        return redirect()->route('dashboard.variants.create')
                         ->with('success', 'Variant created successfully.');
    }

    public function edit($id)
    {
        $variant = Variant::find($id);
        return view("dashboard.variants.edit",compact("variant"));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'variant_name' => 'required|unique:variants,variant_name,' . $id
        ]);

        $variant = Variant::find($id);
        $variant->variant_name          = $request->variant_name;
        $variant->update();

        return redirect()->route('dashboard.variants.index')
                         ->with('success', 'Variant updated successfully.');
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
