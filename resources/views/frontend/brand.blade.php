@php
    $layout = ($country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

@section('title', $metas->title)

@section('description', $metas->description)

@section("keywords", "Mobiles prices, mobile specification, mobile phone features")

@section("canonical", $metas->canonical)

@section("og_graph") @stop

@section("noindex", )
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
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
       "name": "Home",
       "item": "{{ url('/' . ($country->country_code === 'pk' ? '' : $country->country_code)) }}"
     },
    {
      "@type": "ListItem",
      "position": 2,
       "name": "{{ Str::title($category->category_name) }}",
       "item": "{{ url(($country->country_code === 'pk' ? '' : $country->country_code) . '/category/' . $category->slug) }}"
     },
    {
      "@type": "ListItem",
      "position": 3,
       "name": "{{ Str::title($brand->name) }}",
       "item": "{{ url(($country->country_code === 'pk' ? '' : $country->country_code) . '/brand/' . $brand->slug . '/' . $category->slug) }}"
     }
  ]
}
</script>
@stop

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
    {
      "@type": "ListItem",
      "position": 2,
       "name": "{{ Str::title($category->category_name) }}",
       "item": "{{ url(($country->country_code === 'pk' ? '' : $country->country_code) . '/category/' . $category->slug) }}"
     },
    {
      "@type": "ListItem",
      "position": 3,
       "name": "{{ Str::title($brand->name) }}",
       "item": "{{ url(($country->country_code === 'pk' ? '' : $country->country_code) . '/brand/' . $brand->slug . '/' . $category->slug) }}"
     }
  ]
}
</script>

@stop