<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ComparisonService;
use App\Services\MetaService;
use App\Category;
use Illuminate\Http\Request;

/**
 * Comparison Controller - Handles product comparisons
 */
class ComparisonController extends Controller
{
    public function __construct(
        private ComparisonService $comparisonService,
        private MetaService $metaService
    ) {
    }

    /**
     * Show comparison page
     */
    public function show(Request $request)
    {
        $country = $request->attributes->get('country');
        $slug = $request->route('slug');

        // Get products for comparison
        $productsData = $this->comparisonService->getProductsForComparison($slug);

        $product = $productsData['product1'];
        $product1 = $productsData['product2'];
        $product2 = $productsData['product3'];

        if (!$product) {
            abort(404);
        }

        // Get related comparisons
        $slugs = explode('-vs-', $slug);
        $compares = $this->comparisonService->getRelatedComparisons($slugs);

        // Generate meta
        $metas = $this->metaService->generateComparisonMeta($product, $product1, $product2, $country);

        // Get category view
        $category = $product->category->slug;

        return view("frontend.compare.{$category}", compact('product', 'product1', 'product2', 'metas', 'compares', 'country'));
    }

    /**
     * Show all comparisons
     */
    public function index(Request $request)
    {
        $country = $request->attributes->get('country');

        $compares = $this->comparisonService->getAllComparisons();

        $category = Category::where('slug', 'mobile-phones')->first();

        if (!$category) {
            abort(500, 'Mobile phones category not found');
        }

        $metas = (object) [
            'title' => "Mobile Phone Comparison, Spec, Price in {$country->country_name}",
            'description' => "Get all Mobile Phone Comparison, specifications, features, reviews, prices on the Mobilekishop in {$country->country_name}.",
            'canonical' => url()->current(),
            'h1' => 'Mobile Phones Comparison',
            'name' => 'Comparison'
        ];

        if ($request->ajax()) {
            return view('includes.compare-partial', compact('compares'))->render();
        }

        return view('frontend.comparison', compact('compares', 'metas', 'country', 'category'));
    }
}
