<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\{
    ProductController,
    CategoryController,
    BrandController,
    ComparisonController,
    SearchController
};

/*
|--------------------------------------------------------------------------
| New Architecture Routes (Optimized)
|--------------------------------------------------------------------------
|
| These routes use Route Model Binding for cleaner controllers
|
*/

// Route Model Bindings - Automatic model injection by slug
Route::bind('category', function ($value) {
    return \App\Category::whereSlug($value)->firstOrFail();
});

Route::bind('brand', function ($value) {
    return \App\Brand::whereSlug($value)->firstOrFail();
});

Route::bind('product', function ($value) {
    return \App\Product::whereSlug($value)->firstOrFail();
});

// Shared route configuration
$webRoutes = function () {
    // Product details - using route model binding
    Route::get('product/{product:slug}', [ProductController::class, 'show'])
        ->name('product.show');

    // Categories - using route model binding
    Route::get('category/{category:slug}', [CategoryController::class, 'show'])
        ->name('category.show');

    // Brands - using route model binding
    Route::get('brand/{brand:slug}/{categorySlug?}', [BrandController::class, 'show'])
        ->name('brand.show');

    // Comparisons
    Route::get('compare/{slug}', [ComparisonController::class, 'show'])
        ->name('compare.show');
    Route::get('comparison', [ComparisonController::class, 'index'])
        ->name('comparison');

    // Search
    Route::get('search', [SearchController::class, 'search'])
        ->name('search');
};

// Test routes with /v2 prefix
Route::prefix('v2')
    ->name('v2.')
    ->middleware(['default.country'])
    ->group($webRoutes);

// Country-specific routes
Route::prefix('{country_code}')
    ->name('country.')
    ->middleware(['default.country'])
    ->group($webRoutes);
