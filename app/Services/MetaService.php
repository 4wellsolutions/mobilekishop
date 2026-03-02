<?php

namespace App\Services;

use App\Models\Country;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class MetaService
{
    /**
     * Build a standard meta object.
     * Eliminates duplication across 14 methods that all return the same structure.
     */
    private function make(string $title, string $description, string $canonical, string $h1, string $name): object
    {
        return (object) compact('title', 'description', 'canonical', 'h1', 'name');
    }

    /**
     * Generate meta data for product page
     */
    public function generateProductMeta(Product $product, Country $country): object
    {
        return $this->make(
            "{$product->name} Price in {$country->country_name} - Full Specs & Reviews",
            "Get detailed specifications, features, reviews, and the latest price of {$product->name} in {$country->country_name}. Compare and make an informed decision.",
            url("/product/{$product->slug}"),
            "{$product->name} in {$country->country_name}",
            $product->name
        );
    }

    /**
     * Generate meta data for category page
     */
    public function generateCategoryMeta(Category $category, Country $country): object
    {
        return $this->make(
            "Latest {$category->category_name} Reviews, Features, Price in {$country->country_name}",
            "Mobilekishop is the best {$category->category_name} website that provides the latest {$category->category_name} prices in {$country->country_name} with specifications, features, reviews and comparison.",
            url("/category/{$category->slug}"),
            "{$category->category_name} Price in {$country->country_name}",
            $category->category_name
        );
    }

    /**
     * Generate meta data for brand page
     */
    public function generateBrandMeta(Brand $brand, ?Category $category, Country $country): object
    {
        $categoryName = $category ? $category->category_name : 'Products';
        $currentMonthYear = now()->format('F Y');

        return $this->make(
            "{$brand->name} {$categoryName} in {$country->country_name} - Reviews, Prices - {$currentMonthYear}",
            "Discover the latest {$brand->name} {$categoryName} in {$country->country_name} with Mobilekishop: Get detailed specifications, features, reviews, and price comparisons. Updated for {$currentMonthYear}.",
            $category
            ? url("/brand/{$brand->slug}/{$category->slug}")
            : url("/brand/{$brand->slug}/all"),
            "{$brand->name} {$categoryName} in {$country->country_name}",
            "{$brand->name} {$categoryName}"
        );
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

        return $this->make($title, $description, url()->current(), $h1, 'Compare Mobiles');
    }

    /**
     * Generate meta data for price filter page
     */
    public function generatePriceFilterMeta(int $amount, Country $country, ?Category $category = null): object
    {
        $categoryName = $category ? $category->category_name : 'Mobile Phones';

        return $this->make(
            "Latest {$categoryName} Under {$country->currency} {$amount} Price in {$country->country_name}",
            "Find the latest {$categoryName} under {$country->currency} {$amount} on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            url()->current(),
            "{$categoryName} Under {$country->currency} {$amount} in {$country->country_name}",
            "{$categoryName} under {$amount}"
        );
    }

    /**
     * Generate meta data for search page
     */
    public function generateSearchMeta(string $query, Country $country): object
    {
        return $this->make(
            "Search for {$query} Price in {$country->country_name}",
            "Search the mobile phones for {$query} on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            url()->current(),
            "Search for {$query}",
            "Search for {$query}"
        );
    }

    /**
     * Brand + Price Filter Meta
     */
    public function generateBrandPriceFilterMeta(?Brand $brand, int $amount, Country $country): object
    {
        $brandName = $brand ? $brand->name : 'Mobile';

        return $this->make(
            "Latest {$brandName} Mobile Phones Under {$country->currency} {$amount} Price in {$country->country_name}",
            "Find the latest {$brandName} mobile phones under {$country->currency} {$amount} on the Mobilekishop with specifications, features, reviews, comparison, and price in {$country->country_name}.",
            url()->current(),
            "{$brandName} Mobile Phones Under {$country->currency} {$amount} in {$country->country_name}",
            "{$brandName} Mobile Phones under {$amount}"
        );
    }

    /**
     * RAM Filter Meta
     */
    public function generateRamFilterMeta(int $ram, Country $country): object
    {
        return $this->make(
            "Best {$ram}GB RAM Mobile Phones in {$country->country_name} - Prices & Specs",
            "Explore information about {$ram}GB RAM mobile phones in {$country->country_name}. Compare prices, specs, and features.",
            url()->current(),
            "{$ram}GB RAM Mobile Phones in {$country->country_name}",
            "{$ram}GB RAM Mobiles"
        );
    }

    /**
     * ROM Filter Meta
     */
    public function generateRomFilterMeta(int $rom, Country $country): object
    {
        return $this->make(
            "Best {$rom}GB Storage Mobile Phones in {$country->country_name}",
            "Find mobile phones with {$rom}GB internal storage in {$country->country_name}. Compare latest prices and full specifications.",
            url()->current(),
            "{$rom}GB Storage Mobile Phones in {$country->country_name}",
            "{$rom}GB Storage Mobiles"
        );
    }

    /**
     * RAM + ROM Combo Meta
     */
    public function generateRamRomFilterMeta(int $ram, int $rom, Country $country): object
    {
        return $this->make(
            "{$ram}GB RAM & {$rom}GB ROM Mobile Phones in {$country->country_name}",
            "Explore mobile phones featuring {$ram}GB RAM and {$rom}GB internal storage. Compare latest prices and specs in {$country->country_name}.",
            url()->current(),
            "{$ram}GB + {$rom}GB Mobile Phones in {$country->country_name}",
            "{$ram}GB + {$rom}GB Mobiles"
        );
    }

    /**
     * Screen Size Filter Meta
     */
    public function generateScreenFilterMeta(float $size, Country $country): object
    {
        return $this->make(
            "Mobile Phones with Screen Size up to {$size} inches in {$country->country_name}",
            "Find smartphones with display sizes around {$size} inches. Compare screen types, resolutions, and prices in {$country->country_name}.",
            url()->current(),
            "{$size}\" Screen Mobile Phones in {$country->country_name}",
            "{$size}\" Display Mobiles"
        );
    }

    /**
     * Camera Count Filter Meta
     */
    public function generateCameraCountFilterMeta(string $parameter, Country $country): object
    {
        $name = ucfirst($parameter);

        return $this->make(
            "Best {$name} Camera Mobile Phones in {$country->country_name}",
            "Explore mobile phones with {$parameter} cameras. Compare photography features and prices in {$country->country_name}.",
            url()->current(),
            "{$name} Camera Phones in {$country->country_name}",
            "{$name} Camera Mobiles"
        );
    }

    /**
     * Camera MP Filter Meta
     */
    public function generateCameraMpFilterMeta(int $mp, Country $country): object
    {
        return $this->make(
            "{$mp}MP Camera Mobile Phones in {$country->country_name} - High Res Photography",
            "Find smartphones with {$mp}MP cameras in {$country->country_name}. Compare camera specs, sensors, and latest prices.",
            url()->current(),
            "{$mp}MP Camera Phones in {$country->country_name}",
            "{$mp}MP Camera Mobiles"
        );
    }

    /**
     * Processor Filter Meta
     */
    public function generateProcessorFilterMeta(string $processor, Country $country): object
    {
        $processor = ucfirst($processor);

        return $this->make(
            "Best {$processor} Mobile Phones in {$country->country_name}",
            "Explore smartphones powered by {$processor}. Compare specs and prices in {$country->country_name}.",
            url()->current(),
            "{$processor} Mobile Phones in {$country->country_name}",
            "{$processor} Mobile Phones"
        );
    }

    /**
     * Type (Folding/Flip/4G/5G) Filter Meta
     */
    public function generateTypeFilterMeta(string $type, Country $country, ?Category $category = null): object
    {
        $typeName = in_array($type, ['4g', '5g']) ? strtoupper($type) : ucfirst($type);
        $categoryName = $category ? $category->category_name : 'Mobile Phones';

        return $this->make(
            "Best {$typeName} {$categoryName} in {$country->country_name}",
            "Explore {$typeName} {$categoryName}. Compare specs, features, and prices in {$country->country_name}.",
            url()->current(),
            "{$typeName} {$categoryName} in {$country->country_name}",
            "{$typeName} {$categoryName}"
        );
    }

    /**
     * Curved Filter Meta
     */
    public function generateCurvedFilterMeta(?Brand $brand, Country $country): object
    {
        $brandName = $brand ? $brand->name : 'Curved Display';

        return $this->make(
            "{$brandName} Mobile Phones in {$country->country_name}",
            "Explore {$brandName} smartphones with curved displays. Compare specs, features, and prices in {$country->country_name}.",
            url()->current(),
            "{$brandName} Phones in {$country->country_name}",
            "{$brandName} Phones"
        );
    }

    /**
     * Upcoming Meta
     */
    public function generateUpcomingMeta(Country $country): object
    {
        return $this->make(
            "Upcoming Mobile Phones in {$country->country_name} - Launch Dates & Specs",
            "Stay updated with upcoming mobile phone launches in {$country->country_name}. Get expected prices, specifications, and release dates.",
            url()->current(),
            "Upcoming Mobile Phones in {$country->country_name}",
            "Upcoming Mobile Phones"
        );
    }

    /**
     * Home Page Meta
     */
    public function generateHomeMeta(Country $country): object
    {
        return $this->make(
            "Mobile phones prices in {$country->country_name} - Specifications & Reviews",
            "Mobilekishop is the best mobile website that provides the latest mobile prices in {$country->country_name} with specifications, features, reviews and comparison.",
            url('/'),
            "Mobile Ki Shop",
            "Home"
        );
    }
}
