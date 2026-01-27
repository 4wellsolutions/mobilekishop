<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CountryService;
use App\Services\ProductService;
use App\Services\BrandService;
use App\Services\MetaService;
use Illuminate\Http\Request;

/**
 * Brand Controller - Handles brand listing and brand product pages
 */
class BrandController extends Controller
{
    public function __construct(
        private CountryService $countryService,
        private ProductService $productService,
        private BrandService $brandService,
        private MetaService $metaService
    ) {
    }

    /**
     * Show products by brand
     */
    public function show($countrySlug, $brand, $categorySlug = null, Request $request)
    {
        $country = $request->attributes->get('country');

        // Handle Brand (Model or String)
        if (!($brand instanceof \App\Brand)) {
            // If not bound (passed as string), fetch it
            $brandSlug = $brand;
            $brand = $this->brandService->getBrandBySlug($brandSlug);
        }

        if (!$brand) {
            abort(404);
        }

        // Get category if provided
        $category = null;
        if ($categorySlug) {
            $category = \App\Category::whereSlug($categorySlug)->first();
            if (!$category) {
                abort(404);
            }
        }

        // Get products
        $products = $this->productService->getProductsByBrand(
            $brand,
            $country,
            $category,
            $request->get('filter', [])
        );

        $products = $products->simplePaginate(24);

        // Generate meta
        $metas = $this->metaService->generateBrandMeta($brand, $category, $country);

        // Handle AJAX
        if ($request->ajax()) {
            return view('includes.products-partial', compact('products', 'country'))->render();
        }

        return view('frontend.brand', compact('products', 'brand', 'metas', 'category', 'country'));
    }
}
