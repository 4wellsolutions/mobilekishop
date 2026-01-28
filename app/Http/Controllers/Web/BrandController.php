<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CountryService;
use App\Services\ProductService;
use App\Services\BrandService;
use App\Services\MetaService;
use App\Category;
use App\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

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
    public function show(Request $request)
    {
        $country = $request->attributes->get('country');
        $brandParam = $request->route('brand') ?: $request->route('slug');
        $categorySlug = $request->route('categorySlug') ?: $request->route('category_slug') ?: $request->route('category');

        if ($brandParam instanceof Brand) {
            $brand = $brandParam;
        } else {
            $brand = \App\Brand::whereSlug($brandParam)->first();
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

        if ($request->ajax()) {
            return view('includes.products-partial', compact('products', 'country'))->render();
        }

        return view('frontend.brand', compact('products', 'brand', 'metas', 'category', 'country'));
    }

    /**
     * Show all brands or brands by category
     */
    public function index(Request $request)
    {
        $country = $request->attributes->get('country');
        $categorySlug = $request->route('category_slug') ?: 'all';
        $category = null;


        if ($categorySlug === 'all') {
            $metas = (object) [
                "title" => Str::title("All Brands Mobile Phones, Tablets, Smart Watches {$country->country_name}"),
                "description" => "Get all the specifications, features, reviews, comparison, and price of All Mobile Phones Brands on the Mobilekishop in {$country->country_name}.",
                "canonical" => URL::to("/brands/all"),
                "h1" => "All Brands Mobile Phones in {$country->country_name}",
                "name" => "All Brands"
            ];
            $categories = Category::with([
                'brands' => function ($q) use ($country) {
                    // Approximate logic from legacy: brands with active products in country
                    $q->whereHas('products.variants', function ($qv) use ($country) {
                        $qv->where('country_id', $country->id)->where('price', '>', 0);
                    });
                }
            ])->get();


            $brands = collect([]);
        } else {
            $category = Category::where("slug", $categorySlug)->firstOrFail();
            $categories = collect([]);
            $metas = (object) [
                "title" => Str::title("Latest {$category->category_name} Brands Spec, Price in {$country->country_name}"),
                "description" => "Get all the specifications, features, reviews, comparison, and price of All {$category->category_name} Brands on the Mobilekishop in {$country->country_name}.",
                "canonical" => URL::to("/brands/{$category->slug}"),
                "h1" => "{$category->category_name} Brands in {$country->country_name}",
                "name" => "All Brands"
            ];

            $brands = Brand::whereHas('products', function ($query) use ($category, $country) {
                $query->where('category_id', $category->id)
                    ->whereHas('variants', function ($variantQuery) use ($country) {
                        $variantQuery->where('country_id', $country->id)->where('price', '>', 0);
                    });
            })->get();
        }

        return view("frontend.brands", compact('brands', 'metas', 'category', 'country', 'categories'));
    }
}
