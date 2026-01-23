<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use App\Product;
use App\Brand;
use App\Compare;
use App\Category;
use App\Package;
use Carbon\Carbon;
use View;
use URL;

class SitemapController extends Controller
{
	public function index() {
        $fs = new Filesystem();
        try {
            $result = $fs->put($_SERVER['DOCUMENT_ROOT'] . "/sitemap/sitemap.xml", View::make('sitemaps.index')->render());
            if ($result === false) {
                return response()->json(['message' => 'Failed to write sitemap index file.'], 500);
            } else {
                return response()->json(['message' => 'Sitemap index generated successfully.'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function products() {
        $products = Product::all();
        $xmlContent = View::make('sitemaps.products', compact('products'))->render(); // Ensure this is rendering XML correctly

        $filePath = $_SERVER['DOCUMENT_ROOT'] . "/sitemap/sitemap_products.xml";
        $fs = new Filesystem();

        try {
            $result = $fs->put($filePath, $xmlContent);
            if ($result === false) {
                // Handle error, could not write file
                return response()->json(['message' => 'Failed to write sitemap file.'], 500);
            } else {
                // Success response
                return response()->json(['message' => 'Sitemap generated successfully.'], 200);
            }
        } catch (\Exception $e) {
            // Exception handling, in case of any other errors
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function brands() {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $countryId = $country->id;
        $categories = Category::all();
        $fs = new Filesystem();
        
        try {
            $result = $fs->put($_SERVER['DOCUMENT_ROOT'] . "/sitemap/sitemap_brands.xml", View::make('sitemaps.brands', compact('categories','countryId'))->render());
            if ($result === false) {
                return response()->json(['message' => 'Failed to write sitemap brands file.'], 500);
            } else {
                return response()->json(['message' => 'Sitemap brands generated successfully.'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function filters() {
        $fs = new Filesystem();
        try {
            $result = $fs->put($_SERVER['DOCUMENT_ROOT'] . "/sitemap/sitemap_filters.xml", View::make('sitemaps.filters')->render());
            if ($result === false) {
                return response()->json(['message' => 'Failed to write sitemap filters file.'], 500);
            } else {
                return response()->json(['message' => 'Sitemap filters generated successfully.'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function compare() {
        $compares = Compare::all();
        $fs = new Filesystem();
        try {
            $result = $fs->put($_SERVER['DOCUMENT_ROOT'] . "/sitemap/sitemap_compare.xml", View::make('sitemaps.compare', compact('compares'))->render());
            if ($result === false) {
                return response()->json(['message' => 'Failed to write sitemap compare file.'], 500);
            } else {
                return response()->json(['message' => 'Sitemap compare generated successfully.'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function packages() {
        $packages = Package::orderBy("id", "DESC")->get();
        $fs = new Filesystem();
        try {
            $result = $fs->put($_SERVER['DOCUMENT_ROOT'] . "/sitemap/sitemap_packages.xml", View::make('sitemaps.packages', compact('packages'))->render());
            if ($result === false) {
                return response()->json(['message' => 'Failed to write sitemap packages file.'], 500);
            } else {
                return response()->json(['message' => 'Sitemap packages generated successfully.'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function countryIndex($country_code) {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $countryId = $country->id;
        $fs = new Filesystem();
        try {
            $result = $fs->put($_SERVER['DOCUMENT_ROOT'] . "/sitemap/{$country_code}/sitemap.xml", View::make("sitemaps.{$country_code}.index")->render());
            if ($result === false) {
                return response()->json(['message' => 'Failed to write sitemap index file.'], 500);
            } else {
                return response()->json(['message' => 'Sitemap index generated successfully.'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function countryProducts($country_code){
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $countryId = $country->id;
        $products = Product::whereHas('variants', function($query) use ($countryId) {
                        $query->where('country_id', $countryId)->where('price', '>', 0);
                    })->get();   
        
        $xmlContent = View::make('sitemaps.products', compact('products','country'))->render(); // Ensure this is rendering XML correctly

        $filePath = $_SERVER['DOCUMENT_ROOT'] . "/sitemap/{$country_code}/sitemap_products.xml";
        $fs = new Filesystem();

        try {
            $result = $fs->put($filePath, $xmlContent);
            if ($result === false) {
                // Handle error, could not write file
                return response()->json(['message' => 'Failed to write sitemap file.'], 500);
            } else {
                // Success response
                return response()->json(['message' => 'Sitemap generated successfully.'], 200);
            }
        } catch (\Exception $e) {
            // Exception handling, in case of any other errors
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function countryFilters($country_code){
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $countryId = $country->id;
        $fs = new Filesystem();
        try {
            $result = $fs->put($_SERVER['DOCUMENT_ROOT'] . "/sitemap/{$country_code}/sitemap_filters.xml", View::make("sitemaps.{$country_code}.filters")->render());
            if ($result === false) {
                return response()->json(['message' => 'Failed to write sitemap filters file.'], 500);
            } else {
                return response()->json(['message' => 'Sitemap filters generated successfully.'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function countryBrands($country_code) {
        $country = app('App\Http\Controllers\CountryController')->getCountry();
        $countryId = $country->id;
        $categories = Category::all();
        $fs = new Filesystem();
        try {
            $result = $fs->put($_SERVER['DOCUMENT_ROOT'] . "/sitemap/{$country_code}/sitemap_brands.xml", View::make('sitemaps.brands', compact('categories','countryId'))->render());
            // Use Storage facade for file operations instead of direct filesystem access
            ;
            
            if ($result === false) {
                return response()->json(['message' => 'Failed to write sitemap brands file.'], 500);
            } else {
                return response()->json(['message' => 'Sitemap brands generated successfully.'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function htmlSitemap() {
        $country = app('App\Http\Controllers\CountryController')->getCountry();

        // Define $metas as an instance of stdClass
        $metas = new \stdClass();
        $metas->title = "HTML Sitemap - Mobilekishop";
        $metas->description = "HTML Sitemap - Mobilekishop";
        $metas->canonical = URL::full();

        // Pass variables to the view
        return view("frontend.pages.html-sitemap", compact('country', 'metas'));
    }
}
