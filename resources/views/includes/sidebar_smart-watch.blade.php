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