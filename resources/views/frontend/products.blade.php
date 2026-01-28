@php
    $layout = ($country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

@section('title', $metas->title)

@section('description', $metas->description)

@section("keywords", "Mobiles prices, mobile specification, mobile phone features")

@section("canonical", $metas->canonical)

@section("og_graph") @stop

@section("noindex")
@if(str_contains(URL::full(), '?page='))
    <meta name="robots" content="noindex">
@endif
@stop
@section("content")


<main class="main container-lg">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb pt-sm-1">
                <li class="breadcrumb-item"><a href="{{URL::to('/')}}" class="text-decoration-none text-secondary">
                        <img src="{{URL::to('/images/icons/home.png')}}" alt="home-icon" width="16" height="16">
                    </a></li>
                <li class="breadcrumb-item active text-secondary" aria-current="page">Products</li>
            </ol>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-12 col-md-3 pe-1">
                @include("frontend.sidebar_widget")
            </div>
            <div class="col-12 col-md-9">

                <div class="row">
                    <h1 class="heading1 pb-2 fs-4 text-center">{{$metas->h1}}</h1>
                </div>
                <form action="" method="get" class="formFilter mb-1">
                    <input type="hidden" name="filter" value="true">
                    <div class="row d-flex justify-content-between filter">
                        <div class="col-auto">
                            <div class="">
                                <label>Sort By:</label>
                                <div class="select-custom">
                                    <select name="orderby" id="sort_filter" class="select-filter form-control">
                                        <option value="" selected="selected" {{(Request::get('orderby') == 0) ? "selected" : ''}}>Default sorting</option>
                                        <option value="new" {{(Request::get('orderby') == "new") ? "selected" : ''}}>Sort
                                            by Latest</option>
                                        <option value="price_asc" {{(Request::get('orderby') == "price_asc") ? "selected" : ''}}>Sort by price: low to high</option>
                                        <option value="price_desc" {{(Request::get('orderby') == "price_desc") ? "selected" : ''}}>Sort by price: high to low</option>
                                    </select>
                                </div><!-- End .select-custom -->
                            </div>
                        </div>
                        <div class="col-auto my-auto">
                            <div class="border rounded-circle">
                                <img src="{{URL::to('/images/icons/filter.png')}}" class="img-fluid m-2"
                                    alt="filter-icon" style="cursor: pointer;" data-bs-toggle="collapse" href="#filter"
                                    role="button" aria-expanded="false" aria-controls="filter" width="30" height="30">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="collapse {{(new \Jenssegers\Agent\Agent())->isDesktop() ? 'show' : '' }} {{\Request::get('filter') == true ? 'show' : ''}}"
                            id="filter">
                            <div class="row mt-3">
                                <div class="col-6 col-md-4">
                                    <div class="select-filter">
                                        <label class="font-weight-bold">Year</label>
                                        <select class="select-filter form-control rounded py-1" name="year">
                                            <option value="">Select Year</option>
                                            <option value="2023" {{(Request::get('year') == 2023) ? "selected" : ''}}>2023
                                            </option>
                                            <option value="2022" {{(Request::get('year') == 2022) ? "selected" : ''}}>2022
                                            </option>
                                            <option value="2021" {{(Request::get('year') == 2021) ? "selected" : ''}}>2021
                                            </option>
                                            <option value="2020" {{(Request::get('year') == 2020) ? "selected" : ''}}>2020
                                            </option>
                                            <option value="2019" {{(Request::get('year') == 2019) ? "selected" : ''}}>2019
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                @if(!isset($brand))
                                    <div class="col-6 col-md-4">
                                        <div class="select-filter">
                                            <label class="font-weight-bold">Brand</label>
                                            <select class="select-filter form-control rounded py-1" name="brand_id">
                                                <option value="">Select Brand</option>
                                                @if($brands = App\Brand::limit(20)->get())
                                                    @foreach($brands as $brnd)
                                                        <option value="{{$brnd->id}}" {{(Request::get('brand_id') == $brnd->id) ? "selected" : ''}}>{{$brnd->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row my-2">
                    @if(!$products->isEmpty())
                        @foreach($products as $product)
                            <div class="col-6 col-sm-4 col-md-3">
                                <!-- appear-animate -->
                                <div class="p-2 text-center position-relative">
                                    @if(\Carbon\Carbon::now()->lte(optional($product->attributes()->where('attribute_id', 80)->first())->value))
                                        <div class="label-groups">
                                            <span class="product-label label-upcoming fs-10">Upcoming</span>
                                        </div>
                                    @elseif(\Carbon\Carbon::now()->subDays(90)->lte($product->attributes()->where('attribute_id', 80)->first()->value))
                                        <div class="label-groups group-new">
                                            <span class="product-label label-upcoming fs-10">New</span>
                                        </div>
                                    @endif
                                    <a href="{{route('product.show', [$product->brand->slug, $product->slug])}}">
                                        <img src="{{URL::to('/images/thumbnail.png')}}" data-echo="{{$product->thumbnail}}"
                                            alt="{{$product->slug}}" class="img-fluid mx-auto w-auto mobileImage" />
                                    </a>
                                    <div class="product-details">
                                        <div class="d-flex justify-content-between my-1">
                                            <div class="category">
                                                <a href="{{route('brand.show', [$product->brand->slug, $product->category->slug])}}"
                                                    class="text-dark fs-12">{{$product->brand->name}}</a>
                                            </div>
                                            @if(Auth::User())
                                                <a href="#" class="btn-icon-wish fs-14">
                                                    <img src="{{(Auth::User()->wishlists()->where('product_id', $product->id)->whereType(1)->first()) ? URL::to('/images/icons/heart-fill.png') : URL::to('/images/icons/heart.png') }}"
                                                        alt="heart" width="16" height="16">
                                                    <i class="fa-heart" data-id="{{$product->id}}" data-type="0"></i></a>
                                            @else
                                                <a href="#" class="btn-icon-wish fs-14">
                                                    <img src="{{URL::to('/images/icons/heart.png')}}" data-id="{{$product->id}}"
                                                        data-type="0" alt="heart" width="16" height="16">
                                                </a>
                                            @endif
                                        </div>
                                        <a href="{{route('product.show', [$product->brand->slug, $product->slug])}}"
                                            class="text-dark text-decoration-none">
                                            <h2 class="mx-auto fs-16 fw-normal">
                                                {{Str::title($product->name)}}
                                            </h2>
                                        </a>
                                        <div class="price-box mx-auto">
                                            <span
                                                class="fw-bold text-danger">Rs.{{number_format($product->price_in_pkr)}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <h3>No Product Found.</h3>
                    @endif
                </div>

                <div class="row">
                    <div class="col-auto mx-auto">
                        <nav class="toolboxs toolbox-paginations my-3 text-center">
                            {{ $products->withQueryString()->links() }}
                        </nav>
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




@section("style") @stop

@section("script")
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
@stop

@section("style")

@stop