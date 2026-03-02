<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\SearchService;
use App\Services\MetaService;
use Illuminate\Http\Request;

/**
 * Search Controller - Handles product search
 */
class SearchController extends Controller
{
    public function __construct(
        private SearchService $searchService,
        private MetaService $metaService
    ) {
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $country = $request->attributes->get('country');

        if (!$request->filled('q') && !$request->filled('query')) {
            return redirect()->back()->with('fail', 'Please try again with different query!');
        }

        $query = $request->get('q') ?: $request->get('query');

        // Log search
        $this->searchService->logSearch($query);

        // Search products
        $products = $this->searchService->searchProducts($query, $country);

        // Generate meta
        $metas = $this->metaService->generateSearchMeta($query, $country);

        $category = null;

        return view('frontend.search', compact('products', 'metas', 'category', 'country'));
    }
}
