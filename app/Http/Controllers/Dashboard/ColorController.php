<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::paginate(50);
        return view("dashboard.colors.index",compact("colors"));
    }
    public function create(Request $request)
    {
        return view("dashboard.colors.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255|unique:colors'
        ]);

        $color = new Color();
        $color->name = $request->name;
        $color->save();

        return redirect()->route('dashboard.colors.index')
                         ->with('success', 'Color created successfully.');
    }
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $color = Color::findOrFail($id);
        return view('dashboard.colors.edit', compact('color'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $color = Color::findOrFail($id);
        $color->name = $request->name;
        $color->save();

        return redirect()->route('dashboard.colors.index')->with('success', 'Color updated successfully.');
    }

    public function destroy($id)
    {
        $color = Color::findOrFail($id);
        $color->delete();

        return redirect()->route('dashboard.colors.index')->with('success', 'Color deleted successfully.');
    }
}
