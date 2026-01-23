@php
    $layout = ($country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

@section('title', $metas->title)

@section('description', $metas->description)

@section("keywords", "Mobiles prices, mobile specification, mobile phone features")

@section("canonical", $metas->canonical)

@section("og_graph") @stop

@section("content")

<style type="text/css">
    .offcanvas-backdrop {
        background: #FFF !important;
    }

    .widget a {
        text-decoration: none !important;
        color: #777;
    }

    .widget-title a {
        text-decoration: none !important;
        font-family: Poppins, sans-serif;
        color: #343a40;
        font-size: 18px;
    }

    .widget {
        border-bottom: 1px solid #e7e7e7;
        border-bottom-width: 1px;
        border-bottom-style: solid;
        border-bottom-color: rgb(231, 231, 231);
        border: 1px solid #dee2e6 !important;
        margin-top: 5px;
        margin-right: 5px;
        margin-left: 5px;
    }

    .widget-body li a {
        font-size: 14px;
    }

    .nav-tabs.nav-item a {
        text-decoration: none !important;
        color: #343a40;
    }

    .nav-tabs.nav-tabs .nav-link {
        color: #31343a;
        border: none;
    }

    .nav-tabs .nav-item.show .nav-link,
    .nav-tabs .nav-link.active {
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
        color: black !important;
        border-bottom: 2px solid #000000 !important;
    }

    .nav-tabs.nav-link:hover {
        border-bottom: 2px solid #000000 !important;
    }

    h1,
    .h1,
    h2,
    .h2,
    h3,
    .h3,
    h4,
    .h4,
    h5,
    .h5,
    h6,
    .h6 {
        font-weight: 700;
        line-height: 1.1;
        font-family: Poppins, sans-serif;
    }

    body {
        font-family: "Open Sans", sans-serif;
    }


    @media(max-width: 576px) {
        .mobileImage {
            width: 155px !important;
            height: 205px !important;
        }

        .cameraBlock {
            border: none !important;
            border-bottom: 1px solid #dee2e6 !important;
        }

        .screenBlock {
            border-bottom: 1px solid #dee2e6 !important;
        }

        .mobileTable tr td:first-child {
            display: none;
        }

        .mobileTable tr th {
            color: #dc3545 !important
        }

        .table {
            font-size: .8rem;
        }

        .imgDiv {
            height: 120px;
        }

        .detailDiv {
            height: 120px;
        }

        .product-title {
            font-size: 14px;
            font-weight: normal;
        }

        .product-price {
            font-size: 16px;
        }
    }

    @media(min-width: 577px) {
        .mobileImage {
            width: 160px !important;
            height: 212px !important;
        }

        .imgDiv {
            height: 130px;
        }

        .detailDiv {
            height: 130px;
        }

        .product-title {
            font-size: 18px;
            font-weight: normal;
        }

        .product-price {
            font-size: 20px;
        }
    }

    .nav-tabs .nav-link {
        font-size: .9rem;
        padding-right: 7px;
        padding-left: 7px;
    }

    .mobile_image {
        max-height: 160px;
        width: auto;
    }

    .product-title>a {
        text-decoration: none;
    }

    .category>a {
        text-decoration: none;
    }

    .product-label {
        animation: label-groups 2s infinite;
        padding: 3px 6px;
        background-color: #fe5858;
        font-size: 11px;
        color: white;
        border-radius: 20px;
    }

    .label-groups {
        position: absolute;
        top: -0.3rem;
        right: 1.0rem;
    }

    @keyframes label-groups {
        0% {
            background-color: #ed6161
        }

        /*25%     { background-color: #1056c0;}*/
        50% {
            background-color: #ed6161;
        }

        /*75%     { background-color: #254878;}*/
        100% {
            background-color: #7661ed;
        }
    }

    .product-default .group-new .product-label {
        font-size: 8px;
        animation: group-new 2s infinite;
    }

    @keyframes group-new {
        0% {
            background-color: #8abf6f
        }

        /*25%     { background-color: #1056c0;}*/
        /*50%     { background-color: #ed6161;}*/
        /*75%     { background-color: #254878;}*/
        100% {
            background-color: #3e8f15;
        }
    }

    .page-link {
        color: #000000 !important;
    }

    .filter-select {
        height: 4rem !important;
    }

    .icon-angle-right {
        background: #928989ad;
        margin-left: 10px;
        padding-left: 15px !important;
        padding-right: 12px !important;
        padding-bottom: 3px !important;
    }

    .icon-angle-left {
        background: #928989ad;
        margin-left: 10px;
        padding-left: 12px !important;
        padding-right: 15px !important;
        padding-bottom: 3px !important;
    }

    .select-filter:after {
        margin-top: 8px !important;
    }

    #sort_filter:after {
        margin-top: -1px !important;
    }
</style>
<main class="main container-lg">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="my-1" style="font-size: 12px;">
            <ol class="breadcrumb pt-sm-1">
                <li class="breadcrumb-item"><a href="{{URL::to('/')}}" class="text-decoration-none text-secondary">
                        Home
                    </a></li>
                <li class="breadcrumb-item active text-secondary" aria-current="page">
                    {{isset($category) ? Str::title($category->category_name) : $metas->name}}
                </li>
            </ol>
        </div>
    </nav>

    <div class="">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-3 pe-1">
                @include("includes.sidebar_" . $category->slug, ['category' => $category])
            </div>
            <div class="col-12 col-md-8 col-lg-9 pe-1">

                <div class="row">
                    <h1 class="heading1 fs-4">{{$metas->h1}}</h1>
                </div>
                @include("includes.filters")
                <div class="row my-2" id="productList" data-next-page="2">
                    @if(!$products->isEmpty())
                        @foreach($products as $product)
                            <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                                @include('includes.product-details', ['product' => $product])
                            </div>
                        @endforeach
                    @else
                        @include('includes.product-not-found')
                    @endif
                </div>

                <div id="dynamicContentEnd"></div>
                <div id="loadingSpinner" class="text-center" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="row">
                    {!! isset($metas->body) ? $metas->body : '' !!}
                    {!! isset($brand->body) ? $brand->body : '' !!}
                </div>

            </div>

        </div><!-- End .container -->
</main><!-- End .main -->
@stop