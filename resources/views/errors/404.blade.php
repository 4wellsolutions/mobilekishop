@php
    // Extract country code from URL path (first segment)
    $pathSegments = explode('/', trim(request()->path(), '/'));
    $firstSegment = $pathSegments[0] ?? null;

    // Get allowed countries
    $allowedCountries = DB::table('countries')->pluck('country_code')->toArray();

    // Determine country code from path or default to 'pk'
    $countryCode = in_array($firstSegment, $allowedCountries) ? $firstSegment : 'pk';

    // Use the session helper function to set the country_code
    session(['country_code' => $countryCode]);

    $country = DB::table("countries")->where("country_code", $countryCode)->first();
    $layout = ($country && $country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

@section('title', '404 - Page not Found. MKS')

@section('description', '404 - Page not Found. MKS')

@section("keywords") @stop

@section("canonical") @stop

@section("og_graph") @stop


@section("content")

<main class="main">
    <div class="container">
        <div class="row mt-5 pt-5">
            <div class="col-12">
                <div class="text-center my-5 py-5">
                    <h1 class="display-1 fw-bold text-primary">404</h1>
                    <h2 class="mb-4">Page Not Found</h2>
                    <p class="lead mb-4">Sorry, the page you are looking for could not be found.</p>
                    @php
                        $pathSegments = explode('/', trim(request()->path(), '/'));
                        $firstSegment = $pathSegments[0] ?? null;
                        $allowedCountries = ['us', 'uk', 'bd', 'ae', 'in'];
                        $countryCode = in_array($firstSegment, $allowedCountries) ? $firstSegment : 'pk';
                        $homeUrl = $countryCode === 'pk' ? url('/') : url('/' . $countryCode);
                    @endphp
                    <a href="{{ $homeUrl }}" class="btn btn-primary">Go to Homepage</a>
                </div>
            </div>
        </div><!-- End .container -->
    </div>
</main><!-- End .main -->
@stop


@section("script")

@stop

@section("style")
<style type="text/css">
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

    .mobileImage {
        height: 120px !important;
    }

    .card-img {
        border-bottom-left-radius: 0px;
        border-bottom-right-radius: 0px;
    }

    .card-title {
        margin-bottom: 0.3rem;
    }

    .cat {
        display: inline-block;
        margin-bottom: 1rem;
    }

    .fa-users {
        margin-left: 1rem;
    }

    .card-footer {
        font-size: 0.8rem;
    }

    .cat-list li a {
        font-weight: normal !important;
    }
</style>
@stop