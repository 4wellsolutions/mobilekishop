<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@yield("title")</title>

    <meta name="keywords" content="@yield('keywords')" />
    <meta name="description" content="@yield('description')">
    <meta name="author" content="Marketers.pk">
    <link rel="canonical" href="@yield('canonical')" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{URL::to('/')}}/images/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css" />
    <script type="text/javascript">
        WebFontConfig = {
            google: { families: ['Open+Sans:300,400,600,700,800', 'Poppins:300,400,500,600,700,800'] }
        };
        (function (d) {
            var wf = d.createElement('script'), s = d.scripts[0];
            wf.src = '{{URL::to('/')}}/js/webfont.js';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>

    <!-- Plugins CSS File -->

    <!-- Main CSS File -->
    <link rel="stylesheet" href="{{URL::to('/')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/css/style.min.css">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous"> -->
    <!-- <link rel="stylesheet" href="{{URL::to('/')}}/css/merged-css.min.css"> -->
    <style type="text/css">
        @media(max-width: 576px) {
            .mks-logo {
                height: 45px;
                width: auto;
            }
        }
    </style>

    @php
        $brands = App\Brand::limit(20)->get();
    @endphp
</head>

<body>
    <div class="page-wrapper">
        <header class="header mb-0">
            @if(Auth::check() && Auth::User()->type_id != "4")
                <div class="header-top bg-secondary">
                    <div class="container">
                        <div class="header-right text-white py-1">
                            <div class="wel-msg text-uppercase p-r-2 mr-auto">
                                <a href="{{route('dashboard.index')}}">Dashboard</a>
                            </div>
                            <span class="separator d-sm-inline-block"></span>
                            <ul class="top-links mega-menu show-arrow mt-0 mb-0">
                                <li class="menu-item narrow"><a href="{{route('dashboard.mobile.index')}}">Mobiles</a></li>
                                <li class="menu-item narrow"><a href="{{route('dashboard.brand.index')}}">Brands</a></li>

                                <li class="menu-item narrow"><a href="{{URL::to('/blogs/')}}/">Blog</a></li>

                                @if(Auth::check())
                                    <li class="menu-item narrow"><a href="{{route('user.review')}}">Reviews</a></li>
                                    <li class="menu-item">
                                        <a class="login" href="{{route('user.index')}}">Account</a>
                                    </li>
                                @else
                                    <li class="menu-item">
                                        <a class="login" href="{{URL::to('/login')}}">Log In</a>
                                    </li>
                                @endif
                            </ul>
                        </div><!-- End .header-right -->
                    </div><!-- End .container -->
                </div><!-- End .header-top -->
            @endif
            <div class="header-top">
                <div class="container">
                    <div class="header-right py-1">
                        <span class="separator d-none d-sm-inline-block"></span>
                        <ul class="top-links mega-menu show-arrow mt-0 mb-0">

                            @if(Auth::check())
                                <li class="menu-item narrow"><a href="{{route('user.review')}}">Reviews</a></li>
                                <li class="menu-item">
                                    <a class="login" href="{{route('user.index')}}">Account</a>
                                </li>
                            @else
                                <li class="menu-item">
                                    <a class="login" href="{{URL::to('/login')}}">Log In</a>
                                </li>
                            @endif

                        </ul>
                        <span class="separator d-none d-xl-block"></span>
                        <div class="social-icons py-2 py-xl-0">
                            <a target="_blank" class="social-icon social-facebook icon-facebook"
                                href="https://www.facebook.com/mobilekisite/" title="Facebook"></a>
                            <a target="_blank" class="social-icon social-twitter icon-twitter"
                                href="https://twitter.com/mobilekisite" title="Twitter"></a>
                            <a target="_blank" class="social-icon social-instagram icon-instagram"
                                href="https://www.instagram.com/mobilekisite/" title="Instagram"></a>
                        </div>
                    </div><!-- End .header-right -->
                </div><!-- End .container -->
            </div><!-- End .header-top -->
            <div class="header-middle">
                <div class="container">
                    <div class="header-left py-0">
                        <button class="mobile-menu-toggler mr-2" type="button">
                            <i class="icon-menu"></i>
                        </button>
                        <a href="{{url(($country->country_code === 'pk' ? '' : $country->country_code) . '/')}}"
                            class="">
                            <img src="{{URL::to('/')}}/images/logo.png" alt="mobile-ki-site-Logo"
                                class="img-fluid my-2 mks-logo" width="150" height="60" />
                            <!-- <h2 class="my-auto">MOBILE <span class="text-danger">KI</span> SITE</h2> -->
                        </a>
                    </div><!-- End .header-left -->
                    <div class="header-center flex-1 ml-0 justify-content-end justify-content-lg-start">
                        <div class="header-search header-search-inline header-search-category w-lg-max pr-2 pr-lg-0">
                            <a href="javascript:void(0);" title="search"
                                class="search-toggle header-icon d-sm-inline-block d-lg-none mr-0" role="button"><i
                                    class="icon-search-3"></i></a>
                            <form action="{{route('search')}}" method="get">
                                <div class="header-search-wrapper">
                                    <input type="search" class="form-control" name="query" id="searchInput"
                                        placeholder="Search..." required>
                                    <button class="btn icon-search-3" type="submit"></button>
                                </div><!-- End .header-search-wrapper -->
                            </form>
                        </div><!-- End .header-search -->
                    </div>
                    <div class="header-right py-1">
                        @if(!Auth::check())
                            <a href="{{URL::to('/login')}}" class="header-icon" data-toggle="modal"
                                data-target="#loginModal"><i class="icon-user-2"></i></a>
                        @else
                            <a href="{{route('user.wishlist')}}" class="header-icon"><i class="icon-wishlist-2"></i></a>
                            <div class="dropdown cart-dropdown">
                                <a href="#" class="dropdown-toggle dropdown-arrow" role="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false" data-display="static">
                                    <i class="icon-user-2"></i>
                                </a>

                                <div class="dropdown-menu">
                                    <div class="dropdownmenu-wrapper">

                                        <div class="dropdown-cart-products">
                                            <div class="product py-2">
                                                <div class="product-details">
                                                    <h4 class="product-title font-weight-normal">
                                                        <a href="{{route('user.index')}}">Profile</a>
                                                    </h4>
                                                </div><!-- End .product-details -->
                                            </div><!-- End .product -->

                                            <div class="product py-2">
                                                <div class="product-details">
                                                    <h4 class="product-title font-weight-normal">
                                                        <a href="{{route('user.review')}}">Reviews</a>
                                                    </h4>
                                                </div><!-- End .product-details -->
                                            </div>
                                            <div class="product py-2">
                                                <div class="product-details">
                                                    <h4 class="product-title font-weight-normal">
                                                        <a href="{{ route('logout') }}" class="header-icon"
                                                            style="line-height: 1.6rem;height: 1.6rem;"
                                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                                    </h4>
                                                </div><!-- End .product-details -->
                                            </div>
                                        </div><!-- End .cart-product -->
                                    </div><!-- End .dropdownmenu-wrapper -->
                                </div><!-- End .dropdown-menu -->
                            </div><!-- End .dropdown -->
                        @endif
                    </div><!-- End .header-right -->
                </div>
            </div>
            <div class="header-bottom mb-lg-2 sticky-header" data-sticky-options="{
                'move': [
                    {
                        'item': '.header-icon:not(.search-toggle)',
                        'position': 'end',
                        'clone': false
                    },
                    {
                        'item': '.cart-dropdown',
                        'position': 'end',
                        'clone': false
                    }
                ],
                'moveTo': '.container',
                'changes': [
                    {
                        'item': '.logo-white',
                        'removeClass': 'd-none'
                    },
                    {
                        'item': '.header-icon:not(.search-toggle)',
                        'removeClass': 'pb-md-1',
                        'addClass': 'text-white'
                    },
                    {
                        'item': '.cart-dropdown',
                        'addClass': 'text-white'
                    }
                ]
            }">
                <div class="container px-0">
                    <div class="logo logo-transition logo-white w-100 d-none">
                        <a href="{{URL::to('/')}}">
                            <img src="{{URL::to('/')}}/images/logo.png" alt="mobile-ki-site-Logo" class="img-fluid my-2"
                                width="110" height="46" />
                            <!-- <h2 class="my-auto text-white" style="font-size: 25px;">MOBILE <span class="text-danger">KI</span> SITE</h2> -->
                        </a>
                    </div>
                    <div class="bg-dark w-100">
                        <nav class="main-nav">
                            <ul class="menu d-flex justify-content-center">
                                <li class="active">
                                    <a
                                        href="{{url(($country->country_code === 'pk' ? '' : $country->country_code) . '/')}}">Home</a>
                                </li>
                                <li>
                                    <a
                                        href="{{url(($country->country_code === 'pk' ? '' : $country->country_code) . '/brands/all')}}">Brands</a>
                                </li>
                                <li><a href="{{route('up.coming.mobiles')}}">Coming Soon</a></li>
                                <li><a href="{{route('ad.index')}}">Ads <span class="badge badge-danger">New</span></a>
                                </li>
                                <li><a href="{{route('package.index')}}">Packages <span
                                            class="badge badge-danger">New</span></a></li>
                                <li><a href="{{URL::to('/blog/')}}/">Blog</a></li>
                                <li><a
                                        href="{{url(($country->country_code === 'pk' ? '' : $country->country_code) . '/contact')}}">Contact
                                        Us</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </header><!-- End .header -->

        @yield("content")
        <!-- appear-animate -->
        <footer class="footer bg-dark">
            <div class="container">
                <div class="footer-top">
                    <div class="row row-sm">
                        <div class="col-12 col-md-4 col-lg-5">
                            <div class="widget widget-about">
                                <h4 class="widget-title">About Us</h4>
                                <a href="{{url(($country->country_code === 'pk' ? '' : $country->country_code) . '/')}}"
                                    class="logo mb-2">
                                    <h2 class="text-white">MobileKiShop</h2>
                                </a>
                                <p class="mb-3">Mobile ki shop (MKS) is the best mobile website that provides the latest
                                    mobile phone prices in Pakistan with specifications, features, reviews and
                                    comparison.</p>
                            </div><!-- End .widget -->


                        </div><!-- End .col-lg-6 -->


                        <div class="col-12 col-md-5 col-lg-5">
                            <div class="widget">
                                <h4 class="widget-title">Contact Info</h4>
                                <ul class="contact-info d-flex flex-wrap">
                                    <li>
                                        <span class="contact-info-label">Address:</span> Office: 115, Century Tower
                                        Kalma Chowk Gulberg 3 Lahore, Pakistan.
                                    </li>
                                    <li>
                                        <span class="contact-info-label">Phone:</span><a
                                            href="tel:+923111222741">03-111-222-741</a>
                                    </li>
                                    <li>
                                        <span class="contact-info-label">Email:</span> <a
                                            href="mailto:info@mks.com.pk">info@mks.com.pk</a>
                                    </li>
                                    <li>
                                        <span class="contact-info-label">Working Days/Hours:</span>Mon - Fri / 9:00 AM -
                                        6:00 PM
                                    </li>
                                </ul>
                            </div><!-- End .widget -->
                            <div class="social-icons">
                                <a href="https://www.facebook.com/mobilekisite/"
                                    class="social-icon social-facebook icon-facebook" target="_blank"
                                    title="Facebook"></a>
                                <a href="https://twitter.com/mobilekisite"
                                    class="social-icon social-twitter icon-twitter" target="_blank" title="Twitter"></a>
                                <a href="https://www.instagram.com/mobilekisite/"
                                    class="social-icon social-instagram icon-instagram" target="_blank"
                                    title="Instagram"></a>
                            </div>
                        </div><!-- End .widget -->


                        <div class="col-12 col-md-3 col-lg-2">
                            <div class="widget">
                                <h4 class="widget-title">Important Links</h4>

                                <ul class="">
                                    <li>
                                        <a
                                            href="{{url(($country->country_code === 'pk' ? '' : $country->country_code) . '/about-us')}}">About
                                            Us</a>
                                    </li>
                                    <li>
                                        <a
                                            href="{{url(($country->country_code === 'pk' ? '' : $country->country_code) . '/privacy-policy')}}">Privacy
                                            Policy</a>
                                    </li>
                                    <li>
                                        <a
                                            href="{{url(($country->country_code === 'pk' ? '' : $country->country_code) . '/terms-and-conditions')}}">Terms
                                            and Conditions</a>
                                    </li>
                                    <li>
                                        <a
                                            href="{{url(($country->country_code === 'pk' ? '' : $country->country_code) . '/contact')}}">Contact
                                            Us</a>
                                    </li>

                                </ul>
                            </div><!-- End .widget -->
                        </div>
                    </div><!-- End .row -->
                </div>
            </div>


            <div class="container">
                <div class="footer-bottom d-flex justify-content-between align-items-center flex-wrap">
                    <p class="footer-copyright py-3 pr-4 mb-0">{{date('Y')}}. All Rights Reserved</p>
                </div><!-- End .footer-bottom -->
            </div><!-- End .container -->
        </footer><!-- End .footer -->
    </div><!-- End .page-wrapper -->

    <div class="mobile-menu-overlay"></div><!-- End .mobil-menu-overlay -->

    <div class="mobile-menu-container">
        <div class="mobile-menu-wrapper">
            <span class="mobile-menu-close"><i class="icon-cancel"></i></span>
            <nav class="mobile-nav">
                <ul class="mobile-menu">
                    <li class="active"><a
                            href="{{url(($country->country_code === 'pk' ? '' : $country->country_code) . '/')}}">Home</a>
                    </li>
                    <li>
                        <a
                            href="{{url(($country->country_code === 'pk' ? '' : $country->country_code) . '/brands/all')}}">Brands</a>
                    </li>
                    <li><a href="{{URL::to('/blog/')}}/">Blog</a></li>
                    <li><a href="{{route('ad.index')}}" class="font-weight-bold">Ads <span
                                class="badge badge-danger">New</span></a></li>
                    <li><a href="{{url(($country->country_code === 'pk' ? '' : $country->country_code) . '/contact')}}">Contact
                            Us</a></li>
                </ul>
            </nav><!-- End .mobile-nav -->

            <div class="social-icons">
                <a href="#" class="social-icon" target="_blank"><i class="icon-facebook"></i></a>
                <a href="#" class="social-icon" target="_blank"><i class="icon-twitter"></i></a>
                <a href="#" class="social-icon" target="_blank"><i class="icon-instagram"></i></a>
            </div><!-- End .social-icons -->
        </div><!-- End .mobile-menu-wrapper -->
    </div><!-- End .mobile-menu-container -->

    <!-- Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header w-auto"
                    style="border-bottom: none;position: absolute;right: -10px;top: -15px;z-index: 100;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row row-sparse">
                        <div class="col-10">
                            <div class="validation-errors"></div>
                        </div>
                        <div class="col-md-6">

                            <h4 class="title mb-2 text-primary text-uppercase" style="font-size: 2.5rem;">Login</h4>
                            <form action="#" method="post" id="loginForm">
                                @csrf
                                <label for="login-email">Email address <span class="required">*</span></label>
                                <input type="email" name="login_email" class="form-input form-wide mb-2"
                                    id="login-email" required />

                                <label for="login-password">Password <span class="required">*</span></label>
                                <input type="password" name="login_password" class="form-input form-wide mb-2"
                                    id="login-password" required />

                                <div class="form-footer my-1">
                                    <button type="submit" class="btn btn-primary btn-md">LOGIN</button>

                                    <div class="custom-control custom-checkbox form-footer-right">
                                        <input type="checkbox" name="remember" class="custom-control-input"
                                            id="lost-password">
                                        <label class="custom-control-label form-footer-right"
                                            for="lost-password">Remember Me</label>
                                    </div>

                                </div><!-- End .form-footer -->
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <a href="#" id="sign-with-google">
                                            <img src="{{URL::to('/images/login-with-google.png')}}"
                                                alt="login-with-google" class="img-fluid">
                                        </a>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <a href="#" id="sign-with-facebook">
                                            <img src="{{URL::to('/images/login-with-facebook.jpg')}}"
                                                alt="login-with-facebook" class="img-fluid">
                                        </a>
                                    </div>
                                </div>
                                <a href="#" class="forget-password">Forgot your password?</a>
                            </form>

                        </div><!-- End .col-md-6 -->

                        <div class="col-md-6">
                            <h4 class="title mb-2 text-primary text-uppercase" style="font-size: 2.5rem;">Register</h4>

                            <form action="{{route('register')}}" method="post">
                                @csrf
                                <label for="register-name">Full Name <span class="required">*</span></label>
                                <input type="text" name="name" class="form-input form-wide mb-2" id="register-name"
                                    required>

                                <label for="register-phone">Phone Number<span class="required">*</span></label>
                                <input type="text" name="phone_number" class="form-input form-wide mb-2"
                                    id="register-phone" required>

                                <label for="register-email">Email address <span class="required">*</span></label>
                                <input type="email" name="email" class="form-input form-wide mb-2" id="register-email"
                                    required>

                                <label for="register-password">Password <span class="required">*</span></label>
                                <input type="password" name="password" class="form-input form-wide mb-2"
                                    id="register-password" required>


                                <div class="form-footer">
                                    <button type="submit" class="btn btn-primary btn-md">Register</button>
                                </div><!-- End .form-footer -->
                            </form>
                        </div><!-- End .col-md-6 -->
                    </div><!-- End .row -->
                </div>

            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
    <a id="scroll-top" href="#top" title="Top" role="button"><i class="fas fa-chevron-up"></i></a>

    <!-- Plugins JS File -->
    <!-- <script src="{{URL::to('/')}}/js/jquery.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="{{URL::to('/')}}/js/bootstrap.bundle.min.js" defer></script>

    <script src="{{URL::to('/')}}/js/plugins.min.js" defer></script>
    <script src="{{URL::to('/')}}/js/jquery.appear.min.js" defer></script>
    <!-- Main JS File -->
    <script src="{{URL::to('/')}}/js/main.min.js"></script>
    <script type="text/javascript">
        $(".category_type").click(function () {
            console.log($(this).data("id"));
            $(".category_id").val($(this).data("id"));
            $("#formCategory").submit();
        });
    </script>
    <script type="text/javascript">
        /*! echo-js v1.7.3 | (c) 2016 @toddmotto | https://github.com/toddmotto/echo */
        !function (t, e) { "function" == typeof define && define.amd ? define(function () { return e(t) }) : "object" == typeof exports ? module.exports = e : t.echo = e(t) }(this, function (t) { "use strict"; var e, n, o, r, c, a = {}, u = function () { }, d = function (t) { return null === t.offsetParent }, l = function (t, e) { if (d(t)) return !1; var n = t.getBoundingClientRect(); return n.right >= e.l && n.bottom >= e.t && n.left <= e.r && n.top <= e.b }, i = function () { (r || !n) && (clearTimeout(n), n = setTimeout(function () { a.render(), n = null }, o)) }; return a.init = function (n) { n = n || {}; var d = n.offset || 0, l = n.offsetVertical || d, f = n.offsetHorizontal || d, s = function (t, e) { return parseInt(t || e, 10) }; e = { t: s(n.offsetTop, l), b: s(n.offsetBottom, l), l: s(n.offsetLeft, f), r: s(n.offsetRight, f) }, o = s(n.throttle, 250), r = n.debounce !== !1, c = !!n.unload, u = n.callback || u, a.render(), document.addEventListener ? (t.addEventListener("scroll", i, !1), t.addEventListener("load", i, !1)) : (t.attachEvent("onscroll", i), t.attachEvent("onload", i)) }, a.render = function (n) { for (var o, r, d = (n || document).querySelectorAll("[data-echo], [data-echo-background]"), i = d.length, f = { l: 0 - e.l, t: 0 - e.t, b: (t.innerHeight || document.documentElement.clientHeight) + e.b, r: (t.innerWidth || document.documentElement.clientWidth) + e.r }, s = 0; i > s; s++)r = d[s], l(r, f) ? (c && r.setAttribute("data-echo-placeholder", r.src), null !== r.getAttribute("data-echo-background") ? r.style.backgroundImage = "url(" + r.getAttribute("data-echo-background") + ")" : r.src !== (o = r.getAttribute("data-echo")) && (r.src = o), c || (r.removeAttribute("data-echo"), r.removeAttribute("data-echo-background")), u(r, "load")) : c && (o = r.getAttribute("data-echo-placeholder")) && (null !== r.getAttribute("data-echo-background") ? r.style.backgroundImage = "url(" + o + ")" : r.src = o, r.removeAttribute("data-echo-placeholder"), u(r, "unload")); i || a.detach() }, a.detach = function () { document.removeEventListener ? t.removeEventListener("scroll", i) : t.detachEvent("onscroll", i), clearTimeout(n) }, a });
    </script>
    <script type="text/javascript">
        echo.init();
    </script>
    <script type="text/javascript">
        var loginForm = $("#loginForm");
        loginForm.submit(function (e) {
            // console.log("login");
            e.preventDefault();
            var formData = loginForm.serialize();
            // console.log(formData);
            $.ajax({
                url: '{{route("login.post")}}',
                type: 'POST',
                data: formData,
                success: function (data) {
                    // console.log(data.intended);
                    if (data.auth) {
                        // console.log(data.intended);
                        location.reload();
                    } else {
                        $('.validation-errors').html("");
                        // console.log(data.errors);
                        $.each(data.errors, function (key, value) {
                            $('.validation-errors').append('<div class="alert alert-danger py-3">' + value + '</div');
                        });
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        });

    </script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/0.11.1/typeahead.bundle.min.js" defer></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js" defer></script> -->
    </script>
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org/",
      "@type": "WebSite",
      "name": "MobileKiShop",
      "url": "{{url(($country->country_code === 'pk' ? '' : $country->country_code) . '/')}}",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{url(($country->country_code === 'pk' ? '' : $country->country_code) . '/search')}}?query={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    <script type="text/javascript">
        // var route = "{{ route('autocomplete.search') }}";
        // var base_url = "{{URL::to('/')}}";
        // //Set the Options for "Bloodhound" suggestion engine
        // var engine = new Bloodhound({
        //     remote: {
        //         url: route+"?query=%QUERY%",
        //         wildcard: '%QUERY%'
        //     },
        //     datumTokenizer:Bloodhound.tokenizers.obj.whitespace('query'),
        //     queryTokenizer:Bloodhound.tokenizers.whitespace,
        // });

        // $("#searchInput").typeahead({
        //     hint: false,
        //     highlight: true,
        //     minLength: 1
        // }, {
        //     source: engine.ttAdapter(),

        //     limit: 5+1,

        //     // This will be appended to "tt-dataset-" to form the class name of the suggestion menu.
        //     name: 'mobileList',
        //     displayKey: 'name', 
        //     // the key from the array we want to display (name,id,email,etc...)
        //     templates: {
        //         empty: [
        //             '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
        //         ],
        //         header: [
        //             '<div class="list-group search-results-dropdown">'
        //         ],
        //         suggestion: function (data) {
        //             var base_url = "{{URL::to('/')}}";
        //             return '<a href="'+base_url+'/'+data.brand.slug+'/'+data.slug+'"><div class="row bg-white border-bottom"><div class="col-4 col-lg-2"><img src="'+data.thumbnail+'" class="img-fluid searchImage my-1"></div><div class="col-8 col-lg-10 text-uppercase" style="font-weight:600;">'+data.name+'<p class="font-size-12 text-dark">Rs.'+ addCommas(data.price_in_pkr) +'</p></div></div></a>'
        //         }
        //     },

        // });

        // $('#searchInput').bind('typeahead:select', function(ev, suggestion) {
        //     console.log('Selection: ' + $(this).attr("id"));
        //     $("#input-"+$(this).attr("id")).val(suggestion.slug);
        //     if($("#searchInput").val() != ""){
        //         param1 = $("#searchInput").val();
        //     }
        // });
        $("#sign-with-google").click(function () {
            $(this).attr("href", "{{URL::to('/google/redirect')}}");
        });
        $("#sign-with-facebook").click(function () {
            $(this).attr("href", "{{URL::to('/facebook/redirect')}}");
        });
        $(".fa-heart").click(function (e) {
            e.preventDefault();
            var mobile_id = $(this).attr("data-id");
            var type = $(this).attr("data-type");
            if ("{{Auth::check()}}" == "") {

                $('#loginModal').modal('show');
                return;
            }
            if (type == 0) {
                type = 1;
                $(this).attr("data-type", type);
                $(this).removeClass("far").stop(true, true).addClass("fas").addClass("text-danger", 1000);
            } else {
                type = 0;
                $(this).attr("data-type", type);
                $(this).removeClass("text-danger").stop(true, true).addClass("far").removeClass("fas", 1000);
            }
            $.ajax({
                url: "{{route('wishlist.post')}}",
                method: "POST",
                cache: false,
                data: { "mobile_id": mobile_id, "_token": "{{Session::token()}}", "type": type },
                success: function (response) {

                }
            });
            return;

        });
        function addCommas(nStr) {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }
    </script>
    @yield('script')

    <!-- <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/css/animate.min.css"> -->
    <link rel="stylesheet" type="text/css" href="{{URL::to('/')}}/vendor/fontawesome-free/css/all.min.css">
    <!-- <link rel="stylesheet" href="{{URL::to('/')}}/css/merged-css.min.css"> -->

    <style type="text/css">
        .brandWidget {
            overflow-y: scroll;
            max-height: 300px;
        }

        .sidebar-toggle {
            top: 35% !important;
        }

        .product-default:hover {
            box-shadow: 0 25px 35px -5px rgb(0 0 0 / 10%) !important;
        }

        .product-default:hover figure {
            box-shadow: none;
        }

        ul.cat-list li {
            margin-bottom: 5px;
        }

        .twitter-typeahead {
            width: 100% !important;
        }

        .searchImage {
            height: 80px;
        }

        .tt-menu {
            width: inherit !important;
        }
    </style>
    @yield('style')
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.min.css" integrity="sha512-gMjQeDaELJ0ryCI+FtItusU9MkAifCZcGq789FrzkiM49D8lbDhoaUaIX4ASU187wofMNlgBJ4ckbrXM9sE6Pg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
</body>

</html>