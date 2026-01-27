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

<style type="text/css">
    
    .offcanvas-backdrop{
        background: #FFF!important;
    }
    .widget a{
        text-decoration: none !important;
        color: #777;
    }
    .widget-title a{
        text-decoration: none !important;
        font-family: Poppins,sans-serif;
        color: #343a40;
        font-size: 18px;
    }
    .widget{
        border-bottom: 1px solid #e7e7e7;
        border-bottom-width: 1px;
        border-bottom-style: solid;
        border-bottom-color: rgb(231, 231, 231);
        border: 1px solid #dee2e6!important;
        margin-top: 5px;
        margin-right: 5px;
        margin-left: 5px;
    }
    .widget-body li a{
        font-size: 14px;
    }
    .nav-tabs.nav-item a{
        text-decoration: none !important;
        color: #343a40;
    }
    .nav-tabs.nav-tabs .nav-link{
        color: #31343a;
        border: none;
    }
    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
        color: black !important;
        border-bottom: 2px solid #000000 !important;
    }
    .nav-tabs.nav-link:hover{
        border-bottom: 2px solid #000000 !important;
    }
    h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6 {
        font-weight: 700;
        line-height: 1.1;
        font-family: Poppins,sans-serif;
    }
    body{
        font-family: "Open Sans",sans-serif;
    }
    @media(max-width: 576px){
        .cameraBlock{
            border: none !important;
            border-bottom: 1px solid #dee2e6!important;
        }
        .screenBlock{
            border-bottom: 1px solid #dee2e6!important;
        }
        .mobileTable tr td:first-child{ 
            display: none; 
        }
        .mobileTable tr th{
            color: #dc3545!important
        }
        .table{
            font-size: .8rem;
        }
        .imgDiv{
            height: 120px;
        }
        .detailDiv{
            height: 120px;
        }

        .product-title{
            font-size: 14px;
            font-weight: normal;
        }
        .product-price{
            font-size: 16px;
        }
    }
    @media(min-width: 577px){
        .imgDiv{
            height: 130px;
        }
        .detailDiv{
            height: 130px;
        }
        .product-title{
            font-size: 18px;
            font-weight: normal;
        }
        .product-price{
            font-size: 20px;
        }
    }
    .nav-tabs .nav-link{
        font-size: .9rem;
        padding-right: 7px;
        padding-left: 7px;
    }
    .mobile_image{
        max-height: 160px;
        width: auto;
    }
    .product-title > a{
        text-decoration: none;
    }
    .category > a{
        text-decoration: none;
    }
    .product-label{
        animation: label-groups 2s infinite;
        padding: 3px 6px;
        background-color: #fe5858;
        font-size: 11px;
        color: white;
        border-radius: 20px;
    }
    .label-groups{
        position: absolute;
        top: -0.3rem;
        right: 1.0rem;
    }
    @keyframes label-groups{
        0%      { background-color: #ed6161}
        /*25%     { background-color: #1056c0;}*/
        50%     { background-color: #ed6161;}
        /*75%     { background-color: #254878;}*/
        100%    { background-color: #7661ed;}
    }
    .product-default .group-new .product-label{
        font-size: 8px;
        animation: group-new 2s infinite;
    }
    @keyframes group-new{
        0%      { background-color: #8abf6f}
        /*25%     { background-color: #1056c0;}*/
        /*50%     { background-color: #ed6161;}*/
        /*75%     { background-color: #254878;}*/
        100%    { background-color: #3e8f15;}
    }
    .page-link {
        color: #000000 !important;
    }
</style>
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
