<?php

namespace App\Services;

use App\Models\Country;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

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
            'canonical' => url()->current(),
            'h1' => "Search for {$query}",
            'name' => "Search for {$query}"
        ];
    }

    /**
     * Brand + Price Filter Meta
     */
    public function generateBrandPriceFilterMeta(?Brand $brand, int $amount, Country $country): object
    {
        $brandName = $brand ? $brand->name : 'Mobile';
        return (object) [
            'title' => "Latest {$brandName} Mobile Phones Under {$country->currency} {$amount} Price in {$country->country_name}",
            'description' => "Find the latest {$brandName} mobile phones under {$country->currency} {$amount} on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            'canonical' => url()->current(),
            'h1' => "{$brandName} Mobile Phones Under {$country->currency} {$amount} in {$country->country_name}",
            'name' => "{$brandName} Mobile Phones under {$amount}"
        ];
    }

    /**
     * RAM Filter Meta
     */
    public function generateRamFilterMeta(int $ram, Country $country): object
    {
        return (object) [
            'title' => "Best {$ram}GB RAM Mobile Phones in {$country->country_name} - Prices & Specs",
            'description' => "Explore information about {$ram}GB RAM mobile phones in {$country->country_name}. Compare prices, specs, and features.",
            'canonical' => url()->current(),
            'h1' => "{$ram}GB RAM Mobile Phones in {$country->country_name}",
            'name' => "{$ram}GB RAM Mobiles"
        ];
    }

    /**
     * ROM Filter Meta
     */
    public function generateRomFilterMeta(int $rom, Country $country): object
    {
        return (object) [
            'title' => "Best {$rom}GB Storage Mobile Phones in {$country->country_name}",
            'description' => "Find mobile phones with {$rom}GB internal storage in {$country->country_name}. Compare latest prices and full specifications.",
            'canonical' => url()->current(),
            'h1' => "{$rom}GB Storage Mobile Phones in {$country->country_name}",
            'name' => "{$rom}GB Storage Mobiles"
        ];
    }

    /**
     * RAM + ROM Combo Meta
     */
    public function generateRamRomFilterMeta(int $ram, int $rom, Country $country): object
    {
        return (object) [
            'title' => "{$ram}GB RAM & {$rom}GB ROM Mobile Phones in {$country->country_name}",
            'description' => "Explore mobile phones featuring {$ram}GB RAM and {$rom}GB internal storage. Compare latest prices and specs in {$country->country_name}.",
            'canonical' => url()->current(),
            'h1' => "{$ram}GB + {$rom}GB Mobile Phones in {$country->country_name}",
            'name' => "{$ram}GB + {$rom}GB Mobiles"
        ];
    }

    /**
     * Screen Size Filter Meta
     */
    public function generateScreenFilterMeta(float $size, Country $country): object
    {
        return (object) [
            'title' => "Mobile Phones with Screen Size up to {$size} inches in {$country->country_name}",
            'description' => "Find smartphones with display sizes around {$size} inches. Compare screen types, resolutions, and prices in {$country->country_name}.",
            'canonical' => url()->current(),
            'h1' => "{$size}\" Screen Mobile Phones in {$country->country_name}",
            'name' => "{$size}\" Display Mobiles"
        ];
    }

    /**
     * Camera Count Filter Meta
     */
    public function generateCameraCountFilterMeta(string $parameter, Country $country): object
    {
        $name = ucfirst($parameter);
        return (object) [
            'title' => "Best {$name} Camera Mobile Phones in {$country->country_name}",
            'description' => "Explore mobile phones with {$parameter} cameras. Compare photography features and prices in {$country->country_name}.",
            'canonical' => url()->current(),
            'h1' => "{$name} Camera Phones in {$country->country_name}",
            'name' => "{$name} Camera Mobiles"
        ];
    }

    /**
     * Camera MP Filter Meta
     */
    public function generateCameraMpFilterMeta(int $mp, Country $country): object
    {
        return (object) [
            'title' => "{$mp}MP Camera Mobile Phones in {$country->country_name} - High Res Photography",
            'description' => "Find smartphones with {$mp}MP cameras in {$country->country_name}. Compare camera specs, sensors, and latest prices.",
            'canonical' => url()->current(),
            'h1' => "{$mp}MP Camera Phones in {$country->country_name}",
            'name' => "{$mp}MP Camera Mobiles"
        ];
    }

    /**
     * Processor Filter Meta
     */
    public function generateProcessorFilterMeta(string $processor, Country $country): object
    {
        $processor = ucfirst($processor);
        return (object) [
            'title' => "Best {$processor} Mobile Phones in {$country->country_name}",
            'description' => "Explore smartphones powered by {$processor}. Compare specs and prices in {$country->country_name}.",
            'canonical' => url()->current(),
            'h1' => "{$processor} Mobile Phones in {$country->country_name}",
            'name' => "{$processor} Mobile Phones"
        ];
    }

    /**
     * Type (Folding/Flip/4G/5G) Filter Meta
     */
    public function generateTypeFilterMeta(string $type, Country $country, ?Category $category = null): object
    {
        $typeName = strtoupper($type);
        if (!in_array($type, ['4g', '5g'])) {
            $typeName = ucfirst($type);
        }

        $categoryName = $category ? $category->category_name : 'Mobile Phones';

        return (object) [
            'title' => "Best {$typeName} {$categoryName} in {$country->country_name}",
            'description' => "Explore {$typeName} {$categoryName}. Compare specs, features, and prices in {$country->country_name}.",
            'canonical' => url()->current(),
            'h1' => "{$typeName} {$categoryName} in {$country->country_name}",
            'name' => "{$typeName} {$categoryName}"
        ];
    }

    /**
     * Curved Filter Meta
     */
    public function generateCurvedFilterMeta(?Brand $brand, Country $country): object
    {
        $brandName = $brand ? $brand->name : 'Curved Display';
        return (object) [
            'title' => "{$brandName} Mobile Phones in {$country->country_name}",
            'description' => "Explore {$brandName} smartphones with curved displays. Compare specs, features, and prices in {$country->country_name}.",
            'canonical' => url()->current(),
            'h1' => "{$brandName} Phones in {$country->country_name}",
            'name' => "{$brandName} Phones"
        ];
    }

    /**
     * Upcoming Meta
     */
    public function generateUpcomingMeta(Country $country): object
    {
        return (object) [
            'title' => "Upcoming Mobile Phones in {$country->country_name} - Launch Dates & Specs",
            'description' => "Stay updated with upcoming mobile phone launches in {$country->country_name}. Get expected prices, specifications, and release dates.",
            'canonical' => url()->current(),
            'h1' => "Upcoming Mobile Phones in {$country->country_name}",
            'name' => "Upcoming Mobile Phones"
        ];
    }

    /**
     * Home Page Meta
     */
    public function generateHomeMeta(Country $country): object
    {
        return (object) [
            'title' => "Mobile phones prices in {$country->country_name} - Specifications & Reviews",
            'description' => "Mobilekishop is the best mobile website that provides the latest mobile prices in {$country->country_name} with specifications, features, reviews and comparison.",
            'canonical' => url('/'),
            'h1' => "Mobile Ki Shop",
            'name' => "Home"
        ];
    }
}

