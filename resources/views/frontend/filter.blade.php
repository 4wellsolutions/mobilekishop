@php
    $layout = ($country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

@section('title', $metas->title)

@section('description', $metas->description)

@section('keywords', $metas->keywords ?? '')

@section('canonical', $metas->canonical)

@section("og_graph")
{!! $metas->og_graph ?? '' !!}
@stop

@section("noindex")
@if(str_contains(URL::full(), '?page='))
    <meta name="robots" content="noindex">
@endif
@stop

@section("content")
<main class="main">
    <div class="container-lg my-3">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-3">
                @include("includes.sidebar_" . $category->slug, ['category' => $category])
            </div>

            <div class="col-12 col-md-8 col-lg-9">
                <div class="row">
                    <h1 class="fs-4">{{$metas->h1}}</h1>
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

                @include('includes.page-body')

            </div>
        </div>
    </div>
</main>
@stop

@section("script")
<script type="text/javascript">
    var baseUrl = "{!! Request::fullUrl() !!}";
    $(".select-filter").change(function () {
        $(".formFilter").submit();
    });

    $(document).ready(function () {
        var isLoading = false;
        function loadData() {
            if (isLoading) return;
            var nextPage = $('#productList').data('next-page');
            var separator = baseUrl.includes('?') ? '&' : '?';
            var urlWithPageParam = baseUrl + separator + "page=";
            isLoading = true;
            $.ajax({
                url: urlWithPageParam + nextPage,
                type: 'GET',
                success: function (data) {
                    if (data.success === false) {
                        $('#productList').data('next-page', 'done');
                    } else {
                        $('#productList').append(data);
                        $('#productList').data('next-page', nextPage + 1);
                    }
                    isLoading = false;
                },
                error: function (data) {
                    isLoading = false;
                }
            });
        }
        $(window).scroll(function () {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() * 0.95) {
                if ($('#productList').data('next-page') !== 'done') {
                    loadData();
                }
            }
        });
    });
</script>
@stop