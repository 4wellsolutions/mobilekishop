@extends('layouts.frontend')

@section('title', $metas->title)
@section('description', $metas->description)
@section("keywords", "Mobiles prices, mobile specification, mobile phone features")
@section("canonical", $metas->canonical)

@section("content")
    <!-- Breadcrumbs & Page Title -->
    <div
        class="hidden lg:flex mb-8 flex-col gap-4 border-b border-slate-200 pb-6 dark:border-slate-800 md:flex-row md:items-end md:justify-between">
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                <a class="hover:text-primary hover:underline"
                    href="{{ route(($country->country_code == 'pk' ? '' : 'country.') . 'index', ($country->country_code == 'pk' ? [] : ['country_code' => $country->country_code])) }}">Home</a>
                <span class="material-symbols-outlined text-[12px]">chevron_right</span>
                <span class="font-medium text-slate-900 dark:text-white">
                    {{ isset($category) ? Str::title($category->category_name) : $metas->name }}
                </span>
            </div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white md:text-4xl">{{ $metas->h1 }}</h1>
            <p class="text-slate-500 dark:text-slate-400 max-w-2xl">
                {!! $metas->body ?? 'Browse the latest devices from top brands.' !!}
            </p>
        </div>
        <!-- Quick Category Tags (Static for now, could be dynamic) -->
        <div class="flex gap-2 flex-wrap justify-start md:justify-end">
            <a class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-sm font-medium text-slate-700 shadow-sm ring-1 ring-slate-200 hover:bg-slate-50 hover:text-primary dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700 transition-colors"
                href="#">
                <span class="material-symbols-outlined text-[16px]">verified</span> Top Rated
            </a>
            <a class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-sm font-medium text-slate-700 shadow-sm ring-1 ring-slate-200 hover:bg-slate-50 hover:text-primary dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700 transition-colors"
                href="{{ route(($country->country_code == 'pk' ? '' : 'country.') . 'filter.upcoming', ($country->country_code == 'pk' ? [] : ['country_code' => $country->country_code])) }}">
                <span class="material-symbols-outlined text-[16px]">bolt</span> New Arrivals
            </a>
            <a class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-sm font-medium text-slate-700 shadow-sm ring-1 ring-slate-200 hover:bg-slate-50 hover:text-primary dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700"
                href="#">
                <span class="material-symbols-outlined text-[16px]">savings</span> Best Deals
            </a>
        </div>
    </div>

    <!-- Mobile Sticky Bar -->
    <div
        class="mobile-sticky-bar lg:hidden w-full bg-white/95 backdrop-blur-sm border-b border-slate-200 shadow-sm dark:bg-[#101622]/95 dark:border-slate-800 -mx-4 px-4 py-2 mb-4 flex items-center justify-between gap-4 sticky top-[64px] z-40">
        <button
            class="flex items-center gap-2 rounded-lg bg-slate-100 px-3 py-2 text-sm font-bold text-slate-700 transition active:scale-95 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700 flex-1 justify-center relative"
            onclick="document.getElementById('mobileFilters').classList.toggle('hidden')">
            <span class="material-symbols-outlined text-[20px]">tune</span>
            Quick Links
        </button>
        <div class="relative flex-1">
            <select
                class="w-full appearance-none rounded-lg bg-slate-100 py-2 pl-3 pr-8 text-sm font-bold text-slate-700 ring-0 focus:ring-0 dark:bg-slate-800 dark:text-slate-200 text-center"
                onchange="window.location.href=this.value">
                <option value="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}">Sort: Popular</option>
                <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">Sort: Newest</option>
                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}">Price: Low-High</option>
                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}">Price: High-Low</option>
            </select>
            <span
                class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-[18px] text-slate-500">expand_more</span>
        </div>
        <div class="flex items-center rounded-lg bg-slate-100 p-1 dark:bg-slate-800 shrink-0">
            <button class="rounded p-1.5 text-slate-900 shadow-sm bg-white dark:bg-slate-700 dark:text-white">
                <span class="material-symbols-outlined text-[20px]">grid_view</span>
            </button>
            <button class="rounded p-1.5 text-slate-400 hover:text-primary dark:text-slate-500">
                <span class="material-symbols-outlined text-[20px]">view_list</span>
            </button>
        </div>
    </div>

    <!-- Mobile Filters Sidebar (Hidden by default) -->
    <div id="mobileFilters" class="hidden lg:hidden fixed inset-0 z-50 bg-white dark:bg-slate-900 overflow-y-auto p-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold dark:text-white">Quick Links</h3>
            <button onclick="document.getElementById('mobileFilters').classList.add('hidden')"
                class="p-2 rounded-full bg-slate-100 dark:bg-slate-800">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <!-- Sidebar Component -->
        <x-filters-sidebar :category="$category ?? null" :country="$country" />
    </div>


    <div class="flex flex-col gap-8 lg:flex-row">
        <!-- LEFT SIDEBAR: FILTERS -->
        <aside class="hidden lg:block w-full shrink-0 lg:w-72">
            <div class="sticky top-24 max-h-[calc(100vh-120px)] overflow-y-auto custom-scrollbar pr-2 pb-10">


                <!-- Sidebar Component -->
                <x-filters-sidebar :category="$category ?? null" :country="$country" />


            </div>
        </aside>

        <!-- RIGHT MAIN: GRID -->
        <div class="flex-1 min-w-0">
            <!-- Sorting Bar -->
            <div
                class="hidden lg:flex mb-6 flex-wrap items-center justify-between gap-4 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-800">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Showing</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $products->count() }}</span>
                    <span class="text-sm font-medium text-slate-500 dark:text-slate-400">of</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">
                        {{ ($products instanceof \Illuminate\Pagination\LengthAwarePaginator) ? $products->total() : $products->count() }}
                    </span>
                    <span class="text-sm font-medium text-slate-500 dark:text-slate-400">results</span>
                </div>
                <!-- Simple View Toggles (Visual) -->
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="hidden text-sm font-medium text-slate-500 sm:inline dark:text-slate-400">Sort
                            by:</span>
                        <div class="relative">
                            <select
                                class="appearance-none rounded-lg border-none bg-slate-50 py-1.5 pl-3 pr-8 text-sm font-bold text-slate-900 shadow-sm ring-1 ring-slate-200 focus:ring-2 focus:ring-primary dark:bg-slate-800 dark:text-white dark:ring-slate-700"
                                onchange="window.location.href=this.value">
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}">Popularity</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">Newest Arrivals
                                </option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}">Price: Low to
                                    High</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}">Price: High to
                                    Low</option>
                            </select>
                            <span
                                class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-[18px] text-slate-500">expand_more</span>
                        </div>
                    </div>
                    <div class="h-6 w-px bg-slate-200 dark:bg-slate-700"></div>
                    <div
                        class="flex items-center rounded-lg bg-slate-50 p-1 shadow-sm ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
                        <button id="btnGrid" onclick="switchView('grid')"
                            class="rounded p-1 text-slate-900 shadow-sm bg-white dark:bg-slate-700 dark:text-white">
                            <span class="material-symbols-outlined text-[20px]">grid_view</span>
                        </button>
                        <button id="btnList" onclick="switchView('list')"
                            class="rounded p-1 text-slate-400 hover:text-primary dark:text-slate-500">
                            <span class="material-symbols-outlined text-[20px]">view_list</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-2 gap-3 sm:gap-6 lg:grid-cols-2 xl:grid-cols-3" id="productList">
                @if(!$products->isEmpty())
                    @foreach($products as $product)
                        <x-product-card :product="$product" :country="$country" :lazy="$loop->index >= 4" />
                    @endforeach
                @else
                    <div class="col-span-full text-center py-20">
                        <span class="material-symbols-outlined text-6xl text-slate-300 opacity-20 mb-4">search_off</span>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">No Products Found</h3>
                        <p class="text-slate-500 dark:text-slate-400 mt-2">Try adjusting your filters or check back later.</p>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex items-center justify-center gap-2">
                @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $products->links() }}
                @endif
            </div>

            <div id="dynamicContentEnd"></div>
            <div id="loadingSpinner" class="text-center py-8" style="display: none;">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto"></div>
            </div>
        </div>
    </div>

    <!-- Floating Comparison Bar -->
    <div class="fixed bottom-6 left-1/2 z-50 flex -translate-x-1/2 items-center gap-4 rounded-full bg-slate-900 p-2 pl-6 pr-2 shadow-2xl ring-1 ring-slate-800 dark:bg-white w-[90%] max-w-sm sm:w-auto hidden"
        id="comparisonBar">
        <div class="flex items-center gap-3">
            <span
                class="flex size-6 shrink-0 items-center justify-center rounded-full bg-primary text-xs font-bold text-white"
                id="compareCount">0</span>
            <span class="text-xs sm:text-sm font-bold text-white dark:text-slate-900 truncate">Items to compare</span>
        </div>
        <div class="h-6 w-px bg-slate-700 dark:bg-slate-200"></div>
        <button
            onclick="window.location.href='{{ route(($country->country_code == 'pk' ? '' : 'country.') . 'comparison', ($country->country_code == 'pk' ? [] : ['country_code' => $country->country_code])) }}'"
            class="flex items-center gap-2 rounded-full bg-primary px-4 sm:px-5 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-white shadow-md transition hover:bg-blue-600 whitespace-nowrap">
            Compare <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
        </button>
        <button
            class="flex size-9 shrink-0 items-center justify-center rounded-full bg-slate-800 text-slate-400 hover:bg-slate-700 hover:text-white dark:bg-slate-100 dark:text-slate-500"
            onclick="document.getElementById('comparisonBar').classList.add('hidden')">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
    </div>
@endsection

@section("style")
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }

        .mobile-sticky-bar {
            position: sticky;
            top: 64px;
            z-index: 40;
        }

        /* List View Styles */
        #productList.list-view {
            grid-template-columns: 1fr !important;
            gap: 0.75rem;
        }

        #productList.list-view>div {
            display: grid !important;
            grid-template-columns: 140px 1fr !important;
            grid-template-rows: auto auto auto !important;
            padding: 1rem 1.25rem !important;
            gap: 0 1.25rem;
            align-items: start;
        }

        /* Image area — spans all rows on the left */
        #productList.list-view>div>a:first-of-type {
            grid-column: 1;
            grid-row: 1 / -1;
            height: 130px !important;
            width: 140px;
            margin-bottom: 0 !important;
            border-radius: 0.75rem;
            align-self: center;
        }

        #productList.list-view>div>a:first-of-type img {
            height: 110px !important;
        }

        /* Badges & wishlist */
        #productList.list-view>div>.absolute {
            position: absolute;
        }

        /* Title + rating */
        #productList.list-view>div>div:nth-child(3) {
            grid-column: 2;
            grid-row: 1;
            margin-bottom: 0.25rem !important;
        }

        /* Key specs — horizontal row */
        #productList.list-view>div>div:nth-child(4) {
            grid-column: 2;
            grid-row: 2;
            display: flex !important;
            gap: 1.25rem;
            border-top: none !important;
            border-bottom: none !important;
            padding: 0.5rem 0 !important;
            margin-bottom: 0.25rem !important;
        }

        /* Force show hidden specs in list view */
        #productList.list-view>div>div:nth-child(4)>div {
            display: flex !important;
        }

        /* Footer — price + compare on same row */
        #productList.list-view>div>div:nth-child(5) {
            grid-column: 2;
            grid-row: 3;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: space-between;
            margin-top: 0 !important;
        }
    </style>
@endsection

@section('script')
    <script>
        function switchView(mode) {
            const grid = document.getElementById('productList');
            const btnGrid = document.getElementById('btnGrid');
            const btnList = document.getElementById('btnList');
            const activeClass = 'rounded p-1 text-slate-900 shadow-sm bg-white dark:bg-slate-700 dark:text-white';
            const inactiveClass = 'rounded p-1 text-slate-400 hover:text-primary dark:text-slate-500';

            if (mode === 'list') {
                grid.classList.add('list-view');
                btnList.className = activeClass;
                btnGrid.className = inactiveClass;
            } else {
                grid.classList.remove('list-view');
                btnGrid.className = activeClass;
                btnList.className = inactiveClass;
            }
            localStorage.setItem('viewMode', mode);
        }

        // Restore saved preference
        document.addEventListener('DOMContentLoaded', function () {
            const saved = localStorage.getItem('viewMode');
            if (saved === 'list') switchView('list');
        });
    </script>
@endsection