<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CountryService;
use App\Services\ProductService;
use App\Services\MetaService;
use App\Models\Category;
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
    public function show(Request $request)
    {
        // Get category slug from route
        $categoryParam = $request->route('category') ?: $request->route('slug');
        $country = $request->attributes->get('country');

        // Check if category needs lookup
        if ($categoryParam instanceof Category) {
            $category = $categoryParam;
        } else {
            $category = Category::whereSlug($categoryParam)->firstOrFail();
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
