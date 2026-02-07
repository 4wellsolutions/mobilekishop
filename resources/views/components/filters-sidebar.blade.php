@php
    // Helper Variables (Local to Component)
    $currentRoute = Route::currentRouteName();
    $currentBrand = request()->route('brand');
    $currentCategory = request()->route('category') ?? request()->route('category_slug');

    // Filter Arrays
    $processors = [
        ['name' => 'Snapdragon 888', 'slug' => 'snapdragon-888'],
        ['name' => 'Snapdragon 8 Gen 1', 'slug' => 'snapdragon-8-gen-1'],
        ['name' => 'Snapdragon 8 Gen 2', 'slug' => 'snapdragon-8-gen-2'],
        ['name' => 'Snapdragon 8 Gen 3', 'slug' => 'snapdragon-8-gen-3'],
        ['name' => 'Snapdragon 8 Gen 4', 'slug' => 'snapdragon-8-gen-4'],
        ['name' => 'MediaTek', 'slug' => 'mediatek'],
        ['name' => 'Exynos', 'slug' => 'exynos'],
        ['name' => 'Kirin', 'slug' => 'kirin'],
        ['name' => 'Google Tensor', 'slug' => 'google-tensor'],
    ];

    $phoneTypes = [
        ['name' => 'Folding Mobile Phones', 'slug' => 'folding'],
        ['name' => 'Flip Mobile Phones', 'slug' => 'flip'],
        ['name' => 'Curved Display Phones', 'slug' => 'curved-display'],
    ];

    $combinations = [
        ['ram' => 4, 'rom' => 64],
        ['ram' => 4, 'rom' => 128],
        ['ram' => 8, 'rom' => 128],
        ['ram' => 8, 'rom' => 256],
        ['ram' => 12, 'rom' => 256],
        ['ram' => 12, 'rom' => 512],
    ];

    $ramLimits = [2, 3, 4, 6, 8, 12, 16, 24];
    $romLimits = [16, 32, 64, 128, 256, 512, 1024, 2048];
    $screenSizes = [4, 5, 6, 7, 8];
    $cameras = ['dual', 'triple', 'quad'];
    $cameraMp = [12, 16, 24, 48, 64, 108, 200];

    // Country Prefix Helper
    $isPk = ($country->country_code ?? 'pk') == 'pk';
    $countryPrefix = $isPk ? '' : 'country.';
    $routeParams = $isPk ? [] : ['country_code' => $country->country_code];
    
    // Determine if we should show advanced filters (only for mobile-phones)
    $showAdvanced = isset($category) && $category->slug === 'mobile-phones';
@endphp

<div class="space-y-6">
     <!-- Categories -->
     <div class="rounded-xl border border-transparent pb-2 pt-2">
         <h4 class="font-bold text-sm text-slate-900 dark:text-white mb-3">Categories</h4>
         <ul class="space-y-2">
            @foreach([
                    ['name' => 'Mobile Phones', 'slug' => 'mobile-phones'],
                    ['name' => 'Smart Watches', 'slug' => 'smart-watch'],
                    ['name' => 'Tablets', 'slug' => 'tablets'],
                    ['name' => 'Earphones', 'slug' => 'earphone'],
                    ['name' => 'Phone Covers', 'slug' => 'phone-covers'],
                    ['name' => 'Power Banks', 'slug' => 'power-banks'],
                    ['name' => 'Chargers', 'slug' => 'chargers'],
                    ['name' => 'Cables', 'slug' => 'cables']
                ] as $cat)
                 <li>
                    <a href="{{ route($countryPrefix . 'category.show', array_merge($routeParams, ['category' => $cat['slug']])) }}" class="text-sm text-slate-500 dark:text-slate-400 hover:text-primary transition-colors {{ (isset($category) && $category->slug == $cat['slug']) ? 'font-bold text-primary' : '' }}">
                        {{ $cat['name'] }}
                    </a>
                 </li>
            @endforeach
         </ul>
    </div>

    <!-- Network (Mobile Phones Only) -->
    @if($showAdvanced)
    <div class="rounded-xl border border-transparent pb-2 pt-2">
         <h4 class="font-bold text-sm text-slate-900 dark:text-white mb-3">Network</h4>
         <ul class="space-y-2">
             <li><a href="{{ route($countryPrefix . 'filter.type', array_merge($routeParams, ['type' => '4g'])) }}" class="text-sm text-slate-500 dark:text-slate-400 hover:text-primary">4G</a></li>
             <li><a href="{{ route($countryPrefix . 'filter.type', array_merge($routeParams, ['type' => '5g'])) }}" class="text-sm text-slate-500 dark:text-slate-400 hover:text-primary">5G</a></li>
         </ul>
    </div>
    @endif

    <!-- Brand -->
    <div class="rounded-xl border border-transparent pb-2 pt-2">
        <details class="group" open="">
            <summary class="flex cursor-pointer items-center justify-between py-2 font-bold marker:content-none text-slate-900 dark:text-white">
                <span>Brand</span>
                <span class="material-symbols-outlined transition group-open:rotate-180">expand_more</span>
            </summary>
            <div class="pt-2 space-y-2 max-h-60 overflow-y-auto custom-scrollbar">
                @if(isset($category) && $category->brands->isNotEmpty())
                    @foreach($category->brands as $brand)
                        <a href="{{ route($countryPrefix . 'brand.show', array_merge($routeParams, ['brand' => $brand->slug, 'categorySlug' => $category->slug])) }}" class="flex items-center gap-3 cursor-pointer group/item hover:bg-slate-50 dark:hover:bg-slate-800 p-1 rounded">
                            <div class="relative flex items-center">
                               <span class="material-symbols-outlined text-slate-400 group-hover/item:text-primary">smartphone</span>
                            </div>
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300 group-hover/item:text-primary">{{ $brand->name }}</span>
                        </a>
                    @endforeach
                     <a href="{{ route($countryPrefix . 'brands.by.category', array_merge($routeParams, ['category_slug' => $category->slug])) }}" class="text-xs font-bold text-primary mt-2 block hover:underline">View All Brands</a>
                @else
                    <p class="text-xs text-slate-500 dark:text-slate-400">No brands available.</p>
                @endif
            </div>
        </details>
    </div>

    <!-- Price Range -->
    <div class="space-y-4 rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-800">
        <div class="flex items-center justify-between">
            <h4 class="font-bold text-sm text-slate-900 dark:text-white">Price Range</h4>
            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ $country->currency }}</span>
        </div>
         <div class="flex flex-col gap-2">
            @foreach([15000, 20000, 25000, 30000, 35000, 40000, 50000, 60000, 70000, 80000, 90000] as $amount)
                 <a href="{{ route($countryPrefix . 'filter.price', array_merge($routeParams, ['amount' => $amount])) }}" class="text-xs text-slate-500 dark:text-slate-400 hover:text-primary transition-colors">Under {{ number_format($amount) }}</a>
            @endforeach
         </div>
    </div>

    @if($showAdvanced)
        <!-- Phone Types -->
         <div class="rounded-xl border border-transparent pb-2 pt-2">
            <details class="group">
                <summary class="flex cursor-pointer items-center justify-between py-2 font-bold marker:content-none text-slate-900 dark:text-white">
                    <span>Phone Types</span>
                    <span class="material-symbols-outlined transition group-open:rotate-180">expand_more</span>
                </summary>
                <div class="pt-2 space-y-2">
                    @foreach($phoneTypes as $type)
                        <a href="{{ route($countryPrefix . 'filter.type', array_merge($routeParams, ['type' => $type['slug']])) }}" class="block text-sm text-slate-500 dark:text-slate-400 hover:text-primary">
                            {{ $type['name'] }}
                        </a>
                    @endforeach
                </div>
            </details>
        </div>

        <!-- Combination -->
         <div class="rounded-xl border border-transparent pb-2 pt-2">
            <details class="group">
                <summary class="flex cursor-pointer items-center justify-between py-2 font-bold marker:content-none text-slate-900 dark:text-white">
                    <span>Combination</span>
                    <span class="material-symbols-outlined transition group-open:rotate-180">expand_more</span>
                </summary>
                <div class="pt-2 space-y-2">
                    @foreach($combinations as $comb)
                        <a href="{{ route($countryPrefix . 'filter.ramrom', array_merge($routeParams, ['ram' => $comb['ram'], 'rom' => $comb['rom']])) }}" class="block text-sm text-slate-500 dark:text-slate-400 hover:text-primary">
                            {{ $comb['ram'] }}GB RAM + {{ $comb['rom'] }}GB Storage
                        </a>
                    @endforeach
                </div>
            </details>
        </div>

        <!-- Processor -->
         <div class="rounded-xl border border-transparent pb-2 pt-2">
            <details class="group">
                <summary class="flex cursor-pointer items-center justify-between py-2 font-bold marker:content-none text-slate-900 dark:text-white">
                    <span>Processor</span>
                    <span class="material-symbols-outlined transition group-open:rotate-180">expand_more</span>
                </summary>
                <div class="pt-2 space-y-2 max-h-48 overflow-y-auto custom-scrollbar">
                    @foreach($processors as $proc)
                        <a href="{{ route($countryPrefix . 'filter.processor', array_merge($routeParams, ['processor' => $proc['slug']])) }}" class="block text-sm text-slate-500 dark:text-slate-400 hover:text-primary">
                            {{ $proc['name'] }}
                        </a>
                    @endforeach
                </div>
            </details>
        </div>

        <!-- RAM -->
         <div class="rounded-xl border border-transparent pb-2 pt-2">
            <details class="group">
                <summary class="flex cursor-pointer items-center justify-between py-2 font-bold marker:content-none text-slate-900 dark:text-white">
                    <span>RAM</span>
                    <span class="material-symbols-outlined transition group-open:rotate-180">expand_more</span>
                </summary>
                <div class="pt-2 space-y-2">
                    @foreach($ramLimits as $ram)
                        <a href="{{ route($countryPrefix . 'filter.ram', array_merge($routeParams, ['ram' => $ram])) }}" class="block text-sm text-slate-500 dark:text-slate-400 hover:text-primary">
                            {{ $ram }} GB RAM
                        </a>
                    @endforeach
                </div>
            </details>
        </div>

        <!-- Storage -->
         <div class="rounded-xl border border-transparent pb-2 pt-2">
            <details class="group">
                <summary class="flex cursor-pointer items-center justify-between py-2 font-bold marker:content-none text-slate-900 dark:text-white">
                    <span>Storage</span>
                    <span class="material-symbols-outlined transition group-open:rotate-180">expand_more</span>
                </summary>
                <div class="pt-2 space-y-2">
                    @foreach($romLimits as $rom)
                        <a href="{{ route($countryPrefix . 'filter.rom', array_merge($routeParams, ['rom' => $rom, 'unit' => ($rom >= 1024 ? 'tb' : 'gb')])) }}" class="block text-sm text-slate-500 dark:text-slate-400 hover:text-primary">
                            {{ $rom >= 1024 ? ($rom / 1024) . ' TB' : $rom . ' GB' }} Memory
                        </a>
                    @endforeach
                </div>
            </details>
        </div>

        <!-- Screen Size -->
         <div class="rounded-xl border border-transparent pb-2 pt-2">
            <details class="group">
                <summary class="flex cursor-pointer items-center justify-between py-2 font-bold marker:content-none text-slate-900 dark:text-white">
                    <span>Screen Size</span>
                    <span class="material-symbols-outlined transition group-open:rotate-180">expand_more</span>
                </summary>
                <div class="pt-2 space-y-2">
                    @foreach($screenSizes as $size)
                        <a href="{{ route($countryPrefix . 'filter.screen', array_merge($routeParams, ['maxSize' => $size])) }}" class="block text-sm text-slate-500 dark:text-slate-400 hover:text-primary">
                            {{ $size }} Inch Screens
                        </a>
                    @endforeach
                </div>
            </details>
        </div>

        <!-- Camera -->
         <div class="rounded-xl border border-transparent pb-2 pt-2">
            <details class="group">
                <summary class="flex cursor-pointer items-center justify-between py-2 font-bold marker:content-none text-slate-900 dark:text-white">
                    <span>Camera</span>
                    <span class="material-symbols-outlined transition group-open:rotate-180">expand_more</span>
                </summary>
                <div class="pt-2 space-y-2 max-h-48 overflow-y-auto custom-scrollbar">
                    @foreach($cameras as $cam)
                        <a href="{{ route($countryPrefix . 'filter.camera.count', array_merge($routeParams, ['parameter' => $cam])) }}" class="block text-sm text-slate-500 dark:text-slate-400 hover:text-primary">
                            {{ ucfirst($cam) }} Camera
                        </a>
                    @endforeach
                    @foreach($cameraMp as $mp)
                        <a href="{{ route($countryPrefix . 'filter.camera.mp', array_merge($routeParams, ['mp' => $mp])) }}" class="block text-sm text-slate-500 dark:text-slate-400 hover:text-primary">
                            {{ $mp }}MP
                        </a>
                    @endforeach
                </div>
            </details>
        </div>
    @endif
</div>
