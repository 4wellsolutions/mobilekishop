<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ProductDetailService;
use App\Services\MetaService;
use App\Models\Product;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

/**
 * Product Controller - Handles individual product pages
 */
class ProductController extends Controller
{
    public function __construct(
        private ProductDetailService $productDetailService,
        private MetaService $metaService
    ) {
    }

    /**
     * Show product details
     * 
     * @param Product $product - Automatically injected via route model binding
     */
    public function show(Request $request)
    {
        $country = $request->attributes->get('country');
        $productParam = $request->route('product');

        // Handle Product - Fetch if not already bound
        if ($productParam instanceof Product) {
            $product = $productParam;
        } else {
            $product = Product::where('slug', $productParam)->firstOrFail();
        }

        $agent = new Agent();

        // Get product details with related data
        $productData = $this->productDetailService->getProductDetails($product, $country);

        // Generate meta data
        $metas = $this->metaService->generateProductMeta($product, $country);

        // Use the dedicated view for mobile-phones (has experts rating, gallery, reviews etc.)
        // Fall back to the generic show view for other categories
        $view = $product->category && $product->category->slug === 'mobile-phones' ? 'mobile-phones' : 'show';

        return view("frontend.product.{$view}", [
            'product' => $productData['product'],
            'products' => $productData['related_products'],
            'compares' => $productData['comparisons'],
            'agent' => $agent,
            'category' => $product->category,
            'country' => $country,
            'metas' => $metas,
            'amount' => null
        ]);
    }




    /**
     * Redirect ID-based product requests to slug-based URLs
     */
    public function getRedirect(Request $request)
    {
        $id = $request->route('id');
        $product = Product::findOrFail($id);
        return redirect()->route('product.show', $product->slug);
    }

    /**
     * AJAX Autocomplete for search
     */
    public function autocomplete(Request $request)
    {
        $term = $request->get('term');
        $products = Product::where('name', 'LIKE', "%{$term}%")
            ->limit(10)
            ->get(['id', 'name', 'slug', 'thumbnail']);

        return response()->json($products);
    }
}
