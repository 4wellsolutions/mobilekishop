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
    AccessoryController,
    HomeController,
    SitemapController
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
    if ($value instanceof \App\Models\Category)
        return $value;
    return \App\Models\Category::whereSlug($value)->firstOrFail();
});

Route::bind('brand', function ($value) {
    if ($value instanceof \App\Models\Brand)
        return $value;
    $brand = \App\Models\Brand::whereSlug($value)->first();
    if (!$brand) {
        return $value; // Return the slug itself so the controller can handle it or fall through
    }
    return $brand;
});

Route::bind('product', function ($value) {
    if ($value instanceof \App\Models\Product)
        return $value;
    return \App\Models\Product::whereSlug($value)->firstOrFail();
});

// Shared Routing Logic Closure
$webRoutes = function () {
    // Home
    Route::get('/', [HomeController::class, 'index'])->name('index');

    // Static Pages
    Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy.policy');
    Route::get('/terms-and-conditions', [HomeController::class, 'termsConditions'])->name('terms.conditions');
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
    Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('about');

    // Search
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    // Comparison
    Route::get('/comparison', [ComparisonController::class, 'index'])->name('comparison');
    Route::get('/compare/{slug}', [ComparisonController::class, 'show'])->name('compare');

    // Product details
    Route::get('product/{product}', [ProductController::class, 'show'])->name('product.show');
    Route::get('products-show/{id}', [ProductController::class, 'getRedirect'])->name('product.redirect');
    Route::get('product/autocomplete', [ProductController::class, 'autocomplete'])->name('product.autocomplete');



    // Categories
    Route::get('category/{category}', [CategoryController::class, 'show'])->name('category.show');

    // Brands
    Route::get('brand/{brand}/{categorySlug?}', [BrandController::class, 'show'])->name('brand.show');
    Route::get('brands/{category_slug?}', [BrandController::class, 'index'])->name('brands.by.category');

    // Comparisons
    Route::get('compare/{slug}', [ComparisonController::class, 'show'])
        ->name('compare.show');
    Route::get('comparison', [ComparisonController::class, 'index'])
        ->name('comparison');

    // Search
    Route::get('search', [SearchController::class, 'search'])
        ->name('search');

    // Sitemaps
    Route::get('html-sitemap', [SitemapController::class, 'htmlSitemap'])->name('html.sitemap');
    Route::get('sitemap.xml', [SitemapController::class, 'serveSitemap'])->name('sitemap.main');
    Route::get('sitemaps/{sitemap}', [SitemapController::class, 'serveSitemap'])->name('sitemap.file');
    Route::get('sitemap-{sitemap}', [SitemapController::class, 'serveSitemap'])->name('sitemap.file.prefixed');

    Route::get('sponsor', function () {
        return view('frontend.new.sponsor');
    })->name('sponsor');

    // POST Actions
    Route::post('review', [HomeController::class, 'reviewPost'])->name('review.post');
    Route::post('contact', [HomeController::class, 'contactPost'])->name('contact.post');

    // =========================================================================
    // Filter Routes (Mobiles)
    // =========================================================================

    // Price filters: mobile-phones-under-{amount}
    Route::get('mobile-phones-under-{amount}', [FilterController::class, 'underPrice'])
        ->name('filter.price')
        ->where('amount', '[0-9]+');

    // Brand specific price filters: {brand}-mobile-phones-under-{amount}
    Route::get('{brand}-mobile-phones-under-{amount}', [FilterController::class, 'brandUnderAmount'])
        ->name('filter.brand.price')
        ->where(['amount' => '[0-9]+']);

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

    // Folding/Flip: folding-mobile-phones, flip-mobile-phones
    Route::get('{type}-mobile-phones', [FilterController::class, 'byType'])
        ->name('filter.type')
        ->where('type', 'folding|flip|4g|5g');

    // Upcoming: up-coming-mobile-phones
    Route::get('up-coming-mobile-phones', [FilterController::class, 'upcoming'])
        ->name('filter.upcoming');

    // Processor filters: {processor}-mobile-phones
    Route::get('{processor}-mobile-phones', [FilterController::class, 'byProcessor'])
        ->name('filter.processor')
        ->where('processor', 'snapdragon-[a-z0-9-]+|mediatek|exynos|kirin|google-tensor');

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

    // Network: {type}-tablets
    Route::get('{type}-tablets', [TabletFilterController::class, 'byType'])
        ->name('filter.tablet.type')
        ->where('type', '4g|5g');

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

    Route::get('phone-covers/{brand}/{slug}', [AccessoryController::class, 'phoneCoversByBrand'])
        ->name('filter.phonecover.brand');

    // Smart Watches: smart-watches-under-{amount}
    Route::get('smart-watches-under-{amount}', [AccessoryController::class, 'smartWatchesUnderPrice'])
        ->name('filter.smartwatch.price')
        ->where('amount', '[0-9]+');

    // Chargers: usb-type-a-chargers, usb-type-c-chargers
    Route::get('usb-type-{type}-chargers', [AccessoryController::class, 'chargersByPortType'])
        ->name('filter.charger.port')
        ->where('type', 'a|c');

    // Chargers: {watt}watt-chargers
    Route::get('{watt}watt-chargers', [AccessoryController::class, 'chargersByWatt'])
        ->name('filter.charger.watt')
        ->where('watt', '[0-9]+');

    // Chargers: {watt}w-usb-type-c-chargers
    Route::get('{watt}w-usb-type-c-chargers', [AccessoryController::class, 'chargersByWattAndPortType'])
        ->name('filter.charger.watt_type')
        ->where('watt', '[0-9]+');

    // =========================================================================
    // Cable Routes
    // =========================================================================

    // Cables by brand and wattage: {brand}-{watt}w-cables (must be before generic slug route)
    Route::get('{brand}-{watt}w-cables', [AccessoryController::class, 'cablesByBrandAndWatt'])
        ->name('filter.cable.brand_watt')
        ->where(['watt' => '[0-9]+']);

    // Cables by type: {slug}-cables (e.g., usb-c-to-usb-c-cables, usb-a-to-usb-c-cables)
    Route::get('{slug}-cables', [AccessoryController::class, 'cablesByType'])
        ->name('filter.cable.type')
        ->where('slug', '[a-z0-9-]+');

    // Legacy/Alternative Product Path: {brand}/{product} - Catch-all for two segments
    // We add a regex to ensure it doesn't match routes like 'category/xxx', 'product/xxx', etc.
    Route::get('{brand}/{product}', [ProductController::class, 'show'])
        ->name('product.show.legacy')
        ->where([
            'brand' => '^(?!(category|product|products|brand|brands|compare|comparison|search|sitemaps|html-sitemap|sponsor|privacy-policy|terms-and-conditions|contact|about-us|mobile-phones-under|packages|pta-calculator|mobile-installment-calculator|dashboard|user|unsubscribe|auth|login|register|password|api)).*'
        ]);
};

// 1. Specific Country Routes (With Prefix) - Matches 'domain.com/ae/path'
Route::group([
    'prefix' => '{country_code}',
    'middleware' => ['default.country', 'cache.page'],
    'as' => 'country.', // Prefix route names with 'country.'
    'where' => ['country_code' => '[a-z]{2}']
], $webRoutes);

// 2. Default Country Routes (No Prefix) - Matches 'domain.com/path'
Route::middleware(['default.country', 'cache.page'])
    ->group($webRoutes);
