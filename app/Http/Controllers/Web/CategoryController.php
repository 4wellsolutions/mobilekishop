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
     * Handles both /category/{slug} and /{country}/category/{slug}
     */
    public function show(Request $request, $p1, $p2 = null)
    {
        // Resolve category based on parameter type (handling localized vs global routes)
        if ($p1 instanceof Category) {
            $category = $p1;
        } elseif ($p2 instanceof Category) {
            $category = $p2;
        } else {
            // Fallback if binding didn't happen (should not occur with web middleware active)
            $category = $p2 ?: $p1;
        }

        // Get country from request (set by middleware)
        $country = $request->attributes->get('country');

        // Check if category is resolved via binding or needs lookup
        if (!($category instanceof Category)) {
            $category = Category::whereSlug($category)->firstOrFail();
        }

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
