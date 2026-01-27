<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CountryService;
use App\Services\FilterService;
use App\Services\MetaService;
use App\Services\ProductService;
use App\Category;
use Illuminate\Http\Request;

class TabletFilterController extends Controller
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
     * Show tablets under a specific price
     */
    public function underPrice(Request $request)
    {
        // Retrieve 'amount' from route parameters safely
        $amount = (int) $request->route('amount');

        $country = $request->attributes->get('country');

        $products = $this->filterService->getTabletsUnderPrice($amount, $country->id);

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

        $metas = $this->metaService->generateForPriceFilter($amount, $country, Category::find(3));

        $products = $products->simplePaginate(32);
        $category = Category::find(3); // Tablets (Legacy ID 3)
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show tablets by RAM
     */
    public function byRam(Request $request, int $ram)
    {
        $country = $request->attributes->get('country');

        $products = $this->filterService->getTabletsByRam($ram);

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
            'title' => "Tablets with {$ram}GB RAM Price in {$country->country_name}",
            'description' => "Find the latest tablets with {$ram}GB RAM on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "Tablets with {$ram}GB RAM in {$country->country_name}",
            'name' => "Tablets with {$ram}GB RAM"
        ];

        $products = $products->simplePaginate(32);
        $category = Category::find(3);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show tablets by ROM/Storage
     */
    public function byRom(Request $request, int $rom)
    {
        $country = $request->attributes->get('country');

        $products = $this->filterService->getTabletsByRom($rom);

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
            'title' => "Tablets with {$rom}GB Storage Price in {$country->country_name}",
            'description' => "Find the latest tablets with {$rom}GB storage on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "Tablets with {$rom}GB Storage in {$country->country_name}",
            'name' => "Tablets with {$rom}GB Storage"
        ];

        $products = $products->simplePaginate(32);
        $category = Category::find(3);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show tablets by Screen Size
     */
    public function byScreenSize(Request $request, int $inch)
    {
        $country = $request->attributes->get('country');

        $products = $this->filterService->getTabletsByScreenSize($inch);

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
            'title' => "Tablets with {$inch}-inch Screen Price in {$country->country_name}",
            'description' => "Find the latest tablets with {$inch}-inch screen size on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "Tablets with {$inch}-inch Screen in {$country->country_name}",
            'name' => "Tablets with {$inch} Inch Screen"
        ];

        $products = $products->simplePaginate(32);
        $category = Category::find(3);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show tablets by Camera MP
     */
    public function byCameraMp(Request $request, int $mp)
    {
        $country = $request->attributes->get('country');

        $products = $this->filterService->getTabletsByCameraMp($mp);

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
            'title' => "Tablets with {$mp}MP Camera Price in {$country->country_name}",
            'description' => "Find the latest tablets with {$mp}MP camera on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "Tablets with {$mp}MP Camera in {$country->country_name}",
            'name' => "Tablets with {$mp}MP Camera"
        ];

        $products = $products->simplePaginate(32);
        $category = Category::find(3);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }
}
