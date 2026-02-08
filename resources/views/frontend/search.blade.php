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
  <div class="container my-3">
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
  </div>
</main>
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