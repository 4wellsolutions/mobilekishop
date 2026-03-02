<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\FilterService;
use App\Services\MetaService;
use App\Services\ProductService;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccessoryController extends Controller
{
    public function __construct(
        private FilterService $filterService,
        private MetaService $metaService,
        private ProductService $productService
    ) {
    }

    /**
     * Common handler for all accessory filter actions.
     */
    private function handleFilter(Request $request, $productsQuery, object $metas, string $categoryConfigKey, array $extraViewData = [])
    {
        $country = $request->attributes->get('country');

        if ($request->has('filter')) {
            $productsQuery = $this->productService->applyFilters($productsQuery, $request->input('filter'), $country->id);
        }

        if ($request->ajax()) {
            $products = $productsQuery->paginate(32);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }

        $products = $productsQuery->simplePaginate(32);
        $category = Category::find(config("categories.{$categoryConfigKey}"));
        $filters = collect($request->query());

        return view('frontend.filter', array_merge(
            compact('products', 'metas', 'category', 'country', 'filters'),
            $extraViewData
        ));
    }

    /**
     * Show power banks by capacity
     */
    public function powerBanksByCapacity(Request $request)
    {
        $mah = (int) $request->route('mah');
        if ($mah <= 0 || $mah > 100000)
            abort(404);
        $country = $request->attributes->get('country');

        $products = $this->filterService->getPowerBanksByCapacity($mah, $country->country_code);

        $metas = (object) [
            'title' => "Best Power Banks with {$mah}mAh in {$country->country_name}",
            'description' => "Explore our range of {$mah}mAh power banks for reliable charging on the go. Ideal for smartphones, tablets, and laptops with high-capacity needs in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "Power Banks with {$mah}mAh in {$country->country_name}",
            'name' => "Power Banks with {$mah}mAh"
        ];

        return $this->handleFilter($request, $products, $metas, 'power_banks');
    }

    /**
     * Show phone covers by model
     */
    public function phoneCoversByModel(Request $request)
    {
        $slug = $request->route('slug');
        $country = $request->attributes->get('country');

        $products = $this->filterService->getPhoneCoversByModel($slug, $country->country_code);

        $title = ucwords(str_replace('-', ' ', $slug));

        $metas = (object) [
            'title' => "Best {$title} Phone Cases in {$country->country_name}",
            'description' => "Protect your {$title} with premium phone cases designed for durability, style, and functionality. Buy now in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "{$title} Phone Cases {$country->country_name}",
            'name' => "{$title} Phone Cases"
        ];

        return $this->handleFilter($request, $products, $metas, 'phone_covers');
    }

    /**
     * Show smart watches under price
     */
    public function smartWatchesUnderPrice(Request $request)
    {
        $amount = (int) $request->route('amount');
        if ($amount <= 0 || $amount > 10000000)
            abort(404);
        $country = $request->attributes->get('country');

        $products = $this->filterService->getSmartWatchesUnderPrice($amount, $country->country_code);
        $metas = $this->metaService->generatePriceFilterMeta($amount, $country, Category::find(config('categories.smart_watches')));

        return $this->handleFilter($request, $products, $metas, 'smart_watches');
    }

    /**
     * Show phone covers by brand and model
     */
    public function phoneCoversByBrand(Request $request)
    {
        $brandParam = $request->route('brand');
        $brandSlug = ($brandParam instanceof \App\Models\Brand) ? $brandParam->slug : $brandParam;
        $slug = $request->route('slug');
        $country = $request->attributes->get('country');

        $products = $this->filterService->getPhoneCoversByBrand($brandSlug, $slug, $country->country_code);

        $brand = \App\Models\Brand::whereSlug($brandSlug)->first();
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

        return $this->handleFilter($request, $products, $metas, 'phone_covers', ['brand' => $brand]);
    }

    /**
     * Show chargers by port type (USB Type A/C)
     */
    public function chargersByPortType(Request $request)
    {
        $portType = $request->route('type');
        $country = $request->attributes->get('country');

        $products = $this->filterService->getChargersByPortType($portType, $country->country_code);

        $typeName = Str::title(str_replace('-', ' ', $portType));
        $metas = (object) [
            'title' => "{$typeName} – Fast & Reliable Charging Adapters in {$country->country_name}",
            'description' => "Discover {$typeName} designed for fast and efficient power delivery. Compatible with various devices for safe and reliable charging in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "{$typeName} in {$country->country_name}",
            'name' => "{$typeName}"
        ];

        return $this->handleFilter($request, $products, $metas, 'chargers');
    }

    /**
     * Show chargers by wattage
     */
    public function chargersByWatt(Request $request)
    {
        $watt = (int) $request->route('watt');
        if ($watt <= 0 || $watt > 500)
            abort(404);
        $country = $request->attributes->get('country');

        $products = $this->filterService->getChargersByWatt($watt, $country->country_code);

        $metas = (object) [
            'title' => "{$watt} Watt Chargers – Fast & Reliable Charging Adapters in {$country->country_name}",
            'description' => "Discover {$watt} Watt chargers designed for fast and efficient power delivery. Compatible with various devices for safe and reliable charging in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "{$watt} Watt Chargers in {$country->country_name}",
            'name' => "{$watt} Watt Chargers"
        ];

        return $this->handleFilter($request, $products, $metas, 'chargers');
    }

    /**
     * Show chargers by wattage and port type (combined)
     */
    public function chargersByWattAndPortType(Request $request)
    {
        $watt = (int) $request->route('watt');
        if ($watt <= 0 || $watt > 500)
            abort(404);
        $country = $request->attributes->get('country');
        $portType = 'usb-type-c';

        $products = $this->filterService->getChargersByWattAndPortType($watt, $portType, $country->country_code);

        $typeName = $watt . ' Watt USB Type C';
        $metas = (object) [
            'title' => "{$typeName} Chargers – Fast & Reliable Charging Adapters in {$country->country_name}",
            'description' => "Discover {$typeName} chargers designed for fast and efficient power delivery. Compatible with various devices for safe and reliable charging in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "{$typeName} Chargers in {$country->country_name}",
            'name' => "{$typeName} Chargers"
        ];

        return $this->handleFilter($request, $products, $metas, 'chargers');
    }

    /**
     * Show cables by type (e.g., usb-c-to-usb-c, usb-a-to-usb-c)
     */
    public function cablesByType(Request $request)
    {
        $slug = $request->route('slug');
        $country = $request->attributes->get('country');

        $products = $this->filterService->getCablesByType($slug, $country->country_code);

        $title = Str::title(str_replace('-', ' ', $slug));

        $metas = (object) [
            'title' => "{$title} Cables – Premium Quality in {$country->country_name}",
            'description' => "Shop {$title} cables for fast and reliable data transfer and charging. Premium quality cables available in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "{$title} Cables in {$country->country_name}",
            'name' => "{$title} Cables"
        ];

        return $this->handleFilter($request, $products, $metas, 'cables');
    }

    /**
     * Show cables by brand and wattage (e.g., anker-15w-cables)
     */
    public function cablesByBrandAndWatt(Request $request)
    {
        $brand = $request->route('brand');
        $brandSlug = ($brand instanceof \App\Models\Brand) ? $brand->slug : $brand;
        $watt = (int) $request->route('watt');
        if ($watt <= 0 || $watt > 500)
            abort(404);
        $country = $request->attributes->get('country');

        $products = $this->filterService->getCablesByBrandAndWatt($brandSlug, $watt, $country->country_code);

        $brandName = Str::title(str_replace('-', ' ', $brandSlug));

        $metas = (object) [
            'title' => "{$brandName} {$watt}W Cables – Fast Charging in {$country->country_name}",
            'description' => "Discover {$brandName} {$watt}W cables for fast charging and data transfer. Premium quality available in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "{$brandName} {$watt}W Cables in {$country->country_name}",
            'name' => "{$brandName} {$watt}W Cables"
        ];

        return $this->handleFilter($request, $products, $metas, 'cables');
    }
}
