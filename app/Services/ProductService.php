<?php

namespace App\Services;

use App\Product;
use App\Category;
use App\Brand;
use App\Country;
use Illuminate\Database\Eloquent\Builder;

class ProductService
{
    /**
     * Get products by category and country
     */
    public function getProductsByCategory(Category $category, Country $country, array $filters = []): Builder
    {
        $products = Product::where('category_id', $category->id)
            ->whereHas('variants', function ($query) use ($country) {
                $query->where('country_id', $country->id)
                    ->where('price', '>', 0);
            })
            ->with([
                'variants' => function ($query) use ($country) {
                    $query->where('country_id', $country->id)
                        ->where('price', '>', 0);
                }
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
        $products = Product::where('brand_id', $brand->id)
            ->whereHas('variants', function ($query) use ($country) {
                $query->where('country_id', $country->id)
                    ->where('price', '>', 0);
            })
            ->with([
                'variants' => function ($query) use ($country) {
                    $query->where('country_id', $country->id)
                        ->where('price', '>', 0);
                }
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
        $products = Product::whereHas('variants', function ($query) use ($country, $minPrice, $maxPrice) {
            $query->where('country_id', $country->id)
                ->whereBetween('price', [$minPrice, $maxPrice]);
        });

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
        $products = Product::whereHas('variants', function ($q) use ($country) {
            $q->where('country_id', $country->id)
                ->where('price', '>', 0);
        });

        return $products->search($query);
    }

    /**
     * Apply filters to product query
     */
    private function applyFilters(Builder $products, array $filters, int $countryId, ?int $minPrice = null, ?int $maxPrice = null): Builder
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
            if (!is_null($value)) {
                $products = $products->whereHas('productAttributes', function ($query) use ($attribute, $value) {
                    $query->whereHas('attribute', function ($subQuery) use ($attribute, $value) {
                        if ($attribute === 'year') {
                            $subQuery->where('name', 'release_date')
                                ->whereYear('value', '=', $value);
                        } else {
                            $subQuery->where('name', $attribute)
                                ->where('value', $value);
                        }
                    });
                });
            }
        }

        // Sorting
        if (isset($filters['orderby'])) {
            switch ($filters['orderby']) {
                case 'price_asc':
                    $products = $this->sortByPrice($products, $countryId, 'ASC', $minPrice, $maxPrice);
                    break;
                case 'price_desc':
                    $products = $this->sortByPrice($products, $countryId, 'DESC', $minPrice, $maxPrice);
                    break;
                case 'new':
                    $products = $products->orderBy('release_date', 'DESC');
                    break;
                default:
                    $products = $products->orderBy('id', 'DESC');
            }
        } else {
            $products = $products->orderBy('id', 'DESC');
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
