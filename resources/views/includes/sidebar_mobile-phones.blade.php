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
                    <li><a href="{{ url('/brand/' . $brand->slug . '/' . $category->slug) }}">{{$brand->name}}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="widget-sidebar p-2 m-2">
    <div class="widget-title">
        <a class="p-2 d-block text-uppercase fw-bold" data-bs-toggle="collapse" href="#filter" role="button"
            aria-expanded="false" aria-controls="filter">
            Price Range <span class="float-end"><i class="bi bi-chevron-down"></i></span>
        </a>
    </div>
    <div class="collapse show" id="filter">
        <div class="widget-body">
            <form action="{{URL::current()}}">
                @if($filters->isNotEmpty())
                    @foreach($filters as $key => $value)
                        <input type="hidden" name="{{$key}}" value="{{$value}}">
                    @endforeach
                @endif
                <div class="row g-2 align-items-center">
                    <div class="col-4">
                        <input type="number" class="form-control form-control-sm" name="min" placeholder="Min"
                            value="{{request('min')}}">
                    </div>
                    <div class="col-4">
                        <input type="number" class="form-control form-control-sm" name="max" placeholder="Max"
                            value="{{request('max')}}">
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-sm btn-primary w-100">Go</button>
                    </div>
                </div>
            </form>
            <ul class="list-unstyled ps-2 pt-2">
                {{-- @foreach($priceRanges as $range)
                <li><a
                        href="{{request()->fullUrlWithQuery(['min' => $range->min, 'max' => $range->max])}}">{{$range->label}}</a>
                </li>
                @endforeach --}}
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
                {{-- @foreach($ramLimits as $ramLimit)
                    <li><a href="{{request()->fullUrlWithQuery(['ram_in_gb' => $ramLimit])}}">{{$ramLimit}} GB</a></li>
                @endforeach --}}
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
                {{-- @foreach($romLimits as $romLimit)
                    <li><a href="{{request()->fullUrlWithQuery(['rom_in_gb' => $romLimit])}}">{{$romLimit}} GB</a></li>
                @endforeach --}}
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
                @php
                    $cameraRange = [5, 8, 12, 13, 16, 20, 32, 48, 64, 108];
                @endphp
                @foreach($cameraRange as $range)
                    <li><a href="{{request()->fullUrlWithQuery(['pixels' => $range])}}">{{$range}} MP</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<style>
    .widget-sidebar {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }

    .widget-title a {
        color: #333;
        text-decoration: none;
    }

    .widget-body ul li {
        margin-bottom: 5px;
    }

    .widget-body ul li a {
        color: #666;
        text-decoration: none;
        font-size: 14px;
    }

    .widget-body ul li a:hover {
        color: #0d6efd;
    }
</style>