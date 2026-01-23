<?php

namespace App\Services;

use App\Category;

class CategoryService
{
    /**
     * Get all active categories
     */
    public function getActiveCategories()
    {
        return Category::where('is_active', 1)->get();
    }

    /**
     * Get category by slug
     */
    public function getCategoryBySlug(string $slug): ?Category
    {
        return Category::whereSlug($slug)->first();
    }

    /**
     * Get categories with latest products for homepage
     */
    public function getCategoriesWithLatestProducts($country, int $limit = 4)
    {
        return Category::where('is_active', 1)
            ->get()
            ->map(function ($category) use ($country, $limit) {
                $category->latest_products = $category->products()
                    ->whereHas('variants', function ($query) use ($country) {
                        $query->where('country_id', $country->id);
                    })
                    ->latest()
                    ->take($limit)
                    ->get();
                return $category;
            });
    }
}
