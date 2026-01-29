@extends("layouts.frontend")

@section('title')My Account - MKS @stop

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
                <h2>Edit Account Information</h2>
                <form action="{{route('user.update', Auth::User()->id)}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-11 mx-auto">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-group required-field">
                                        <label for="acc-name">First Name</label>
                                        <input type="text" class="form-control" id="acc-name" name="name"
                                            value="{{Auth::User()->name}}" required>
                                    </div><!-- End .form-group -->
                                </div><!-- End .col-md-4 -->
                                <div class="col-md-4 mb-3">
                                    <div class="form-group required-field">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email"
                                            value="{{Auth::User()->email}}" readonly>
                                    </div><!-- End .form-group -->
                                </div><!-- End .col-md-4 -->
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="text" value="{{Auth::user()->phone_number}}" class="form-control"
                                            id="phone" name="phone">
                                    </div><!-- End .form-group -->
                                </div><!-- End .col-md-4 -->
                            </div><!-- End .row -->

                            <div class="mb-3"></div><!-- margin -->

                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" name="newsletter" class="custom-control-input" id="newsletter"
                                    {{Auth::User()->newsletter ? 'checked' : ''}}>
                                <label class="custom-control-label" for="newsletter">NewsLetter</label>
                            </div><!-- End .custom-checkbox -->

                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="change-pass-checkbox" value="1">
                                <label class="custom-control-label" for="change-pass-checkbox">Change Password</label>
                            </div><!-- End .custom-checkbox -->

                            <div id="account-chage-pass" class="mt-3 d-none">
                                <h3 class="mb-3">Change Password</h3>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group required-field">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password">
                                        </div><!-- End .form-group -->
                                    </div><!-- End .col-md-6 -->

                                    <div class="col-md-6 mb-3">
                                        <div class="form-group required-field">
                                            <label for="password_confirmation">Confirm Password</label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation">
                                        </div><!-- End .form-group -->
                                    </div><!-- End .col-md-6 -->
                                    <div class="required text-end mb-3">* Required Field</div>
                                </div><!-- End .row -->
                            </div><!-- End #account-chage-pass -->


                            <div class="form-footer d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div><!-- End .form-footer -->
                        </div><!-- End .col-sm-11 -->
                    </div><!-- End .row -->
                </form>

            </div><!-- End .col-lg-9 -->
        </div><!-- End .row -->
    </div><!-- End .container -->
</main><!-- End .main -->
@stop

@section('script') @stop

@section('style') @stop