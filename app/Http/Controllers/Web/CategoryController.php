<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CountryService;
use App\Services\ProductService;
use App\Services\MetaService;
use App\Category;
use Illuminate\Http\Request;

/**
 * Example of new controller architecture
 * This demonstrates how controllers should be thin and delegate to services
 */
class CategoryController extends Controller
{
    public function __construct(
        private CountryService $countryService,
        private ProductService $productService,
        private MetaService $metaService
    ) {
    }

    /**
     * Show products in a category
     */
    public function show(string $slug, Request $request)
    {
        // Get country from request (set by middleware)
        $country = $request->attributes->get('country');

        // Find category or fail
        $category = Category::whereSlug($slug)->firstOrFail();

        // Get products using service
        $products = $this->productService->getProductsByCategory(
            $category,
            $country,
            $request->get('filter', [])
        );

        // Paginate results
        $products = $products->simplePaginate(32);

        // Generate meta data
        $metas = $this->metaService->generateCategoryMeta($category, $country);

        // Handle AJAX requests
        if ($request->ajax()) {
            return view('includes.products-partial', compact('products', 'country'))->render();
        }

        // Return view
        return view('frontend.category', compact('products', 'metas', 'category', 'country'));
    }
}
