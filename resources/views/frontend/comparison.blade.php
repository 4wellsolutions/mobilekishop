@php
    $layout = ($country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

@section('title', $metas->title)

@section('description', $metas->description)

@section("keywords", "Mobiles prices, mobile specification, mobile phone features")

@section("canonical", $metas->canonical)

@section("og_graph")
@endsection

@section("noindex")
    <meta name="robots" content="noindex">
@endsection

@section("content")
    <style type="text/css">
        .mobileImage {
            height: 150px !important;
        }

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

            50% {
                background-color: #ed6161;
            }

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

            100% {
                background-color: #3e8f15;
            }
        }

        .page-link {
            color: #000000 !important;
        }
    </style>
    <main class="main container-lg">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <div class="container">
                <ol class="breadcrumb pt-sm-1">
                    <li class="breadcrumb-item"><a href="{{URL::to('/')}}" class="text-decoration-none text-secondary">
                            <img src="{{URL::to('/images/icons/home.png')}}" alt="home-icon" width="16" height="16">
                        </a></li>
                    <li class="breadcrumb-item active text-secondary" aria-current="page">
                        {{isset($brand->name) ? Str::title($brand->name) : $metas->name}}
                    </li>
                </ol>
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-3 pe-1">
                    @include("includes.sidebar_" . $category->slug, ['category' => $category])
                </div>
                <div class="col-12 col-md-9">
                    <h1 class="fs-3 fw-bolder text-center">{{$metas->h1}}</h1>
                    <div class="row g-3 my-3" id="compareList" data-next-page="2">
                        @if(!$compares->isEmpty())
                            @foreach($compares as $compare)
                                @include("includes.compare-details", ["compare" => $compare])
                            @endforeach
                        @else
                            <h3>No Comparison Found.</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section("script")
    <script type="text/javascript">
        var baseUrl = "{!! Request::fullUrl() !!}";
        $(".select-filter").change(function () {
            $(".formFilter").submit();
        });
        $(document).ready(function () {
            var isLoading = false;
            var loadAfterProductNumber = 18;
            function loadData() {
                if (isLoading) return;
                var nextPage = $('#compareList').data('next-page');
                var separator = baseUrl.includes('?') ? '&' : '?';
                var urlWithPageParam = baseUrl + separator + "page=";
                isLoading = true;
                $.ajax({
                    url: urlWithPageParam + nextPage,
                    type: 'GET',
                    beforeSend: function () {
                        $('#loadingSpinner').show();
                    },
                    success: function (response) {
                        if (response.trim() === "") {
                            $(window).off('scroll');
                            $('#loadingSpinner').hide();
                            return;
                        }
                        $('#compareList').append(response);
                        $('#compareList').data('next-page', nextPage + 1);
                        isLoading = false;
                        $('#loadingSpinner').hide();
                        loadAfterProductNumber += 18;
                    },
                    error: function () {
                        isLoading = false;
                        $('#loadingSpinner').hide();
                    }
                });
            }
            function checkScrollPosition() {
                var $triggerProduct = $('.compareImage').eq(loadAfterProductNumber - 1);
                if ($triggerProduct.length) {
                    var topOfTriggerProduct = $triggerProduct.offset().top;
                    var bottomOfScreen = $(window).scrollTop() + $(window).height();
                    if (bottomOfScreen > topOfTriggerProduct && !isLoading) {
                        loadData();
                    }
                }
            }
            $(window).scroll(checkScrollPosition);
        });
    </script>
    <script type="application/ld+json">
            {
              "@@context": "https://schema.org/", 
              "@type": "BreadcrumbList", 
              "itemListElement": [{
                "@type": "ListItem", 
                "position": 1, 
                "name": "Home",
                "item": "{{url('/')}}/"  
              },{
                "@type": "ListItem", 
                "position": 2, 
                "name": "{{$metas->name}}",
                "item": "{{$metas->canonical}}"  
              }]
            }
            </script>
@endsection

@section("style")
    <style type="text/css">
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

        .fs-12 {
            font-size: 12px !important;
        }

        .fs-14 {
            font-size: 14px !important;
        }

        .fs-15 {
            font-size: 15px !important;
        }

        .fs-16 {
            font-size: 16px !important;
        }
    </style>
@endsection