<div class="widget-sidebar p-2 m-2">
    <p class="widget-title fw-bold mb-1">
        <a data-toggle="collapse" href="#widget-body-1" role="button" aria-expanded="true"
            aria-controls="widget-body-1">Categories</a>
    </p>

    <div class="collapse show" id="widget-body-1">
        <div class="widget-body">
            <ul class="list-unstyled ps-2">
                @foreach($categories as $categori)
                    <li>
                        <a href="{{route('category.show', $categori->slug)}}"
                            class="{{ isset($category) ? ($category->slug == $categori->slug ? 'fw-bold' : '') : (Request::segment(2) == $categori->slug ? 'fw-bold' : '') }}">{{$categori->category_name}}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 brandWidget">
    <p class="widget-title fw-bold mb-1">
        <a data-toggle="collapse" href="#widget-body-22" role="button" aria-expanded="true"
            aria-controls="widget-body-22">Brands</a>
    </p>

    <div class="collapse show" id="widget-body-22">
        <div class="widget-body p-0">
            <ul class="list-unstyled ps-2">
                @foreach($brands as $brnd)
                    <li><a href="{{route('brand.show', [$brnd->slug, $category->slug ?? null])}}" class="{{Request::is("brand/" . $brnd->slug) ? "fw-bold" : ''}}>{{Str::title($brnd->name)}}</a></li>
                @endforeach
                <li><a href=" {{route('brands.by.category', $category->slug)}}" class="fw-bold">View All Brands</a></li>
            </ul>
        </div>
    </div>
</div>

@if(isset($filters) && $filters->isNotEmpty())
    <div class="widget-sidebar p-2 m-2">
        <p class="widget-title fw-bold mb-1">
            <a data-toggle="collapse" href="#widget-body-4" role="button" aria-expanded="true"
                aria-controls="widget-body-4">Filters</a>
        </p>
        <div class="collapse show" id="widget-body-4">
            <div class="widget-body">
                <ul class="list-unstyled p-2">
                    @foreach($filters as $filter)
                        <li>
                            <a href="{{$filter->url}}"
                                class="{{ str_contains(request()->url(), $filter->url) ? 'fw-bold' : '' }}">{{$filter->title}}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<div class="widget-sidebar p-2 m-2">
    <p class="widget-title fw-bold mb-1">
        <a data-toggle="collapse" href="#widget-body-3" role="button" aria-expanded="true"
            aria-controls="widget-body-3">Price</a>
    </p>
    <div class="collapse show" id="widget-body-3">
        <div class="widget-body">
            <ul class="list-unstyled p-2">
                @if(isset($priceRanges))
                    @foreach($priceRanges as $priceLimit)
                        <li>
                            <a href="{{route('tablet.under.amount', $priceLimit)}}">Tablets Under
                                {{number_format($priceLimit)}}</a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2">
    <p class="widget-title fw-bold mb-1">
        <a data-toggle="collapse" href="#widget-body-4" role="button" aria-expanded="true"
            aria-controls="widget-body-4">RAM</a>
    </p>
    <div class="collapse show" id="widget-body-4">
        <div class="widget-body">
            <ul class="list-unstyled p-2">
                @php
                    $ramLimits = array_merge(
                        range(2, 4, 1),
                        range(6, 8, 2),
                        range(12, 16, 4)
                    );
                @endphp
                @foreach($ramLimits as $ramLimit)
                    <li><a href="{{route('tablet.ram', $ramLimit)}}" {{Request::is('tablets-' . $ramLimit . 'gb-ram') ? "class=fw-bold" : ''}}>{{$ramLimit}}GB RAM</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2">
    <p class="widget-title fw-bold mb-1">
        <a data-toggle="collapse" href="#widget-body-5" role="button" aria-expanded="true"
            aria-controls="widget-body-5">Storage</a>
    </p>
    <div class="collapse show" id="widget-body-5">
        <div class="widget-body">
            <ul class="list-unstyled p-2">
                @php
                    $romLimits = array_merge(
                        range(16, 32, 16),
                        range(64, 128, 64),
                        range(256, 256, 128),
                        range(512, 1024, 512),
                    );
                @endphp
                @foreach($romLimits as $romLimit)
                    <li><a href="{{route('tablet.rom', $romLimit)}}" {{Request::is('tablets-' . $romLimit . 'gb-storage') ? "class=fw-bold" : ''}}>{{$romLimit}}GB ROM</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2">
    <p class="widget-title fw-bold mb-1">
        <a data-toggle="collapse" href="#widget-body-6" role="button" aria-expanded="true"
            aria-controls="widget-body-6">Screen Size</a>
    </p>
    <div class="collapse show" id="widget-body-6">
        <div class="widget-body">
            <ul class="list-unstyled p-2">
                <li><a href="{{route('tablet.screen', [8])}}" {{Request::is('tablets-screen-8-inch') ? "class=fw-bold" : ''}}>8 Inch Display</a></li>
                <li><a href="{{route('tablet.screen', [10])}}" {{Request::is('tablets-screen-10-inch') ? "class=fw-bold" : ''}}>10 Inch Display</a></li>
                <li><a href="{{route('tablet.screen', [12])}}" {{Request::is('tablets-screen-12-inch') ? "class=fw-bold" : ''}}>12 Inch Display</a></li>
                <li><a href="{{route('tablet.screen', [14])}}" {{Request::is('tablets-screen-14-inch') ? "class=fw-bold" : ''}}>14 Inch Display</a></li>
                <li><a href="{{route('tablet.screen', [16])}}" {{Request::is('tablets-screen-16-inch') ? "class=fw-bold" : ''}}>16 Inch Display</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2">
    <p class="widget-title fw-bold mb-1">
        <a data-toggle="collapse" href="#widget-body-7" role="button" aria-expanded="false"
            aria-controls="widget-body-7">Camera</a>
    </p>
    <div class="collapse show" id="widget-body-7">
        <div class="widget-body">
            <ul class="list-unstyled p-2">
                <li><a href="{{route('tablet.camera', [5])}}" {{Request::is('tablets-5mp-camera') ? "class=fw-bold" : ''}}>5MP Camera</a></li>
                <li><a href="{{route('tablet.camera', [13])}}" {{Request::is('tablets-13mp-camera') ? "class=fw-bold" : ''}}>13MP Camera</a></li>
                <li><a href="{{route('tablet.camera', [24])}}" {{Request::is('tablets-24mp-camera') ? "class=fw-bold" : ''}}>24MP Camera</a></li>
            </ul>
        </div>
    </div>
</div>