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
    public function __construct(
        private FilterService $filterService,
        private ProductService $productService,
        private MetaService $metaService
    ) {
    }

    /**
     * Show products under a specific price
     */
    public function underPrice(Request $request)
    {
        $amount = (int) $request->route('amount');
        $country = $request->attributes->get('country');
        $filters = collect($request->input('filter', [])); // Pass as collection
        if ($filters->isEmpty() && $request->has('min')) {
            $filters = collect($request->all());
        }

        // Get products
        $products = $this->filterService->getProductsUnderPrice($amount, $country->country_code);

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
        $metas = $this->metaService->generatePriceFilterMeta($amount, $country);

        // Paginate
        $products = $products->simplePaginate(32);
        $category = Category::find(1); // Mobile phones category

        // Pass filters explicitly
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show products by brand and under price
     */
    public function brandUnderAmount(Request $request)
    {
        $brandParam = $request->route('brand');
        $brandSlug = ($brandParam instanceof \App\Brand) ? $brandParam->slug : $brandParam;
        $amount = (int) $request->route('amount');
        $country = $request->attributes->get('country');

        $products = $this->filterService->getProductsByBrandAndPrice($brandSlug, $amount, $country->country_code);

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

        $brand = \App\Models\Brand::whereSlug($brandSlug)->first();
        $metas = $this->metaService->generateBrandPriceFilterMeta($brand, $amount, $country);
        $products = $products->simplePaginate(32);
        $category = Category::find(1);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'brand', 'filters'));
    }

    /**
     * Show products by RAM size
     */
    public function byRam(Request $request)
    {
        $ram = (int) $request->route('ram');
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
        $metas = $this->metaService->generateRamFilterMeta($ram, $country);

        $products = $products->simplePaginate(32);
        $category = Category::find(1);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show products by ROM/Storage size
     */
    public function byRom(Request $request)
    {
        $rom = (int) $request->route('rom');
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

        $metas = $this->metaService->generateRomFilterMeta($rom, $country);

        $products = $products->simplePaginate(32);
        $category = Category::find(1);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show products by RAM and ROM combination
     */
    public function ramRomCombo(Request $request)
    {
        $ram = (int) $request->route('ram');
        $rom = (int) $request->route('rom');
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

        $metas = $this->metaService->generateRamRomFilterMeta($ram, $rom, $country);

        $products = $products->simplePaginate(32);
        $category = Category::find(1);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show products by screen size
     */
    public function byScreenSize(Request $request)
    {
        $maxSize = (float) $request->route('maxSize');
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

        $metas = $this->metaService->generateScreenFilterMeta($maxSize, $country);

        $products = $products->simplePaginate(32);
        $category = Category::find(1);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show products by camera count (dual, triple, quad)
     */
    public function byCameraCount(Request $request)
    {
        $parameter = $request->route('parameter');
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

        $metas = $this->metaService->generateCameraCountFilterMeta($parameter, $country);

        $products = $products->simplePaginate(32);
        $category = Category::find(1);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show products by camera megapixels
     */
    public function byCameraMp(Request $request)
    {
        $mp = (int) $request->route('mp');
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

        $metas = $this->metaService->generateCameraMpFilterMeta($mp, $country);

        $products = $products->simplePaginate(32);
        $category = Category::find(1);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show products by processor type
     */
    public function byProcessor(Request $request)
    {
        $processor = $request->route('processor');
        $country = $request->attributes->get('country');

        $products = $this->filterService->getProductsByProcessor($processor);

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

        $metas = $this->metaService->generateProcessorFilterMeta($processor, $country);

        $products = $products->simplePaginate(32);
        $category = Category::find(1);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show products by type (folding, flip)
     */
    public function byType(Request $request)
    {
        $type = $request->route('type');
        $country = $request->attributes->get('country');

        $products = $this->filterService->getProductsByType($type);

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

        $metas = $this->metaService->generateTypeFilterMeta($type, $country);

        $products = $products->simplePaginate(32);
        $category = Category::find(1);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }

    /**
     * Show curved screen phones by brand
     */
    public function curvedScreensByBrand(Request $request)
    {
        $brandSlug = $request->route('brandSlug') ?: 'all';
        $country = $request->attributes->get('country');

        $products = $this->filterService->getCurvedScreensByBrand($brandSlug);

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

        $brand = $brandSlug !== 'all' ? \App\Models\Brand::whereSlug($brandSlug)->first() : null;
        $metas = $this->metaService->generateCurvedFilterMeta($brand, $country);

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
        $metas = $this->metaService->generateUpcomingMeta($country);
        $products = $products->simplePaginate(32);
        $category = Category::find(1);
        $filters = collect($request->query());

        return view('frontend.filter', compact('products', 'metas', 'category', 'country', 'filters'));
    }
}
