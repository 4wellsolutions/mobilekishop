@php
    $mksCategories = [
        ['name' => 'Mobile Phones', 'slug' => 'mobile-phones'],
        ['name' => 'Smart Watches', 'slug' => 'smart-watch'],
        ['name' => 'Tablets', 'slug' => 'tablets'],
        ['name' => 'Earphones', 'slug' => 'earphone'],
        ['name' => 'Phone Covers', 'slug' => 'phone-covers'],
        ['name' => 'Power Banks', 'slug' => 'power-banks'],
        ['name' => 'Chargers', 'slug' => 'chargers'],
        ['name' => 'Cables', 'slug' => 'cables'],
    ];

    $pk = $country->country_code == 'pk';
    $prefix = $pk ? '' : '/' . $country->country_code;
    $currentSlug = isset($category) && $category ? $category->slug : null;
@endphp

{{-- Categories --}}
<div class="bg-surface-card rounded-xl p-4 mb-3">
    <h5 class="text-sm font-semibold text-text-main mb-3">Categories</h5>
    <ul class="space-y-1.5 list-none p-0 m-0">
        @foreach($mksCategories as $mksCat)
            <li>
                <a href="{{ url($prefix . '/category/' . $mksCat['slug']) }}"
                    class="text-sm no-underline block py-0.5 transition-colors {{ ($currentSlug == $mksCat['slug']) ? 'text-primary font-semibold' : 'text-text-muted hover:text-primary' }}">
                    {{ $mksCat['name'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div>

{{-- Brands --}}
@if(isset($category) && $category)
    @php
        $sidebarBrands = $category->brands;
        if ($sidebarBrands->isEmpty()) {
            $sidebarBrands = \App\Models\Brand::whereHas('products', function ($q) use ($category) {
                $q->where('category_id', $category->id);
            })->orderBy('name')->get();
        }
    @endphp
    @if($sidebarBrands->isNotEmpty())
        <div class="bg-surface-card rounded-xl p-4 mb-3">
            <div class="flex justify-between items-center mb-3 cursor-pointer"
                onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.toggle-icon').textContent = this.nextElementSibling.classList.contains('hidden') ? 'expand_more' : 'expand_less';">
                <h5 class="text-sm font-semibold text-text-main m-0">Brands</h5>
                <span class="material-symbols-outlined text-lg text-text-muted toggle-icon">expand_less</span>
            </div>
            <div class="max-h-[250px] overflow-y-auto">
                <ul class="space-y-1.5 list-none p-0 m-0">
                    @foreach($sidebarBrands as $sbBrand)
                        <li>
                            <a href="{{ route(($pk ? '' : 'country.') . 'brand.show', ($pk ? [$sbBrand->slug, $category->slug] : ['country_code' => $country->country_code, 'brand' => $sbBrand->slug, 'categorySlug' => $category->slug])) }}"
                                class="text-sm no-underline block py-0.5 transition-colors {{ (isset($activeBrand) && $activeBrand->id === $sbBrand->id) ? 'text-primary font-semibold' : 'text-text-muted hover:text-primary' }}">
                                {{ $sbBrand->name }}
                            </a>
                        </li>
                    @endforeach
                    <li class="mt-2">
                        <a href="{{ route('brands.by.category', $category->slug) }}"
                            class="text-sm font-semibold text-text-main hover:text-primary no-underline transition-colors">View
                            All Brands</a>
                    </li>
                </ul>
            </div>
        </div>
    @endif
@endif

{{-- ================================================================== --}}
{{-- Dynamic Filters from Database --}}
{{-- ================================================================== --}}
@php
    $currentUrl = request()->url();
    $dbFilters = \App\Models\Filter::where('page_url', $currentUrl)
        ->whereNotNull('title')->where('title', '!=', '')
        ->whereNotNull('url')->where('url', '!=', '')
        ->orderBy('title')
        ->get();

    // For brand-price filter pages, also check the brand's main page URL
    if ($dbFilters->isEmpty() && isset($activeBrand) && $activeBrand) {
        $brandPageUrl = url(($pk ? '' : $country->country_code . '/') . 'brand/' . $activeBrand->slug . '/mobile-phones');
        $dbFilters = \App\Models\Filter::where('page_url', $brandPageUrl)
            ->whereNotNull('title')->where('title', '!=', '')
            ->whereNotNull('url')->where('url', '!=', '')
            ->orderBy('title')
            ->get();
    }
@endphp

@if($dbFilters->isNotEmpty())
    <div class="bg-surface-card rounded-xl p-4 mb-3">
        <div class="flex justify-between items-center mb-3 cursor-pointer"
            onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.toggle-icon').textContent = this.nextElementSibling.classList.contains('hidden') ? 'expand_more' : 'expand_less';">
            <h5 class="text-sm font-semibold text-text-main m-0">Quick Links</h5>
            <span class="material-symbols-outlined text-lg text-text-muted toggle-icon">expand_less</span>
        </div>
        <div class="max-h-[300px] overflow-y-auto">
            <ul class="space-y-1.5 list-none p-0 m-0">
                @foreach($dbFilters as $dbFilter)
                    <li>
                        <a href="{{ $dbFilter->url }}"
                            class="text-sm no-underline block py-0.5 transition-colors {{ request()->url() === $dbFilter->url ? 'text-primary font-semibold' : 'text-text-muted hover:text-primary' }}">
                            {{ $dbFilter->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- ================================================================== --}}
{{-- Mobile Phones-specific filters --}}
{{-- ================================================================== --}}
@if($currentSlug === 'mobile-phones')
    @php
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
    @endphp

    {{-- Network --}}
    <div class="bg-surface-card rounded-xl p-4 mb-3">
        <div class="flex justify-between items-center mb-3 cursor-pointer"
            onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.toggle-icon').textContent = this.nextElementSibling.classList.contains('hidden') ? 'expand_more' : 'expand_less';">
            <h5 class="text-sm font-semibold text-text-main m-0">Network</h5>
            <span class="material-symbols-outlined text-lg text-text-muted toggle-icon">expand_less</span>
        </div>
        <div>
            <ul class="space-y-1.5 list-none p-0 m-0">
                <li><a href="{{ url($prefix . '/4g-mobile-phones') }}"
                        class="text-sm text-text-muted hover:text-primary no-underline block py-0.5 transition-colors">4G</a>
                </li>
                <li><a href="{{ url($prefix . '/5g-mobile-phones') }}"
                        class="text-sm text-text-muted hover:text-primary no-underline block py-0.5 transition-colors">5G</a>
                </li>
            </ul>
        </div>
    </div>

    {{-- Phone Type --}}
    <div class="bg-surface-card rounded-xl p-4 mb-3">
        <div class="flex justify-between items-center mb-3 cursor-pointer"
            onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.toggle-icon').textContent = this.nextElementSibling.classList.contains('hidden') ? 'expand_more' : 'expand_less';">
            <h5 class="text-sm font-semibold text-text-main m-0">Phone Type</h5>
            <span class="material-symbols-outlined text-lg text-text-muted toggle-icon">expand_less</span>
        </div>
        <div>
            <ul class="space-y-1.5 list-none p-0 m-0">
                @foreach($phoneTypes as $type)
                    <li>
                        <a href="{{ url($prefix . '/' . $type['slug'] . '-mobile-phones') }}"
                            class="text-sm text-text-muted hover:text-primary no-underline block py-0.5 transition-colors">
                            {{ $type['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Combination --}}
    <div class="bg-surface-card rounded-xl p-4 mb-3">
        <div class="flex justify-between items-center mb-3 cursor-pointer"
            onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.toggle-icon').textContent = this.nextElementSibling.classList.contains('hidden') ? 'expand_more' : 'expand_less';">
            <h5 class="text-sm font-semibold text-text-main m-0">Combination</h5>
            <span class="material-symbols-outlined text-lg text-text-muted toggle-icon">expand_less</span>
        </div>
        <div>
            <ul class="space-y-1.5 list-none p-0 m-0">
                @foreach($combinations as $comb)
                    <li>
                        <a href="{{ url($prefix . '/mobile-phones-with-' . $comb['ram'] . 'gb-ram-' . $comb['rom'] . 'gb-storage') }}"
                            class="text-sm text-text-muted hover:text-primary no-underline block py-0.5 transition-colors">
                            {{ $comb['ram'] }}GB + {{ $comb['rom'] }}GB
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Processor --}}
    <div class="bg-surface-card rounded-xl p-4 mb-3">
        <div class="flex justify-between items-center mb-3 cursor-pointer"
            onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.toggle-icon').textContent = this.nextElementSibling.classList.contains('hidden') ? 'expand_more' : 'expand_less';">
            <h5 class="text-sm font-semibold text-text-main m-0">Processor</h5>
            <span class="material-symbols-outlined text-lg text-text-muted toggle-icon">expand_less</span>
        </div>
        <div class="max-h-[200px] overflow-y-auto">
            <ul class="space-y-1.5 list-none p-0 m-0">
                @foreach($processors as $proc)
                    <li>
                        <a href="{{ url($prefix . '/' . $proc['slug'] . '-mobile-phones') }}"
                            class="text-sm text-text-muted hover:text-primary no-underline block py-0.5 transition-colors">
                            {{ $proc['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- RAM --}}
    <div class="bg-surface-card rounded-xl p-4 mb-3">
        <div class="flex justify-between items-center mb-3 cursor-pointer"
            onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.toggle-icon').textContent = this.nextElementSibling.classList.contains('hidden') ? 'expand_more' : 'expand_less';">
            <h5 class="text-sm font-semibold text-text-main m-0">RAM</h5>
            <span class="material-symbols-outlined text-lg text-text-muted toggle-icon">expand_less</span>
        </div>
        <div>
            <ul class="space-y-1.5 list-none p-0 m-0">
                @foreach($ramLimits as $ramLimit)
                    <li>
                        <a href="{{ url($prefix . '/mobile-phones-' . $ramLimit . 'gb-ram') }}"
                            class="text-sm text-text-muted hover:text-primary no-underline block py-0.5 transition-colors">
                            {{ $ramLimit }} GB
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Storage --}}
    <div class="bg-surface-card rounded-xl p-4 mb-3">
        <div class="flex justify-between items-center mb-3 cursor-pointer"
            onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.toggle-icon').textContent = this.nextElementSibling.classList.contains('hidden') ? 'expand_more' : 'expand_less';">
            <h5 class="text-sm font-semibold text-text-main m-0">Storage</h5>
            <span class="material-symbols-outlined text-lg text-text-muted toggle-icon">expand_less</span>
        </div>
        <div class="max-h-[200px] overflow-y-auto">
            <ul class="space-y-1.5 list-none p-0 m-0">
                @foreach($romLimits as $romLimit)
                    <li>
                        <a href="{{ url($prefix . '/mobile-phones-' . ($romLimit >= 1024 ? ($romLimit / 1024) . 'tb' : $romLimit . 'gb-storage')) }}"
                            class="text-sm text-text-muted hover:text-primary no-underline block py-0.5 transition-colors">
                            {{ $romLimit >= 1024 ? ($romLimit / 1024) . ' TB' : $romLimit . ' GB' }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Screen Size --}}
    <div class="bg-surface-card rounded-xl p-4 mb-3">
        <div class="flex justify-between items-center mb-3 cursor-pointer"
            onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.toggle-icon').textContent = this.nextElementSibling.classList.contains('hidden') ? 'expand_more' : 'expand_less';">
            <h5 class="text-sm font-semibold text-text-main m-0">Screen Size</h5>
            <span class="material-symbols-outlined text-lg text-text-muted toggle-icon">expand_less</span>
        </div>
        <div>
            <ul class="space-y-1.5 list-none p-0 m-0">
                @foreach($screenSizes as $size)
                    <li>
                        <a href="{{ url($prefix . '/mobile-phones-screen-' . $size . '-inch') }}"
                            class="text-sm text-text-muted hover:text-primary no-underline block py-0.5 transition-colors">
                            {{ $size }} Inch
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Camera --}}
    <div class="bg-surface-card rounded-xl p-4 mb-3">
        <div class="flex justify-between items-center mb-3 cursor-pointer"
            onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.toggle-icon').textContent = this.nextElementSibling.classList.contains('hidden') ? 'expand_more' : 'expand_less';">
            <h5 class="text-sm font-semibold text-text-main m-0">Camera</h5>
            <span class="material-symbols-outlined text-lg text-text-muted toggle-icon">expand_less</span>
        </div>
        <div class="max-h-[250px] overflow-y-auto">
            <ul class="space-y-1.5 list-none p-0 m-0">
                @foreach($cameras as $cam)
                    <li>
                        <a href="{{ url($prefix . '/mobile-phones-' . $cam . '-camera') }}"
                            class="text-sm text-text-muted hover:text-primary no-underline block py-0.5 transition-colors">
                            {{ ucfirst($cam) }} Camera
                        </a>
                    </li>
                @endforeach
                @foreach($cameraMp as $mp)
                    <li>
                        <a href="{{ url($prefix . '/mobile-phones-' . $mp . '-camera') }}"
                            class="text-sm text-text-muted hover:text-primary no-underline block py-0.5 transition-colors">
                            {{ $mp }} MP Camera
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- ================================================================== --}}
{{-- Chargers-specific filters --}}
{{-- ================================================================== --}}
@if($currentSlug === 'chargers')
    @php
        $capacityTypes = [
            ['name' => 'USB Type A Chargers', 'slug' => 'usb-type-a-chargers'],
            ['name' => 'USB Type C Chargers', 'slug' => 'usb-type-c-chargers'],
        ];
        $wattages = [15, 20, 25, 35, 45, 65, 75, 100, 120, 150, 180];
        $wattAndTypes = [
            ['name' => '30 Watt USB Type C Chargers', 'slug' => '30-watt-usb-type-c-chargers'],
            ['name' => '45 Watt USB Type C Chargers', 'slug' => '45-watt-usb-type-c-chargers'],
            ['name' => '60 Watt USB Type C Chargers', 'slug' => '60-watt-usb-type-c-chargers'],
            ['name' => '65 Watt USB Type C Chargers', 'slug' => '65-watt-usb-type-c-chargers'],
            ['name' => '67 Watt USB Type C Chargers', 'slug' => '67-watt-usb-type-c-chargers'],
            ['name' => '140 Watt USB Type C Chargers', 'slug' => '140-watt-usb-type-c-chargers'],
        ];
    @endphp

    {{-- Capacity Type --}}
    <div class="bg-surface-card rounded-xl p-4 mb-3">
        <h5 class="text-sm font-semibold text-text-main mb-3">Capacity</h5>
        <ul class="space-y-1.5 list-none p-0 m-0">
            @foreach($capacityTypes as $type)
                <li>
                    <a href="{{ url($prefix . '/' . $type['slug']) }}"
                        class="text-sm text-text-muted hover:text-primary no-underline block py-0.5 transition-colors">
                        {{ $type['name'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Wattage --}}
    <div class="bg-surface-card rounded-xl p-4 mb-3">
        <div class="flex justify-between items-center mb-3 cursor-pointer"
            onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.toggle-icon').textContent = this.nextElementSibling.classList.contains('hidden') ? 'expand_more' : 'expand_less';">
            <h5 class="text-sm font-semibold text-text-main m-0">Wattage</h5>
            <span class="material-symbols-outlined text-lg text-text-muted toggle-icon">expand_less</span>
        </div>
        <div class="max-h-[250px] overflow-y-auto">
            <ul class="space-y-1.5 list-none p-0 m-0">
                @foreach($wattages as $watt)
                    @php
                        $targetUrl = url($prefix . '/' . $watt . '-watt-chargers');
                        $isActive = request()->url() === $targetUrl;
                    @endphp
                    <li>
                        <a href="{{ $targetUrl }}"
                            class="text-sm no-underline block py-0.5 transition-colors {{ $isActive ? 'text-primary font-semibold' : 'text-text-muted hover:text-primary' }}">
                            {{ $watt }} Watt Chargers
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Watt & Type --}}
    <div class="bg-surface-card rounded-xl p-4 mb-3">
        <h5 class="text-sm font-semibold text-text-main mb-3">Watt & Type</h5>
        <ul class="space-y-1.5 list-none p-0 m-0">
            @foreach($wattAndTypes as $item)
                <li>
                    <a href="{{ url($prefix . '/' . $item['slug']) }}"
                        class="text-sm text-text-muted hover:text-primary no-underline block py-0.5 transition-colors">
                        {{ $item['name'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif