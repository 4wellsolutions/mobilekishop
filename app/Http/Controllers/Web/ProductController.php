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
    public function show(Product $product, Request $request)
    {
        $country = $request->attributes->get('country');
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
}
