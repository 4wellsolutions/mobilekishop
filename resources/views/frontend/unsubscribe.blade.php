@php
    $layout = ($country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

@section('title', "Unsubscribe - MKS")

@section('description',"Unsubscribe - MKS")

@section("keywords","Mobiles prices, mobile specification, mobile phone features")

@section("canonical",URL::to('/unsubscribe'))

@section("og_graph") @stop

@section("content")

<main class="main container-lg">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <div class="container">
                <ol class="breadcrumb pt-sm-1">
                    <li class="breadcrumb-item"><a href="{{URL::to('/')}}" class="text-decoration-none text-secondary">
                        <img src="{{URL::to('/images/icons/home.png')}}" alt="home-icon" width="16" height="16">
                    </a></li>
                    <li class="breadcrumb-item active text-secondary" aria-current="page">Unsubscribe</li>
                </ol>
            </div>
        </nav>

        <div class="row my-4">
            <div class="col-12 col-md-6 col-lg-4 mx-auto">
                @include("includes.info-bar")
                @if(isset($user))
                <div class="alert alert-success">Unsubscribed successfully.</div>
                @else
                <form class="my-3" action="{{route('unsubscribe.post')}}" method="post">
                    @csrf
                    <h1 class="text-uppercase text-center">Unsubscribe</h1>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="form-group my-2">
                        <button class="btn btn-dark w-100">Unsubscribe</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
        
    </main><!-- End .main -->
@stop




@section("script")

@stop

@section("style")

@stop