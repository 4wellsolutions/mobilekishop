<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CountryService;
use App\Services\FilterService;
use App\Services\MetaService;
use App\Services\ProductService;
use App\Services\PriceFilterService;
use App\Category;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    protected $countryService;
    protected $filterService;
    protected $metaService;
    protected $productService;
    protected $priceFilterService;

    public function __construct(
        CountryService $countryService,
        FilterService $filterService,
        MetaService $metaService,
        ProductService $productService,
        PriceFilterService $priceFilterService
    ) {
        $this->countryService = $countryService;
        $this->filterService = $filterService;
        $this->metaService = $metaService;
        $this->productService = $productService;
        $this->priceFilterService = $priceFilterService;
    }

    /**
     * Show products under a specific price
     */
    public function underPrice(Request $request, int $amount)
    {
        $country = $request->attributes->get('country');
        $countryCode = $country->country_code;
        $filters = collect($request->input('filter', [])); // Pass as collection
        if ($filters->isEmpty() && $request->has('min')) {
            $filters = collect($request->all());
        }

        // Get products
        $products = $this->filterService->getProductsUnderPrice($amount, $countryCode);

        // Apply additional filters if present
        if ($request->has('filter')) {
            $products = $this->productService->applyFilters($products, $request->input('filter'), $country->id);
            $filters = collect($request->input('filter'));
        }

        // Handle AJAX pagination
        if ($request->ajax()) {
            $products = $products->paginate(32);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }

        // Get metadata
        $metas = $this->metaService->generateForPriceFilter($amount, $country);

        // Get price slabs for sidebar
        $priceSlabs = $this->priceFilterService->getPriceSlabsForCountry($countryCode);

        // Paginate
        $products = $products->simplePaginate(32);
        $category = Category::find(1); // Mobile phones category

        // Pass filters explicitly
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'priceSlabs', 'filters'));
    }

    /**
     * Show products by RAM size
     */
    public function byRam(Request $request, int $ram)
    {
        $country = $request->attributes->get('country');

        // Get products
        $products = $this->filterService->getProductsByRam($ram);

        // Apply additional filters
        if ($request->has('filter')) {
            $products = $this->productService->applyFilters($products, $request->input('filter'), $country->id);
        }

        // Handle AJAX
        if ($request->ajax()) {
            $products = $products->paginate(32);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }

        // Metadata
        $metas = (object) [
            'title' => "Best {$ram}GB RAM Smartphones in {$country->country_name}: Prices, Specs & Deals",
            'description' => "Explore top {$ram}GB Memory smartphones on MobileKiShop. Compare specs, features, and prices in {$country->country_name}. Read user reviews and find the best deals. Shop smart today!",
            'canonical' => request()->fullUrl(),
            'h1' => "All {$ram}GB RAM Mobile Phones Price in {$country->country_name}",
            'name' => "Mobile phones with {$ram}GB RAM"
        ];

        $products = $products->simplePaginate(32);
        $category = Category::find(1);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show products by ROM/Storage size
     */
    public function byRom(Request $request, int $rom)
    {
        $country = $request->attributes->get('country');

        $products = $this->filterService->getProductsByRom($rom);

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
            'title' => "Latest {$rom}GB Storage Mobile Phones Price in {$country->country_name}",
            'description' => "Discover top {$rom}GB storage smartphones on MobileKiShop. Compare specs, features, and prices in {$country->country_name}. Read expert reviews and make an informed choice. Shop now!",
            'canonical' => request()->fullUrl(),
            'h1' => "Best {$rom}GB Storage Smartphones in {$country->country_name}: Top Picks & Deals",
            'name' => "Mobile phones with {$rom}GB Storage"
        ];

        $products = $products->simplePaginate(32);
        $category = Category::find(1);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'rom', 'filters'));
    }

    /**
     * Show products by RAM and ROM combination
     */
    public function ramRomCombo(Request $request, int $ram, int $rom)
    {
        $country = $request->attributes->get('country');

        $products = $this->filterService->getProductsByRamRom($ram, $rom);

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
            'title' => "Mobile Phones with {$ram}GB RAM and {$rom}GB storage in {$country->country_name}",
            'description' => "Mobile phones with {$ram}GB RAM and {$rom}GB storage on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "Mobile Phones with {$ram}GB RAM and {$rom}GB storage in {$country->country_name}",
            'name' => "Mobile phones with {$ram}GB RAM and {$rom}GB storage"
        ];

        $products = $products->simplePaginate(32);
        $category = Category::find(1);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show products by screen size
     */
    public function byScreenSize(Request $request, int $maxSize)
    {
        $country = $request->attributes->get('country');

        $products = $this->filterService->getProductsByScreenSize($maxSize);

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
            'title' => "All {$maxSize}-Inch Screen Size Mobile Phones Price in {$country->country_name}",
            'description' => "Find the latest mobile phones on the Mobilekishop with {$maxSize}-inch screen size and Their specifications, features, reviews, comparison, and price in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "{$maxSize}-Inch Screen Size Mobile Phones in {$country->country_name}",
            'name' => "Mobile phones with {$maxSize} Inch Screen"
        ];

        $products = $products->simplePaginate(32);
        $category = Category::find(1);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show products by camera count (dual, triple, quad)
     */
    public function byCameraCount(Request $request, string $parameter)
    {
        $country = $request->attributes->get('country');

        $products = $this->filterService->getProductsByCameraCount($parameter);

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

        $cameraLabel = ucfirst(str_replace('-', ' ', $parameter));

        $metas = (object) [
            'title' => "{$cameraLabel} Mobile Phones in {$country->country_name}",
            'description' => "Explore {$cameraLabel} smartphones with advanced photography features. Compare specs and prices in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "{$cameraLabel} Mobile Phones in {$country->country_name}",
            'name' => "{$cameraLabel} Mobile Phones"
        ];

        $products = $products->simplePaginate(32);
        $category = Category::find(1);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show products by camera megapixels
     */
    public function byCameraMp(Request $request, int $mp)
    {
        $country = $request->attributes->get('country');

        $products = $this->filterService->getProductsByCameraMp($mp);

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
            'title' => "Mobile Phones with {$mp}MP+ Camera in {$country->country_name}",
            'description' => "Discover smartphones with {$mp}MP or higher camera. Compare specs, features, and prices in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "Mobile Phones with {$mp}MP+ Camera in {$country->country_name}",
            'name' => "{$mp}MP+ Camera Phones"
        ];

        $products = $products->simplePaginate(32);
        $category = Category::find(1);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show curved screen phones by brand
     */
    public function curvedScreensByBrand(Request $request, string $brandSlug)
    {
        $country = $request->attributes->get('country');

        if ($brandSlug === 'all') {
            $products = $this->filterService->getCurvedScreens();
        } else {
            $products = $this->filterService->getCurvedScreensByBrand($brandSlug);
        }

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

        $brand = $products->first()->brand ?? null;
        $brandName = $brand ? $brand->name : ($brandSlug === 'all' ? 'Curved Display' : ucfirst($brandSlug));

        $metas = (object) [
            'title' => "{$brandName} Mobile Phones in {$country->country_name}",
            'description' => "Explore {$brandName} smartphones. Compare specs, features, and prices in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "{$brandName} Phones in {$country->country_name}",
            'name' => "{$brandName} Phones"
        ];

        $products = $products->simplePaginate(32);
        $category = Category::find(1);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'brand', 'filters'));
    }

    /**
     * Show upcoming/unreleased products
     */
    public function upcoming(Request $request)
    {
        $country = $request->attributes->get('country');

        $products = $this->filterService->getUpcomingProducts();

        if ($request->ajax()) {
            $products = $products->paginate(32);
            if ($products->isEmpty()) {
                return response()->json(['success' => false]);
            }
            return view('includes.products-partial', compact('products', 'country'))->render();
        }

        $metas = (object) [
            'title' => "Upcoming Mobile Phones in {$country->country_name} - Launch Dates & Specs",
            'description' => "Stay updated with upcoming mobile phone launches in {$country->country_name}. Get expected prices, specifications, and release dates.",
            'canonical' => request()->fullUrl(),
            'h1' => "Upcoming Mobile Phones in {$country->country_name}",
            'name' => "Upcoming Mobile Phones"
        ];

        $products = $products->simplePaginate(32);
        $category = Category::find(1);
        $filters = collect($request->query());

        return view('frontend.upcoming', compact('products', 'metas', 'category', 'country', 'filters'));
    }
}
