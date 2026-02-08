@extends('layouts.frontend')

@section('title', $metas->title)

@section('description', $metas->description)

@section("keywords", "Mobiles prices, mobile specification, mobile phone features")

@section("canonical", $metas->canonical)

@section("og_graph") @stop

@section("content")
<main class="main container-lg">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb pt-sm-1">
                <li class="breadcrumb-item"><a href="{{URL::to('/')}}" class="text-decoration-none text-secondary">
                        <img src="{{URL::to('/images/icons/home.png')}}" alt="home-icon" width="16" height="16">
                    </a></li>
                <li class="breadcrumb-item active text-secondary" aria-current="page">
                    {{isset($brand->name) ? Str::title($brand->name) : $metas->name}}
                </li>
            </ol>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="row">
                    @include("includes.info-bar")
                    <h1>Contact</h1>
                    <form action="{{route('contact.post')}}" method="post" class="needs-validation" novalidate>
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Name"
                                value="{{old('name')}}" required>
                            <label for="name">*Name</label>
                            <div class="invalid-feedback">
                                Please provide your full name.
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control" id="email" placeholder="Email"
                                value="{{old('email')}}" required>
                            <label for="email">*Email</label>
                            <div class="invalid-feedback">
                                Please provide a valid email address.
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone Number"
                                value="{{old('phone')}}">
                            <label for="phone">Phone Number</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea name="message" id="message" class="form-control" placeholder="Message"
                                style="height: 150px;" required>{{old('message')}}</textarea>
                            <label for="message">*Message</label>
                            <div class="invalid-feedback">
                                Please provide a message.
                            </div>
                        </div>
                        <div class="form-group my-2">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <p>For more details or any queries, please don’t hesitate to contact us on WhatsApp. <a class=""
                            href="https://wa.me/message/ZEKV4JT3A4LDK1"><img
                                src="{{URL::to('/images/icons/whatsapp.png')}}" alt="WhatsApp"></a></p>
                </div>
            </div>

        </div><!-- End .container -->
    </div>
</main><!-- End .main -->
@stop


@section("style") @stop

@section("script")

@section('script')

@stop