<?php

namespace App\Services;

use App\Brand;
use App\Category;
use App\Country;
use Illuminate\Database\Eloquent\Builder;

class BrandService
{
    /**
     * Get all brands
     */
    public function getAllBrands()
    {
        return Brand::orderBy('name', 'ASC')->get();
    }

    /**
     * Get brands by category
     */
    public function getBrandsByCategory(?Category $category = null)
    {
        if (!$category) {
            return $this->getAllBrands();
        }

        return Brand::whereHas('products', function ($query) use ($category) {
            $query->where('category_id', $category->id)
                ->whereHas('variants', function ($variantQuery) {
                    $variantQuery->where('price', '>', 0);
                });
        })->get();
    }

    /**
     * Get brand by slug
     */
    public function getBrandBySlug(string $slug): ?Brand
    {
        return Brand::whereSlug($slug)->first();
    }
}
