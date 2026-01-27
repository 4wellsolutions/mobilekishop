@php
    $layout = ($country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

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
                    {{isset($brand->name) ? Str::title($brand->name) : $metas->name}}</li>
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


@section("style")
<style>
    .form-floating label {
        color: #888;
    }

    .form-control,
    .form-control:focus {
        border: 1px solid #ced4da;
        color: #495057;
    }

    .form-control::placeholder {
        color: transparent;
    }

    .form-control:focus::placeholder {
        color: #adb5bd;
    }

    .btn-primary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-primary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }
</style>
<style type="text/css">
    .filter-select {
        height: 4rem !important;
    }

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

    .select-filter:after {
        margin-top: 8px !important;
    }

    #sort_filter:after {
        margin-top: -1px !important;
    }

    .fs-12 {
        font-size: 12px !important;
    }

    .fs-14 {
        font-size: 14px !important;
    }

    .fs-15 {
        font-size: 15px !important;
    }

    .fs-16 {
        font-size: 16px !important;
    }

    .ck-editor__editable_inline {
        min-height: 200px;
    }
</style>
@stop

@section('script')

@stop