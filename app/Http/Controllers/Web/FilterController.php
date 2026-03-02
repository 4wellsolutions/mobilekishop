<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CountryService;
use App\Services\FilterService;
use App\Services\MetaService;
use App\Services\ProductService;
use App\Services\PriceFilterService;
use App\Models\Category;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    /** @var Category|null Cached mobile phones category */
    private ?Category $mobileCategory = null;

    public function __construct(
        private FilterService $filterService,
        private ProductService $productService,
        private MetaService $metaService
    ) {
    }

    /**
     * Get the Mobile Phones category (cached per request).
     */
    private function getMobileCategory(): Category
    {
        if (!$this->mobileCategory) {
            $this->mobileCategory = Category::find(config('categories.mobile_phones'));
        }
        return $this->mobileCategory;
    }

    /**
     * Common handler for all filter actions.
     * Eliminates duplication across 11 near-identical methods.
     *
     * @param Request $request
     * @param \Illuminate\Database\Eloquent\Builder $productsQuery
     * @param object $metas
     * @param array $extraViewData Additional variables to pass to the view
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\View\View|string
     */
    private function handleFilter(Request $request, $productsQuery, object $metas, array $extraViewData = [])
    {
        $country = $request->attributes->get('country');

        // Apply additional filters if present
        if ($request->has('filter')) {
            $productsQuery = $this->productService->applyFilters($productsQuery, $request->input('filter'), $country->id);
        }

        // Handle AJAX pagination
        if ($request->ajax()) {
            $products = $productsQuery->paginate(32);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }

        // Paginate
        $products = $productsQuery->simplePaginate(32);
        $category = $this->getMobileCategory();
        $filters = collect($request->query());

        return view('frontend.filter', array_merge(
            compact('products', 'metas', 'category', 'country', 'filters'),
            $extraViewData
        ));
    }

    /**
     * Show products under a specific price
     */
    public function underPrice(Request $request)
    {
        $amount = (int) $request->route('amount');
        if ($amount <= 0 || $amount > 10000000)
            abort(404);
        $country = $request->attributes->get('country');

        $products = $this->filterService->getProductsUnderPrice($amount, $country->country_code);
        $metas = $this->metaService->generatePriceFilterMeta($amount, $country);

        return $this->handleFilter($request, $products, $metas);
    }

    /**
     * Show products by brand and under price
     */
    public function brandUnderAmount(Request $request)
    {
        $brandParam = $request->route('brand');
        $brandSlug = ($brandParam instanceof \App\Models\Brand) ? $brandParam->slug : $brandParam;
        $amount = (int) $request->route('amount');
        if ($amount <= 0 || $amount > 10000000)
            abort(404);
        $country = $request->attributes->get('country');

        $products = $this->filterService->getProductsByBrandAndPrice($brandSlug, $amount, $country->country_code);

        $brand = \App\Models\Brand::whereSlug($brandSlug)->first();
        $metas = $this->metaService->generateBrandPriceFilterMeta($brand, $amount, $country);

        return $this->handleFilter($request, $products, $metas, [
            'brand' => $brand,
            'activeBrand' => $brand,
        ]);
    }

    /**
     * Show products by RAM size
     */
    public function byRam(Request $request)
    {
        $ram = (int) $request->route('ram');
        if ($ram <= 0 || $ram > 64)
            abort(404);
        $country = $request->attributes->get('country');

        $products = $this->filterService->getProductsByRam($ram, $country->country_code);
        $metas = $this->metaService->generateRamFilterMeta($ram, $country);

        return $this->handleFilter($request, $products, $metas);
    }

    /**
     * Show products by ROM/Storage size
     */
    public function byRom(Request $request)
    {
        $rom = (int) $request->route('rom');
        if ($rom <= 0 || $rom > 2048)
            abort(404);
        $country = $request->attributes->get('country');

        $products = $this->filterService->getProductsByRom($rom, 'GB', $country->country_code);
        $metas = $this->metaService->generateRomFilterMeta($rom, $country);

        return $this->handleFilter($request, $products, $metas);
    }

    /**
     * Show products by RAM and ROM combination
     */
    public function ramRomCombo(Request $request)
    {
        $ram = (int) $request->route('ram');
        $rom = (int) $request->route('rom');
        if ($ram <= 0 || $ram > 64 || $rom <= 0 || $rom > 2048)
            abort(404);
        $country = $request->attributes->get('country');

        $products = $this->filterService->getProductsByRamRom($ram, $rom, $country->country_code);
        $metas = $this->metaService->generateRamRomFilterMeta($ram, $rom, $country);

        return $this->handleFilter($request, $products, $metas);
    }

    /**
     * Show products by screen size
     */
    public function byScreenSize(Request $request)
    {
        $maxSize = (float) $request->route('maxSize');
        if ($maxSize <= 0 || $maxSize > 20)
            abort(404);
        $country = $request->attributes->get('country');

        $products = $this->filterService->getProductsByScreenSize($maxSize, $country->country_code);
        $metas = $this->metaService->generateScreenFilterMeta($maxSize, $country);

        return $this->handleFilter($request, $products, $metas);
    }

    /**
     * Show products by camera count (dual, triple, quad)
     */
    public function byCameraCount(Request $request)
    {
        $parameter = $request->route('parameter');
        $country = $request->attributes->get('country');

        $products = $this->filterService->getProductsByCameraCount($parameter, $country->country_code);
        $metas = $this->metaService->generateCameraCountFilterMeta($parameter, $country);

        return $this->handleFilter($request, $products, $metas);
    }

    /**
     * Show products by camera megapixels
     */
    public function byCameraMp(Request $request)
    {
        $mp = (int) $request->route('mp');
        if ($mp <= 0 || $mp > 300)
            abort(404);
        $country = $request->attributes->get('country');

        $products = $this->filterService->getProductsByCameraMp($mp, $country->country_code);
        $metas = $this->metaService->generateCameraMpFilterMeta($mp, $country);

        return $this->handleFilter($request, $products, $metas);
    }

    /**
     * Show products by processor type
     */
    public function byProcessor(Request $request)
    {
        $processor = $request->route('processor');
        $country = $request->attributes->get('country');

        $products = $this->filterService->getProductsByProcessor($processor, $country->country_code);
        $metas = $this->metaService->generateProcessorFilterMeta($processor, $country);

        return $this->handleFilter($request, $products, $metas);
    }

    /**
     * Show products by type (folding, flip, 4g, 5g)
     */
    public function byType(Request $request)
    {
        $type = $request->route('type');
        $country = $request->attributes->get('country');

        $products = $this->filterService->getProductsByType($type, $country->country_code);
        $metas = $this->metaService->generateTypeFilterMeta($type, $country);

        return $this->handleFilter($request, $products, $metas);
    }

    /**
     * Show curved screen phones by brand
     */
    public function curvedScreensByBrand(Request $request)
    {
        $brandSlug = $request->route('brandSlug') ?: 'all';
        $country = $request->attributes->get('country');

        $products = $this->filterService->getCurvedScreensByBrand($brandSlug, $country->country_code);

        $brand = $brandSlug !== 'all' ? \App\Models\Brand::whereSlug($brandSlug)->first() : null;
        $metas = $this->metaService->generateCurvedFilterMeta($brand, $country);

        return $this->handleFilter($request, $products, $metas, [
            'brand' => $brand,
        ]);
    }

    /**
     * Show upcoming/unreleased products
     */
    public function upcoming(Request $request)
    {
        $country = $request->attributes->get('country');

        $products = $this->filterService->getUpcomingProducts($country->country_code);
        $metas = $this->metaService->generateUpcomingMeta($country);

        return $this->handleFilter($request, $products, $metas);
    }
}
