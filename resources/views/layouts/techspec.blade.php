<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'TechSpec - Modern Device Specifications')</title>
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

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "primary-hover": "#0f4bc2",
                        "page-bg": "#f8fafc",
                        "surface-card": "#ffffff",
                        "surface-hover": "#f1f5f9",
                        "text-main": "#0f172a",
                        "text-muted": "#64748b",
                        "border-light": "#e2e8f0"
                    },
                    fontFamily: {
                        "display": ["Space Grotesk", "Noto Sans", "sans-serif"]
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @yield('style')
</head>

<body
    class="bg-page-bg text-text-main font-display min-h-screen flex flex-col antialiased selection:bg-primary selection:text-white">

    <header class="sticky top-0 z-50 w-full bg-white/90 backdrop-blur-md border-b border-border-light">
        <div class="px-4 md:px-6 lg:px-8 max-w-[1400px] mx-auto h-16 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 text-text-main shrink-0">
                <a href="{{ $homeRoute }}" class="flex items-center gap-3 text-text-main shrink-0 no-underline">
                    <div class="size-8 text-primary">
                        <span class="material-symbols-outlined text-4xl">devices</span>
                    </div>
                    <h2 class="text-text-main text-xl font-bold tracking-tight hidden sm:block">TechSpec</h2>
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
                <nav class="hidden md:flex items-center gap-6">
                    <a class="text-sm font-medium text-text-muted hover:text-primary transition-colors"
                        href="{{ $phonesRoute }}">Phones</a>
                    <a class="text-sm font-medium text-text-muted hover:text-primary transition-colors"
                        href="{{ $tabletsRoute }}">Tablets</a>
                    <a class="text-sm font-medium text-text-muted hover:text-primary transition-colors"
                        href="{{ $comparisonRoute }}">Compare</a>
                </nav>
                <div class="h-6 w-px bg-border-light hidden md:block"></div>
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
    <footer class="w-full bg-white border-t border-border-light py-12 mt-12">
        <div class="max-w-[1400px] mx-auto px-4 md:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
            <div class="col-span-2 lg:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-primary text-2xl">devices</span>
                    <h2 class="text-text-main text-xl font-bold">TechSpec</h2>
                </div>
                <p class="text-text-muted text-sm max-w-xs leading-relaxed">
                    The ultimate destination for mobile tech enthusiasts. Detailed specifications, in-depth reviews, and
                    comprehensive comparisons.
                </p>
            </div>
            <div class="flex flex-col gap-4">
                <h4 class="text-text-main font-bold text-sm uppercase tracking-wider">Devices</h4>
                <a class="text-text-muted hover:text-primary text-sm transition-colors" href="#">Top 10 Phones</a>
                <a class="text-text-muted hover:text-primary text-sm transition-colors" href="#">Best Battery Life</a>
                <a class="text-text-muted hover:text-primary text-sm transition-colors" href="#">Best Cameras</a>
                <a class="text-text-muted hover:text-primary text-sm transition-colors"
                    href="{{ $upcomingRoute }}">New Releases</a>
            </div>
            <div class="flex flex-col gap-4">
                <h4 class="text-text-main font-bold text-sm uppercase tracking-wider">Brands</h4>
                <a class="text-text-muted hover:text-primary text-sm transition-colors"
                    href="{{ route('brand.show', ['brand' => 'apple', 'categorySlug' => 'mobile-phones']) }}">Apple</a>
                <a class="text-text-muted hover:text-primary text-sm transition-colors"
                    href="{{ route('brand.show', ['brand' => 'samsung', 'categorySlug' => 'mobile-phones']) }}">Samsung</a>
                <a class="text-text-muted hover:text-primary text-sm transition-colors"
                    href="{{ route('brand.show', ['brand' => 'google', 'categorySlug' => 'mobile-phones']) }}">Google</a>
                <a class="text-text-muted hover:text-primary text-sm transition-colors"
                    href="{{ route('brand.show', ['brand' => 'xiaomi', 'categorySlug' => 'mobile-phones']) }}">Xiaomi</a>
            </div>
            <div class="flex flex-col gap-4">
                <h4 class="text-text-main font-bold text-sm uppercase tracking-wider">Company</h4>
                <a class="text-text-muted hover:text-primary text-sm transition-colors"
                    href="{{ route('about') }}">About Us</a>
                <a class="text-text-muted hover:text-primary text-sm transition-colors"
                    href="{{ route('contact') }}">Contact</a>
                <a class="text-text-muted hover:text-primary text-sm transition-colors"
                    href="{{ route('privacy.policy') }}">Privacy Policy</a>
                <a class="text-text-muted hover:text-primary text-sm transition-colors"
                    href="{{ route('terms.conditions') }}">Terms of Use</a>
            </div>
        </div>
        <div
            class="max-w-[1400px] mx-auto px-4 md:px-6 lg:px-8 mt-12 pt-8 border-t border-border-light flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-text-muted text-xs">© {{ date('Y') }} TechSpec Inc. All rights reserved.</p>
            <div class="flex gap-4">
                <a class="text-text-muted hover:text-primary" href="#"><i
                        class="material-symbols-outlined text-lg">public</i></a>
                <a class="text-text-muted hover:text-primary" href="#"><i
                        class="material-symbols-outlined text-lg">rss_feed</i></a>
            </div>
        </div>
    </footer>
    @yield('script')
</body>

</html>