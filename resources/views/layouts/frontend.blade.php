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
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    @php $isBlogPath = str_starts_with(ltrim($basePath, '/'), 'blogs'); @endphp
    @foreach($allCountries as $c)
        <link rel="alternate" hreflang="{{ $c->locale ?? 'en-' . $c->country_code }}"
            href="{{ url(($c->country_code == 'pk' || $isBlogPath) ? $basePath : $c->country_code . '/' . $basePath) }}" />
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

    {{-- Custom head code (Analytics, AdSense, etc.) – lazy-loaded on user interaction --}}
    @php
        $__headCode = '';
        try { $__headCode = \App\Models\SiteSetting::get('head_code') ?? ''; } catch (\Throwable $e) {}
    @endphp
    @if($__headCode)
        <template id="deferred-head-code">{!! $__headCode !!}</template>
    @endif
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
                                // Auth pages and blog pages are global — no country prefix
                                $isAuthPage = in_array(ltrim($basePath, '/'), ['login', 'register', 'password/reset', '']);
                                $isBlogPage = str_starts_with(ltrim($basePath, '/'), 'blogs');
                                // Auth pages → redirect to country homepage; Blog pages → keep same URL
                                $switchPath = ($isAuthPage && $basePath !== '') ? '' : $basePath;
                                $countryUrl = ($mc->country_code === 'pk' || $isBlogPage)
                                    ? url($switchPath)
                                    : url($mc->country_code . '/' . $switchPath);
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
                <div class="relative group" id="userDropdown">
                    <button onclick="document.getElementById('userDropdown').classList.toggle('open')"
                        class="flex items-center justify-center size-9 rounded-lg hover:bg-slate-100 transition-colors text-text-muted hover:text-text-main">
                        <span class="material-symbols-outlined text-[24px]">account_circle</span>
                    </button>
                    <div class="absolute top-full right-0 pt-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-[.open]:opacity-100 group-[.open]:visible transition-all duration-200 z-50">
                        <div class="bg-white rounded-xl border border-border-light shadow-lg shadow-black/8 py-2 min-w-[180px]">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-sm font-bold text-text-main truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-text-muted truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('user.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm text-text-muted hover:text-primary hover:bg-slate-50 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">person</span>
                                My Account
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-text-muted hover:text-red-600 hover:bg-red-50 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">logout</span>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                    <button type="button" onclick="document.getElementById('authModal').classList.replace('hidden','flex')"
                        class="flex items-center justify-center size-9 rounded-lg hover:bg-slate-100 transition-colors text-text-muted hover:text-text-main">
                        <span class="material-symbols-outlined text-[24px]">login</span>
                    </button>
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
    {{-- ========== Login / Register Modal ========== --}}
    @guest
    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm" id="authModal">
        <div class="bg-white rounded-2xl shadow-2xl ring-1 ring-slate-200 w-full max-w-md mx-4 max-h-[90vh] overflow-y-auto animate-modal-in">
            {{-- Close --}}
            <div class="flex items-center justify-end p-3 pb-0">
                <button type="button" id="authModalClose"
                    class="p-1.5 rounded-full hover:bg-slate-100 transition">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            {{-- Tabs --}}
            <div class="flex border-b border-slate-200 mx-6" id="authTabs">
                <button type="button" data-tab="login"
                    class="flex-1 pb-3 text-sm font-semibold text-primary border-b-2 border-primary transition">Login</button>
                <button type="button" data-tab="register"
                    class="flex-1 pb-3 text-sm font-semibold text-slate-400 border-b-2 border-transparent hover:text-slate-600 transition">Register</button>
            </div>

            {{-- Error area --}}
            <div class="px-6 pt-4 hidden" id="authError">
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3" id="authErrorText"></div>
            </div>

            {{-- Login form --}}
            <form id="modalLoginForm" class="px-6 pt-5 pb-6 space-y-4" data-url="{{ url('auth/login') }}">
                @csrf
                <div>
                    <label for="ml-email" class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="login_email" id="ml-email" required autocomplete="email"
                        class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" />
                </div>
                <div>
                    <label for="ml-pass" class="block text-sm font-medium text-slate-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="login_password" id="ml-pass" required autocomplete="current-password"
                        class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" />
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="size-4 rounded border-slate-300 text-primary focus:ring-primary">
                    <span class="text-sm text-slate-500">Remember me</span>
                </label>
                <button type="submit"
                    class="w-full py-2.5 bg-gradient-to-r from-primary to-primary/90 text-white text-sm font-bold rounded-xl hover:shadow-lg hover:shadow-primary/25 transition-all">Log in</button>
                <div class="flex items-center gap-3">
                    <span class="flex-1 h-px bg-slate-200"></span>
                    <span class="text-xs text-slate-400">or</span>
                    <span class="flex-1 h-px bg-slate-200"></span>
                </div>
                <a href="{{ URL::to('/google/redirect') }}"
                    class="flex items-center justify-center gap-2 w-full py-2.5 border border-slate-300 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                    <svg class="size-5" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                    Sign in with Google
                </a>
                <a href="{{ url('password/reset') }}" class="block text-center text-xs text-slate-400 hover:text-primary transition">Forgot your password?</a>
            </form>

            {{-- Register form --}}
            <form id="modalRegisterForm" class="px-6 pt-5 pb-6 space-y-4 hidden" data-url="{{ url('auth/register') }}">
                @csrf
                <div>
                    <label for="mr-name" class="block text-sm font-medium text-slate-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="mr-name" required autocomplete="name"
                        class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" />
                </div>
                <div>
                    <label for="mr-phone" class="block text-sm font-medium text-slate-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                    <input type="text" name="phone_number" id="mr-phone" required autocomplete="tel"
                        class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" />
                </div>
                <div>
                    <label for="mr-email" class="block text-sm font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="mr-email" required autocomplete="email"
                        class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" />
                </div>
                <div>
                    <label for="mr-pass" class="block text-sm font-medium text-slate-700 mb-1">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="mr-pass" required minlength="8" autocomplete="new-password"
                        class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition" />
                </div>
                <button type="submit"
                    class="w-full py-2.5 bg-gradient-to-r from-primary to-primary/90 text-white text-sm font-bold rounded-xl hover:shadow-lg hover:shadow-primary/25 transition-all">Create Account</button>
            </form>
        </div>
    </div>
    <style>
        @keyframes modal-in { from { opacity:0; transform:scale(.95) translateY(10px); } to { opacity:1; transform:scale(1) translateY(0); } }
        .animate-modal-in { animation: modal-in .2s ease-out; }
    </style>
    @endguest

    @yield('script')
    <script>
        // Close dropdowns on outside click
        document.addEventListener('click', function (e) {
            const dd = document.getElementById('countryDropdown');
            if (dd && !dd.contains(e.target)) {
                dd.classList.remove('open');
            }
            const ud = document.getElementById('userDropdown');
            if (ud && !ud.contains(e.target)) {
                ud.classList.remove('open');
            }
        });

        // ========== Auth Modal Logic ==========
        (function() {
            const modal = document.getElementById('authModal');
            if (!modal) return; // Guest-only

            const close     = document.getElementById('authModalClose');
            const tabs      = document.querySelectorAll('#authTabs button');
            const loginForm = document.getElementById('modalLoginForm');
            const regForm   = document.getElementById('modalRegisterForm');
            const errBox    = document.getElementById('authError');
            const errText   = document.getElementById('authErrorText');

            // Close modal
            close.addEventListener('click', () => modal.classList.replace('flex','hidden'));
            modal.addEventListener('click', e => { if (e.target === modal) modal.classList.replace('flex','hidden'); });
            document.addEventListener('keydown', e => { if (e.key === 'Escape') modal.classList.replace('flex','hidden'); });

            // Tab switching
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const t = tab.dataset.tab;
                    tabs.forEach(b => { b.classList.toggle('text-primary', b.dataset.tab === t); b.classList.toggle('border-primary', b.dataset.tab === t); b.classList.toggle('text-slate-400', b.dataset.tab !== t); b.classList.toggle('border-transparent', b.dataset.tab !== t); });
                    loginForm.classList.toggle('hidden', t !== 'login');
                    regForm.classList.toggle('hidden', t !== 'register');
                    errBox.classList.add('hidden');
                });
            });

            function showError(msg) {
                errText.innerHTML = msg;
                errBox.classList.remove('hidden');
            }

            // Login
            loginForm.addEventListener('submit', async e => {
                e.preventDefault();
                errBox.classList.add('hidden');
                const btn = loginForm.querySelector('button[type=submit]');
                btn.disabled = true; btn.textContent = 'Logging in…';
                try {
                    const res = await fetch(loginForm.dataset.url, {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json' 
                        },
                        body: JSON.stringify({ login_email: loginForm.login_email.value, login_password: loginForm.login_password.value, remember: loginForm.remember?.checked ? 1 : 0 })
                    });
                    
                    if (res.status === 419) {
                        window.location.reload();
                        return;
                    }

                    const data = await res.json();
                    if (data.auth) { window.location.reload(); }
                    else { showError(data.message || 'Invalid email or password.'); btn.disabled = false; btn.textContent = 'Log in'; }
                } catch { showError('Something went wrong. Please try again.'); btn.disabled = false; btn.textContent = 'Log in'; }
            });

            // Register
            regForm.addEventListener('submit', async e => {
                e.preventDefault();
                errBox.classList.add('hidden');
                const btn = regForm.querySelector('button[type=submit]');
                btn.disabled = true; btn.textContent = 'Creating account…';
                try {
                    const fd = new FormData(regForm);
                    const res = await fetch(regForm.dataset.url, {
                        method: 'POST',
                        headers: { 
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest', 
                            'Accept': 'application/json' 
                        },
                        body: fd
                    });

                    if (res.status === 419) {
                        window.location.reload();
                        return;
                    }

                    const data = await res.json();
                    if (data.success) { window.location.reload(); }
                    else {
                        let msgs = '';
                        if (data.errors) { for (const k in data.errors) { msgs += data.errors[k].join('<br>') + '<br>'; } }
                        showError(msgs || data.message || 'Registration failed.');
                        btn.disabled = false; btn.textContent = 'Create Account';
                    }
                } catch { showError('Something went wrong. Please try again.'); btn.disabled = false; btn.textContent = 'Create Account'; }
            });
        })();
    </script>
    @php
        $__bodyCode = '';
        try { $__bodyCode = \App\Models\SiteSetting::get('body_end_code') ?? ''; } catch (\Throwable $e) {}
    @endphp
    @if($__bodyCode)
        <template id="deferred-body-code">{!! $__bodyCode !!}</template>
    @endif
    <script>
        // Lazy-load third-party scripts (AdSense, etc.) on first user interaction
        (function() {
            var loaded = false;
            function loadDeferred() {
                if (loaded) return;
                loaded = true;
                ['deferred-head-code', 'deferred-body-code'].forEach(function(id) {
                    var tpl = document.getElementById(id);
                    if (!tpl) return;
                    var frag = document.createRange().createContextualFragment(tpl.innerHTML);
                    document.head.appendChild(frag);
                    tpl.remove();
                });
            }
            ['scroll','click','touchstart','keydown'].forEach(function(evt) {
                window.addEventListener(evt, loadDeferred, { once: true, passive: true });
            });
            // Fallback: load after 5 seconds even without interaction
            setTimeout(loadDeferred, 5000);
        })();
    </script>
</body>

</html>