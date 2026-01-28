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

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4 border-subtle">
    <div class="widget-header p-2">
        <h5 class="fw-bold mb-0">Categories</h5>
    </div>
    <div class="widget-body">
        <ul class="list-unstyled ps-2 pt-1">
            @foreach($mksCategories as $mksCat)
                <li class="mb-1">
                    <a href="{{ url($prefix . '/category/' . $mksCat['slug']) }}"
                        class="{{ (isset($category) && $category && $category->slug == $mksCat['slug']) ? 'fw-bold text-dark' : 'text-muted' }} fs-14 text-decoration-none hover-link">
                        {{ $mksCat['name'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4 border-subtle">
    <div class="widget-header p-2 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-uppercase">Network</h5>
        <a data-bs-toggle="collapse" href="#network-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill"></i>
        </a>
    </div>
    <div class="collapse show" id="network-collapse">
        <div class="widget-body px-2">
            <ul class="list-unstyled pt-1">
                <li class="mb-1"><a href="{{ url($prefix . '/4g-mobile-phones') }}"
                        class="text-muted fs-14 text-decoration-none hover-link">4G</a></li>
                <li class="mb-1"><a href="{{ url($prefix . '/5g-mobile-phones') }}"
                        class="text-muted fs-14 text-decoration-none hover-link">5G</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4 border-subtle">
    <div class="widget-header p-2 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-uppercase">Brands</h5>
        <a data-bs-toggle="collapse" href="#brands-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill"></i>
        </a>
    </div>
    <div class="collapse show" id="brands-collapse">
        <div class="widget-body px-2 scroll-container" style="max-height: 250px; overflow-y: auto;">
            <ul class="list-unstyled pt-1">
                @if(isset($category) && $category)
                    @foreach($category->brands as $brand)
                        <li class="mb-1">
                            <a href="{{ route(($pk ? '' : 'country.') . 'brand.show', ($pk ? [$brand->slug, $category->slug] : ['country_code' => $country->country_code, 'brand' => $brand->slug, 'categorySlug' => $category->slug])) }}"
                                class="text-muted fs-14 text-decoration-none hover-link">
                                {{ $brand->name }}
                            </a>
                        </li>
                    @endforeach
                    <li class="mt-1">
                        <a href="{{ route('brands.by.category', $category->slug) }}"
                            class="fw-bold text-dark fs-14 text-decoration-none hover-link">View All Brands</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4 border-subtle">
    <div class="widget-header p-2 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-uppercase">Phone Type</h5>
        <a data-bs-toggle="collapse" href="#type-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill"></i>
        </a>
    </div>
    <div class="collapse show" id="type-collapse">
        <div class="widget-body px-2">
            <ul class="list-unstyled pt-1">
                @foreach($phoneTypes as $type)
                    <li class="mb-1">
                        <a href="{{ url($prefix . '/' . $type['slug'] . '-mobile-phones') }}"
                            class="text-muted fs-14 text-decoration-none hover-link">
                            {{ $type['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4 border-subtle">
    <div class="widget-header p-2 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-uppercase">Combination</h5>
        <a data-bs-toggle="collapse" href="#combination-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill"></i>
        </a>
    </div>
    <div class="collapse show" id="combination-collapse">
        <div class="widget-body px-2">
            <ul class="list-unstyled pt-1">
                @foreach($combinations as $comb)
                    <li class="mb-1">
                        <a href="{{ url($prefix . '/mobile-phones-with-' . $comb['ram'] . 'gb-ram-' . $comb['rom'] . 'gb-storage') }}"
                            class="text-muted fs-14 text-decoration-none hover-link">
                            {{ $comb['ram'] }}GB + {{ $comb['rom'] }}GB
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4 border-subtle">
    <div class="widget-header p-2 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-uppercase">Processor</h5>
        <a data-bs-toggle="collapse" href="#processor-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill"></i>
        </a>
    </div>
    <div class="collapse show" id="processor-collapse">
        <div class="widget-body px-2 scroll-container" style="max-height: 200px; overflow-y: auto;">
            <ul class="list-unstyled pt-1">
                @foreach($processors as $proc)
                    <li class="mb-1">
                        <a href="{{ url($prefix . '/' . $proc['slug'] . '-mobile-phones') }}"
                            class="text-muted fs-14 text-decoration-none hover-link">
                            {{ $proc['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4 border-subtle">
    <div class="widget-header p-2 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-uppercase">RAM</h5>
        <a data-bs-toggle="collapse" href="#ram-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill"></i>
        </a>
    </div>
    <div class="collapse show" id="ram-collapse">
        <div class="widget-body px-2">
            <ul class="list-unstyled pt-1">
                @foreach($ramLimits as $ramLimit)
                    <li class="mb-1">
                        <a href="{{ url($prefix . '/mobile-phones-' . $ramLimit . 'gb-ram') }}"
                            class="text-muted fs-14 text-decoration-none hover-link">
                            {{$ramLimit}} GB
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4 border-subtle">
    <div class="widget-header p-2 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-uppercase">Storage</h5>
        <a data-bs-toggle="collapse" href="#rom-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill"></i>
        </a>
    </div>
    <div class="collapse show" id="rom-collapse">
        <div class="widget-body px-2 scroll-container" style="max-height: 200px; overflow-y: auto;">
            <ul class="list-unstyled pt-1">
                @foreach($romLimits as $romLimit)
                    <li class="mb-1">
                        <a href="{{ url($prefix . '/mobile-phones-' . ($romLimit >= 1024 ? ($romLimit / 1024) . 'tb' : $romLimit . 'gb-storage')) }}"
                            class="text-muted fs-14 text-decoration-none hover-link">
                            {{$romLimit >= 1024 ? ($romLimit / 1024) . ' TB' : $romLimit . ' GB'}}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4 border-subtle">
    <div class="widget-header p-2 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-uppercase">Screen Size</h5>
        <a data-bs-toggle="collapse" href="#screen-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill"></i>
        </a>
    </div>
    <div class="collapse show" id="screen-collapse">
        <div class="widget-body px-2">
            <ul class="list-unstyled pt-1">
                @foreach($screenSizes as $size)
                    <li class="mb-1">
                        <a href="{{ url($prefix . '/mobile-phones-screen-' . $size . '-inch') }}"
                            class="text-muted fs-14 text-decoration-none hover-link">
                            {{ $size }} Inch
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4 border-subtle">
    <div class="widget-header p-2 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-uppercase">Camera</h5>
        <a data-bs-toggle="collapse" href="#camera-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill"></i>
        </a>
    </div>
    <div class="collapse show" id="camera-collapse">
        <div class="widget-body px-2 scroll-container" style="max-height: 250px; overflow-y: auto;">
            <ul class="list-unstyled pt-1">
                @foreach($cameras as $cam)
                    <li class="mb-1">
                        <a href="{{ url($prefix . '/mobile-phones-' . $cam . '-camera') }}"
                            class="text-muted fs-14 text-decoration-none hover-link">
                            {{ ucfirst($cam) }} Camera
                        </a>
                    </li>
                @endforeach
                @foreach($cameraMp as $mp)
                    <li class="mb-1">
                        <a href="{{ url($prefix . '/mobile-phones-' . $mp . '-camera') }}"
                            class="text-muted fs-14 text-decoration-none hover-link">
                            {{ $mp }} MP Camera
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<style>
    .fs-14 {
        font-size: 14px;
    }

    .border-subtle {
        border: 1px solid rgba(0, 0, 0, .05) !important;
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
    }

    .widget-header i.bi {
        font-size: 0.8rem;
    }
</style>