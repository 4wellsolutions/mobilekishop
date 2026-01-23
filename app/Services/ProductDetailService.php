<?php

namespace App\Services;

use App\Product;
use App\Country;
use App\Compare;

class ProductDetailService
{
    /**
     * Get product with all related data
     */
    public function getProductDetails(Product $product, Country $country): array
    {
        // Load variants for this country
        $product->load([
            'variants' => function ($query) use ($country) {
                $query->where('country_id', $country->id)
                    ->withPivot('price');
            }
        ]);

        // Get related products (same brand, same category)
        $relatedProducts = $this->getRelatedProducts($product, $country);

        // Get comparisons
        $comparisons = $this->getProductComparisons($product);

        // Increment view count
        $product->increment('views');

        return [
            'product' => $product,
            'related_products' => $relatedProducts,
            'comparisons' => $comparisons,
        ];
    }

    /**
     * Get related products
     */
    public function getRelatedProducts(Product $product, Country $country, int $limit = 4)
    {
        return Product::where('brand_id', $product->brand_id)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->whereHas('variants', function ($query) use ($country) {
                $query->where('country_id', $country->id)
                    ->where('price', '>', 0);
            })
            ->with([
                'variants' => function ($query) use ($country) {
                    $query->where('country_id', $country->id)
                        ->where('price', '>', 0);
                }
            ])
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Get product comparisons
     */
    public function getProductComparisons(Product $product, int $limit = 3)
    {
        return Compare::where('product1', $product->slug)
            ->orWhere('product2', $product->slug)
            ->orWhere('product3', $product->slug)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}
