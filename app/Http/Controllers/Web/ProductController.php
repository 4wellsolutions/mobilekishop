<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ProductDetailService;
use App\Services\MetaService;
use App\Product;
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
        $productSlug = $request->route('product') ?: $request->route('slug');

        // Handle Product - Fetch if not bound
        $product = Product::where('slug', $productSlug)->firstOrFail();

        $agent = new Agent();

        // Get product details with related data
        $productData = $this->productDetailService->getProductDetails($product, $country);

        // Generate meta data
        $metas = $this->metaService->generateProductMeta($product, $country);

        // Get the view based on category
        $view = $product->category->slug;

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
     * Show embedded product view
     */
    public function showEmbed(Request $request)
    {
        $slug = $request->route('product') ?: $request->route('slug');
        $product = Product::where("slug", $slug)->firstOrFail();
        return view("frontend.embed.mobile", compact('product'));
    }

    /**
     * Show embedded product view with CTA button
     */
    public function showEmbedWithButton(Request $request)
    {
        $slug = $request->route('product') ?: $request->route('slug');
        $product = Product::where("slug", $slug)->firstOrFail();
        return view("frontend.embed.mobile-with-button", compact('product'));
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
            ->where('is_active', 1)
            ->limit(10)
            ->get(['id', 'name', 'slug']);

        return response()->json($products);
    }
}
