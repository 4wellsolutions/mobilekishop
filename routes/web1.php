<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\RobotsController;

// Route to trigger sitemap generation
Route::get('/sitemap.xml', [SitemapController::class, 'serveSitemap'])->name('sitemap.index');
// Routes for serving additional sitemaps (brands, categories, filters, products)
Route::get('/sitemap-{type}.xml', [SitemapController::class, 'serveSitemap'])
     ->where('type', 'brands|categories|filters|products|products-\d+')
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
Route::get('dashboard/products/{brand}', function ($brand, Request $request) {
    $queryParams = $request->query();
    $product = key($queryParams);
    if ($product) {
        return redirect("$brand/$product");
    }
    return abort(404);
});
Route::post('/store-user-info', "ProductController@storeUserInfo")->name('store.user.info');
Route::get('/review/{any}', function () {
    return redirect('/')->setStatusCode(301);
})->where('any', '.*');

// Subscription routes
Route::get('/unsubscribe/{token?}', "SubscribeController@index")->name("unsubscribe");
Route::post('/unsubscribe', "SubscribeController@update")->name("unsubscribe.post");

// Misc routes
Route::get('/autocomplete-search', "ProductController@autocompleteSearch")->name("autocomplete.search");
// user routes
Route::group(['prefix' => "/user", "middleware" => "auth"], function(){
    Route::get('/index',[
        "uses" => "UserController@index",
        "as" => "user.index"
    ]);
    Route::post('/update/{id}',[
        "uses" => "UserController@userUpdate",
        "as" => "user.update"
    ]);
    Route::get('/review',[
        "uses" => "UserController@review",
        "as" => "user.review"
    ]);
    Route::get('/review/delete/{id}',[
        "uses" => "UserController@reviewDelete",
        "as" => "user.review.delete"
    ]);
    Route::post('/review/update',[
        "uses" => "UserController@reviewUpdate",
        "as" => "user.review.update"
    ]);
    Route::get('/wishlist',[
        "uses" => "UserController@wishlist",
        "as" => "user.wishlist"
    ]);
    Route::get('/wishlist/delete/{id}',[
        "uses" => "UserController@wishlistDelete",
        "as" => "user.wishlist.delete"
    ]);
});
// Social login
Route::get('/{social}/redirect', "Auth\LoginController@socialRedirect")->name("social.redirect");
Route::get('/{social}/callback', "Auth\LoginController@socialCallback")->name("social.callback");

// user routes
Route::get('/password/reset', function(){
    return view("auth.login");
});
if (App::environment('local')) {
    $baseUrl = 'localhost';
} else {
    $baseUrl = 'mobilekishop.net';
}
Route::domain($baseUrl)->group(function () {
    Route::middleware(['default.country'])->group(function () {
        Route::get('/sitemap/generate', [SitemapController::class, 'generate'])->name('sitemap.generate');
        Auth::routes();
        // common routes for main and subdomain
        Route::get('/comparison', "HomeController@comparison")->name("comparison");
        
        Route::get('/search', "HomeController@search")->name("search");
        // Static pages
        Route::get('/privacy-policy', "HomeController@privacyPolicy")->name("privacy.policy");
        Route::get('/terms-and-conditions', "HomeController@termsConditions")->name("terms.conditions");
        Route::get('/contact', "HomeController@contact")->name("contact");
        Route::get('/about-us', "HomeController@aboutUs")->name("about");
        Route::get('/sponsor', function() {
            return view("frontend.new.sponsor");
        });
        Route::get('/category/{slug}', "HomeController@categoryShow")->name("category.show");
        Route::get('/brand/{slug}/{category_slug}', "HomeController@brandShow")->name("brand.show");
        Route::get('/brands/{category_slug}', "HomeController@showBrandsByCategory")->name("brands.by.category");
        Route::get('/compare/{slug}', "HomeController@compare")->name("compare");
        // Packages routes
        Route::get('/packages', "PackageController@index")->name("package.network.index");
        Route::get('/packages/{network}', "PackageController@showNetwork")->name("package.network.index");
        
        Route::get('/packages/jazz/{type}', "PackageController@showNetworkPackages")->name("package.network.type");
        Route::get('/packages/telenor/{type}', "PackageController@showNetworkPackages")->name("package.network.type");
        Route::get('/packages/ufone/{type}', "PackageController@showNetworkPackages")->name("package.network.type");
        Route::get('/packages/zong/{type}', "PackageController@showNetworkPackages")->name("package.network.type");

        Route::get('/packages/{network}/{slug}', "PackageController@show")->name("package.show");
        Route::get('/packages/{network}/{type}', "PackageController@showNetwork")->where('type', '^(jazz|telenor|ufone|zong)$')->name("package.network.type");
        
        Route::get('/packages/{network}-{type}-{validity}-{package}-packages', "PackageController@showNetworkValidityPackages")->name("package.network.network.type.validity");
        Route::get('/product/{slug}', "ProductController@show")->name("product.show");
        Route::get('/{brand}/{slug}', "ProductController@showOld")->name("product.show.old");
        
        Route::get('/html-sitemap', "SitemapController@htmlSitemap")->name("html.sitemap");
        Route::get('/', "HomeController@index")->name("index");
        
        
        Route::get('/mobile-phones-with-{ram}gb-ram-{rom}gb-storage', "MobileController@combinationRamRom");
        Route::get('/{brand}-mobile-phones-under-{price}', "MobileController@productUnderMobileAmount")->name("product.brand.mobile.phones.amount");

        // Product routes
        Route::get('/products-show', "ProductController@show")->name("products.shows");

        // Installment routes
        Route::get('/installment/{price}/{bank}', "FinanceController@calculateInstallment")->name("installment.plan.details");

        // Tools
        Route::get('/mobile-installment-calculator', "FinanceController@index")->name("installment.plan");
        Route::post('/mobile-installment-calculator-post', "FinanceController@postInstallments")->name("installment.plan.post");
        Route::get('/pta-calculator', "PTACalculatorController@index")->name("pta.calculator");
        Route::get('/get-products-by-brand-pta', "PTACalculatorController@getProductsByBrandPTA")->name("get.products.by.brand.pta");
        Route::get('/get-products-by-brand', "FinanceController@getProductsByBrand")->name("get.products.by.brand");
        Route::get('/get-pta-tax', "PTACalculatorController@getPTATax")->name("get.pta");

        
        // Package index
        Route::get('/packages', "PackageController@index")->name("package.index");

        // Product price routes
        Route::get('/product-under-{price}', "HomeController@productUnder")->name("product.under");

        // Tablet routes
        Route::get('/4g-tablets', "TabletController@tablets4g")->name("tablet.4g");
        Route::get('/5g-tablets', "TabletController@tablets5g")->name("tablet.5g");
        Route::get('/tablets-under-{price}', "TabletController@underPrice")->name("tablet.under.amount");
        Route::get('/{brand}-tablets-under-{price}', "TabletController@underBrandPrice")->name("tablet.brand.under.amount");
        Route::get('/tablets-{ram}gb-ram', "TabletController@underRam")->name("tablet.ram");
        Route::get('/tablets-{rom}gb-storage', "TabletController@underRom")->name("tablet.rom");
        Route::get('/tablets-screen-{inch}-inch', "TabletController@underScreen")->name("tablet.screen");
        Route::get('/tablets-{mp}mp-camera', "TabletController@underCamera")->name("tablet.camera");

        // Mobile routes
        Route::get('/up-coming-mobile-phones', "MobileController@upComingMobiles")->name("up.coming.mobiles");
        Route::get('/4g-mobile-phones', "MobileController@mobilePhones4g")->name("mobile.phones.4g");
        Route::get('/5g-mobile-phones', "MobileController@mobilePhones5g")->name("mobile.phones.5g");
        Route::get('/mobile-phones-{ram}gb-ram', "MobileController@underRam")->name("mobile.phones.ram");
        Route::get('/snapdragon-888-mobile-phones', "MobileController@underProcessor")->name("mobile.phones.processor");
        Route::get('/snapdragon-8-gen-1-mobile-phones', "MobileController@underProcessor")->name("mobile.phones.processor");
        Route::get('/snapdragon-8-gen-2-mobile-phones', "MobileController@underProcessor")->name("mobile.phones.processor");
        Route::get('/snapdragon-8-gen-3-mobile-phones', "MobileController@underProcessor")->name("mobile.phones.processor");

        Route::get('/snapdragon-8-gen-4-mobile-phones', "MobileController@underProcessor")->name("mobile.phones.processor");
        Route::get('/mediatek-mobile-phones', "MobileController@underProcessor")->name("mobile.phones.processor");
        Route::get('/exynos-mobile-phones', "MobileController@underProcessor")->name("mobile.phones.processor");
        Route::get('/kirin-mobile-phones', "MobileController@underProcessor")->name("mobile.phones.processor");
        Route::get('/google-tensor-mobile-phones', "MobileController@underProcessor")->name("mobile.phones.processor");
        Route::get('/mobile-phones-{rom}gb-storage', "MobileController@underRom")->name("mobile.phones.rom");
        Route::get('/mobile-phones-screen-{size}-inch', "MobileController@mobilePhonesScreen")->name("mobile.phones.screen");
        Route::get('/folding-mobile-phones', "MobileController@mobilePhonesFolding")->name("mobile.phones.folding");
        Route::get('/flip-mobile-phones', "MobileController@mobilePhonesFlip")->name("mobile.phones.flip");
        Route::get('/curved-display-mobile-phones', "MobileController@mobilePhonesCurved")->name("mobile.phones.curved");
        Route::get('/{brand}-curved-display-mobile-phones', "MobileController@mobilePhonesCurvedByBrand")->name("mobile.phones.curved.brand");
        Route::get('/mobile-phones-dual-camera', "MobileController@mobilePhonesDualCamera")->name("mobile.phones.dual.camera");
        Route::get('/mobile-phones-triple-camera', "MobileController@mobilePhonesTripleCamera")->name("mobile.phones.triple.camera");
        Route::get('/mobile-phones-quad-camera', "MobileController@mobilePhonesQuadCamera")->name("mobile.phones.quad.camera");
        Route::get('/mobile-phones-{camera}mp-camera', "MobileController@mobilePhonesUnderCamera")->where('camera', '^(12|16|24|48|64|108|200)$')->name("mobile.phones.under.camera");
        Route::get('/mobile-phones-under-{price}', "MobileController@underPrice")->name("mobile.under.amount");
        Route::get('/phone-covers/{slug}', "HomeController@FilterPhoneCoverProducts")->name("phone.covers.by.model");
        Route::get('/phone-covers/{brand}/{slug}', "HomeController@FilterPhoneCoverByBrandProducts")->name("phone.covers.by.brand");
        // Specific brand routes
        Route::get('/tecno-mobile-phones-more-20000', "MobileController@tecnoMobileMore20000")->name("mobile.tecno.more.20000");
        
        Route::get('/compare/embed/{slug}', "MobileController@compareMobileEmbed")->name("mobile.compare.embed");

        // Watch routes
        Route::get('/{brand}-smart-watches-under-{price}', "WatchController@underAmountByBrand")->name("product.brand.smart.watches.amount");
        Route::get('/smart-watches-under-{amount}', "WatchController@underAmount")->name("watch.under");
        // Sitemap routes
        Route::get('/sitemaps', "SitemapController@index")->name("sitemap.index");
        Route::get('/sitemaps-products', "SitemapController@products")->name("sitemap.mobiles");
        Route::get('/sitemaps-brands', "SitemapController@brands")->name("sitemap.brands");
        Route::get('/sitemaps-filters', "SitemapController@filters")->name("sitemap.filters");
        Route::get('/sitemaps-compare', "SitemapController@compare")->name("sitemap.compare");
        Route::get('/sitemaps-packages', "SitemapController@packages")->name("sitemap.packages");
        // Embed routes
        Route::get('/mobile/embed/{slug}', "MobileController@showMobileEmbed")->name("mobile.show.embed");
        Route::get('/mobile/embeds/{slug}', "MobileController@showMobileEmbedWithButton")->name("mobile.show.embed.with.button");
        Route::get('robots.txt', [RobotsController::class, 'index']);
    });
    //admin routes
    Route::group(['prefix' => '/dashboard','middleware' => ['auth','admin']], function () {

        Route::get('/',[
            'uses' => "Dashboard\DashboardController@index",
            "as" => "dashboard.index"
        ]);
        
        // product routes
        Route::get('/product/index', 'Dashboard\ProductController@index')->name('dashboard.product.index');
        Route::get('/product/create', 'Dashboard\ProductController@create')->name('dashboard.product.create');
        Route::post('/product', 'Dashboard\ProductController@store')->name('dashboard.product.store');
        Route::get('/product/{product}', 'Dashboard\ProductController@show')->name('dashboard.product.show');
        Route::get('/product/{product}/edit', 'Dashboard\ProductController@edit')->name('dashboard.product.edit');
        Route::put('/product/{product}', 'Dashboard\ProductController@update')->name('dashboard.product.update');
        Route::get('/product/price/{id}', 'Dashboard\ProductController@priceCreate')->name('dashboard.product.price.create');

        Route::post('/product/price/{id}', 'Dashboard\ProductController@priceStore')->name('dashboard.product.price.store');
        Route::get('/dashboard/product/price/getPrices', 'Dashboard\ProductController@getPrices')->name('dashboard.product.price.getPrices');
        Route::post('/product/price/update/{id}', 'Dashboard\ProductController@priceUpdate')->name('dashboard.product.price.update');
        Route::delete('/product/{product}', 'Dashboard\ProductController@destroy')->name('dashboard.products.destroy');
        Route::get('/product/delete-image/{id}', 'Dashboard\ProductController@deleteImage')->name('dashboard.products.image.delete');
        Route::get('/product/brand/{brand_id}', 'Dashboard\ProductController@byBrand')->name('dashboard.brand.products');
        Route::delete('/product/{product}/color/{color}', 'Dashboard\ProductController@removeColor')->name('dashboard.product.color.remove');

        // product routes

        Route::get('variant/index', "Dashboard\VariantController@index")->name("dashboard.variant.index");
        Route::get('variant/create', "Dashboard\VariantController@create")->name("dashboard.variant.create");
        Route::post('variant/store', "Dashboard\VariantController@store")->name("dashboard.variant.store");
        Route::get('variant/edit/{variant}', "Dashboard\VariantController@edit")->name("dashboard.variant.edit");
        Route::post('variant/update/{variant}', "Dashboard\VariantController@update")->name("dashboard.variant.update");
        Route::get('variant/destroy/{variant}', "Dashboard\VariantController@destroy")->name("dashboard.variant.destroy");

        Route::get('color/index', "Dashboard\ColorController@index")->name("dashboard.color.index");
        Route::get('color/create', "Dashboard\ColorController@create")->name("dashboard.color.create");
        Route::post('color/store', "Dashboard\ColorController@store")->name("dashboard.color.store");
        Route::get('color/edit/{color}', "Dashboard\ColorController@edit")->name("dashboard.color.edit");
        Route::post('color/update/{color}', "Dashboard\ColorController@update")->name("dashboard.color.update");
        Route::get('color/destroy/{color}', "Dashboard\ColorController@destroy")->name("dashboard.color.destroy");
        // category routes
        Route::get('/category/index', 'Dashboard\CategoryController@index')->name('dashboard.category.index');
        Route::get('/category/create', 'Dashboard\CategoryController@create')->name('dashboard.category.create');
        Route::post('/category', 'Dashboard\CategoryController@store')->name('dashboard.category.store');
        Route::get('/category/{category}/edit', 'Dashboard\CategoryController@edit')->name('dashboard.category.edit');
        Route::post('/category/update/{category}', 'Dashboard\CategoryController@update')->name('dashboard.category.update');
        // category routes

        // attribute routes
        Route::get('/attribute/index', 'Dashboard\AttributeController@index')->name('dashboard.attribute.index');
        Route::get('/attribute/create', 'Dashboard\AttributeController@create')->name('dashboard.attribute.create');
        Route::post('/attribute', 'Dashboard\AttributeController@store')->name('dashboard.attribute.store');
        Route::get('/attribute/{attribute}/edit', 'Dashboard\AttributeController@edit')->name('dashboard.attribute.edit');
        Route::put('/attribute/{attribute}', 'Dashboard\AttributeController@update')->name('dashboard.attribute.update');
        Route::delete('/attribute/{attribute}', 'Dashboard\AttributeController@destroy')->name('dashboard.attribute.destroy');
        // attribute routes

        // PTA tax routes
        Route::get('/tax/index', "Dashboard\TaxController@index")->name("dashboard.tax.index");
        Route::get('/tax/create', "Dashboard\TaxController@create")->name("dashboard.tax.create");
        Route::post('/tax/store', "Dashboard\TaxController@store")->name("dashboard.tax.store");
        Route::get('/tax/edit/{id}', "Dashboard\TaxController@edit")->name("dashboard.tax.edit");
        Route::put('/tax/update/{id}', "Dashboard\TaxController@update")->name("dashboard.tax.update");
        Route::get('/tax/destroy/{id}', "Dashboard\TaxController@destroy")->name("dashboard.tax.destroy");
        // PTA tax routes

        Route::get('/brand/index',[
            'uses' => "Dashboard\BrandController@index",
            "as" => "dashboard.brand.index"
        ]);
        Route::get('/package/index',[
            'uses' => "Dashboard\PackageController@index",
            "as" => "dashboard.package.index"
        ]);
        Route::get('/package/create',[
            'uses' => "Dashboard\PackageController@create",
            "as" => "dashboard.package.create"
        ]);
        Route::post('/package/store',[
            'uses' => "Dashboard\PackageController@store",
            "as" => "dashboard.package.store"
        ]);
        Route::post('/package/update/{id}', [
            "uses" => 'Dashboard\PackageController@update',
            "as" => "dashboard.package.update"
        ]);
        Route::get('/package/edit/{id}',[
            'uses' => "Dashboard\PackageController@edit",
            "as" => "dashboard.package.edit"
        ]);
        Route::get('/brand/create',[
            'uses' => "Dashboard\BrandController@create",
            "as" => "dashboard.brand.create"
        ]);
        Route::post('/brand/store',[
            'uses' => "Dashboard\BrandController@store",
            "as" => "dashboard.brand.store"
        ]);
        Route::get('/brand/edit/{id}',[
            'uses' => "Dashboard\BrandController@edit",
            "as" => "dashboard.brand.edit"
        ]);
        Route::post('/brand/update/{id}',[
            'uses' => "Dashboard\BrandController@update",
            "as" => "dashboard.brand.update"
        ]);
        Route::get('/compare/create',[
            'uses' => "Dashboard\CompareController@create",
            "as" => "dashboard.compare.create"
        ]);
        Route::post('/compare/store',[
            'uses' => "Dashboard\CompareController@store",
            "as" => "dashboard.compare.store"
        ]);
        Route::get('/compare/edit/{id}',[
            'uses' => "Dashboard\CompareController@edit",
            "as" => "dashboard.compare.edit"
        ]);
        Route::post('/compare/update/{id}',[
            'uses' => "Dashboard\CompareController@update",
            "as" => "dashboard.compare.update"
        ]);
        Route::get('/compare/image',[
            'uses' => "Dashboard\CompareController@mergeImages",
            "as" => "dashboard.compare.merge"
        ]);
        Route::get('/compare/index',[
            'uses' => "Dashboard\CompareController@index",
            "as" => "dashboard.compare.index"
        ]);
        Route::get('/compare/autocomplete-search',[
            'uses' => "Dashboard\CompareController@autocompleteSearch",
            "as" => "dashboard.compare.autocomplete"
        ]);
        Route::get('/review/index',[
            'uses' => "Dashboard\ReviewController@index",
            "as" => "dashboard.review.index"
        ]);
        Route::get('/review/edit/{id}',[
            'uses' => "Dashboard\ReviewController@edit",
            "as" => "dashboard.review.edit"
        ]);
        Route::post('/review/update/{id}',[
            'uses' => "Dashboard\ReviewController@update",
            "as" => "dashboard.review.update"
        ]);
        
        Route::get('/filter/index',[
            'uses' => "Dashboard\FilterController@index",
            "as" => "dashboard.filter.index"
        ]);
        Route::get('/filter/create',[
            'uses' => "Dashboard\FilterController@create",
            "as" => "dashboard.filter.create"
        ]);
        Route::post('/filter/store',[
            'uses' => "Dashboard\FilterController@store",
            "as" => "dashboard.filter.store"
        ]);
        Route::get('/filter/edit/{filter}',[
            'uses' => "Dashboard\FilterController@edit",
            "as" => "dashboard.filter.edit"
        ]);
        Route::put('/filter/update/{filter}',[
            'uses' => "Dashboard\FilterController@update",
            "as" => "dashboard.filter.update"
        ]);
        Route::get("/redirection/index",[
            "uses"  => "Dashboard\RedirectionController@index",
            "as"    => "dashboard.redirection.index"
        ]);
        Route::get("/redirection/create",[
            "uses"  => "Dashboard\RedirectionController@create",
            "as"    => "dashboard.redirection.create"
        ]);
        Route::get("/redirection/edit/{id}",[
            "uses"  => "Dashboard\RedirectionController@edit",
            "as"    => "dashboard.redirection.edit"
        ]);
        Route::post("/redirection/store",[
            "uses"  => "Dashboard\RedirectionController@store",
            "as"    => "dashboard.redirection.store"
        ]);
        Route::post("/redirection/update/{redirection}",[
            "uses"  => "Dashboard\RedirectionController@update",
            "as"    => "dashboard.redirection.update"
        ]);
        Route::get("/redirection/delete/{redirection}",[
            "uses"  => "Dashboard\RedirectionController@destroy",
            "as"    => "dashboard.redirection.destroy"
        ]);
        Route::get('/page/index', 'Dashboard\PageController@index')->name('dashboard.page.index');
        Route::get('/page/edit/{id}', 'Dashboard\PageController@edit')->name('dashboard.page.edit');
        Route::get('/page/create', 'Dashboard\PageController@create')->name('dashboard.page.create');
        Route::post('/page/store', 'Dashboard\PageController@store')->name('dashboard.page.store');
        Route::post('/page/update/{id}', 'Dashboard\PageController@update')->name('dashboard.page.update');
    });
    //admin routes
});


// App scraper
Route::get('/app-scraper', "AppController@index");
Route::post('/app-scraper', "AppController@postData")->name("app.scraper.post");
Route::post('/contact', "HomeController@contactPost")->name("contact.post");
// Background jobs
Route::get('/jobs/marked-deleted', "JobController@markAsDeleted")->name("job.mark.deleted");
Route::get('/jobs/marked-expired', "JobController@markAsExpired")->name("job.mark.expired");
Route::get('/run-queue', function () {
    Artisan::call('queue:work', [
        '--queue' => 'default',
        '--tries' => 3,
        '--timeout' => 45
    ]);
});
Route::get('/clear-view', function () {
    Artisan::call('view:clear');
    return 'View cache cleared!';
});
Route::get('/config-cache', function () {
    Artisan::call('config:cache');
    return 'Config cache cleared!';
});


// review post and wishlish post
Route::post('/review', "HomeController@reviewPost")->name("review.post");
Route::post('/wishlist', "HomeController@wishlistPost")->name("wishlist.post");

Route::get('/password/reset', function () {
    return view("auth.login");
});
Route::get('/test', "TestController@index");
Route::get('/brand/{slug}', function ($slug) {
    $url = route('brand.show', [$slug, "mobile-phones"]);
    return new Response('', 301, ['Location' => $url]);
});


// Country-specific routes
Route::domain('{country_code}.mobilekishop.net')->group(function () {
    Route::middleware(['default.country'])->group(function () {
        Route::get('/sitemap/generate', [SitemapController::class, 'generate'])->name('sitemap.generate');
        Route::post('/logout',"Auth\LoginController@logout")->name("country.logout");
        // common routes for main and subdomain
        Route::get('/comparison', "HomeController@comparison")->name("country.comparison");
        Route::get('/compare/{slug}', "CountryController@compare")->name("country.compare");
        Route::get('/search', "CountryController@search")->name("country.search");
        // Static pages
        Route::get('/privacy-policy', "HomeController@privacyPolicy")->name("country.privacy.policy");
        Route::get('/terms-and-conditions', "HomeController@termsConditions")->name("country.terms.conditions");
        Route::get('/contact', "HomeController@contact")->name("country.contact");
        Route::get('/about-us', "HomeController@aboutUs")->name("country.about");
        
        Route::get('/sponsor', function() {
            return view("frontend.new.sponsor");
        });
        Route::get('/power-banks/power-banks-with-{mah}-mah', "CountryController@FilterPowerBankAttributeProduct")->name("country.powerbank.by.mah");
        Route::get('/phone-covers/{slug}', "CountryController@FilterPhoneCoverProducts")->name("country.phone.covers.by.model");
        Route::get('/phone-covers/{brand}/{slug}', "CountryController@FilterPhoneCoverByBrandProducts")->name("country.phone.covers.by.brand");

        // Country-specific routes
        Route::get('/', "HomeController@index")->name("country.index");

        Route::get('/4g-mobile-phones', "MobileController@mobilePhones4g")->name("country.mobile.phones.4g");
        Route::get('/5g-mobile-phones', "MobileController@mobilePhones5g")->name("country.mobile.phones.5g");
        Route::get('/mobile-phones-with-{ram}gb-ram-{rom}gb-storage', "CountryController@combinationRamRom");
        Route::get('/mobile-phones-{ram}gb-ram', "CountryController@underRam")->name("country.mobile.phones.ram")->where('ram', '^(2|3|4|6|8|12|16|20|24)$');

        Route::get('/mobile-phones-{rom}gb-storage', "CountryController@underRom")->name("country.mobile.phones.rom");
        
        Route::get('/mobile-phones-screen-{size}-inch', "CountryController@mobilePhonesScreen")->name("country.mobile.phones.screen");
        
        Route::get('/folding-mobile-phones', "MobileController@mobilePhonesFolding")->name("country.mobile.phones.folding");
        Route::get('/up-coming-mobile-phones', "MobileController@upComingMobiles")->name("country.up.coming.mobiles");
        Route::get('/flip-mobile-phones', "MobileController@mobilePhonesFlip")->name("country.mobile.phones.flip");
        Route::get('/curved-display-mobile-phones', "MobileController@mobilePhonesCurved")->name("country.mobile.phones.curved");
        Route::get('/{brand}-curved-display-mobile-phones', "CountryController@mobilePhonesCurvedByBrand")->name("mobile.phones.curved.brand");
        Route::get('/snapdragon-888-mobile-phones', "MobileController@underProcessor")->name("country.mobile.phones.processor");
        Route::get('/snapdragon-8-gen-1-mobile-phones', "MobileController@underProcessor")->name("country.mobile.phones.processor");
        Route::get('/snapdragon-8-gen-2-mobile-phones', "MobileController@underProcessor")->name("country.mobile.phones.processor");
        Route::get('/snapdragon-8-gen-3-mobile-phones', "MobileController@underProcessor")->name("country.mobile.phones.processor");
        Route::get('/filter/snapdragon-8-gen-3-mobile-phones-under-{amount}', "MobileController@filterUnderProcessorAmount")->name("mobile.phones.processor.amount");
        Route::get('/snapdragon-8-gen-4-mobile-phones', "MobileController@underProcessor")->name("country.mobile.phones.processor");
        Route::get('/mediatek-mobile-phones', "MobileController@underProcessor")->name("country.mobile.phones.processor");
        Route::get('/exynos-mobile-phones', "MobileController@underProcessor")->name("country.mobile.phones.processor");
        Route::get('/kirin-mobile-phones', "MobileController@underProcessor")->name("country.mobile.phones.processor");
        Route::get('/google-tensor-mobile-phones', "MobileController@underProcessor")->name("country.mobile.phones.processor");
        Route::get('/mobile-phones-{number}-camera', "CountryController@mobilePhonesNumberCamera")->name("country.mobile.phones.number.camera")->where('number', '^(dual|triple|quad)$');
        
        Route::get('/mobile-phones-{camera}mp-camera', "CountryController@mobilePhonesUnderCamera")->where('camera', '^(12|16|24|48|64|108|200)$')->name("country.mobile.phones.under.camera");
        
        Route::get('/mobile-phones-under-{price}', "CountryController@underPrice")->name("country.mobile.under.amount");
        Route::get('/{brand}-mobile-phones-under-{price}', "CountryController@productUnderMobileAmount")->name("country.mobile.under.amount");
        
        Route::get('/brand/{slug}/{category_slug}', "CountryController@brandShow")->name("country.brand.show");
        Route::get('/brands/{category_slug}', "CountryController@showBrandsByCategory")->name("country.brands.by.category");
        Route::get('/category/{slug}', "CountryController@categoryShow")->name("country.category.show");
        
        Route::get('/tablets-under-{price}', "CountryController@tabletsUnderPrice")->name("country.tablet.under.amount");
        Route::get('/4g-tablets', "TabletController@tablets4g")->name("country.tablet.4g");
        Route::get('/5g-tablets', "TabletController@tablets5g")->name("country.tablet.5g");
        Route::get('/tablets-{ram}gb-ram', "CountryController@tabletsUnderRam")->name("country.tablet.ram");
        Route::get('/tablets-{rom}gb-storage', "CountryController@tabletsUnderRom")->name("country.tablet.rom");
        Route::get('/tablets-screen-{inch}-inch', "CountryController@tabletsUnderScreen")->name("country.tablet.screen");
        Route::get('/tablets-{mp}mp-camera', "CountryController@tabletsUnderCamera")->name("country.tablet.camera");

        Route::get('/smart-watches-under-{amount}', "CountryController@underAmountWatches")->name("country.watch.under")->where('country_code', '^(in|us|uk|bd|ae)$');
        Route::get('/product/{slug}', "CountryController@showProduct")->name("country.product.show");
        Route::get('/{brand}/{slug}', "CountryController@showOld")->name("country.product.show.old");
        Route::get('/html-sitemap', "CountryController@htmlSitemap")->name("country.html.sitemap");
        Route::get('robots.txt', [RobotsController::class, 'index']);
    });
});

Route::post('/auth/login',"Auth\LoginController@postLogin")->name("login.post");
Route::post('/auth/register',"Auth\LoginController@register")->name('auth.register');