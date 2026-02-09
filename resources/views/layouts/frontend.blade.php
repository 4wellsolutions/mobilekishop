<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'MobileKiShop - Mobile Phone Prices & Specifications')</title>
    @php
        $isPk = ($country->country_code ?? 'pk') == 'pk';
        $countryPrefix = $isPk ? '' : 'country.';
        $routeParams = $isPk ? [] : ['country_code' => $country->country_code];

        $homeRoute = route($countryPrefix . 'index', $routeParams);
        // brands.by.category needs category slug
        $phonesRoute = route($countryPrefix . 'brands.by.category', array_merge($routeParams, ['category_slug' => 'mobile-phones']));
        $tabletsRoute = route($countryPrefix . 'brands.by.category', array_merge($routeParams, ['category_slug' => 'tablets']));
        $comparisonRoute = route($countryPrefix . 'comparison', $routeParams);
        $upcomingRoute = route($countryPrefix . 'filter.upcoming', $routeParams);
    @endphp
    <meta name="description" content="@yield('description', 'The ultimate destination for mobile tech enthusiasts.')">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Preconnect to external domains for faster loading --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin />

    {{-- Flag icons - deferred (not needed for first paint) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css"
        media="print" onload="this.media='all'" />
    <noscript>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css" />
    </noscript>

    {{-- Hreflang tags for multi-country SEO --}}
    @php
        $allCountries = \Illuminate\Support\Facades\Cache::remember('hreflang_countries', 3600, function () {
            return \App\Models\Country::select('country_code', 'locale')->get();
        });
        $currentPath = request()->path();
        // Strip country prefix from path if present (handles both "us/something" and bare "us")
        $basePath = preg_replace('/^[a-z]{2}(\/|$)/', '', $currentPath);
    @endphp
    <link rel="alternate" hreflang="x-default" href="{{ url($basePath) }}" />
    @foreach($allCountries as $c)
        <link rel="alternate" hreflang="{{ $c->locale ?? 'en-' . $c->country_code }}"
            href="{{ url($c->country_code == 'pk' ? $basePath : $c->country_code . '/' . $basePath) }}" />
    @endforeach

    {{-- Open Graph Meta Tags --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', 'MobileKiShop - Mobile Phone Prices & Specifications')">
    <meta property="og:description"
        content="@yield('description', 'The ultimate destination for mobile tech enthusiasts.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.png'))">
    <meta property="og:site_name" content="MobileKiShop">
    <meta property="og:locale" content="{{ $country->locale ?? 'en_PK' }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'MobileKiShop - Mobile Phone Prices & Specifications')">
    <meta name="twitter:description"
        content="@yield('description', 'The ultimate destination for mobile tech enthusiasts.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-default.png'))">

    {{-- Compiled Tailwind CSS (replaces CDN) --}}
    @vite('resources/css/app.css')
    {{-- Google Fonts - deferred loading with font-display:swap --}}
    <link rel="preload" as="style"
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" />
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet" media="print" onload="this.media='all'" />
    {{-- Material Symbols - deferred loading with font-display:swap --}}
    <link rel="preload" as="style"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL@20..48,100..700,0..1&display=swap" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL@20..48,100..700,0..1&display=swap"
        rel="stylesheet" media="print" onload="this.media='all'" />
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
            rel="stylesheet" />
        <link
            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
            rel="stylesheet" />
    </noscript>
    @yield('style')

    {{-- JSON-LD Organization Schema --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "Organization",
        "name": "MobileKiShop",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/logo.png') }}",
        "sameAs": [
            "https://www.facebook.com/mobilekishop",
            "https://twitter.com/mobilekishop"
        ]
    }
    </script>

    {{-- WebSite Schema for Sitelinks Search Box --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "WebSite",
        "name": "MobileKiShop",
        "url": "{{ url('/') }}",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ url('/search') }}?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    {{-- Page-specific structured data (Product, BreadcrumbList, etc.) --}}
    @yield('structured_data')

    {{-- Custom head code (Analytics, AdSense, etc.) --}}
    @php try {
            echo \App\Models\SiteSetting::get('head_code');
        } catch (\Throwable $e) {
    } @endphp
</head>

<body
    class="bg-page-bg text-text-main font-display min-h-screen flex flex-col antialiased selection:bg-primary selection:text-white">
    @php try {
            echo \App\Models\SiteSetting::get('body_start_code');
        } catch (\Throwable $e) {
    } @endphp

    <header class="sticky top-0 z-50 w-full bg-white/90 backdrop-blur-md border-b border-border-light">
        <div class="px-4 md:px-6 lg:px-8 max-w-[1400px] mx-auto h-16 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 text-text-main shrink-0">
                <a href="{{ $homeRoute }}" class="flex items-center gap-3 text-text-main shrink-0 no-underline">
                    <div class="size-8 text-primary">
                        <span class="material-symbols-outlined text-4xl">devices</span>
                    </div>
                    <h2 class="text-text-main text-xl font-bold tracking-tight hidden sm:block">MobileKiShop</h2>
                </a>
            </div>
            <div class="flex-1 max-w-xl mx-4">
                <form action="{{ route('search') }}" method="GET">
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-text-muted group-focus-within:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">search</span>
                        </div>
                        <input name="q"
                            class="block w-full p-2.5 pl-10 text-sm text-text-main bg-slate-100 border border-transparent rounded-lg focus:bg-white focus:ring-primary focus:border-primary placeholder-text-muted transition-all shadow-sm"
                            placeholder="Search devices, brands, or specs..." type="text" />
                    </div>
                </form>
            </div>
            <div class="flex items-center gap-4 shrink-0">
                <nav class="hidden md:flex items-center gap-1">
                    <!-- Categories Dropdown -->
                    <div class="relative group">
                        <button
                            class="flex items-center gap-1 text-sm font-medium text-text-muted hover:text-primary px-3 py-2 rounded-lg hover:bg-slate-50 transition-all">
                            Categories
                            <span
                                class="material-symbols-outlined text-[16px] transition-transform group-hover:rotate-180">expand_more</span>
                    </button>
                    <div
                    class="absolute top-full left-0 pt-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <div
                        class="bg-white rounded-xl border border-border-light shadow-lg shadow-black/8 py-2 min-w-[200px]">
                        @php
                            $navCategories = \Illuminate\Support\Facades\Cache::remember('nav_categories', 3600, function () {
                                return \App\Models\Category::orderBy('category_name')->get();
                            });
                            $catIcons = [
                                'mobile-phones' => 'smartphone',
                                'tablets' => 'tablet',
                                'smart-watch' => 'watch',
                                'earphone' => 'headphones',
                                'phone-covers' => 'phone_iphone',
                                'power-banks' => 'battery_charging_full',
                                'chargers' => 'electrical_services',
                                'cables' => 'cable',
                            ];
                        @endphp
                            @foreach($navCategories as $navCat)
                                <a href="{{ route($countryPrefix . 'category.show', array_merge($routeParams, ['category' => $navCat->slug])) }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-text-muted hover:text-primary hover:bg-slate-50 transition-colors">
                                    <span
                                        class="material-symbols-outlined text-[18px]">{{ $catIcons[$navCat->slug] ?? 'category' }}</span>
                                    {{ $navCat->category_name }}
                                </a>
                            @endforeach
                                <div class="border-t border-slate-100 my-1"></div>
                                <a href="{{ $upcomingRoute }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-text-muted hover:text-primary hover:bg-slate-50 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">upcoming</span>
                                    Upcoming Phones
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Compare -->
                    <a class="text-sm font-medium text-text-muted hover:text-primary px-3 py-2 rounded-lg hover:bg-slate-50 transition-all"
                        href="{{ $comparisonRoute }}">Compare</a>
                    <!-- Blog -->
                    <a class="text-sm font-medium text-text-muted hover:text-primary px-3 py-2 rounded-lg hover:bg-slate-50 transition-all"
                    href="{{ url('/blogs') }}">Blog</a>
            </nav>
                <div class="h-6 w-px bg-border-light hidden md:block"></div>

                {{-- Country Dropdown --}}
                @php
                    $menuCountries = \App\Models\Country::where('is_active', 1)->orderBy('country_name')->get();
                    $currentCountryCode = $country->country_code ?? 'pk';
                @endphp
                <div class="relative group" id="countryDropdown">
                    <button
                        class="flex items-center gap-1.5 text-sm font-medium text-text-muted hover:text-primary px-2 py-1.5 rounded-lg hover:bg-slate-50 transition-all"
                        onclick="document.getElementById('countryDropdown').classList.toggle('open')">
                        <span class="fi fi-{{ $currentCountryCode }} rounded-sm" style="font-size: 18px;"></span>
                        <span class="uppercase text-xs font-bold">{{ $currentCountryCode }}</span>
                        <span class="material-symbols-outlined text-[14px] transition-transform">expand_more</span>
                </button>
            <div
                class="absolute top-full right-0 pt-1 opacity-0 invisible group-[.open]:opacity-100 group-[.open]:visible transition-all duration-200 z-50">
            <div
                class="bg-white rounded-xl border border-border-light shadow-lg shadow-black/8 py-2 w-56 max-h-80 overflow-y-auto">
                        @foreach($menuCountries as $mc)
                            @php
                                $isActive = $mc->country_code === $currentCountryCode;
                                $countryUrl = $mc->country_code === 'pk'
                                    ? url($basePath)
                                    : url($mc->country_code . '/' . $basePath);
                            @endphp
                            <a href="{{ $countryUrl }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ $isActive ? 'text-primary bg-primary/5 font-semibold' : 'text-text-muted hover:text-primary hover:bg-slate-50' }}">
                                    <span class="fi fi-{{ $mc->country_code }} rounded-sm" style="font-size: 16px;"></span>
                                    <span class="truncate">{{ $mc->country_name }}</span>
                                    @if($isActive)
                                        <span class="material-symbols-outlined text-[16px] ml-auto text-primary">check</span>
                                    @endif
                            </a>
                        @endforeach
                    </div>
                </div>
                </div>
            @auth
                <a href="{{ route('user.index') }}"
                    class="flex items-center justify-center size-9 rounded-lg hover:bg-slate-100 transition-colors text-text-muted hover:text-text-main">
                    <span class="material-symbols-outlined text-[24px]">account_circle</span>
                    </a>
            @else
                    <a href="{{ route('login') }}"
                        class="flex items-center justify-center size-9 rounded-lg hover:bg-slate-100 transition-colors text-text-muted hover:text-text-main">
                        <span class="material-symbols-outlined text-[24px]">login</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>
    <main class="flex-1 w-full max-w-[1400px] mx-auto px-4 md:px-6 lg:px-8 py-8 space-y-12">
        @yield('content')
    </main>
    <footer class="w-full bg-slate-900 text-slate-300 pt-14 pb-8 mt-12">
        <div class="max-w-[1400px] mx-auto px-4 md:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-10 pb-12 border-b border-slate-700/50">
                <!-- Brand + Social -->
                <div class="col-span-2 lg:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-primary text-2xl">devices</span>
                        <h2 class="text-white text-xl font-bold">MobileKiShop</h2>
                    </div>
                    <p class="text-slate-400 text-sm max-w-xs leading-relaxed mb-6">
                        Your go-to destination for mobile phone specifications, prices, comparisons, and expert reviews
                        across all major brands.
                    </p>
                    <!-- Social Links -->
                    <div class="flex items-center gap-3">
                        <a href="https://facebook.com/mobilekishop" target="_blank" rel="noopener"
                            class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-primary flex items-center justify-center text-slate-400 hover:text-white transition-all"
                            aria-label="Facebook">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="https://twitter.com/mobilekishop" target="_blank" rel="noopener"
                            class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-primary flex items-center justify-center text-slate-400 hover:text-white transition-all"
                            aria-label="Twitter">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                        <a href="https://youtube.com/@mobilekishop" target="_blank" rel="noopener"
                            class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-primary flex items-center justify-center text-slate-400 hover:text-white transition-all"
                            aria-label="YouTube">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                        </a>
                        <a href="https://instagram.com/mobilekishop" target="_blank" rel="noopener"
                            class="w-9 h-9 rounded-lg bg-slate-800 hover:bg-primary flex items-center justify-center text-slate-400 hover:text-white transition-all"
                            aria-label="Instagram">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Categories -->
                <div class="flex flex-col gap-3">
                    <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-1">Categories</h4>
                    <a class="text-slate-400 hover:text-white text-sm transition-colors"
                        href="{{ $phonesRoute }}">Mobile Phones</a>
                    <a class="text-slate-400 hover:text-white text-sm transition-colors"
                        href="{{ $tabletsRoute }}">Tablets</a>
                    <a class="text-slate-400 hover:text-white text-sm transition-colors"
                        href="{{ $upcomingRoute }}">Upcoming Phones</a>
                    <a class="text-slate-400 hover:text-white text-sm transition-colors"
                        href="{{ $comparisonRoute }}">Comparisons</a>
                    <a class="text-slate-400 hover:text-white text-sm transition-colors"
                        href="{{ url('/blogs') }}">Blog</a>
                </div>

                <!-- Top Brands -->
                <div class="flex flex-col gap-3">
                    <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-1">Top Brands</h4>
                    <a class="text-slate-400 hover:text-white text-sm transition-colors"
                        href="{{ route('brand.show', ['brand' => 'apple', 'categorySlug' => 'mobile-phones']) }}">Apple</a>
                    <a class="text-slate-400 hover:text-white text-sm transition-colors"
                        href="{{ route('brand.show', ['brand' => 'samsung', 'categorySlug' => 'mobile-phones']) }}">Samsung</a>
                    <a class="text-slate-400 hover:text-white text-sm transition-colors"
                        href="{{ route('brand.show', ['brand' => 'google', 'categorySlug' => 'mobile-phones']) }}">Google</a>
                    <a class="text-slate-400 hover:text-white text-sm transition-colors"
                        href="{{ route('brand.show', ['brand' => 'xiaomi', 'categorySlug' => 'mobile-phones']) }}">Xiaomi</a>
                    <a class="text-slate-400 hover:text-white text-sm transition-colors"
                        href="{{ route('brand.show', ['brand' => 'oneplus', 'categorySlug' => 'mobile-phones']) }}">OnePlus</a>
                    <a class="text-slate-400 hover:text-white text-sm transition-colors"
                        href="{{ route('brand.show', ['brand' => 'huawei', 'categorySlug' => 'mobile-phones']) }}">Huawei</a>
                </div>

                <!-- Company -->
                <div class="flex flex-col gap-3">
                    <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-1">Company</h4>
                    <a class="text-slate-400 hover:text-white text-sm transition-colors"
                        href="{{ route('about') }}">About Us</a>
                    <a class="text-slate-400 hover:text-white text-sm transition-colors"
                        href="{{ route('contact') }}">Contact</a>
                    <a class="text-slate-400 hover:text-white text-sm transition-colors"
                        href="{{ route('privacy.policy') }}">Privacy Policy</a>
                    <a class="text-slate-400 hover:text-white text-sm transition-colors"
                        href="{{ route('terms.conditions') }}">Terms of Use</a>
                </div>
            </div>

            <!-- Copyright -->
            <div class="mt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 text-xs">© {{ date('Y') }} MobileKiShop. All rights reserved.</p>
                <div class="flex gap-6">
                    <a class="text-slate-500 hover:text-slate-300 text-xs transition-colors"
                        href="{{ route('privacy.policy') }}">Privacy</a>
                    <a class="text-slate-500 hover:text-slate-300 text-xs transition-colors"
                        href="{{ route('terms.conditions') }}">Terms</a>
                </div>
            </div>
        </div>
    </footer>
    @yield('script')
    <script>
        // Close country dropdown on outside click
        document.addEventListener('click', function (e) {
            const dd = document.getElementById('countryDropdown');
            if (dd && !dd.contains(e.target)) {
                dd.classList.remove('open');
            }
        });
    </script>
    @php try {
            echo \App\Models\SiteSetting::get('body_end_code');
        } catch (\Throwable $e) {
    } @endphp
</body>

</html>