@php
    $host = request()->getHost();

    // Split the host into parts
    $parts = explode('.', $host);

    // Check if there's a subdomain
    if (count($parts) > 2) {
        $subdomain = $parts[0];
    } else {
        $subdomain = 'pk'; // Default to 'pk' if no subdomain
    }

    // Use the session helper function to set the country_code
    session(['country_code' => $subdomain]);

    $country = DB::table("countries")->where("country_code", $subdomain)->first();    
    $layout = ($country && $country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

@section('title','404 - Page not Found. MKS')

@section('description','404 - Page not Found. MKS')

@section("keywords") @stop

@section("canonical") @stop

@section("og_graph") @stop


@section("content")

<main class="main">
        <div class="container">
            <div class="row mt-2">
                <div class="col-12 col-md-3 pe-1">
                    @include("frontend.sidebar_widget")
                </div>
                <div class="col-12 col-md-9">
                    <div class="row my-5 py-5">
                        <h1 class="text-center">404 Page</h1>
                    </div>
                </div>
            
        </div><!-- End .container -->
    </main><!-- End .main -->
@stop


@section("script")

@stop

@section("style")
<style type="text/css">
    .icon-angle-right{
        background: #928989ad;
        margin-left: 10px;
        padding-left: 15px !important;
        padding-right: 12px !important;
        padding-bottom: 3px !important;
    }
    .icon-angle-left{
        background: #928989ad;
        margin-left: 10px;
        padding-left: 12px !important;
        padding-right: 15px !important;
        padding-bottom: 3px !important;   
    }
    .mobileImage{
        height:120px !important;
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