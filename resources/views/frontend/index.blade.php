@php
    $layout = ($country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

@section('title',$metas->title)
@section('description',$metas->description)
@section("keywords","Mobiles prices, mobile specification, mobile phone features ")
@section("canonical",\URL::full())
@section("og_graph") @stop

@section("content")
<main class="main container-lg">
    <div class="container my-3">
        <div class="row">
            <div class="col-12 col-md-3 col-lg-3  d-none d-md-block">
              @php 
                $category = \App\Category::find(1);
              @endphp
              @include("includes.sidebar_".$category->slug, ['category' => $category])
            </div>
            
            <div class="col-12 col-md-9 col-lg-9">
              <div class="col-12 text-center my-2">
                <h1 class="fs-2">Mobile Ki Shop</h1>

                  <p>Mobilekishop is the best mobile website that provides the latest {{ $categories->pluck('category_name')->implode(', ') }} prices, reviews and specifications in {{$country->country_name}} with specifications, features, reviews and comparison.</p>
              </div>
              @foreach ($categories as $category)
                <section class="my-3 my-md-4">
                    <div class="col-lg-12 col-md-12 m-b-3">
                        <h2 class="pb-2 fs-4 text-center"><u>Latest {{$category->category_name}}</u></h2>
                        <div class="row">
                            @if ($category->latest_products->isNotEmpty())
                                @foreach ($category->latest_products as $product)
                                    <div class="col-6 col-sm-4 col-md-3">
                                        @include('includes.product-details', ['product' => $product])
                                    </div>
                                @endforeach
                            @else
                                <p class="text-center">No products available in this category.</p>
                            @endif
                        </div>
                        <p class="text-end mt-2"><a href="{{url('/category/'.$category->slug)}}" class="fs-6 text-dark">View All</a></p>
                    </div>
                </section>
              @endforeach

              <div class="row">
                  @if($page = App\Page::whereSlug(\Request::fullUrl())->first())
                      {!! $page->body !!}
                  @endif
              </div>

            </div>
        </div>      
    </div>
</main>
@stop
