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

<div class="widget-sidebar p-2 m-2">
    <div class="widget-title">
        <a class="p-2 d-block text-uppercase fw-bold" data-bs-toggle="collapse" href="#categories" role="button"
            aria-expanded="false" aria-controls="categories">
            Categories <span class="float-end"><i class="bi bi-chevron-down"></i></span>
        </a>
    </div>
    <div class="collapse show" id="categories">
        <div class="widget-body">
            <ul class="list-unstyled ps-2 pt-2">
                @foreach($mksCategories as $mksCat)
                    <li><a href="{{ url($prefix . '/category/' . $mksCat['slug']) }}">{{ $mksCat['name'] }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2">
    <div class="widget-title">
        <a class="p-2 d-block text-uppercase fw-bold" data-bs-toggle="collapse" href="#network" role="button"
            aria-expanded="false" aria-controls="network">
            Network <span class="float-end"><i class="bi bi-chevron-down"></i></span>
        </a>
    </div>
    <div class="collapse show" id="network">
        <div class="widget-body">
            <ul class="list-unstyled ps-2 pt-2">
                <li><a href="{{ url($prefix . '/4g-mobile-phones') }}">4G</a></li>
                <li><a href="{{ url($prefix . '/5g-mobile-phones') }}">5G</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2">
    <div class="widget-title">
        <a class="p-2 d-block text-uppercase fw-bold" data-bs-toggle="collapse" href="#brands" role="button"
            aria-expanded="false" aria-controls="brands">
            Brands <span class="float-end"><i class="bi bi-chevron-down"></i></span>
        </a>
    </div>
    <div class="collapse show" id="brands">
        <div class="widget-body">
            <ul class="list-unstyled ps-2 pt-2">
                @foreach($category->brands as $brand)
                    <li>
                        <a
                            href="{{ route(($pk ? '' : 'country.') . 'brand.show', ($pk ? [$brand->slug, $category->slug] : ['country_code' => $country->country_code, 'brand' => $brand->slug, 'categorySlug' => $category->slug])) }}">
                            {{$brand->name}}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2">
    <div class="widget-title">
        <a class="p-2 d-block text-uppercase fw-bold" data-bs-toggle="collapse" href="#price" role="button"
            aria-expanded="false" aria-controls="price">
            Price Range <span class="float-end"><i class="bi bi-chevron-down"></i></span>
        </a>
    </div>
    <div class="collapse show" id="price">
        <div class="widget-body">
            <form action="{{URL::current()}}">
                @if($filters->isNotEmpty())
                    @foreach($filters as $key => $value)
                        <input type="hidden" name="{{$key}}" value="{{$value}}">
                    @endforeach
                @endif
                <div class="row g-2 align-items-center mb-2">
                    <div class="col-4"><input type="number" class="form-control form-control-sm" name="min"
                            placeholder="Min" value="{{request('min')}}"></div>
                    <div class="col-4"><input type="number" class="form-control form-control-sm" name="max"
                            placeholder="Max" value="{{request('max')}}"></div>
                    <div class="col-4"><button type="submit" class="btn btn-sm btn-primary w-100">Go</button></div>
                </div>
            </form>
            <ul class="list-unstyled ps-2 pt-2">
                @foreach($priceRanges as $range)
                    @php
                        $max = is_object($range) ? $range->max : $range;
                    @endphp
                    <li><a href="{{ url($prefix . '/mobile-phones-under-' . $max) }}">Mobile Phones Under
                            {{ number_format($max) }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2">
    <div class="widget-title">
        <a class="p-2 d-block text-uppercase fw-bold" data-bs-toggle="collapse" href="#type" role="button"
            aria-expanded="false" aria-controls="type">
            Phone Type <span class="float-end"><i class="bi bi-chevron-down"></i></span>
        </a>
    </div>
    <div class="collapse show" id="type">
        <div class="widget-body">
            <ul class="list-unstyled ps-2 pt-2">
                @foreach($phoneTypes as $type)
                    <li><a href="{{ url($prefix . '/' . $type['slug'] . '-mobile-phones') }}">{{ $type['name'] }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2">
    <div class="widget-title">
        <a class="p-2 d-block text-uppercase fw-bold" data-bs-toggle="collapse" href="#combination" role="button"
            aria-expanded="false" aria-controls="combination">
            Combination <span class="float-end"><i class="bi bi-chevron-down"></i></span>
        </a>
    </div>
    <div class="collapse show" id="combination">
        <div class="widget-body">
            <ul class="list-unstyled ps-2 pt-2">
                @foreach($combinations as $comb)
                    <li><a
                            href="{{ url($prefix . '/mobile-phones-with-' . $comb['ram'] . 'gb-ram-' . $comb['rom'] . 'gb-storage') }}">{{ $comb['ram'] }}GB
                            + {{ $comb['rom'] }}GB</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2">
    <div class="widget-title">
        <a class="p-2 d-block text-uppercase fw-bold" data-bs-toggle="collapse" href="#processor" role="button"
            aria-expanded="false" aria-controls="processor">
            Processor <span class="float-end"><i class="bi bi-chevron-down"></i></span>
        </a>
    </div>
    <div class="collapse show" id="processor">
        <div class="widget-body">
            <ul class="list-unstyled ps-2 pt-2">
                @foreach($processors as $proc)
                    <li><a href="{{ url($prefix . '/' . $proc['slug'] . '-mobile-phones') }}">{{ $proc['name'] }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2">
    <div class="widget-title">
        <a class="p-2 d-block text-uppercase fw-bold" data-bs-toggle="collapse" href="#ram" role="button"
            aria-expanded="false" aria-controls="ram">
            RAM <span class="float-end"><i class="bi bi-chevron-down"></i></span>
        </a>
    </div>
    <div class="collapse show" id="ram">
        <div class="widget-body">
            <ul class="list-unstyled ps-2 pt-2">
                @foreach($ramLimits as $ramLimit)
                    <li><a href="{{ url($prefix . '/mobile-phones-' . $ramLimit . 'gb-ram') }}">{{$ramLimit}} GB</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2">
    <div class="widget-title">
        <a class="p-2 d-block text-uppercase fw-bold" data-bs-toggle="collapse" href="#rom" role="button"
            aria-expanded="false" aria-controls="rom">
            Storage <span class="float-end"><i class="bi bi-chevron-down"></i></span>
        </a>
    </div>
    <div class="collapse show" id="rom">
        <div class="widget-body">
            <ul class="list-unstyled ps-2 pt-2">
                @foreach($romLimits as $romLimit)
                    <li><a
                            href="{{ url($prefix . '/mobile-phones-' . ($romLimit >= 1024 ? ($romLimit / 1024) . 'tb' : $romLimit . 'gb-storage')) }}">{{$romLimit >= 1024 ? ($romLimit / 1024) . ' TB' : $romLimit . ' GB'}}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2">
    <div class="widget-title">
        <a class="p-2 d-block text-uppercase fw-bold" data-bs-toggle="collapse" href="#screen" role="button"
            aria-expanded="false" aria-controls="screen">
            Screen Size <span class="float-end"><i class="bi bi-chevron-down"></i></span>
        </a>
    </div>
    <div class="collapse show" id="screen">
        <div class="widget-body">
            <ul class="list-unstyled ps-2 pt-2">
                @foreach($screenSizes as $size)
                    <li><a href="{{ url($prefix . '/mobile-phones-screen-' . $size . '-inch') }}">{{ $size }} Inch</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2">
    <div class="widget-title">
        <a class="p-2 d-block text-uppercase fw-bold" data-bs-toggle="collapse" href="#camera" role="button"
            aria-expanded="false" aria-controls="camera">
            Camera <span class="float-end"><i class="bi bi-chevron-down"></i></span>
        </a>
    </div>
    <div class="collapse show" id="camera">
        <div class="widget-body">
            <ul class="list-unstyled ps-2 pt-2">
                @foreach($cameras as $cam)
                    <li><a href="{{ url($prefix . '/mobile-phones-' . $cam . '-camera') }}">{{ ucfirst($cam) }} Camera</a>
                    </li>
                @endforeach
                @foreach($cameraMp as $mp)
                    <li><a href="{{ url($prefix . '/mobile-phones-' . $mp . '-camera') }}">{{ $mp }} MP Camera</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>