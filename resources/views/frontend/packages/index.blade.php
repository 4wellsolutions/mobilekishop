@extends("layouts.frontend")

@section('title', $metas->title)

@section('description', $metas->description)

@section("keywords","Mobiles prices, mobile specification, mobile phone features")

@section("canonical",$metas->canonical)

@section("og_graph") @stop

@section("content")
<style type="text/css">
    h1,p {
        font-family: Poppins,sans-serif;
    }
    .list-group-item .list-item a{
        text-decoration: none;
        
    }
</style>
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
                        <h1 class="heading1 pb-2 fs-4 text-center text-uppercase">{{$metas->h1}}</h1>
                    </div>
                    <form action="" method="get" class="formFilter mb-1 d-none">
                        <input type="hidden" name="filter" value="true">
                        <div class="row d-flex justify-content-between filter">
                            <div class="col-auto">
                                <div class="">
                                    <label>Sort By:</label>
                                    <div class="select-custom">
                                        <select name="orderby" id="sort_filter" class="select-filter form-control">
                                            <option value="new" {{(Request::get('orderby') == "new") ? "selected" : ''}}>Sort by Latest</option>
                                            <option value="price_asc" {{(Request::get('orderby') == "price_asc") ? "selected" : ''}}>Sort by price: low to high</option>
                                            <option value="price_desc" {{(Request::get('orderby') == "price_desc") ? "selected" : ''}}>Sort by price: high to low</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <div class="border rounded-circle">
                                    <img src="{{URL::to('/images/icons/filter.png')}}" class="img-fluid m-2" alt="filter-icon" style="cursor: pointer;" data-bs-toggle="collapse" href="#filter" role="button" aria-expanded="false" aria-controls="filter" width="30" height="30">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="collapse {{(new \Jenssegers\Agent\Agent())->isDesktop() ? 'show' : '' }} {{\Request::get('filter') == true ? 'show' : ''}}" id="filter">
                              <div class="row mt-3">
                                    <div class="col-6 col-md-6">
                                        <div class="filter">
                                            <label class="fw-bold">Network</label>
                                            <select class="select-filter form-control rounded py-1" name="network">
                                                <option value="">All Networks</option>
                                                <option value="jazz">Jazz</option>
                                                <option value="zong">Zong</option>
                                                <option value="ufone">Ufone</option>
                                                <option value="telenor">Telenor</option>
                                            </select>
                                        </div>
                                    </div>    
                                    <div class="col-6 col-md-6">
                                        <div class="select-custom">
                                            <label class="fw-bold">Type</label>
                                            <select class="select-filter form-control rounded py-1" name="rom_in_gb">
                                                <option value="">All</option>
                                                <option value="voice">Voice/Call</option>
                                                <option value="sms">Sms</option>
                                                <option value="data">Data</option>
                                                <option value="hybrid">Hybrid</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-6">
                                        <div class="select-custom">
                                            <label class="fw-bold">Validity</label>
                                            <select class="select-filter form-control rounded py-1" name="rom_in_gb">
                                                <option value="">All</option>
                                                <option value="hourly">Hourly</option>
                                                <option value="daily">Daily</option>
                                                <option value="weekly">Weekly</option>
                                                <option value="fortnightly">Fortnightly</option>
                                                <option value="monthly">Monthly</option>
                                                <option value="3-months">3 Months</option>
                                                <option value="6-months">6 Months</option>
                                                <option value="12-months">12 Month</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-6 col-md-6">
                                        <div class="select-custom">
                                            <label class="fw-bold">Data GB</label>
                                            <select class="select-filter form-control rounded py-1" name="rom_in_gb">
                                                <option value="">All</option>
                                                <option value="1">1GB or Less</option>
                                                <option value="5">5GB or Less</option>
                                                <option value="10">10GB or Less</option>
                                                <option value="25">25GB or Less</option>
                                                <option value="100">100GB or Less</option>
                                                <option value="1000">1000GB or Less</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-6 mt-2">
                                        <button class="btn btn-dark rounded-0">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    @if($networks = App\Package::distinct('filter_network')->pluck('filter_network'))
                    <div class="row mt-3">
                        @foreach($networks as $network)
                        <div class="col-6 col-md-3  text-center p-3">
                            <div class="border p-3">
                            <a href="{{route('package.network.index',$network)}}" class="text-decoration-none">
                                <img src="{{URL::to('/images/packages/'.$network.'.png')}}" class="img-fluid network_logo" alt="{{$network}}">
                            </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>    
        </div>
    </main>
@stop




@section("script")

@stop

@section("style")
<style type="text/css">


</style>
@stop