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

    $capacityTypes = [
        ['name' => 'USB Type A Chargers', 'slug' => 'usb-type-a-chargers'],
        ['name' => 'USB Type C Chargers', 'slug' => 'usb-type-c-chargers'],
    ];

    $wattages = [15, 20, 25, 35, 45, 65, 75, 100, 120, 150, 180];

    $wattAndTypes = [
        ['name' => '30W USB Type C Chargers', 'slug' => '30w-usb-type-c-chargers'],
        ['name' => '45W USB Type C Chargers', 'slug' => '45w-usb-type-c-chargers'],
        ['name' => '60W USB Type C Chargers', 'slug' => '60w-usb-type-c-chargers'],
        ['name' => '65W USB Type C Chargers', 'slug' => '65w-usb-type-c-chargers'],
        ['name' => '67W USB Type C Chargers', 'slug' => '67w-usb-type-c-chargers'],
        ['name' => '140W USB Type C Chargers', 'slug' => '140w-usb-type-c-chargers'],
    ];
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
                        class="{{ $category->slug == $mksCat['slug'] ? 'fw-bold text-dark' : 'text-muted' }} fs-14 text-decoration-none hover-link">
                        {{ $mksCat['name'] }}
                    </a>
                </li>
            @endforeach
        </ul>
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
                <li class="mt-1">
                    <a href="{{ route('brands.by.category', $category->slug) }}"
                        class="fw-bold text-dark fs-14 text-decoration-none hover-link">View All Brands</a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4">
    <div class="widget-header p-2">
        <h5 class="fw-bold mb-0">Capacity</h5>
    </div>
    <div class="widget-body px-2">
        <ul class="list-unstyled pt-1">
            @foreach($capacityTypes as $type)
                <li class="mb-1">
                    <a href="{{ url($prefix . '/' . $type['slug']) }}"
                        class="text-muted fs-14 text-decoration-none hover-link">
                        {{ $type['name'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4">
    <div class="widget-header p-2 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Capacity</h5>
        <a data-bs-toggle="collapse" href="#wattage-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill"></i>
        </a>
    </div>
    <div class="collapse show" id="wattage-collapse">
        <div class="widget-body px-2 scroll-container" style="max-height: 250px; overflow-y: auto;">
            <ul class="list-unstyled pt-1">
                @foreach($wattages as $watt)
                    <li class="mb-1">
                        <a href="{{ url($prefix . '/' . $watt . 'watt-chargers') }}"
                            class="text-muted fs-14 text-decoration-none hover-link">
                            {{ $watt }}Watt Chargers
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 shadow-sm rounded-4">
    <div class="widget-header p-2">
        <h5 class="fw-bold mb-0 text-dark">Watt & Type</h5>
    </div>
    <div class="widget-body px-2">
        <ul class="list-unstyled pt-1">
            @foreach($wattAndTypes as $item)
                <li class="mb-1">
                    <a href="{{ url($prefix . '/' . $item['slug']) }}"
                        class="text-muted fs-14 text-decoration-none hover-link">
                        {{ $item['name'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

@if(isset($filters) && $filters->isNotEmpty())
    <div class="widget-sidebar p-2 m-2 shadow-sm rounded-4">
        <div class="widget-header p-2 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Filters</h5>
            <a data-bs-toggle="collapse" href="#filters-collapse" role="button" aria-expanded="true" class="text-dark">
                <i class="bi bi-caret-up-fill"></i>
            </a>
        </div>
        <div class="collapse show" id="filters-collapse">
            <div class="widget-body px-2">
                <ul class="list-unstyled pt-1">
                    @foreach($filters as $filter)
                        <li class="mb-1">
                            <a href="{{$filter->url}}"
                                class="text-muted fs-14 text-decoration-none hover-link {{ str_contains(request()->url(), $filter->url) ? 'fw-bold text-dark' : '' }}">
                                {{$filter->title}}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

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