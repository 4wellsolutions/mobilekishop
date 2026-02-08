@extends('layouts.frontend')

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
        <div class="my-1" style="font-size: 12px;">
            <ol class="breadcrumb pt-sm-1">
                <li class="breadcrumb-item"><a
                        href="{{ url('/' . ($country->country_code === 'pk' ? '' : $country->country_code)) }}"
                        class="text-decoration-none text-secondary">
                        Home
                    </a></li>
                @if($category)
                    <li class="breadcrumb-item"><a
                            href="{{ url(($country->country_code === 'pk' ? '' : $country->country_code) . '/category/' . $category->slug) }}"
                            class="text-decoration-none text-secondary">
                            {{ Str::title($category->category_name) }}
                        </a></li>
                @endif
                <li class="breadcrumb-item active text-secondary" aria-current="page">
                    {{ Str::title($brand->name) }}
                </li>
            </ol>
        </div>
    </nav>

    <div class="">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-3 pe-1">
                @if($category)
                    @include("includes.sidebar_" . $category->slug, ['category' => $category])
                @else
                    @include("includes.sidebar_mobile-phones")
                @endif
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
                                <x-product-card :product="$product" :country="$country" />
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

@section("style") @stop

@section("script")
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
       "name": "Home",
       "item": "{{ url('/' . ($country->country_code === 'pk' ? '' : $country->country_code)) }}"
     },
    @if($category)
        {
          "@type": "ListItem",
          "position": 2,
           "name": "{{ Str::title($category->category_name) }}",
           "item": "{{ url(($country->country_code === 'pk' ? '' : $country->country_code) . '/category/' . $category->slug) }}"
         },
    @endif
    {
      "@type": "ListItem",
      "position": {{ (isset($category) && $category) ? 3 : 2 }},
      "name": "{{ Str::title($brand->name ?? 'Brand') }}",
      "item": "{{ url(($country->country_code === 'pk' ? '' : $country->country_code) . '/brand/' . ($brand->slug ?? '') . ((isset($category) && $category) ? '/' . $category->slug : '/all')) }}"
    }
  ]
}
</script>
@stop