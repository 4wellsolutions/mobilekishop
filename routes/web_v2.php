<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\{
    ProductController,
    CategoryController,
    BrandController,
    ComparisonController,
    SearchController,
    FilterController,
    TabletFilterController,
    AccessoryController
};



/*
|--------------------------------------------------------------------------
| New Architecture Routes (Optimized)
|--------------------------------------------------------------------------
|
| These routes use Route Model Binding for cleaner controllers.
| They match the Legacy URL structure to ensure 0 downtime and SEO preservation.
|
*/

// Route Model Bindings - Automatic model injection by slug
Route::bind('category', function ($value) {
    return \App\Category::whereSlug($value)->firstOrFail();
});

Route::bind('brand', function ($value) {
    \Log::info("Attempting to bind brand with slug: " . $value);
    $brand = \App\Brand::whereSlug($value)->first();
    if (!$brand) {
        \Log::error("Brand binding failed for slug: " . $value);
        abort(404);
    }
    return $brand;
});

Route::bind('product', function ($value) {
    return \App\Product::whereSlug($value)->firstOrFail();
});

// Shared Routing Logic Closure
$webRoutes = function () {
    // Product details
    // Product details
    // Product details
    Route::get('product/{product}', function (\Illuminate\Http\Request $request) {
        $params = $request->route()->parameters();
        $product = $params['product'] ?? null;
        $countryCode = $params['country_code'] ?? null;

        $controller = app(ProductController::class);
        return $controller->show($countryCode, $product, $request);
    })->name('product.show');

    // Categories
    Route::get('category/{category}', [CategoryController::class, 'show'])
        ->name('category.show');

    // Brands
    Route::get('brand/{brand}/{categorySlug?}', function (\Illuminate\Http\Request $request) {
        $params = $request->route()->parameters();
        $brand = $params['brand'] ?? null;
        $categorySlug = $params['categorySlug'] ?? null;
        $countryCode = $params['country_code'] ?? null;

        $controller = app(BrandController::class);
        // Map arguments correctly: $countrySlug, $brand, $categorySlug, $request
        return $controller->show($countryCode, $brand, $categorySlug, $request);
    })->name('brand.show');

    // Comparisons
    Route::get('compare/{slug}', [ComparisonController::class, 'show'])
        ->name('compare.show');
    Route::get('comparison', [ComparisonController::class, 'index'])
        ->name('comparison');

    // Search
    Route::get('search', [SearchController::class, 'search'])
        ->name('search');

    // =========================================================================
    // Filter Routes (Mobiles)
    // =========================================================================

    // Price filters: mobile-phones-under-{amount}
    Route::get('mobile-phones-under-{amount}', [FilterController::class, 'underPrice'])
        ->name('filter.price')
        ->where('amount', '[0-9]+');

    // RAM filters: mobile-phones-{ram}gb-ram
    Route::get('mobile-phones-{ram}gb-ram', [FilterController::class, 'byRam'])
        ->name('filter.ram')
        ->where('ram', '[0-9]+');

    // ROM/Storage filters: mobile-phones-{rom}gb-storage, mobile-phones-2tb, etc.
    Route::get('mobile-phones-{rom}{unit}-storage', [FilterController::class, 'byRom'])
        ->name('filter.rom')
        ->where(['rom' => '[0-9]+', 'unit' => 'gb|tb']);

    Route::get('mobile-phones-{rom}{unit}', [FilterController::class, 'byRom'])
        ->name('filter.rom.legacy')
        ->where(['rom' => '[0-9]+', 'unit' => 'gb|tb']);

    // RAM + ROM combination: mobile-phones-with-{ram}gb-ram-{rom}gb-storage
    Route::get('mobile-phones-with-{ram}gb-ram-{rom}gb-storage', [FilterController::class, 'ramRomCombo'])
        ->name('filter.ramrom')
        ->where(['ram' => '[0-9]+', 'rom' => '[0-9]+']);

    // Screen size filters: mobile-phones-screen-{maxSize}-inch
    Route::get('mobile-phones-screen-{maxSize}-inch', [FilterController::class, 'byScreenSize'])
        ->name('filter.screen')
        ->where('maxSize', '[0-9]+');

    // Camera count: mobile-phones-{parameter}-camera
    Route::get('mobile-phones-{parameter}-camera', [FilterController::class, 'byCameraCount'])
        ->name('filter.camera.count')
        ->where('parameter', 'dual|triple|quad|penta');

    // Camera MP: mobile-phones-{mp}mp-camera, mobile-phones-{mp}-camera
    Route::get('mobile-phones-{mp}mp-camera', [FilterController::class, 'byCameraMp'])
        ->name('filter.camera.mp')
        ->where('mp', '[0-9]+');

    Route::get('mobile-phones-{mp}-camera', [FilterController::class, 'byCameraMp'])
        ->name('filter.camera.mp.legacy')
        ->where('mp', '[0-9]+');

    // Curved screens: curved-display-mobile-phones
    Route::get('curved-display-mobile-phones', [FilterController::class, 'curvedScreensByBrand'])
        ->name('filter.curved.all')
        ->defaults('brandSlug', 'all');

    // Curved screens by brand: {brandSlug}-curved-display-mobile-phones
    Route::get('{brandSlug}-curved-display-mobile-phones', [FilterController::class, 'curvedScreensByBrand'])
        ->name('filter.curved.brand')
        ->where('brandSlug', '[a-z0-9-]+');

    // Upcoming: up-coming-mobile-phones
    Route::get('up-coming-mobile-phones', [FilterController::class, 'upcoming'])
        ->name('filter.upcoming');

    // =========================================================================
    // Tablet Filter Routes
    // =========================================================================

    // Price: tablets-under-{amount}
    Route::get('tablets-under-{amount}', [TabletFilterController::class, 'underPrice'])
        ->name('filter.tablet.price')
        ->where('amount', '[0-9]+');

    // RAM: tablets-{ram}gb-ram
    Route::get('tablets-{ram}gb-ram', [TabletFilterController::class, 'byRam'])
        ->name('filter.tablet.ram')
        ->where('ram', '[0-9]+');

    // ROM: tablets-{rom}gb-storage
    Route::get('tablets-{rom}gb-storage', [TabletFilterController::class, 'byRom'])
        ->name('filter.tablet.rom')
        ->where('rom', '[0-9]+');

    // Screen: tablets-screen-{inch}-inch
    Route::get('tablets-screen-{inch}-inch', [TabletFilterController::class, 'byScreenSize'])
        ->name('filter.tablet.screen')
        ->where('inch', '[0-9]+');

    // Camera: tablets-{mp}mp-camera
    Route::get('tablets-{mp}mp-camera', [TabletFilterController::class, 'byCameraMp'])
        ->name('filter.tablet.camera')
        ->where('mp', '[0-9]+');

    // =========================================================================
    // Accessory Routes
    // =========================================================================

    // Power Banks: power-banks/power-banks-with-{mah}-mah
    Route::get('power-banks/power-banks-with-{mah}-mah', [AccessoryController::class, 'powerBanksByCapacity'])
        ->name('filter.powerbank.capacity')
        ->where('mah', '[0-9]+');

    // Phone Covers: phone-covers/{slug}
    Route::get('phone-covers/{slug}', [AccessoryController::class, 'phoneCoversByModel'])
        ->name('filter.phonecover.model');

    // Smart Watches: smart-watches-under-{amount}
    Route::get('smart-watches-under-{amount}', [AccessoryController::class, 'smartWatchesUnderPrice'])
        ->name('filter.smartwatch.price')
        ->where('amount', '[0-9]+');
};

// 1. Default Country Routes (No Prefix) - Matches 'domain.com/path'
Route::middleware(['default.country'])
    ->group($webRoutes);

// 2. Specific Country Routes (With Prefix) - Matches 'domain.com/ae/path'
Route::group([
    'prefix' => '{country_code}',
    'middleware' => ['default.country'],
    'as' => 'country.', // Prefix route names with 'country.'
    'where' => ['country_code' => '[a-z]{2}']
], $webRoutes);
