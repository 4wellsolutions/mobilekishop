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
        $brandParam = $request->route('brand');
        $brandSlug = ($brandParam instanceof \App\Brand) ? $brandParam->slug : $brandParam;
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

    /**
     * Show chargers by port type (USB Type A/C)
     */
    public function chargersByPortType(Request $request)
    {
        $portType = $request->route('type');
        $country = $request->attributes->get('country');

        $products = $this->filterService->getChargersByPortType($portType);

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

        $typeName = Str::title(str_replace('-', ' ', $portType));
        $metas = (object) [
            'title' => "{$typeName} – Fast & Reliable Charging Adapters in {$country->country_name}",
            'description' => "Discover {$typeName} designed for fast and efficient power delivery. Compatible with various devices for safe and reliable charging in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "{$typeName} in {$country->country_name}",
            'name' => "{$typeName}"
        ];

        $products = $products->simplePaginate(32);
        $category = Category::find(10); // Chargers
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show chargers by wattage
     */
    public function chargersByWatt(Request $request)
    {
        $watt = (int) $request->route('watt');
        $country = $request->attributes->get('country');

        $products = $this->filterService->getChargersByWatt($watt);

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
            'title' => "{$watt}W Chargers – Fast & Reliable Charging Adapters in {$country->country_name}",
            'description' => "Discover {$watt} Watt chargers designed for fast and efficient power delivery. Compatible with various devices for safe and reliable charging in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "{$watt}Watt Chargers in {$country->country_name}",
            'name' => "{$watt}Watt Chargers"
        ];

        $products = $products->simplePaginate(32);
        $category = Category::find(10); // Chargers
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show chargers by wattage and port type (combined)
     */
    public function chargersByWattAndPortType(Request $request)
    {
        $watt = (int) $request->route('watt');
        $country = $request->attributes->get('country');
        $portType = 'usb-type-c'; // Currently only Type C is used in specific watt routes in sidebar

        $products = $this->filterService->getChargersByWattAndPortType($watt, $portType);

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

        $typeName = Str::upper($watt) . 'W USB Type C';
        $metas = (object) [
            'title' => "{$typeName} Chargers – Fast & Reliable Charging Adapters in {$country->country_name}",
            'description' => "Discover {$typeName} chargers designed for fast and efficient power delivery. Compatible with various devices for safe and reliable charging in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "{$typeName} Chargers in {$country->country_name}",
            'name' => "{$typeName} Chargers"
        ];

        $products = $products->simplePaginate(32);
        $category = Category::find(10); // Chargers
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }
}
