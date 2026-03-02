<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

/**
 * Legacy ProductController — only autocomplete search remains here.
 * Product detail pages are handled by Web\ProductController.
 * Dashboard CRUD is handled by Dashboard\ProductController.
 */
class ProductController extends Controller
{
    /**
     * Autocomplete search for product names.
     * Used by the search bar typeahead across the site.
     */
    public function autocompleteSearch(Request $request)
    {
        $query = $request->get('query', '');
        $escaped = str_replace(['%', '_'], ['\%', '\_'], $query);

        $products = Product::query();

        if ($request->get('category_id')) {
            $products->where('category_id', (int) $request->get('category_id'));
        }

        $results = $products
            ->where('name', 'LIKE', '%' . $escaped . '%')
            ->select('id', 'name', 'slug', 'thumbnail')
            ->limit(10)
            ->get();

        return response()->json($results);
    }
}
