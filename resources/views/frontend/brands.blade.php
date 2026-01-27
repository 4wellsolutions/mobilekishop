@php
    $layout = ($country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

@section('title', $metas->title)

@section('description', $metas->description)

@section("keywords","Mobiles prices, mobile specification, mobile phone features")

@section("canonical",$metas->canonical)

@section("og_graph") @stop

@section("noindex")
@if(str_contains(URL::full(), '?page='))
<meta name="robots" content="noindex">
@endif
@stop

@section("content")

@section("style") @stop
<main class="main container-lg">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <div class="my-1" style="font-size: 12px;">
                <ol class="breadcrumb pt-sm-1">
                    <!-- Home -->
                    <li class="breadcrumb-item">
                        <a href="{{ url('/' . ($country->country_code === 'pk' ? '' : $country->country_code)) }}" class="text-decoration-none text-secondary">
                            Home
                        </a>
                    </li>

                    @if (isset($category))
                        <!-- Category -->
                        <li class="breadcrumb-item">
                            <a href="{{ url(($country->country_code === 'pk' ? '' : $country->country_code) . '/category/'.$category->slug) }}" class="text-decoration-none text-secondary">
                                {{ Str::title($category->category_name) }}
                            </a>
                        </li>
                        <!-- Brand -->
                        <li class="breadcrumb-item text-secondary active" aria-current="page">
                            {{ Str::title($category->category_name) }} Brands
                        </li>
                    @else
                    <!-- Brand -->
                    <li class="breadcrumb-item text-secondary active" aria-current="page">
                       All Brands
                    </li>
                    @endif
                    
                </ol>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                <div class="col-12 col-md-3 pe-1">
                    @if(isset($category))
                    @include("includes.sidebar_".$category->slug, ['category' => $category])
                    @endif
                </div>
                <div class="col-12 col-md-9">
                    
                    <div class="row">
                        <h1 class="heading1 fs-4">{{$metas->h1}}</h1>
                    </div>
                    
                    <div class="row g-3">
                    @if(isset($category))
                        @if(!$brands->isEmpty())
                        @foreach($brands as $brand)
                            <div class="col-6 col-sm-4 col-md-3">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <a href="{{ $country->country_code === 'pk' ? route('brand.show', [$brand->slug, $category->slug]) : route('country.brand.show', [$country->country_code, $brand->slug, $category->slug]) }}" class="text-decoration-none text-secondary">
                                            <img src="{{$brand->thumbnail}}" width="155" height="65" alt="{{$brand->name}}" title="{{$brand->name}}">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @endif
                    @else
                        @if($categories->isNotEmpty())
                        @foreach($categories as $cat)

                            <div class="col-12">
                                <h2 class="fw-normal text-center my-3 border-bottom pb-2">{{ $cat->category_name }}</h2>
                            </div>
                            @if($cat->brands->isNotEmpty())
                                <div class="row g-4">
                                    @foreach($cat->brands as $brd)
                                    
                                        <div class="col-6 col-sm-4 col-md-3">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <div class="card-body text-center">
                                                    <a href="{{ $country->country_code === 'pk' ? route('brand.show', [$brd->slug, $cat->slug]) : route('country.brand.show', [$country->country_code, $brd->slug, $cat->slug]) }}" class="text-decoration-none text-secondary">
                                                        <img src="{{$brd->thumbnail}}" width="155" height="65" alt="{{$brd->name}}" title="{{$brd->name}}">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="col-12">
                                    <h2 class="text-uppercase fs-5 text-danger text-center">No Brand found</h2>
                                </div>
                            @endif
                        @endforeach
                        @endif
                    @endif
                    </div>
                    
                </div>
            
        </div><!-- End .container -->
    </main><!-- End .main -->
@stop

@section("script")

@stop

@section("style")

@stop
