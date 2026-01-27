<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\PTACalculatorController;
use App\Http\Controllers\MobileController;
use App\Http\Controllers\TabletController;
use App\Http\Controllers\WatchController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ChargerController;
use App\Http\Controllers\CountryChargerController;
use App\Http\Controllers\CableController;
use App\Http\Controllers\CountryCableController;



// Route to trigger sitemap generation
Route::get('/sitemap.xml', [SitemapController::class, 'serveSitemap'])->name('sitemap.index');
// Routes for serving additional sitemaps (brands, categories, filters, products)
Route::get('/sitemap-{type}.xml', [SitemapController::class, 'serveSitemap'])
    ->where('type', 'brands|categories|news|filters|products|products-\d+')
    ->name('sitemap.type');

Route::get('/extract-prices/{slug}', [PriceController::class, 'extractPrices']);
// URL redirection logic
$url = \Request::fullUrl();
$urlNew = DB::table('redirections')->where("from_url", $url)->first();
if ($urlNew) {
    return redirect($urlNew->to_url, 301)->send();
}

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

Route::post('/store-user-info', [ProductController::class, "storeUserInfo"])->name('store.user.info');
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

    // GET /user/wishlist
    Route::get('/wishlist', [UserController::class, 'wishlist'])->name('user.wishlist');

    // GET /user/wishlist/delete/{id}
    Route::get('/wishlist/delete/{id}', [UserController::class, 'wishlistDelete'])->name('user.wishlist.delete');
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
Route::prefix('{country_code}')->where(['country_code' => '^[a-z]{2}$'])->middleware(['default.country'])->group(function () {
    // Logout route
    Route::post('/logout', [LoginController::class, 'logout'])->name('country.logout');
    Route::get('news', [NewsController::class, "index"]);
    Route::get('news/{slug}', [NewsController::class, "show"]);
    // Common routes
    Route::get('/comparison', [HomeController::class, 'comparison'])->name('country.comparison');
    Route::get('/compare/{slug}', [CountryController::class, 'compare'])->name('country.compare');
    Route::get('/search', [CountryController::class, 'search'])->name('country.search');

    // Static pages
    Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('country.privacy.policy');
    Route::get('/terms-and-conditions', [HomeController::class, 'termsConditions'])->name('country.terms.conditions');
    Route::get('/contact', [HomeController::class, 'contact'])->name('country.contact');
    Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('country.about');

    Route::get('/sponsor', function () {
        return view("frontend.new.sponsor");
    });

    // Product filters
    Route::get('/power-banks/power-banks-with-{mah}-mah', [CountryController::class, 'FilterPowerBankAttributeProduct'])->name('country.powerbank.by.mah');
    Route::get('/phone-covers/{slug}', [CountryController::class, 'FilterPhoneCoverProducts'])->name('country.phone.covers.by.model');
    Route::get('/phone-covers/{brand}/{slug}', [CountryController::class, 'FilterPhoneCoverByBrandProducts'])->name('country.phone.covers.by.brand');

    // Country-specific main route
    Route::get('/', [HomeController::class, 'index'])->name('country.index');

    // Mobile phone routes
    Route::get('/4g-mobile-phones', [MobileController::class, 'mobilePhones4g'])->name('country.mobile.phones.4g');
    Route::get('/5g-mobile-phones', [MobileController::class, 'mobilePhones5g'])->name('country.mobile.phones.5g');
    Route::get('/mobile-phones-with-{ram}gb-ram-{rom}gb-storage', [CountryController::class, 'combinationRamRom']);
    Route::get('/mobile-phones-{ram}gb-ram', [CountryController::class, 'underRam'])->name('country.mobile.phones.ram')->where('ram', '^(2|3|4|6|8|12|16|20|24)$');
    Route::get('/mobile-phones-{rom}gb-storage', [CountryController::class, 'underRom'])->name('country.mobile.phones.rom');
    Route::get('/mobile-phones-screen-{size}-inch', [CountryController::class, 'mobilePhonesScreen'])->name('country.mobile.phones.screen');

    // Additional mobile phone types
    Route::get('/folding-mobile-phones', [MobileController::class, 'mobilePhonesFolding'])->name('country.mobile.phones.folding');
    Route::get('/up-coming-mobile-phones', [MobileController::class, 'upComingMobiles'])->name('country.up.coming.mobiles');
    Route::get('/flip-mobile-phones', [MobileController::class, 'mobilePhonesFlip'])->name('country.mobile.phones.flip');
    Route::get('/curved-display-mobile-phones', [MobileController::class, 'mobilePhonesCurved'])->name('country.mobile.phones.curved');
    Route::get('/{brand}-curved-display-mobile-phones', [CountryController::class, 'mobilePhonesCurvedByBrand'])->name('mobile.phones.curved.brand');

    // Processor-related routes
    Route::get('/snapdragon-888-mobile-phones', [MobileController::class, 'underProcessor'])->name('country.mobile.phones.processor');
    Route::get('/snapdragon-8-gen-1-mobile-phones', [MobileController::class, 'underProcessor'])->name('country.mobile.phones.processor');
    Route::get('/snapdragon-8-gen-2-mobile-phones', [MobileController::class, 'underProcessor'])->name('country.mobile.phones.processor');
    Route::get('/snapdragon-8-gen-3-mobile-phones', [MobileController::class, 'underProcessor'])->name('country.mobile.phones.processor');
    Route::get('/filter/snapdragon-8-gen-3-mobile-phones-under-{amount}', [MobileController::class, 'filterUnderProcessorAmount'])->name('mobile.phones.processor.amount');
    Route::get('/snapdragon-8-gen-4-mobile-phones', [MobileController::class, 'underProcessor'])->name('country.mobile.phones.processor');
    Route::get('/mediatek-mobile-phones', [MobileController::class, 'underProcessor'])->name('country.mobile.phones.processor');
    Route::get('/exynos-mobile-phones', [MobileController::class, 'underProcessor'])->name('country.mobile.phones.processor');
    Route::get('/kirin-mobile-phones', [MobileController::class, 'underProcessor'])->name('country.mobile.phones.processor');
    Route::get('/google-tensor-mobile-phones', [MobileController::class, 'underProcessor'])->name('country.mobile.phones.processor');

    // Camera-related routes
    Route::get('/mobile-phones-{number}-camera', [CountryController::class, 'mobilePhonesNumberCamera'])->name('country.mobile.phones.number.camera')->where('number', '^(dual|triple|quad)$');
    Route::get('/mobile-phones-{camera}mp-camera', [CountryController::class, 'mobilePhonesUnderCamera'])->where('camera', '^(12|16|24|48|64|108|200)$')->name('country.mobile.phones.under.camera');

    // Price-related routes
    Route::get('/mobile-phones-under-{price}', [CountryController::class, 'underPrice'])->name('country.mobile.under.amount');
    Route::get('/{brand}-mobile-phones-under-{price}', [CountryController::class, 'productUnderMobileAmount'])->name('country.mobile.under.amount');

    // Brand and category routes
    Route::get('/brand/{slug}/{category_slug}', [CountryController::class, 'brandShow'])->name('country.brand.show');
    Route::get('/brands/{category_slug}', [CountryController::class, 'showBrandsByCategory'])->name('country.brands.by.category');
    Route::get('/category/{slug}', [CountryController::class, 'categoryShow'])->name('country.category.show');

    // Tablet-related routes
    Route::get('/tablets-under-{price}', [CountryController::class, 'tabletsUnderPrice'])->name('country.tablet.under.amount');
    Route::get('/4g-tablets', [TabletController::class, 'tablets4g'])->name('country.tablet.4g');
    Route::get('/5g-tablets', [TabletController::class, 'tablets5g'])->name('country.tablet.5g');
    Route::get('/tablets-{ram}gb-ram', [CountryController::class, 'tabletsUnderRam'])->name('country.tablet.ram');
    Route::get('/tablets-{rom}gb-storage', [CountryController::class, 'tabletsUnderRom'])->name('country.tablet.rom');
    Route::get('/tablets-screen-{inch}-inch', [CountryController::class, 'tabletsUnderScreen'])->name('country.tablet.screen');
    Route::get('/tablets-{mp}mp-camera', [CountryController::class, 'tabletsUnderCamera'])->name('country.tablet.camera');

    // Smart watch route
    Route::get('/smart-watches-under-{amount}', [CountryController::class, 'underAmountWatches'])->name('country.watch.under');

    Route::get('/{watt}-watt-chargers', [CountryChargerController::class, 'capacity']);
    Route::get('/usb-type-a-chargers', [CountryChargerController::class, 'typeACharger']);
    Route::get('/usb-type-c-chargers', [CountryChargerController::class, 'typeCCharger']);
    Route::get('/{watt}-usb-type-c-chargers', [CountryChargerController::class, 'wattTypeCCharger']);
    Route::get('/{brand}-{watt}-chargers', [CountryChargerController::class, 'brandWattCharger']);
    Route::get('/usb-c-to-usb-c-cables', [CountryCableController::class, 'typeCToC']);
    Route::get('/usb-a-to-usb-c-cables', [CountryCableController::class, 'typeAToC']);
    Route::get('/{brand}-{watt}-cables', [CountryCableController::class, 'brandWatt']);

    // Product and old product routes
    Route::get('/product/{slug}', [CountryController::class, 'showProduct'])->name('country.product.show');
    Route::get('/{brand}/{slug}', [CountryController::class, 'showOld'])->name('country.product.show.old');

    // Sitemap route
    Route::get('/html-sitemap', [CountryController::class, 'htmlSitemap'])->name('country.html.sitemap');

    // Robots.txt route
    Route::get('robots.txt', [RobotsController::class, 'index']);
});

Route::middleware(['default.country'])->group(function () {
    Route::get('/sitemap/generate', [SitemapController::class, 'generate'])->name('sitemap.generate');
    Auth::routes();
    Route::get('news', [NewsController::class, "index"]);
    Route::get('news/{slug}', [NewsController::class, "show"]);
    // Common routes
    Route::get('/comparison', [HomeController::class, 'comparison'])->name('comparison');
    Route::get('/search', [HomeController::class, 'search'])->name('search');

    // Static pages
    Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy.policy');
    Route::get('/terms-and-conditions', [HomeController::class, 'termsConditions'])->name('terms.conditions');
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
    Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('about');
    Route::get('/sponsor', function () {
        return view('frontend.new.sponsor');
    });

    // Category and brand routes
    Route::get('/category/{slug}', [HomeController::class, 'categoryShow'])->name('category.show');
    Route::get('/brand/{slug}/{category_slug}', [HomeController::class, 'brandShow'])->name('brand.show');
    Route::get('/brands/{category_slug}', [HomeController::class, 'showBrandsByCategory'])->name('brands.by.category');
    Route::get('/compare/{slug}', [HomeController::class, 'compare'])->name('compare');

    // Packages routes
    Route::get('/packages', [PackageController::class, 'index'])->name('package.network.index');
    Route::get('/packages/{network}', [PackageController::class, 'showNetwork'])->name('package.network.index');

    // Packages routes for different networks
    Route::get('/packages/jazz/{type}', [PackageController::class, 'showNetworkPackages'])->name('package.network.type');
    Route::get('/packages/telenor/{type}', [PackageController::class, 'showNetworkPackages'])->name('package.network.type');
    Route::get('/packages/ufone/{type}', [PackageController::class, 'showNetworkPackages'])->name('package.network.type');
    Route::get('/packages/zong/{type}', [PackageController::class, 'showNetworkPackages'])->name('package.network.type');

    // General package routes
    Route::get('/packages/{network}/{slug}', [PackageController::class, 'show'])->name('package.show');

    // Network-specific package routes with validation for network types
    Route::get('/packages/{network}/{type}', [PackageController::class, 'showNetwork'])
        ->where('type', '^(jazz|telenor|ufone|zong)$')
        ->name('package.network.type');

    // Package route with network, type, validity, and package details
    Route::get('/packages/{network}-{type}-{validity}-{package}-packages', [PackageController::class, 'showNetworkValidityPackages'])->name('package.network.network.type.validity');

    // Product routes
    Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/{brand}/{slug}', [ProductController::class, 'showOld'])->name('product.show.old');

    // Sitemap routes
    Route::get('/html-sitemap', [SitemapController::class, 'htmlSitemap'])->name('html.sitemap');

    // Home route
    Route::get('/', [HomeController::class, 'index'])->name('index');

    // Mobile phone routes with RAM and storage
    Route::get('/mobile-phones-with-{ram}gb-ram-{rom}gb-storage', [MobileController::class, 'combinationRamRom']);

    // Product routes with dynamic brand and price
    Route::get('/{brand}-mobile-phones-under-{price}', [MobileController::class, 'productUnderMobileAmount'])->name('product.brand.mobile.phones.amount');

    // Product display route
    Route::get('/products-show', [ProductController::class, 'show'])->name('products.shows');

    // Installment-related routes
    Route::get('/installment/{price}/{bank}', [FinanceController::class, 'calculateInstallment'])->name('installment.plan.details');

    // Mobile installment calculator and related post routes
    Route::get('/mobile-installment-calculator', [FinanceController::class, 'index'])->name('installment.plan');
    Route::post('/mobile-installment-calculator-post', [FinanceController::class, 'postInstallments'])->name('installment.plan.post');

    // PTA-related routes
    Route::get('/pta-calculator', [PTACalculatorController::class, 'index'])->name('pta.calculator');
    Route::get('/get-products-by-brand-pta', [PTACalculatorController::class, 'getProductsByBrandPTA'])->name('get.products.by.brand.pta');
    Route::get('/get-pta-tax', [PTACalculatorController::class, 'getPTATax'])->name('get.pta');

    // Finance-related product routes
    Route::get('/get-products-by-brand', [FinanceController::class, 'getProductsByBrand'])->name('get.products.by.brand');


    // Package index
    Route::get('/packages', [PackageController::class, 'index'])->name('package.index');

    // Product price routes
    Route::get('/product-under-{price}', [HomeController::class, 'productUnder'])->name('product.under');

    // Tablet routes
    Route::get('/4g-tablets', [TabletController::class, 'tablets4g'])->name('tablet.4g');
    Route::get('/5g-tablets', [TabletController::class, 'tablets5g'])->name('tablet.5g');
    Route::get('/tablets-under-{price}', [TabletController::class, 'underPrice'])->name('tablet.under.amount');
    Route::get('/{brand}-tablets-under-{price}', [TabletController::class, 'underBrandPrice'])->name('tablet.brand.under.amount');
    Route::get('/tablets-{ram}gb-ram', [TabletController::class, 'underRam'])->name('tablet.ram');
    Route::get('/tablets-{rom}gb-storage', [TabletController::class, 'underRom'])->name('tablet.rom');
    Route::get('/tablets-screen-{inch}-inch', [TabletController::class, 'underScreen'])->name('tablet.screen');
    Route::get('/tablets-{mp}mp-camera', [TabletController::class, 'underCamera'])->name('tablet.camera');

    // Mobile routes
    Route::get('/up-coming-mobile-phones', [MobileController::class, 'upComingMobiles'])->name('up.coming.mobiles');
    Route::get('/4g-mobile-phones', [MobileController::class, 'mobilePhones4g'])->name('mobile.phones.4g');
    Route::get('/5g-mobile-phones', [MobileController::class, 'mobilePhones5g'])->name('mobile.phones.5g');
    Route::get('/mobile-phones-{ram}gb-ram', [MobileController::class, 'underRam'])->name('mobile.phones.ram');
    // Processor-related mobile routes
    Route::get('/snapdragon-888-mobile-phones', [MobileController::class, 'underProcessor'])->name('mobile.phones.processor');
    Route::get('/snapdragon-8-gen-1-mobile-phones', [MobileController::class, 'underProcessor'])->name('mobile.phones.processor');
    Route::get('/snapdragon-8-gen-2-mobile-phones', [MobileController::class, 'underProcessor'])->name('mobile.phones.processor');
    Route::get('/snapdragon-8-gen-3-mobile-phones', [MobileController::class, 'underProcessor'])->name('mobile.phones.processor');
    Route::get('/snapdragon-8-gen-4-mobile-phones', [MobileController::class, 'underProcessor'])->name('mobile.phones.processor');
    Route::get('/mediatek-mobile-phones', [MobileController::class, 'underProcessor'])->name('mobile.phones.processor');
    Route::get('/exynos-mobile-phones', [MobileController::class, 'underProcessor'])->name('mobile.phones.processor');
    Route::get('/kirin-mobile-phones', [MobileController::class, 'underProcessor'])->name('mobile.phones.processor');
    Route::get('/google-tensor-mobile-phones', [MobileController::class, 'underProcessor'])->name('mobile.phones.processor');

    // Storage and screen size routes
    Route::get('/mobile-phones-{rom}gb-storage', [MobileController::class, 'underRom'])->name('mobile.phones.rom');
    Route::get('/mobile-phones-screen-{size}-inch', [MobileController::class, 'mobilePhonesScreen'])->name('mobile.phones.screen');

    // Mobile phone types
    Route::get('/folding-mobile-phones', [MobileController::class, 'mobilePhonesFolding'])->name('mobile.phones.folding');
    Route::get('/flip-mobile-phones', [MobileController::class, 'mobilePhonesFlip'])->name('mobile.phones.flip');
    Route::get('/curved-display-mobile-phones', [MobileController::class, 'mobilePhonesCurved'])->name('mobile.phones.curved');
    Route::get('/{brand}-curved-display-mobile-phones', [MobileController::class, 'mobilePhonesCurvedByBrand'])->name('mobile.phones.curved.brand');

    // Camera-related mobile routes
    Route::get('/mobile-phones-dual-camera', [MobileController::class, 'mobilePhonesDualCamera'])->name('mobile.phones.dual.camera');
    Route::get('/mobile-phones-triple-camera', [MobileController::class, 'mobilePhonesTripleCamera'])->name('mobile.phones.triple.camera');
    Route::get('/mobile-phones-quad-camera', [MobileController::class, 'mobilePhonesQuadCamera'])->name('mobile.phones.quad.camera');
    Route::get('/mobile-phones-{camera}mp-camera', [MobileController::class, 'mobilePhonesUnderCamera'])->where('camera', '^(12|16|24|48|64|108|200)$')->name('mobile.phones.under.camera');

    // Price-related mobile routes
    Route::get('/mobile-phones-under-{price}', [MobileController::class, 'underPrice'])->name('mobile.under.amount');

    // Phone cover routes
    Route::get('/phone-covers/{slug}', [HomeController::class, 'FilterPhoneCoverProducts'])->name('phone.covers.by.model');
    Route::get('/phone-covers/{brand}/{slug}', [HomeController::class, 'FilterPhoneCoverByBrandProducts'])->name('phone.covers.by.brand');

    // Specific brand routes
    Route::get('/tecno-mobile-phones-more-20000', [MobileController::class, 'tecnoMobileMore20000'])->name('mobile.tecno.more.20000');

    // Compare mobile route
    Route::get('/compare/embed/{slug}', [MobileController::class, 'compareMobileEmbed'])->name('mobile.compare.embed');

    // Watch routes
    Route::get('/{brand}-smart-watches-under-{price}', [WatchController::class, 'underAmountByBrand'])->name('product.brand.smart.watches.amount');
    Route::get('/smart-watches-under-{amount}', [WatchController::class, 'underAmount'])->name('watch.under');
    Route::get('/{watt}-watt-chargers', [ChargerController::class, 'capacity'])->name('charger.capacity');
    Route::get('/usb-type-a-chargers', [ChargerController::class, 'typeACharger'])->name('charger.type.c');
    Route::get('/usb-type-c-chargers', [ChargerController::class, 'typeCCharger'])->name('charger.type.c');
    Route::get('/{watt}-usb-type-c-chargers', [ChargerController::class, 'wattTypeCCharger'])->name('charger.watt.type.c');
    Route::get('/{brand}-{watt}-chargers', [ChargerController::class, 'brandWattCharger'])->name('charger.brand.watt');
    Route::get('/usb-c-to-usb-c-cables', [CableController::class, 'typeCToC']);
    Route::get('/usb-a-to-usb-c-cables', [CableController::class, 'typeAToC']);
    Route::get('/{brand}-{watt}-cables', [CableController::class, 'brandWatt']);

    // Sitemap routes
    Route::get('/sitemaps', [SitemapController::class, 'index'])->name('sitemap.index');
    Route::get('/sitemaps-products', [SitemapController::class, 'products'])->name('sitemap.mobiles');
    Route::get('/sitemaps-brands', [SitemapController::class, 'brands'])->name('sitemap.brands');
    Route::get('/sitemaps-filters', [SitemapController::class, 'filters'])->name('sitemap.filters');
    Route::get('/sitemaps-compare', [SitemapController::class, 'compare'])->name('sitemap.compare');
    Route::get('/sitemaps-packages', [SitemapController::class, 'packages'])->name('sitemap.packages');

    // Embed routes
    Route::get('/mobile/embed/{slug}', [MobileController::class, 'showMobileEmbed'])->name('mobile.show.embed');
    Route::get('/mobile/embeds/{slug}', [MobileController::class, 'showMobileEmbedWithButton'])->name('mobile.show.embed.with.button');

    // Robots.txt route
    Route::get('robots.txt', [RobotsController::class, 'index']);
});

// Include Dashboard Routes
require base_path('routes/dashboard.php');

// App scraper routes
Route::get('/app-scraper', [AppController::class, 'index']);
Route::post('/app-scraper', [AppController::class, 'postData'])->name('app.scraper.post');
Route::post('/contact', [HomeController::class, 'contactPost'])->name('contact.post')->middleware('throttle:1,60');

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

// Review and wishlist post routes
Route::post('/review', [HomeController::class, 'reviewPost'])->name('review.post');
Route::post('/wishlist', [HomeController::class, 'wishlistPost'])->name('wishlist.post');

// Password reset route
Route::get('/password/reset', function () {
    return view('auth.login');
});

// Test route
Route::get('/test', [TestController::class, 'index']);

// Brand redirect route
Route::get('/brand/{slug}', function ($slug) {
    $url = route('brand.show', [$slug, 'mobile-phones']);
    return new Response('', 301, ['Location' => $url]);
});



// Authentication routes
Route::post('/auth/login', [LoginController::class, 'postLogin'])->name('login.post');
Route::post('/auth/register', [LoginController::class, 'register'])->name('auth.register');