@extends('layouts.frontend')

@section('title')Login - Mobile Ki Site @stop

@section('description')Login - Mobile Ki Site @stop

@section("keywords")Mobiles prices, mobile specification, mobile phone features @stop

@section('canonical',URL::to('/login'))

@section('content')
<div class="container">
    <div class="row justify-content-center">    
        <div class="row justify-content-center  pt-3">
            <div class="col-12">
                @include("includes.info-bar")
            </div>
            <div class="col-12 col-sm-6 col-md-6">
                <h1 class="fs-2 text-uppercase text-center text-primary bg-light py-2 py-md-3">Login</h1>
                <form action="{{route('login')}}" method="post">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="email" value="{{old('email')}}" name="email"  placeholder="name@example.com" class="form-control" id="login-email" required />
                        <label for="login-email">Email address <span class="required">*</span></label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" name="password"  placeholder="Password" class="form-control" id="login-password" required />
                        <label for="login-password">Password <span class="required">*</span></label>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-dark rounded-0">Login</button>
                    </div>
                    <div class="form-footer mt-2">
                        <div class="custom-control custom-checkbox form-footer-right">
                            <input type="checkbox" name="remember" class="custom-control-input" id="lost-password">
                            <label class="custom-control-label form-footer-right" for="lost-password">Remember Me</label>
                        </div>
                    </div><!-- End .form-footer -->
                    <div class="form-group my-2">
                        <a href="#" class="forget-password text-secondary text-decoration-none">Forgot your password?</a>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <a href="#" data-href="{{URL::to('/google/redirect')}}" id="sign-with-google">
                                <img src="{{URL::to('/images/login-with-google.png')}}" alt="login-with-google" class="img-fluid">
                            </a>
                        </div>
                    </div>
                </form>
            </div><!-- End .col-md-6 -->

            <div class="col-12 col-sm-6 col-md-6">
                <h4 class="fs-2 text-uppercase text-center text-primary bg-light py-2 py-md-3">Register</h4>

                <form action="{{route('register')}}" method="post">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" name="name" placeholder="Full name" value="{{old('name')}}" class="form-control mb-2" id="register-name" required>
                        <label for="register-name">Full Name<span class="required">*</span></label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" name="phone_number"  placeholder="Phone number" value="{{old('phone_number')}}" class="form-control mb-2" id="register-phone" required>
                        <label for="register-phone">Phone Number<span class="required">*</span></label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email"  placeholder="name@example.com" name="email" value="{{old('email')}}" class="form-control mb-2" id="register-email" required>
                        <label for="register-email">Email address<span class="required">*</span></label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="password" placeholder="password*" class="form-control mb-2" id="register-password" required>
                        <label for="register-password">Password <span class="required">*</span></label>
                    </div>

                    <div class="form-floating mb-3">
                        <div class="custom-control custom-checkbox my-2">
                            <input type="checkbox" name="newsletter" class="custom-control-input" id="newsletter-signup" checked>
                            <label class="custom-control-label" for="newsletter-signup">Sign up our Newsletter</label>
                        </div>
                    </div>


                    <div class="form-group my-3">
                        <button type="submit" class="btn btn-dark rounded-0">Register</button>
                    </div><!-- End .form-footer -->
                </form>
            </div><!-- End .col-md-6 -->
            </div><!-- End .row -->
    </div>
</div>
@endsection
