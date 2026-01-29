@extends("layouts.frontend")

@section('title', 'Wishlist - MKS')

@section('description') @stop

@section("keywords") @stop

@section("canonical") @stop

@section("og_graph") @stop


@section("content")
<main class="main container-lg">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="my-1" style="font-size: 12px;">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{URL::to('/')}}" class="text-decoration-none text-secondary">
                        <img src="{{URL::to('/images/icons/home.png')}}" alt="home-icon" width="16" height="14">
                    </a></li>
                <li class="breadcrumb-item active" aria-current="page">My Account</li>
            </ol>
        </div>
    </nav>

    <div class="mb-5">
        <div class="row">
            <div class="col-12 col-md-3">
                @include("includes.user-sidebar")
            </div>
            <div class="col-lg-9 order-lg-last dashboard-content">
                @include("includes.info-bar")
                @if(isset($wishlists))
                    @foreach($wishlists as $wishlist)
                        <div class="col-6 col-sm-4 col-md-3">
                            <div class="p-2 product-default inner-quickview inner-icon appear-animate"
                                data-animation-name="fadeInRightShorter">
                                <figure>
                                    <a
                                        href="{{route('product.show', [$wishlist->product->brand->slug, $wishlist->product->slug])}}">
                                        <img src="{{$wishlist->product->thumbnail}}" alt="{{$wishlist->product->slug}}"
                                            class="img-fluid mx-auto w-auto mobileImage" />
                                    </a>
                                    <!-- <div class="label-group">
                                                        <span class="product-label label-sale">-26%</span>
                                                    </div> -->

                                </figure>
                                <div class="product-details">
                                    <div class="category-wrap">
                                        <div class="category-list">
                                            <a href="{{route('brand.show', $wishlist->product->brand->slug)}}"
                                                class="product-category">{{$wishlist->product->brand->name}}</a>
                                        </div>
                                        <a href="{{route('user.wishlist.delete', $wishlist->id)}}" class="btn-icon-wish"><i
                                                class="fas fa-trash-alt text-danger"></i></a>
                                    </div>
                                    <h2 class="product-title mx-auto">
                                        <a
                                            href="{{route('product.show', [$wishlist->product->brand->slug, $wishlist->product->slug])}}">{{Str::title($wishlist->product->name)}}</a>
                                    </h2>
                                    <div class="price-box mx-auto">
                                        <span
                                            class="product-price text-danger">Rs.{{number_format($wishlist->product->price_in_pkr)}}</span>
                                    </div><!-- End .price-box -->
                                </div><!-- End .product-details -->
                            </div>
                        </div><!-- End .col-md-3 -->
                    @endforeach
                @else
                    <h3>No Mobile Found.</h3>
                @endif
            </div><!-- End .col-lg-9 -->

        </div><!-- End .row -->
    </div><!-- End .container -->

</main><!-- End .main -->
@stop

@section('script') @stop

@section('style') @stop