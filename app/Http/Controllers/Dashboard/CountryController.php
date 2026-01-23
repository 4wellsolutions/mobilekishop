<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Country;

class CountryController extends Controller
{
    /**
     * Display a listing of the countries.
     */
    public function index()
    {
        $countries = Country::all();
        return view('dashboard.countries.index', compact('countries'));
    }

    /**
     * Show the form for creating a new country.
     */
    public function create()
    {
        return view('dashboard.countries.create');
    }

    /**
     * Store a newly created country in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'country_name' => 'required|string|max:255',
            'country_code' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'currency' => 'required|string|max:255',
            'iso_currency' => 'required|string',
            'currency_name' => 'nullable|string|max:255',
            'domain' => 'required|string|max:255',
            'locale' => 'nullable|string|max:255',
            'is_menu' => 'required|boolean',
            'is_active' => 'required|boolean',
            'amazon_url' => 'nullable|url|max:255',
            'amazon_tag' => 'nullable|string|max:255',
        ]);

        Country::create($validated);

        return redirect()->route('dashboard.countries.index')
                         ->with('success', 'Country created successfully.');
    }

    /**
     * Display the specified country.
     */
    public function show(Country $country)
    {
        return view('dashboard.countries.show', compact('country'));
    }

    /**
     * Show the form for editing the specified country.
     */
    public function edit(Country $country)
    {
        return view('dashboard.countries.edit', compact('country'));
    }

    /**
     * Update the specified country in storage.
     */
    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'country_name' => 'required|string|max:255',
            'country_code' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'currency' => 'required|string|max:255',
            'iso_currency' => 'required|string',
            'currency_name' => 'nullable|string|max:255',
            'domain' => 'required|string|max:255',
            'locale' => 'nullable|string|max:255',
            'is_menu' => 'required|boolean',
            'is_active' => 'required|boolean',
            'amazon_url' => 'nullable|url|max:255',
            'amazon_tag' => 'nullable|string|max:255',
        ]);

        $country->update($validated);

        return redirect()->route('dashboard.countries.index')
                         ->with('success', 'Country updated successfully.');
    }

    /**
     * Remove the specified country from storage.
     */
    public function destroy(Country $country)
    {
        $country->delete();

        return redirect()->route('dashboard.countries.index')
                         ->with('success', 'Country deleted successfully.');
    }
}
