<?php

namespace App\Services;

use App\Product;
use App\Compare;
use App\Country;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ComparisonService
{
    /**
     * Get products for comparison by slug
     */
    public function getProductsForComparison(string $slug): array
    {
        $validator = Validator::make(['slug' => $slug], [
            'slug' => 'required|string',
        ]);

        if ($validator->fails()) {
            abort(404);
        }

        $slugs = explode('-vs-', $slug);

        $products = Product::whereIn('slug', $slugs)
            ->orderByRaw(DB::raw("FIELD(slug, '" . implode("','", $slugs) . "')"))
            ->get();

        if ($products->isEmpty()) {
            abort(404);
        }

        return [
            'product1' => $products->firstWhere('slug', $slugs[0]),
            'product2' => isset($slugs[1]) ? $products->firstWhere('slug', $slugs[1]) : null,
            'product3' => isset($slugs[2]) ? $products->firstWhere('slug', $slugs[2]) : null,
        ];
    }

    /**
     * Get related comparisons
     */
    public function getRelatedComparisons(array $slugs, int $limit = 6)
    {
        return Compare::whereIn('product1', $slugs)
            ->orWhereIn('product2', $slugs)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Get all comparisons paginated
     */
    public function getAllComparisons(int $perPage = 32)
    {
        return Compare::orderBy('id', 'DESC')->simplePaginate($perPage);
    }
}
