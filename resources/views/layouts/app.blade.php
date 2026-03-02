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

    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <script>
        window.MKS_STATE = {
            baseUrl: '{{ url("/") }}',
            currentUrl: '{!! url()->current() !!}',
            csrfToken: '{{ csrf_token() }}',
            countryCode: '{{ $country->country_code ?? "pk" }}',
            routes: {
                login: '{{ route("login.post") }}',
                register: '{{ route("auth.register") }}',
                reviewPost: '{{ route("review.post") }}'
            },
            isLoggedIn: {{ auth()->check() ? 'true' : 'false' }}
        };
    </script>
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
                                <li class="menu-item narrow"><a href="{{route('dashboard.products.index')}}">Mobiles</a>
                                </li>
                                <li class="menu-item narrow"><a href="{{route('dashboard.brands.index')}}">Brands</a></li>

                                <li class="menu-item narrow"><a href="{{url('/blogs')}}">Blog</a></li>

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
                                <li><a href="{{route('filter.upcoming')}}">Coming Soon</a></li>
                                <li><a href="{{route('package.index')}}">Packages <span
                                            class="badge badge-danger">New</span></a></li>
                                <li><a href="{{url('/blogs')}}">Blog</a></li>
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
                    <li><a href="{{url('/blogs')}}">Blog</a></li>
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

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
    <a id="scroll-top" href="#top" title="Top" role="button"><i class="fas fa-chevron-up"></i></a>

    @include('layouts.footer')
    @yield('script')
</body>

</html>