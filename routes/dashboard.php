<?php
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\VariantController;
use App\Http\Controllers\Dashboard\TaxController;
use App\Http\Controllers\Dashboard\CountryController;
use App\Http\Controllers\Dashboard\BrandController;
use App\Http\Controllers\Dashboard\PackageController;
use App\Http\Controllers\Dashboard\CompareController;
use App\Http\Controllers\Dashboard\ReviewController;
use App\Http\Controllers\Dashboard\FilterController;
use App\Http\Controllers\Dashboard\RedirectionController;
use App\Http\Controllers\Dashboard\PageController;
use App\Http\Controllers\Dashboard\ColorController;
use App\Http\Controllers\Dashboard\AttributeController;
use App\Http\Controllers\Dashboard\SiteSettingController;
use App\Http\Controllers\Dashboard\ErrorLogController;

Route::prefix('dashboard')
	->middleware(['auth', 'admin'])
	->name('dashboard.')
	->group(function () {
		// Dashboard Home
		Route::get('/', [DashboardController::class, 'index'])->name('index');

		// Profile Routes
		Route::get('profile', [\App\Http\Controllers\Dashboard\ProfileController::class, 'index'])->name('profile.index');
		Route::put('profile', [\App\Http\Controllers\Dashboard\ProfileController::class, 'update'])->name('profile.update');
		Route::put('profile/password', [\App\Http\Controllers\Dashboard\ProfileController::class, 'updatePassword'])->name('profile.password');

		// Blog Routes
		Route::get('blogs', [\App\Http\Controllers\Dashboard\BlogController::class, 'index'])->name('blogs.index');
		Route::get('blogs/create', [\App\Http\Controllers\Dashboard\BlogController::class, 'create'])->name('blogs.create');
		Route::post('blogs', [\App\Http\Controllers\Dashboard\BlogController::class, 'store'])->name('blogs.store');
		Route::get('blogs/{blog}/edit', [\App\Http\Controllers\Dashboard\BlogController::class, 'edit'])->name('blogs.edit');
		Route::put('blogs/{blog}', [\App\Http\Controllers\Dashboard\BlogController::class, 'update'])->name('blogs.update');
		Route::delete('blogs/{blog}', [\App\Http\Controllers\Dashboard\BlogController::class, 'destroy'])->name('blogs.destroy');

		// Blog Category Routes
		Route::get('blog-categories', [\App\Http\Controllers\Dashboard\BlogCategoryController::class, 'index'])->name('blog-categories.index');
		Route::get('blog-categories/create', [\App\Http\Controllers\Dashboard\BlogCategoryController::class, 'create'])->name('blog-categories.create');
		Route::post('blog-categories', [\App\Http\Controllers\Dashboard\BlogCategoryController::class, 'store'])->name('blog-categories.store');
		Route::get('blog-categories/{blog_category}/edit', [\App\Http\Controllers\Dashboard\BlogCategoryController::class, 'edit'])->name('blog-categories.edit');
		Route::put('blog-categories/{blog_category}', [\App\Http\Controllers\Dashboard\BlogCategoryController::class, 'update'])->name('blog-categories.update');
		Route::delete('blog-categories/{blog_category}', [\App\Http\Controllers\Dashboard\BlogCategoryController::class, 'destroy'])->name('blog-categories.destroy');

		// Media Library Routes
		Route::get('media', [\App\Http\Controllers\Dashboard\MediaController::class, 'index'])->name('media.index');
		Route::get('media/api', [\App\Http\Controllers\Dashboard\MediaController::class, 'apiIndex'])->name('media.api');
		Route::post('media/upload', [\App\Http\Controllers\Dashboard\MediaController::class, 'upload'])->name('media.upload');
		Route::delete('media', [\App\Http\Controllers\Dashboard\MediaController::class, 'destroy'])->name('media.destroy');


		// Product Routes
		Route::get('products/scrap', [ProductController::class, 'scrap'])->name('products.scrap');
		Route::post('products/scrap/amazon', [ProductController::class, 'scrapAmazon'])->name('products.scrap.amazon');
		Route::get('products/price/getPrices', [ProductController::class, 'getPrices'])->name('products.price.getPrices');
		Route::get('products/index', [ProductController::class, 'index'])->name('products.index');
		Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
		Route::post('products', [ProductController::class, 'store'])->name('products.store');
		Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
		Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
		Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
		Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

		// Additional Product Routes
		Route::get('products/price/{id}', [ProductController::class, 'priceCreate'])->name('products.price.create');
		Route::post('products/price/{id}', [ProductController::class, 'priceStore'])->name('products.price.store');

		Route::post('products/price/update/{id}', [ProductController::class, 'priceUpdate'])->name('products.price.update');
		Route::get('products/delete-image/{id}', [ProductController::class, 'deleteImage'])->name('products.image.delete');
		Route::get('products/brand/{brand_id}', [ProductController::class, 'byBrand'])->name('products.brand.products');
		Route::delete('products/{product}/color/{color}', [ProductController::class, 'removeColor'])->name('products.color.remove');



		// Variant Routes
		Route::get('variants', [VariantController::class, 'index'])->name('variants.index');
		Route::get('variants/create', [VariantController::class, 'create'])->name('variants.create');
		Route::post('variants', [VariantController::class, 'store'])->name('variants.store');
		Route::get('variants/{variant}', [VariantController::class, 'show'])->name('variants.show');
		Route::get('variants/{variant}/edit', [VariantController::class, 'edit'])->name('variants.edit');
		Route::put('variants/{variant}', [VariantController::class, 'update'])->name('variants.update');
		Route::delete('variants/{variant}', [VariantController::class, 'destroy'])->name('variants.destroy');


		// Color Routes
		Route::get('colors', [ColorController::class, 'index'])->name('colors.index');
		Route::get('colors/create', [ColorController::class, 'create'])->name('colors.create');
		Route::post('colors', [ColorController::class, 'store'])->name('colors.store');
		Route::get('colors/{color}', [ColorController::class, 'show'])->name('colors.show');
		Route::get('colors/{color}/edit', [ColorController::class, 'edit'])->name('colors.edit');
		Route::put('colors/{color}', [ColorController::class, 'update'])->name('colors.update');
		Route::delete('colors/{color}', [ColorController::class, 'destroy'])->name('colors.destroy');


		// Category Routes
		Route::get('categories/index', [CategoryController::class, 'index'])->name('categories.index');
		Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
		Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
		Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
		Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
		Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
		Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');


		// Attribute Routes
		Route::get('attributes/index', [AttributeController::class, 'index'])->name('attributes.index');
		Route::get('attributes/create', [AttributeController::class, 'create'])->name('attributes.create');
		Route::post('attributes', [AttributeController::class, 'store'])->name('attributes.store');
		Route::get('attributes/{attribute}', [AttributeController::class, 'show'])->name('attributes.show');
		Route::get('attributes/{attribute}/edit', [AttributeController::class, 'edit'])->name('attributes.edit');
		Route::put('attributes/{attribute}', [AttributeController::class, 'update'])->name('attributes.update');
		Route::delete('attributes/{attribute}', [AttributeController::class, 'destroy'])->name('attributes.destroy');


		// Tax Routes
		Route::get('taxes/index', [TaxController::class, 'index'])->name('taxes.index');
		Route::get('taxes/create', [TaxController::class, 'create'])->name('taxes.create');
		Route::post('taxes', [TaxController::class, 'store'])->name('taxes.store');
		Route::get('taxes/{tax}', [TaxController::class, 'show'])->name('taxes.show');
		Route::get('taxes/{tax}/edit', [TaxController::class, 'edit'])->name('taxes.edit');
		Route::put('taxes/{tax}', [TaxController::class, 'update'])->name('taxes.update');
		Route::delete('taxes/{tax}', [TaxController::class, 'destroy'])->name('taxes.destroy');


		// Brand Routes
		Route::get('brands/index', [BrandController::class, 'index'])->name('brands.index');
		Route::get('brands/create', [BrandController::class, 'create'])->name('brands.create');
		Route::post('brands', [BrandController::class, 'store'])->name('brands.store');
		Route::get('brands/{brand}', [BrandController::class, 'show'])->name('brands.show');
		Route::get('brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
		Route::put('brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
		Route::delete('brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');


		// Package Routes
		Route::get('packages/index', [PackageController::class, 'index'])->name('packages.index');
		Route::get('packages/create', [PackageController::class, 'create'])->name('packages.create');
		Route::post('packages', [PackageController::class, 'store'])->name('packages.store');
		Route::get('packages/{package}', [PackageController::class, 'show'])->name('packages.show');
		Route::get('packages/{package}/edit', [PackageController::class, 'edit'])->name('packages.edit');
		Route::put('packages/{package}', [PackageController::class, 'update'])->name('packages.update');
		Route::delete('packages/{package}', [PackageController::class, 'destroy'])->name('packages.destroy');


		// Country Routes
		Route::get('countries/index', [CountryController::class, 'index'])->name('countries.index');
		Route::get('countries/create', [CountryController::class, 'create'])->name('countries.create');
		Route::post('countries', [CountryController::class, 'store'])->name('countries.store');
		Route::get('countries/{country}', [CountryController::class, 'show'])->name('countries.show');
		Route::get('countries/{country}/edit', [CountryController::class, 'edit'])->name('countries.edit');
		Route::put('countries/{country}', [CountryController::class, 'update'])->name('countries.update');
		Route::delete('countries/{country}', [CountryController::class, 'destroy'])->name('countries.destroy');


		// Compare Routes
		Route::get('compares/index', [CompareController::class, 'index'])->name('compares.index');
		Route::get('compares/create', [CompareController::class, 'create'])->name('compares.create');
		Route::post('compares', [CompareController::class, 'store'])->name('compares.store');
		Route::get('compares/{compare}', [CompareController::class, 'show'])->name('compares.show');
		Route::get('compares/{compare}/edit', [CompareController::class, 'edit'])->name('compares.edit');
		Route::put('compares/{compare}', [CompareController::class, 'update'])->name('compares.update');
		Route::delete('compares/{compare}', [CompareController::class, 'destroy'])->name('compares.destroy');


		Route::get('compares/image', [CompareController::class, 'mergeImages'])->name('compare.merge');
		Route::get('compares/autocomplete-search', [CompareController::class, 'autocompleteSearch'])->name('compare.autocomplete');

		// Review Routes
		Route::get('reviews/index', [ReviewController::class, 'index'])->name('reviews.index');
		Route::get('reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
		Route::put('reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');


		// Expert Rating Routes
		Route::get('expert-ratings/index', [\App\Http\Controllers\Dashboard\ExpertRatingController::class, 'index'])->name('expert-ratings.index');
		Route::get('expert-ratings/{product}/edit', [\App\Http\Controllers\Dashboard\ExpertRatingController::class, 'edit'])->name('expert-ratings.edit');
		Route::put('expert-ratings/{product}', [\App\Http\Controllers\Dashboard\ExpertRatingController::class, 'update'])->name('expert-ratings.update');
		Route::delete('expert-ratings/{product}', [\App\Http\Controllers\Dashboard\ExpertRatingController::class, 'destroy'])->name('expert-ratings.destroy');


		// Filter Routes
		Route::get('filters/index', [FilterController::class, 'index'])->name('filters.index');
		Route::get('filters/create', [FilterController::class, 'create'])->name('filters.create');
		Route::post('filters', [FilterController::class, 'store'])->name('filters.store');
		Route::get('filters/{filter}', [FilterController::class, 'show'])->name('filters.show');
		Route::get('filters/{filter}/edit', [FilterController::class, 'edit'])->name('filters.edit');
		Route::put('filters/{filter}', [FilterController::class, 'update'])->name('filters.update');
		Route::delete('filters/{filter}', [FilterController::class, 'destroy'])->name('filters.destroy');


		// Redirection Routes
		Route::get('redirections/index', [RedirectionController::class, 'index'])->name('redirections.index');
		Route::get('redirections/create', [RedirectionController::class, 'create'])->name('redirections.create');
		Route::post('redirections', [RedirectionController::class, 'store'])->name('redirections.store');
		Route::get('redirections/{redirection}', [RedirectionController::class, 'show'])->name('redirections.show');
		Route::get('redirections/{redirection}/edit', [RedirectionController::class, 'edit'])->name('redirections.edit');
		Route::put('redirections/{redirection}', [RedirectionController::class, 'update'])->name('redirections.update');
		Route::delete('redirections/{redirection}', [RedirectionController::class, 'destroy'])->name('redirections.destroy');


		// Page Routes
		Route::get('pages/index', [PageController::class, 'index'])->name('pages.index');
		Route::get('pages/create', [PageController::class, 'create'])->name('pages.create');
		Route::post('pages', [PageController::class, 'store'])->name('pages.store');
		Route::get('pages/{page}', [PageController::class, 'show'])->name('pages.show');
		Route::get('pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
		Route::put('pages/{page}', [PageController::class, 'update'])->name('pages.update');
		Route::delete('pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');
		// Settings
		Route::get('settings', [SiteSettingController::class, 'index'])->name('settings.index');
		Route::put('settings', [SiteSettingController::class, 'update'])->name('settings.update');

		// AI Product Import
		Route::get('ai-import', [\App\Http\Controllers\Dashboard\AiImportController::class, 'index'])->name('ai-import.index');
		Route::post('ai-import/process', [\App\Http\Controllers\Dashboard\AiImportController::class, 'process'])->name('ai-import.process');
		Route::post('ai-import/save', [\App\Http\Controllers\Dashboard\AiImportController::class, 'save'])->name('ai-import.save');

		// Cache Management
		Route::get('cache', [\App\Http\Controllers\Dashboard\CacheController::class, 'index'])->name('cache.index');
		Route::post('cache/clear', [\App\Http\Controllers\Dashboard\CacheController::class, 'clear'])->name('cache.clear');
		Route::post('cache/clear-all', [\App\Http\Controllers\Dashboard\CacheController::class, 'clearAll'])->name('cache.clear-all');
		Route::post('cache/build-route', [\App\Http\Controllers\Dashboard\CacheController::class, 'buildRoute'])->name('cache.build.route');
		Route::post('cache/build-config', [\App\Http\Controllers\Dashboard\CacheController::class, 'buildConfig'])->name('cache.build.config');

		// Sitemap Management
		Route::get('sitemap', [\App\Http\Controllers\Dashboard\SitemapController::class, 'index'])->name('sitemap.index');
		Route::post('sitemap/generate', [\App\Http\Controllers\Dashboard\SitemapController::class, 'generate'])->name('sitemap.generate');
		Route::post('sitemap/generate-all', [\App\Http\Controllers\Dashboard\SitemapController::class, 'generateAll'])->name('sitemap.generate-all');
		Route::post('sitemap/create-index', [\App\Http\Controllers\Dashboard\SitemapController::class, 'createIndex'])->name('sitemap.create-index');
		Route::delete('sitemap/destroy', [\App\Http\Controllers\Dashboard\SitemapController::class, 'destroy'])->name('sitemap.destroy');

		Route::get('error_logs/index', [ErrorLogController::class, 'index'])->name('error_logs.index');
		Route::get('error_logs/{id}/check', [ErrorLogController::class, 'checkStatus'])->name('error_logs.check');
		Route::delete('error_logs/clear-all', [ErrorLogController::class, 'clearAll'])->name('error_logs.clearAll');
		Route::delete('error_logs/bulk-delete', [ErrorLogController::class, 'bulkDestroy'])->name('error_logs.bulkDestroy');
		Route::post('error_logs/bulk-check-status', [ErrorLogController::class, 'bulkCheckStatus'])->name('error_logs.bulkCheckStatus');
		Route::delete('error_logs/{id}', [ErrorLogController::class, 'destroy'])->name('error_logs.destroy');


	});
