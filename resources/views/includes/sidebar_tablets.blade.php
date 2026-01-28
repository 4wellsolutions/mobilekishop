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

    // Price ranges for tablets
    $tabletPriceRanges = [25000, 30000, 35000, 40000, 50000, 60000, 70000, 80000, 90000, 100000, 200000, 300000];

    // RAM limits
    $ramLimits = [2, 3, 4, 6, 8, 12, 16];

    // Storage limits
    $romLimits = [16, 32, 64, 128, 256, 512, 1024];

    // Screen sizes
    $screenSizes = [8, 10, 12, 14, 16];

    // Cameras
    $cameras = [5, 13, 24];
@endphp

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4">
    <div class="widget-header p-2">
        <h5 class="fw-bold mb-0">Categories</h5>
    </div>
    <div class="widget-body">
        <ul class="list-unstyled ps-2 pt-1">
            @foreach($mksCategories as $mksCat)
                <li class="mb-1">
                    <a href="{{ url($prefix . '/category/' . $mksCat['slug']) }}"
                        class="{{ $category->slug == $mksCat['slug'] ? 'fw-bold text-dark' : 'text-muted' }} fs-14 text-decoration-none">
                        {{ $mksCat['name'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4">
    <div class="widget-header p-2 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Network</h5>
        <a data-bs-toggle="collapse" href="#network-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill"></i>
        </a>
    </div>
    <div class="collapse show" id="network-collapse">
        <div class="widget-body px-2">
            <ul class="list-unstyled pt-1">
                <li class="mb-1"><a href="{{ url($prefix . '/4g-tablets') }}"
                        class="text-muted fs-14 text-decoration-none hover-link">4G</a></li>
                <li class="mb-1"><a href="{{ url($prefix . '/5g-tablets') }}"
                        class="text-muted fs-14 text-decoration-none hover-link">5G</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4">
    <div class="widget-header p-2 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Brands</h5>
        <a data-bs-toggle="collapse" href="#brands-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill"></i>
        </a>
    </div>
    <div class="collapse show" id="brands-collapse">
        <div class="widget-body px-2 scroll-container" style="max-height: 250px; overflow-y: auto;">
            <ul class="list-unstyled pt-1">
                @foreach($category->brands as $brand)
                    <li class="mb-1">
                        <a href="{{ route(($pk ? '' : 'country.') . 'brand.show', ($pk ? [$brand->slug, $category->slug] : ['country_code' => $country->country_code, 'brand' => $brand->slug, 'categorySlug' => $category->slug])) }}"
                            class="text-muted fs-14 text-decoration-none hover-link">
                            {{ $brand->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4">
    <div class="widget-header p-2 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Price</h5>
        <a data-bs-toggle="collapse" href="#prices-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill"></i>
        </a>
    </div>
    <div class="collapse show" id="prices-collapse">
        <div class="widget-body px-2 scroll-container" style="max-height: 250px; overflow-y: auto;">
            <ul class="list-unstyled pt-1">
                @foreach($tabletPriceRanges as $val)
                    <li class="mb-1">
                        <a href="{{ url($prefix . '/tablets-under-' . $val) }}"
                            class="text-muted fs-14 text-decoration-none hover-link">
                            Tablets Under {{ number_format($val) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4">
    <div class="widget-header p-2">
        <h5 class="fw-bold mb-0">RAM</h5>
    </div>
    <div class="widget-body px-2">
        <ul class="list-unstyled pt-1">
            @foreach($ramLimits as $ram)
                <li class="mb-1">
                    <a href="{{ url($prefix . '/tablets-' . $ram . 'gb-ram') }}"
                        class="text-muted fs-14 text-decoration-none hover-link">
                        {{ $ram }}GB RAM
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4">
    <div class="widget-header p-2">
        <h5 class="fw-bold mb-0">Storage</h5>
    </div>
    <div class="widget-body px-2">
        <ul class="list-unstyled pt-1">
            @foreach($romLimits as $rom)
                <li class="mb-1">
                    <a href="{{ url($prefix . '/tablets-' . $rom . 'gb-storage') }}"
                        class="text-muted fs-14 text-decoration-none hover-link">
                        {{ $rom }}GB ROM
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4">
    <div class="widget-header p-2">
        <h5 class="fw-bold mb-0">Screen Size</h5>
    </div>
    <div class="widget-body px-2">
        <ul class="list-unstyled pt-1">
            @foreach($screenSizes as $size)
                <li class="mb-1">
                    <a href="{{ url($prefix . '/tablets-screen-' . $size . '-inch') }}"
                        class="text-muted fs-14 text-decoration-none hover-link">
                        {{ $size }} Inch Display
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4">
    <div class="widget-header p-2">
        <h5 class="fw-bold mb-0">Camera</h5>
    </div>
    <div class="widget-body px-2">
        <ul class="list-unstyled pt-1">
            @foreach($cameras as $cam)
                <li class="mb-1">
                    <a href="{{ url($prefix . '/tablets-' . $cam . 'mp-camera') }}"
                        class="text-muted fs-14 text-decoration-none hover-link">
                        {{ $cam }}MP Camera
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<style>
    .fs-14 {
        font-size: 14px;
    }

    .scroll-container::-webkit-scrollbar {
        width: 8px;
    }

    .scroll-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .scroll-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .scroll-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .hover-link:hover {
        color: #0d6efd !important;
    }

    .widget-sidebar {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, .05);
    }
</style>