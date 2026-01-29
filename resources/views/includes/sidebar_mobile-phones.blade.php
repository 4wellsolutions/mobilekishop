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

    $pk = $country->country_code == 'pk';
    $prefix = $pk ? '' : '/' . $country->country_code;
@endphp

{{-- Categories --}}
<div class="sidebar-card">
    <h5 class="sidebar-title">Categories</h5>
    <ul class="sidebar-list">
        @foreach($mksCategories as $mksCat)
            <li>
                <a href="{{ url($prefix . '/category/' . $mksCat['slug']) }}"
                    class="{{ (isset($category) && $category && $category->slug == $mksCat['slug']) ? 'active' : '' }}">
                    {{ $mksCat['name'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div>

{{-- Network --}}
<div class="sidebar-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="sidebar-title mb-0">Network</h5>
        <a data-bs-toggle="collapse" href="#network-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill small"></i>
        </a>
    </div>
    <div class="collapse show" id="network-collapse">
        <ul class="sidebar-list">
            <li><a href="{{ url($prefix . '/4g-mobile-phones') }}">4G</a></li>
            <li><a href="{{ url($prefix . '/5g-mobile-phones') }}">5G</a></li>
        </ul>
    </div>
</div>

{{-- Brands --}}
<div class="sidebar-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="sidebar-title mb-0">Brands</h5>
        <a data-bs-toggle="collapse" href="#brands-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill small"></i>
        </a>
    </div>
    <div class="collapse show" id="brands-collapse">
        <div class="scroll-container" style="max-height: 250px; overflow-y: auto;">
            <ul class="sidebar-list">
                @if(isset($category) && $category)
                    @foreach($category->brands as $brand)
                        <li>
                            <a
                                href="{{ route(($pk ? '' : 'country.') . 'brand.show', ($pk ? [$brand->slug, $category->slug] : ['country_code' => $country->country_code, 'brand' => $brand->slug, 'categorySlug' => $category->slug])) }}">
                                {{ $brand->name }}
                            </a>
                        </li>
                    @endforeach
                    <li class="mt-2">
                        <a href="{{ route('brands.by.category', $category->slug) }}" class="fw-bold text-dark">View All
                            Brands</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>

{{-- Phone Type --}}
<div class="sidebar-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="sidebar-title mb-0">Phone Type</h5>
        <a data-bs-toggle="collapse" href="#type-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill small"></i>
        </a>
    </div>
    <div class="collapse show" id="type-collapse">
        <ul class="sidebar-list">
            @foreach($phoneTypes as $type)
                <li>
                    <a href="{{ url($prefix . '/' . $type['slug'] . '-mobile-phones') }}">
                        {{ $type['name'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

{{-- Combination --}}
<div class="sidebar-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="sidebar-title mb-0">Combination</h5>
        <a data-bs-toggle="collapse" href="#combination-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill small"></i>
        </a>
    </div>
    <div class="collapse show" id="combination-collapse">
        <ul class="sidebar-list">
            @foreach($combinations as $comb)
                <li>
                    <a
                        href="{{ url($prefix . '/mobile-phones-with-' . $comb['ram'] . 'gb-ram-' . $comb['rom'] . 'gb-storage') }}">
                        {{ $comb['ram'] }}GB + {{ $comb['rom'] }}GB
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

{{-- Processor --}}
<div class="sidebar-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="sidebar-title mb-0">Processor</h5>
        <a data-bs-toggle="collapse" href="#processor-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill small"></i>
        </a>
    </div>
    <div class="collapse show" id="processor-collapse">
        <div class="scroll-container" style="max-height: 200px; overflow-y: auto;">
            <ul class="sidebar-list">
                @foreach($processors as $proc)
                    <li>
                        <a href="{{ url($prefix . '/' . $proc['slug'] . '-mobile-phones') }}">
                            {{ $proc['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

{{-- RAM --}}
<div class="sidebar-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="sidebar-title mb-0">RAM</h5>
        <a data-bs-toggle="collapse" href="#ram-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill small"></i>
        </a>
    </div>
    <div class="collapse show" id="ram-collapse">
        <ul class="sidebar-list">
            @foreach($ramLimits as $ramLimit)
                <li>
                    <a href="{{ url($prefix . '/mobile-phones-' . $ramLimit . 'gb-ram') }}">
                        {{$ramLimit}} GB
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

{{-- Storage --}}
<div class="sidebar-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="sidebar-title mb-0">Storage</h5>
        <a data-bs-toggle="collapse" href="#rom-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill small"></i>
        </a>
    </div>
    <div class="collapse show" id="rom-collapse">
        <div class="scroll-container" style="max-height: 200px; overflow-y: auto;">
            <ul class="sidebar-list">
                @foreach($romLimits as $romLimit)
                    <li>
                        <a
                            href="{{ url($prefix . '/mobile-phones-' . ($romLimit >= 1024 ? ($romLimit / 1024) . 'tb' : $romLimit . 'gb-storage')) }}">
                            {{$romLimit >= 1024 ? ($romLimit / 1024) . ' TB' : $romLimit . ' GB'}}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

{{-- Screen Size --}}
<div class="sidebar-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="sidebar-title mb-0">Screen Size</h5>
        <a data-bs-toggle="collapse" href="#screen-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill small"></i>
        </a>
    </div>
    <div class="collapse show" id="screen-collapse">
        <ul class="sidebar-list">
            @foreach($screenSizes as $size)
                <li>
                    <a href="{{ url($prefix . '/mobile-phones-screen-' . $size . '-inch') }}">
                        {{ $size }} Inch
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

{{-- Camera --}}
<div class="sidebar-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="sidebar-title mb-0">Camera</h5>
        <a data-bs-toggle="collapse" href="#camera-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill small"></i>
        </a>
    </div>
    <div class="collapse show" id="camera-collapse">
        <div class="scroll-container" style="max-height: 250px; overflow-y: auto;">
            <ul class="sidebar-list">
                @foreach($cameras as $cam)
                    <li>
                        <a href="{{ url($prefix . '/mobile-phones-' . $cam . '-camera') }}">
                            {{ ucfirst($cam) }} Camera
                        </a>
                    </li>
                @endforeach
                @foreach($cameraMp as $mp)
                    <li>
                        <a href="{{ url($prefix . '/mobile-phones-' . $mp . '-camera') }}">
                            {{ $mp }} MP Camera
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

{{-- Dynamic Filters --}}
@if(isset($filters) && $filters->isNotEmpty())
    <div class="sidebar-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="sidebar-title mb-0">Filters</h5>
            <a data-bs-toggle="collapse" href="#filters-collapse" role="button" aria-expanded="true" class="text-dark">
                <i class="bi bi-caret-up-fill small"></i>
            </a>
        </div>
        <div class="collapse show" id="filters-collapse">
            <ul class="sidebar-list">
                @foreach($filters as $filter)
                    <li>
                        <a href="{{$filter->url}}" class="{{ str_contains(request()->url(), $filter->url) ? 'active' : '' }}">
                            {{$filter->title}}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif