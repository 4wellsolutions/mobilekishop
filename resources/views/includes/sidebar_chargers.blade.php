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

{{-- Categories --}}
<div class="sidebar-card">
    <h5 class="sidebar-title">Categories</h5>
    <ul class="sidebar-list">
        @foreach($mksCategories as $mksCat)
            <li>
                <a href="{{ url($prefix . '/category/' . $mksCat['slug']) }}"
                    class="{{ $category->slug == $mksCat['slug'] ? 'active' : '' }}">
                    {{ $mksCat['name'] }}
                </a>
            </li>
        @endforeach
    </ul>
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
            </ul>
        </div>
    </div>
</div>

{{-- Capacity Type --}}
<div class="sidebar-card">
    <h5 class="sidebar-title">Capacity</h5>
    <ul class="sidebar-list">
        @foreach($capacityTypes as $type)
            <li>
                <a href="{{ url($prefix . '/' . $type['slug']) }}">
                    {{ $type['name'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div>

{{-- Wattage --}}
<div class="sidebar-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="sidebar-title mb-0">Wattage</h5>
        <a data-bs-toggle="collapse" href="#wattage-collapse" role="button" aria-expanded="true" class="text-dark">
            <i class="bi bi-caret-up-fill small"></i>
        </a>
    </div>
    <div class="collapse show" id="wattage-collapse">
        <div class="scroll-container" style="max-height: 250px; overflow-y: auto;">
            <ul class="sidebar-list">
                @foreach($wattages as $watt)
                    <li>
                        <a href="{{ url($prefix . '/' . $watt . 'watt-chargers') }}">
                            {{ $watt }}Watt Chargers
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

{{-- Watt & Type --}}
<div class="sidebar-card">
    <h5 class="sidebar-title">Watt & Type</h5>
    <ul class="sidebar-list">
        @foreach($wattAndTypes as $item)
            <li>
                <a href="{{ url($prefix . '/' . $item['slug']) }}">
                    {{ $item['name'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div>

{{-- Filters --}}
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