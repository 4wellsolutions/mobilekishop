@extends('layouts.frontend')

@section("title", $metas->title)
@section("description", $metas->description)
@section("canonical", $metas->canonical)

@section("content")
    <main class="max-w-[1400px] mx-auto px-4 md:px-6 lg:px-8 py-4">
        <h1 class="text-lg font-bold text-text-main mb-2">{{ $metas->h1 }}</h1>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 my-2" id="productList" data-next-page="2">
            @if(!$products->isEmpty())
                @foreach($products as $product)
                    <x-product-card :product="$product" :country="$country" />
                @endforeach
            @else
                @include("includes.product-not-found")
            @endif
        </div>

        @include("includes.page-body")
    </main>
@endsection