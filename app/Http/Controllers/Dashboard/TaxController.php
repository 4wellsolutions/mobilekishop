<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tax;
class TaxController extends Controller
{

    public function index()
    {   
        $taxes = new Tax();
        if($search = \Request::get("search")){
            $taxes = $taxes->whereHas('product', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })->with('product');
        }

        $taxes = $taxes->paginate(50);
        return view('dashboard.taxes.index', compact('taxes'));
    }

    public function create()
    {
        return view("dashboard.taxes.create");
    }
    public function store(Request $request)
    {
        $request->validate([
            'brand_id'          => 'required|integer',
            'product_id'        => 'required|integer',
            'variant'           => 'nullable|string',
            'tax_on_passport'   => 'nullable|numeric',
            'tax_on_cnic'       => 'nullable|numeric',
        ]);

        // Create a new tax record
        $tax                = new Tax();
        $tax->brand_id      = $request->brand_id;
        $tax->product_id    = $request->product_id;
        $tax->variant       = $request->variant;
        $tax->tax_on_passport = $request->tax_on_passport;
        $tax->tax_on_cnic   = $request->tax_on_cnic;
        $tax->save();

        // Redirect after successful storage
        return redirect()->route('dashboard.taxes.index')->with('success', 'Tax information saved successfully!');
    }
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $tax = Tax::findOrFail($id);
        return view("dashboard.taxes.edit",compact("tax"));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'brand_id' => 'required|integer',
            'product_id' => 'required|integer',
            'variant' => 'nullable|string',
            'tax_on_passport' => 'nullable|numeric',
            'tax_on_cnic' => 'nullable|numeric',
        ]);

        // Fetch the existing tax record
        $tax = Tax::findOrFail($id);

        // Update the tax record with new data
        $tax->brand_id = $request->brand_id;
        $tax->product_id = $request->product_id;
        $tax->variant = $request->variant;
        $tax->tax_on_passport = $request->tax_on_passport;
        $tax->tax_on_cnic = $request->tax_on_cnic;

        // Save the updated tax record
        $tax->save();

        // Redirect after successful update
        return redirect()->route('dashboard.taxes.index')->with('success', 'Tax information updated successfully!');
    }

    public function destroy($id)
    {
        $tax = Tax::findOrFail($id);

        // Delete the tax record
        $tax->delete();

        // Redirect after successful deletion with a success message
        return redirect()->route('dashboard.taxes.index')->with('success', 'Tax information deleted successfully!');
    }
}
