<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Country;
use App\Models\ExpertRating;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AiImportController extends Controller
{
    /**
     * Show the AI Import page.
     */
    public function index()
    {
        $brands = Brand::orderBy('name')->get(['id', 'name', 'slug']);
        $categories = Category::all(['id', 'category_name', 'slug']);
        $countries = Country::withoutGlobalScopes()->orderBy('id')->get(['id', 'country_name', 'country_code', 'currency']);

        // Get attribute names for mobile phones (category_id = 1)
        $attributes = Attribute::where('category_id', 1)
            ->orderBy('sequence')
            ->whereNotIn('name', ['reviewer', 'review_count', 'rating', 'release_date', 'thumbnail'])
            ->get(['id', 'name', 'label']);

        return view('dashboard.ai-import.index', compact('brands', 'categories', 'countries', 'attributes'));
    }

    /**
     * Process pasted JSON — validate and return preview data.
     */
    public function process(Request $request)
    {
        $request->validate([
            'json_data' => 'required|string',
        ]);

        $raw = $request->input('json_data');

        // Try to extract JSON from markdown code blocks if present
        if (preg_match('/```(?:json)?\s*([\s\S]*?)```/', $raw, $matches)) {
            $raw = trim($matches[1]);
        }

        $data = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid JSON: ' . json_last_error_msg(),
            ], 422);
        }

        // Validate required fields
        if (empty($data['name'])) {
            return response()->json([
                'success' => false,
                'error' => 'Missing required field: "name"',
            ], 422);
        }

        // Resolve brand
        $brand = null;
        if (!empty($data['brand'])) {
            $brand = Brand::where('name', 'like', $data['brand'])->first();
            if (!$brand) {
                $brand = Brand::where('slug', Str::slug($data['brand']))->first();
            }
        }

        // Resolve category
        $category = null;
        if (!empty($data['category'])) {
            $category = Category::where('slug', $data['category'])->first();
            if (!$category) {
                $category = Category::where('category_name', 'like', $data['category'])->first();
            }
        }
        if (!$category) {
            $category = Category::where('slug', 'mobile-phones')->first();
        }

        // Check if product already exists
        $slug = Str::slug($data['name']);
        $existingProduct = Product::where('slug', $slug)->first();

        // Map country codes to country records for prices
        $pricePreview = [];
        if (!empty($data['prices']) && is_array($data['prices'])) {
            $allCountries = Country::withoutGlobalScopes()->get();
            foreach ($data['prices'] as $code => $price) {
                $country = $allCountries->first(function ($c) use ($code) {
                    return strtolower($c->country_code) === strtolower($code);
                });
                if ($country && $price > 0) {
                    $pricePreview[] = [
                        'country_id' => $country->id,
                        'country_name' => $country->country_name,
                        'country_code' => $country->country_code,
                        'currency' => $country->currency,
                        'price' => $price,
                    ];
                }
            }
        }

        // Build spec preview
        $specPreview = [];
        if (!empty($data['specifications']) && is_array($data['specifications'])) {
            $attrs = Attribute::where('category_id', $category->id ?? 1)->get();
            foreach ($data['specifications'] as $key => $value) {
                if (empty($value) || $value === 'N/A' || $value === '-')
                    continue;
                $attr = $attrs->first(function ($a) use ($key) {
                    return strtolower($a->name) === strtolower($key);
                });
                if ($attr) {
                    $specPreview[] = [
                        'attribute_id' => $attr->id,
                        'name' => $attr->name,
                        'label' => $attr->label,
                        'value' => $value,
                    ];
                }
            }
        }

        // Expert rating preview
        $expertPreview = $data['expert_rating'] ?? null;

        return response()->json([
            'success' => true,
            'is_update' => $existingProduct !== null,
            'existing_product_id' => $existingProduct?->id,
            'preview' => [
                'name' => $data['name'],
                'slug' => $slug,
                'brand' => $brand ? ['id' => $brand->id, 'name' => $brand->name] : null,
                'category' => $category ? ['id' => $category->id, 'name' => $category->category_name] : null,
                'release_date' => $data['release_date'] ?? null,
                'specifications' => $specPreview,
                'prices' => $pricePreview,
                'expert_rating' => $expertPreview,
            ],
            'raw_json' => $data,
        ]);
    }

    /**
     * Save the validated JSON data to the database.
     */
    public function save(Request $request)
    {
        $request->validate([
            'json_data' => 'required|string',
        ]);

        $raw = $request->input('json_data');

        // Extract JSON from markdown code blocks if present
        if (preg_match('/```(?:json)?\s*([\s\S]*?)```/', $raw, $matches)) {
            $raw = trim($matches[1]);
        }

        $data = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE || empty($data['name'])) {
            return response()->json(['success' => false, 'error' => 'Invalid JSON data.'], 422);
        }

        DB::beginTransaction();

        try {
            // Resolve brand
            $brandId = null;
            if (!empty($data['brand'])) {
                $brand = Brand::where('name', 'like', $data['brand'])->first()
                    ?? Brand::where('slug', Str::slug($data['brand']))->first();
                $brandId = $brand?->id;
            }

            // Resolve category
            $category = null;
            if (!empty($data['category'])) {
                $category = Category::where('slug', $data['category'])->first()
                    ?? Category::where('category_name', 'like', $data['category'])->first();
            }
            if (!$category) {
                $category = Category::where('slug', 'mobile-phones')->first();
            }
            $categoryId = $category?->id ?? 1;

            $slug = Str::slug($data['name']);
            $product = Product::where('slug', $slug)->first();

            if ($product) {
                // UPDATE existing product
                $product->update([
                    'name' => $data['name'],
                    'brand_id' => $brandId ?? $product->brand_id,
                    'category_id' => $categoryId,
                    'release_date' => !empty($data['release_date']) ? $data['release_date'] : $product->release_date,
                ]);
            } else {
                // CREATE new product
                $product = Product::create([
                    'name' => $data['name'],
                    'slug' => $slug,
                    'brand_id' => $brandId,
                    'category_id' => $categoryId,
                    'release_date' => $data['release_date'] ?? null,
                    'title' => ucwords($data['name']) . ' Price, Specification and Review',
                ]);
            }

            // Sync specifications
            if (!empty($data['specifications']) && is_array($data['specifications'])) {
                $attrs = Attribute::where('category_id', $categoryId)->get();
                $syncData = [];

                foreach ($data['specifications'] as $key => $value) {
                    if (empty($value) || $value === 'N/A' || $value === '-')
                        continue;
                    $attr = $attrs->first(function ($a) use ($key) {
                        return strtolower($a->name) === strtolower($key);
                    });
                    if ($attr) {
                        $syncData[$attr->id] = ['value' => $value];
                    }
                }

                // Merge with existing attributes to avoid losing unrecognized ones
                $existingAttrs = $product->attributes()->pluck('value', 'attribute_id')->toArray();
                foreach ($existingAttrs as $attrId => $val) {
                    if (!isset($syncData[$attrId])) {
                        $syncData[$attrId] = ['value' => $val];
                    }
                }

                $product->attributes()->sync($syncData);
            }

            // Upsert country prices (variant_id = 1 by default)
            if (!empty($data['prices']) && is_array($data['prices'])) {
                $allCountries = Country::withoutGlobalScopes()->get();

                foreach ($data['prices'] as $code => $price) {
                    $country = $allCountries->first(function ($c) use ($code) {
                        return strtolower($c->country_code) === strtolower($code);
                    });

                    if ($country && $price > 0) {
                        DB::table('product_variants')->updateOrInsert(
                            [
                                'product_id' => $product->id,
                                'variant_id' => 1,
                                'country_id' => $country->id,
                            ],
                            [
                                'price' => $price,
                            ]
                        );
                    }
                }
            }

            // Upsert expert rating
            if (!empty($data['expert_rating']) && is_array($data['expert_rating'])) {
                $er = $data['expert_rating'];
                $ratingFields = ['design', 'display', 'performance', 'camera', 'battery', 'value_for_money'];
                $ratingData = [];
                $total = 0;
                $count = 0;

                foreach ($ratingFields as $field) {
                    if (isset($er[$field])) {
                        $ratingData[$field] = floatval($er[$field]);
                        $total += $ratingData[$field];
                        $count++;
                    }
                }

                if ($count > 0) {
                    $overall = round($total / $count, 1);
                    $ratingData['overall'] = $overall;
                    $ratingData['verdict'] = $er['verdict'] ?? null;
                    $ratingData['rated_by'] = $er['rated_by'] ?? 'MobileKiShop';

                    ExpertRating::updateOrCreate(
                        ['product_id' => $product->id],
                        $ratingData
                    );
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $product->wasRecentlyCreated
                    ? "Product \"{$product->name}\" created successfully!"
                    : "Product \"{$product->name}\" updated successfully!",
                'product_id' => $product->id,
                'edit_url' => route('dashboard.products.edit', $product->id),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Database error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
