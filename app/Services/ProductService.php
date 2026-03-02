<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Country;
use Illuminate\Database\Eloquent\Builder;

class ProductService
{
    /**
     * Get products by category and country
     */
    public function getProductsByCategory(Category $category, Country $country, array $filters = []): Builder
    {
        $products = Product::query()->where('category_id', $category->id)
            ->whereHas('variants', function ($query) use ($country) {
                $query->where('country_id', $country->id)
                    ->where('price', '>', 0);
            })
            ->with([
                'variants' => function ($query) use ($country) {
                    $query->where('country_id', $country->id)
                        ->where('price', '>', 0);
                },
                'brand',
                'category',
                'attributes' => function ($query) {
                    $query->whereIn('attributes.name', ['size', 'chipset', 'main', 'capacity', 'battery']);
                },
            ]);

        if (!empty($filters)) {
            $products = $this->applyFilters($products, $filters, $country->id);
        }

        return $products;
    }

    /**
     * Get products by brand and country
     */
    public function getProductsByBrand(Brand $brand, Country $country, ?Category $category = null, array $filters = []): Builder
    {
        $products = Product::query()->where('brand_id', $brand->id)
            ->whereHas('variants', function ($query) use ($country) {
                $query->where('country_id', $country->id)
                    ->where('price', '>', 0);
            })
            ->with([
                'variants' => function ($query) use ($country) {
                    $query->where('country_id', $country->id)
                        ->where('price', '>', 0);
                },
                'brand',
                'category',
                'attributes' => function ($query) {
                    $query->whereIn('attributes.name', ['size', 'chipset', 'main', 'capacity', 'battery']);
                },
            ]);

        if ($category) {
            $products->where('category_id', $category->id);
        }

        if (!empty($filters)) {
            $products = $this->applyFilters($products, $filters, $country->id);
        }

        return $products;
    }

    /**
     * Get products by price range
     */
    public function getProductsByPriceRange(Country $country, int $minPrice, int $maxPrice, ?Category $category = null, array $filters = []): Builder
    {
        $products = Product::query()->whereHas('variants', function ($query) use ($country, $minPrice, $maxPrice) {
            $query->where('country_id', $country->id)
                ->whereBetween('price', [$minPrice, $maxPrice]);
        })->with([
                    'variants' => function ($query) use ($country, $minPrice, $maxPrice) {
                        $query->where('country_id', $country->id)
                            ->whereBetween('price', [$minPrice, $maxPrice]);
                    },
                    'brand',
                    'category',
                    'attributes' => function ($query) {
                        $query->whereIn('attributes.name', ['size', 'chipset', 'main', 'capacity', 'battery']);
                    },
                ]);

        if ($category) {
            $products->where('category_id', $category->id);
        }

        if (!empty($filters)) {
            $products = $this->applyFilters($products, $filters, $country->id, $minPrice, $maxPrice);
        }

        return $products;
    }

    /**
     * Search products
     */
    public function searchProducts(string $query, Country $country): Builder
    {
        $products = Product::query()->whereHas('variants', function ($q) use ($country) {
            $q->where('country_id', $country->id)
                ->where('price', '>', 0);
        });

        return $products->search($query);
    }

    /**
     * Apply filters to product query
     */
    public function applyFilters(\Illuminate\Database\Eloquent\Builder $products, $filters, int $countryId, ?int $minPrice = null, ?int $maxPrice = null): \Illuminate\Database\Eloquent\Builder
    {
        // Attribute filters
        $attributeFilters = array_intersect_key($filters, array_flip([
            'ram_in_gb',
            'rom_in_gb',
            'pixels',
            'year',
            'network_band'
        ]));

        foreach ($attributeFilters as $attribute => $value) {
            if (!empty($value)) {
                $products = $products->whereHas('attributes', function ($query) use ($attribute, $value) {
                    if ($attribute === 'year') {
                        $escaped = str_replace(['%', '_'], ['\\%', '\\_'], $value);
                        $query->where('name', 'release_date')
                            ->where('product_attributes.value', 'like', "%$escaped%");
                    } else {
                        $query->where('name', $attribute)
                            ->where('product_attributes.value', $value);
                    }
                });
            }
        }

        // Price filters if passed via request query instead of route
        if (isset($filters['min']) || isset($filters['max'])) {
            $min = (int) ($filters['min'] ?? 0);
            $max = (int) ($filters['max'] ?? 1000000);
            $products->whereHas('variants', function ($query) use ($countryId, $min, $max) {
                $query->where('country_id', $countryId)->whereBetween('price', [$min, $max]);
            });
        }

        // Sorting
        $orderby = $filters['orderby'] ?? 'new';

        if ($orderby == 'price_asc') {
            $products->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                ->where('product_variants.country_id', $countryId)
                ->where('product_variants.price', '>', 0)
                ->orderBy('product_variants.price', 'ASC')
                ->select('products.*');
        } elseif ($orderby == 'price_desc') {
            $products->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                ->where('product_variants.country_id', $countryId)
                ->where('product_variants.price', '>', 0)
                ->orderBy('product_variants.price', 'DESC')
                ->select('products.*');
        } elseif ($orderby == 'new') {
            $products->orderBy('release_date', 'DESC');
        } else {
            $products->orderBy('products.id', 'DESC');
        }

        return $products;
    }

    /**
     * Sort products by price
     */
    private function sortByPrice(Builder $products, int $countryId, string $direction, ?int $minPrice = null, ?int $maxPrice = null): Builder
    {
        if ($minPrice && $maxPrice) {
            return $products->with([
                'variants' => function ($query) use ($countryId, $minPrice, $maxPrice, $direction) {
                    $query->where('country_id', $countryId)
                        ->whereBetween('price', [$minPrice, $maxPrice])
                        ->orderBy('price', $direction)
                        ->limit(1);
                }
            ]);
        }

        return $products->orderBy('product_variants.price', $direction);
    }
}
