<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\FilterService;
use App\Services\MetaService;
use App\Services\ProductService;
use App\Models\Category;
use Illuminate\Http\Request;

class TabletFilterController extends Controller
{
    /** @var Category|null Cached tablets category */
    private ?Category $tabletCategory = null;

    public function __construct(
        private FilterService $filterService,
        private MetaService $metaService,
        private ProductService $productService
    ) {
    }

    /**
     * Get the Tablets category (cached per request).
     */
    private function getTabletCategory(): Category
    {
        if (!$this->tabletCategory) {
            $this->tabletCategory = Category::find(config('categories.tablets'));
        }
        return $this->tabletCategory;
    }

    /**
     * Common handler for all tablet filter actions.
     */
    private function handleFilter(Request $request, $productsQuery, object $metas, array $extraViewData = [])
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
        $category = $this->getTabletCategory();
        $filters = collect($request->query());

        return view('frontend.filter', array_merge(
            compact('products', 'metas', 'category', 'country', 'filters'),
            $extraViewData
        ));
    }

    /**
     * Show tablets under a specific price
     */
    public function underPrice(Request $request)
    {
        $amount = (int) $request->route('amount');
        if ($amount <= 0 || $amount > 10000000)
            abort(404);
        $country = $request->attributes->get('country');

        $products = $this->filterService->getTabletsUnderPrice($amount, $country->country_code);
        $metas = $this->metaService->generatePriceFilterMeta($amount, $country, $this->getTabletCategory());

        return $this->handleFilter($request, $products, $metas);
    }

    /**
     * Show tablets by RAM
     */
    public function byRam(Request $request)
    {
        $ram = (int) $request->route('ram');
        if ($ram <= 0 || $ram > 64)
            abort(404);
        $country = $request->attributes->get('country');

        $products = $this->filterService->getTabletsByRam($ram, $country->country_code);

        $metas = (object) [
            'title' => "Tablets with {$ram}GB RAM Price in {$country->country_name}",
            'description' => "Find the latest tablets with {$ram}GB RAM on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "Tablets with {$ram}GB RAM in {$country->country_name}",
            'name' => "Tablets with {$ram}GB RAM"
        ];

        return $this->handleFilter($request, $products, $metas);
    }

    /**
     * Show tablets by ROM/Storage
     */
    public function byRom(Request $request)
    {
        $rom = (int) $request->route('rom');
        if ($rom <= 0 || $rom > 2048)
            abort(404);
        $country = $request->attributes->get('country');

        $products = $this->filterService->getTabletsByRom($rom, $country->country_code);

        $metas = (object) [
            'title' => "Tablets with {$rom}GB Storage Price in {$country->country_name}",
            'description' => "Find the latest tablets with {$rom}GB storage on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "Tablets with {$rom}GB Storage in {$country->country_name}",
            'name' => "Tablets with {$rom}GB Storage"
        ];

        return $this->handleFilter($request, $products, $metas);
    }

    /**
     * Show tablets by Screen Size
     */
    public function byScreenSize(Request $request)
    {
        $inch = (int) $request->route('inch');
        if ($inch <= 0 || $inch > 20)
            abort(404);
        $country = $request->attributes->get('country');

        $products = $this->filterService->getTabletsByScreenSize($inch, $country->country_code);

        $metas = (object) [
            'title' => "Tablets with {$inch}-inch Screen Price in {$country->country_name}",
            'description' => "Find the latest tablets with {$inch}-inch screen size on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "Tablets with {$inch}-inch Screen in {$country->country_name}",
            'name' => "Tablets with {$inch} Inch Screen"
        ];

        return $this->handleFilter($request, $products, $metas);
    }

    /**
     * Show tablets by Camera MP
     */
    public function byCameraMp(Request $request)
    {
        $mp = (int) $request->route('mp');
        if ($mp <= 0 || $mp > 300)
            abort(404);
        $country = $request->attributes->get('country');

        $products = $this->filterService->getTabletsByCameraMp($mp, $country->country_code);

        $metas = (object) [
            'title' => "Tablets with {$mp}MP Camera Price in {$country->country_name}",
            'description' => "Find the latest tablets with {$mp}MP camera on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            'canonical' => request()->fullUrl(),
            'h1' => "Tablets with {$mp}MP Camera in {$country->country_name}",
            'name' => "Tablets with {$mp}MP Camera"
        ];

        return $this->handleFilter($request, $products, $metas);
    }

    /**
     * Show tablets by Network Type (4G, 5G)
     */
    public function byType(Request $request)
    {
        $type = $request->route('type');
        $country = $request->attributes->get('country');

        $products = $this->filterService->getTabletsByType($type, $country->country_code);
        $metas = $this->metaService->generateTypeFilterMeta($type, $country, $this->getTabletCategory());

        return $this->handleFilter($request, $products, $metas);
    }
}
