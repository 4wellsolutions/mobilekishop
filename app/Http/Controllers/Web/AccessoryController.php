<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CountryService;
use App\Services\FilterService;
use App\Services\MetaService;
use App\Services\ProductService;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccessoryController extends Controller
{
    protected $countryService;
    protected $filterService;
    protected $metaService;
    protected $productService;

    public function __construct(
        CountryService $countryService,
        FilterService $filterService,
        MetaService $metaService,
        ProductService $productService
    ) {
        $this->countryService = $countryService;
        $this->filterService = $filterService;
        $this->metaService = $metaService;
        $this->productService = $productService;
    }

    /**
     * Show power banks by capacity
     */
    public function powerBanksByCapacity(Request $request)
    {
        $mah = (int) $request->route('mah');
        $country = $request->attributes->get('country');

        $products = $this->filterService->getPowerBanksByCapacity($mah);

        if ($request->has('filter')) {
            $products = $this->productService->applyFilters($products, $request->input('filter'), $country->id);
        }

        if ($request->ajax()) {
            $products = $products->paginate(32);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }

        $metas = (object) [
            'title' => "Best Power Banks with {$mah}mAh in {$country->country_name}",
            'description' => "Explore our range of {$mah}mAh power banks for reliable charging on the go. Ideal for smartphones, tablets, and laptops with high-capacity needs in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "Power Banks with {$mah}mAh in {$country->country_name}",
            'name' => "Power Banks with {$mah}mAh"
        ];

        $products = $products->simplePaginate(32);
        $category = Category::find(9); // Power Banks
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show phone covers by model
     */
    public function phoneCoversByModel(Request $request)
    {
        $slug = $request->route('slug');
        $country = $request->attributes->get('country');

        $products = $this->filterService->getPhoneCoversByModel($slug);

        if ($request->has('filter')) {
            $products = $this->productService->applyFilters($products, $request->input('filter'), $country->id);
        }

        if ($request->ajax()) {
            $products = $products->paginate(32);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }

        $title = ucwords(str_replace('-', ' ', $slug));

        $metas = (object) [
            'title' => "Best {$title} Phone Cases in {$country->country_name}",
            'description' => "Protect your {$title} with premium phone cases designed for durability, style, and functionality. Buy now in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "{$title} Phone Cases {$country->country_name}",
            'name' => "{$title} Phone Cases"
        ];

        $products = $products->simplePaginate(32);
        $category = Category::find(8); // Phone Covers
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show smart watches under price
     */
    public function smartWatchesUnderPrice(Request $request)
    {
        $amount = (int) $request->route('amount');
        $country = $request->attributes->get('country');
        $countryCode = $country->country_code;

        $products = $this->filterService->getSmartWatchesUnderPrice($amount, $countryCode);

        if ($request->has('filter')) {
            $products = $this->productService->applyFilters($products, $request->input('filter'), $country->id);
        }

        if ($request->ajax()) {
            $products = $products->paginate(32);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }

        $metas = $this->metaService->generatePriceFilterMeta($amount, $country, Category::find(2));

        $products = $products->simplePaginate(32);
        $category = Category::find(2); // Smart Watches (Legacy ID 2)
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show phone covers by brand and model
     */
    public function phoneCoversByBrand(Request $request)
    {
        $brandSlug = $request->route('brand');
        $slug = $request->route('slug');
        $country = $request->attributes->get('country');

        $products = $this->filterService->getPhoneCoversByBrand($brandSlug, $slug);

        if ($request->has('filter')) {
            $products = $this->productService->applyFilters($products, $request->input('filter'), $country->id);
        }

        if ($request->ajax()) {
            $products = $products->paginate(32);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }

        $brand = \App\Brand::whereSlug($brandSlug)->first();
        if (!$brand) {
            abort(404);
        }
        $title = ucwords(str_replace('-', ' ', $slug));

        $metas = (object) [
            'title' => "Best {$brand->brand_name} {$title} Phone Cases in {$country->country_name}",
            'description' => "Shop premium {$brand->brand_name} {$title} cases. High-quality protection and style for your device in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "{$brand->brand_name} {$title} Phone Cases {$country->country_name}",
            'name' => "{$brand->brand_name} {$title} Cases"
        ];

        $products = $products->simplePaginate(32);
        $category = Category::find(8);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'brand', 'filters'));
    }
}
