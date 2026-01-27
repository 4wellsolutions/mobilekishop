<?php

namespace App\Services;

use App\Country;
use App\Brand;
use App\Category;
use App\Product;

class MetaService
{
    /**
     * Generate meta data for product page
     */
    public function generateProductMeta(Product $product, Country $country): object
    {
        return (object) [
            'title' => "{$product->name} Price in {$country->country_name} - Full Specs & Reviews",
            'description' => "Get detailed specifications, features, reviews, and the latest price of {$product->name} in {$country->country_name}. Compare and make an informed decision.",
            'canonical' => url("/product/{$product->slug}"),
            'h1' => "{$product->name} in {$country->country_name}",
            'name' => $product->name
        ];
    }

    /**
     * Generate meta data for category page
     */
    public function generateCategoryMeta(Category $category, Country $country): object
    {
        return (object) [
            'title' => "Latest {$category->category_name} Reviews, Features, Price in {$country->country_name}",
            'description' => "Mobilekishop is the best {$category->category_name} website that provides the latest {$category->category_name} prices in {$country->country_name} with specifications, features, reviews and comparison.",
            'canonical' => url("/category/{$category->slug}"),
            'h1' => "{$category->category_name} Price in {$country->country_name}",
            'name' => $category->category_name
        ];
    }

    /**
     * Generate meta data for brand page
     */
    public function generateBrandMeta(Brand $brand, ?Category $category, Country $country): object
    {
        $categoryName = $category ? $category->category_name : 'Products';
        $currentMonthYear = now()->format('F Y');

        return (object) [
            'title' => "{$brand->name} {$categoryName} in {$country->country_name} - Reviews, Prices - {$currentMonthYear}",
            'description' => "Discover the latest {$brand->name} {$categoryName} in {$country->country_name} with Mobilekishop: Get detailed specifications, features, reviews, and price comparisons. Updated for {$currentMonthYear}.",
            'canonical' => $category
                ? url("/brand/{$brand->slug}/{$category->slug}")
                : url("/brand/{$brand->slug}/all"),
            'h1' => "{$brand->name} {$categoryName} in {$country->country_name}",
            'name' => "{$brand->name} {$categoryName}"
        ];
    }

    /**
     * Generate meta data for comparison page
     */
    public function generateComparisonMeta(Product $product1, ?Product $product2, ?Product $product3, Country $country): object
    {
        if ($product1 && $product2 && $product3) {
            $title = "{$product1->name} vs {$product2->name} vs {$product3->name} Specs & Prices";
            $description = "Compare {$product1->name} vs {$product2->name} vs {$product3->name} by specs, features, and prices. Detailed reviews to find the best choice for you.";
            $h1 = "{$product1->name} vs {$product2->name} vs {$product3->name} in {$country->country_name}";
        } elseif ($product1 && $product2) {
            $title = "{$product1->name} vs {$product2->name} Specs & Prices";
            $description = "Compare {$product1->name} vs {$product2->name} by specs, features, and prices. Detailed reviews to find the best choice for you.";
            $h1 = "{$product1->name} vs {$product2->name} in {$country->country_name}";
        } else {
            $title = "Compare Mobile Phones";
            $description = "Compare mobile phones by specifications, features, and prices.";
            $h1 = "Mobile Phone Comparison";
        }

        return (object) [
            'title' => $title,
            'description' => $description,
            'canonical' => url()->current(),
            'h1' => $h1,
            'name' => 'Compare Mobiles'
        ];
    }

    /**
     * Generate meta data for price filter page
     */
    public function generatePriceFilterMeta(int $amount, Country $country, ?Category $category = null): object
    {
        $categoryName = $category ? $category->category_name : 'Mobile Phones';

        return (object) [
            'title' => "Latest {$categoryName} Under {$country->currency} {$amount} Price in {$country->country_name}",
            'description' => "Find the latest {$categoryName} under {$country->currency} {$amount} on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            'canonical' => url()->current(),
            'h1' => "{$categoryName} Under {$country->currency} {$amount} in {$country->country_name}",
            'name' => "{$categoryName} under {$amount}"
        ];
    }

    /**
     * Generate meta data for search page
     */
    public function generateSearchMeta(string $query, Country $country): object
    {
        return (object) [
            'title' => "Search for {$query} Price in {$country->country_name}",
            'description' => "Search the mobile phones for {$query} on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            'canonical' => route('search'),
            'h1' => "Search for {$query}",
            'name' => "Search for {$query}"
        ];
    }

    /**
     * Alias for generatePriceFilterMeta for better naming consistency
     */
    public function generateForPriceFilter(int $amount, Country $country, ?Category $category = null): object
    {
        return $this->generatePriceFilterMeta($amount, $country, $category);
    }
}

