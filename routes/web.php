<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\PTACalculatorController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\JobController;




// Route to trigger sitemap generation
Route::get('/sitemap.xml', [SitemapController::class, 'serveSitemap'])->name('sitemap.index');

// Routes for serving additional sitemaps (brands, categories, filters, products)
Route::get('/sitemap-{type}.xml', [SitemapController::class, 'serveSitemap'])
    ->where('type', 'brands|categories|filters|products|products-\d+')
    ->name('sitemap.type');

Route::get('/extract-prices/{slug}', [PriceController::class, 'extractPrices']);
// URL redirections are now handled by the HandleRedirections middleware with caching

// Redirect product images with .jpg extension
Route::get('products/{product}', function ($product) {
    if (!Str::endsWith($product, '.jpg')) {
        return redirect('products/' . $product . '.jpg');
    }
});
Route::get('product/product', function () {
    $url = request()->getRequestUri(); // e.g., /product/product?product=samsung-galaxy-a12

    // Use str_replace to remove "product/product?" part from the URL
    $productName = str_replace('/product/product?', '', $url);

    if (isset($productName)) {
        return redirect()->to('product/' . $productName)->setStatusCode(301);
        // Redirect to the correct URL
    }
});


Route::get('/review/{any}', function () {
    return redirect('/')->setStatusCode(301);
})->where('any', '.*');

// Subscription Routes
Route::prefix('unsubscribe')->name('unsubscribe.')->group(function () {
    // GET /unsubscribe/{token?}
    Route::get('/{token?}', [SubscribeController::class, 'index'])->name('index');

    // POST /unsubscribe
    Route::post('/', [SubscribeController::class, 'update'])->name('post');
});

// Misc Routes
Route::get('/autocomplete-search', [ProductController::class, 'autocompleteSearch'])
    ->name('autocomplete.search');

// Grouping user-related routes with common prefix and middleware
Route::prefix('user')->middleware('auth')->group(function () {
    // GET /user/index
    Route::get('/index', [UserController::class, 'index'])->name('user.index');

    // POST /user/update/{id}
    Route::post('/update/{id}', [UserController::class, 'userUpdate'])->name('user.update');

    // GET /user/review
    Route::get('/review', [UserController::class, 'review'])->name('user.review');

    // GET /user/review/delete/{id}
    Route::get('/review/delete/{id}', [UserController::class, 'reviewDelete'])->name('user.review.delete');

    // POST /user/review/update
    Route::post('/review/update', [UserController::class, 'reviewUpdate'])->name('user.review.update');


});



// Social login routes
Route::get('/{social}/redirect', [LoginController::class, 'socialRedirect'])->name('social.redirect');
Route::get('/{social}/callback', [LoginController::class, 'socialCallback'])->name('social.callback');

// user routes
Route::get('/password/reset', function () {
    return view("auth.login");
});
// Main domain routes (Pakistan default)
// Country-specific routes using directory-based routing (e.g., /us/, /uk/)
require base_path('routes/web_v2.php');

Route::middleware(['default.country'])->group(function () {
    Route::get('/sitemap/generate', [SitemapController::class, 'generate'])->name('sitemap.generate');
    Auth::routes();

    // Packages routes
    Route::get('/packages', [PackageController::class, 'index'])->name('package.index');
    Route::get('/packages/{network}', [PackageController::class, 'showNetwork'])->name('package.network.index');
    Route::get('/packages/jazz/{type}', [PackageController::class, 'showNetworkPackages'])->name('package.network.type_jazz');
    Route::get('/packages/telenor/{type}', [PackageController::class, 'showNetworkPackages'])->name('package.network.type_telenor');
    Route::get('/packages/ufone/{type}', [PackageController::class, 'showNetworkPackages'])->name('package.network.type_ufone');
    Route::get('/packages/zong/{type}', [PackageController::class, 'showNetworkPackages'])->name('package.network.type_zong');
    Route::get('/packages/{network}/{slug}', [PackageController::class, 'show'])->name('package.show');
    Route::get('/packages/{network}/{type}', [PackageController::class, 'showNetwork'])
        ->where('type', '^(jazz|telenor|ufone|zong)$')
        ->name('package.network.type');
    Route::get('/packages/{network}-{type}-{validity}-{package}-packages', [PackageController::class, 'showNetworkValidityPackages'])->name('package.network.network.type.validity');

    // PTA & Finance
    Route::get('/pta-calculator', [PTACalculatorController::class, 'index'])->name('pta.calculator');
    Route::get('/get-products-by-brand-pta', [PTACalculatorController::class, 'getProductsByBrandPTA'])->name('get.products.by.brand.pta');
    Route::get('/get-pta-tax', [PTACalculatorController::class, 'getPTATax'])->name('get.pta');
    Route::get('/mobile-installment-calculator', [FinanceController::class, 'index'])->name('installment.plan');
    Route::post('/mobile-installment-calculator-post', [FinanceController::class, 'postInstallments'])->name('installment.plan.post');
    Route::get('/installment/{price}/{bank}', [FinanceController::class, 'calculateInstallment'])->name('installment.plan.details');
    Route::get('/get-products-by-brand', [FinanceController::class, 'getProductsByBrand'])->name('get.products.by.brand');

    // Sitemaps (Legacy/XML)
    Route::get('/sitemaps', [SitemapController::class, 'index'])->name('sitemap.index');
    Route::get('/sitemaps-products', [SitemapController::class, 'products'])->name('sitemap.mobiles');
    Route::get('/sitemaps-brands', [SitemapController::class, 'brands'])->name('sitemap.brands');
    Route::get('/sitemaps-filters', [SitemapController::class, 'filters'])->name('sitemap.filters');
    Route::get('/sitemaps-compare', [SitemapController::class, 'compare'])->name('sitemap.compare');
    Route::get('/sitemaps-packages', [SitemapController::class, 'packages'])->name('sitemap.packages');

    // Robots
    Route::get('robots.txt', [RobotsController::class, 'index']);
});

// Include Dashboard Routes
require base_path('routes/dashboard.php');

// App scraper routes
Route::get('/app-scraper', [AppController::class, 'index']);
Route::post('/app-scraper', [AppController::class, 'postData'])->name('app.scraper.post');
Route::post('/contact', [HomeController::class, 'contactPost'])->name('contact.post')->middleware(['default.country', 'throttle:1,60']);

// Background job routes
Route::get('/jobs/marked-deleted', [JobController::class, 'markAsDeleted'])->name('job.mark.deleted');
Route::get('/jobs/marked-expired', [JobController::class, 'markAsExpired'])->name('job.mark.expired');

// Artisan commands for background tasks and cache clearing
Route::get('/run-queue', function () {
    Artisan::call('queue:work', [
        '--queue' => 'default',
        '--tries' => 3,
        '--timeout' => 45
    ]);
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');

    return 'All caches cleared successfully!';
});

// Review post route
Route::post('/review', [HomeController::class, 'reviewPost'])->name('review.post');

// Password reset route
Route::get('/password/reset', function () {
    return view('auth.login');
});



// Brand redirect route
Route::get('/brand/{slug}', function ($slug) {
    $url = route('brand.show', [$slug, 'mobile-phones']);
    return new Response('', 301, ['Location' => $url]);
});



// Authentication routes
Route::post('/auth/login', [LoginController::class, 'postLogin'])->name('login.post');
Route::post('/auth/register', [LoginController::class, 'register'])->name('auth.register');