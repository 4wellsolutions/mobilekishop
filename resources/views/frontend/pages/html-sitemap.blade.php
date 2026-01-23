@php
    $layout = ($country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

@section('title', $metas->title)

@section('description', $metas->description)

@section("keywords","Mobiles prices, mobile specification, mobile phone features")

@section("canonical",$metas->canonical)

@section("og_graph") @stop

@section("content")
<style type="text/css">
    .mobileImage{
        height:150px !important;
    }
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
<main class="main">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="fs-2 my-3">Sitemap Mobilekishop</h1>
            </div>
            @if($categories = App\Category::has('products')->get())
            @foreach($categories as $category)
            <div class="col-12 col-md-12 py-2">                
                <div class="col-12">
                    <h2 class="mb-2 text-center fs-4">
                        <a class="text-decoration-none text-dark" href="{{URL::to('/category/').'/'.$category->slug}}">{{$category->category_name}}</a>
                    </h2>
                    <div class="row">
                    @php
                    $categoryId = $category->id;
                    $countryId = $country->id;
                    $brands = \App\Brand::whereHas('products', function ($query) use ($categoryId, $countryId) {
                        $query->where('category_id', $categoryId)
                              ->whereHas('variants', function ($query) use ($countryId) {
                                  $query->where('country_id', $countryId)
                                        ->where('price', '>', 0);
                              });
                    })->get();
                    @endphp

                    @foreach($brands as $brand)
                    <div class="col-12">
                        <p class="mb-2 text-center bg-light py-2">
                            <a class="text-decoration-none text-dark fs-5" href="{{url("/brand/{$brand->slug}/" . ($category->slug ?? 'mobile-phones'))}}">{{$brand->name}}</a>
                        </p>
                    </div>
                        <div class="row">
                            @php
                            $products = \App\Product::whereHas('variants', function($query) use ($countryId) {
                                $query->where('country_id', $countryId)->where('price', '>', 0);
                            })->where("brand_id",$brand->id)->where("category_id",$category->id)->get();
                            @endphp
                            @foreach($products as $product)
                            <div class="col-12 col-sm-6 col-md-3">
                                <p class="mb-2">
                                    <a class="text-decoration-none text-dark" href="{{ url('/product/' . $product->slug) }}">{{Str::title($product->name)}}</a>
                                </p>
                            </div>
                            @endforeach
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>            
            @endforeach
            @endif
            <div class="row">
                @if($compares = App\Compare::all())
                <h2 class="fs-4 text-center"><a class="text-decoration-none text-dark" href="{{url('comparison')}}">Compares</a></h2>
                @foreach($compares as $compare)
                <div class="col-12 col-sm-6 col-md-3">
                    @php
                        // Extract the path from the full URL
                        $parsedUrl = parse_url($compare->link);
                        $relativeUrl = $parsedUrl['path'] ?? '';

                        // Generate the full URL using Laravel's url() helper
                        $fullUrl = url($relativeUrl);
                    @endphp
                    <p class="mb-2">
                        <a class="text-decoration-none text-dark" href="{{ $fullUrl }}">{!! Str::title(Str::of($compare->product1 . " VS ". $compare->product2 . ($compare->product3 ? " Vs ". $compare->product3 : ""))->replace('-', ' ')) !!}</a>
                    </p>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</main><!-- End .main -->

@stop