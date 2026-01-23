@extends("layouts.frontend")

@section('title', $package->meta_title)

@section('description', $package->meta_description)

@section("keywords","Mobiles prices, mobile specification, mobile phone features")

@section("canonical", URL::to('/packages/'.$package->filter_network.'/'.$package->slug))

@section("og_graph") @stop

@section("content")

<main class="main">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <div class="container">
                <ol class="breadcrumb pt-sm-1">
                    <li class="breadcrumb-item"><a href="{{URL::to('/')}}" class="text-decoration-none text-secondary">
                        <img src="{{URL::to('/images/icons/home.png')}}" alt="home-icon" width="16" height="14">
                    </a></li>
                    <li class="breadcrumb-item active text-secondary" aria-current="page">Packages</li>
                </ol>
            </div>
        </nav>
            
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-3 pe-1">
                    @include("includes.sidebar.packages")
                </div>
                <div class="col-12 col-md-9">
                    <div class="row">
                        <div class="row">
                            <div class="col-12 col-md-8  order-2 order-md-1">
                                <div class="table-responsive">
                                    <h1 class="text-uppercase">{{$package->name}}</h1>
                                    <table class="table">
                                        <tr>
                                            <td class="w-50">
                                                <img src="{{URL::to('/images/icons/internet.png')}}" class="img-fluid me-2" alt="internet-icon" width="32" height="32">Internet
                                            </td>
                                            <td class="w-50">{{$package->data}}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <img src="{{URL::to('/images/icons/calling.png')}}" class="img-fluid me-2" alt="calling-icon" width="32" height="32">Onnet Min
                                            </td>
                                            <td>{{$package->onnet}}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <img src="{{URL::to('/images/icons/calling.png')}}" class="img-fluid me-2" alt="calling-icon" width="32" height="32">OffNet Min
                                            </td>
                                            <td>{{$package->offnet}}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <img src="{{URL::to('/images/icons/icon-sms.png')}}" class="img-fluid me-2" alt="sms-icon" width="32" height="32">SMS
                                            </td>
                                            <td>{{$package->sms}}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-danger fw-bold">
                                                <img src="{{URL::to('/images/icons/charges.png')}}" style="width: 24px;" class="img-fluid me-2" alt="price-icon" width="32" height="32">Price
                                            </td>
                                            <td class="text-danger fw-bold">Rs.{{$package->price}} <small style="font-size:15px;" class="text-secondary">(Incl. Tax)</small></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 my-auto order-1 order-md-2 text-center">
                                <img src="{{URL::to('/images/packages/'.$package->filter_network.'.png')}}" width="200" height="85" class="img-fluid network_logo" alt="{{$package->filter_network}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h3>Description</h3>
                                <p class="fw-light">{!! $package->description !!}</p>
                                <div class="method">{!! $package->method !!}</div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            
        </div><!-- End .container -->
    </main><!-- End .main -->
@stop


@section("script")

@stop

@section("style")
<style type="text/css">
    .method table{
    --bs-table-bg: transparent;
    --bs-table-accent-bg: transparent;
    --bs-table-striped-color: #212529;
    --bs-table-striped-bg: rgba(0, 0, 0, 0.05);
    --bs-table-active-color: #212529;
    --bs-table-active-bg: rgba(0, 0, 0, 0.1);
    --bs-table-hover-color: #212529;
    --bs-table-hover-bg: rgba(0, 0, 0, 0.075);
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
    vertical-align: top;
    border-color: #dee2e6;
    }
    .network_logo{
        max-width: 200px;
    }
</style>
@stop