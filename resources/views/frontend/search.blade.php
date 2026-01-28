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
@stop