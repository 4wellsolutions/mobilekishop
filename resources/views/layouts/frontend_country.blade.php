<!doctype html>
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
    @yield("noindex")
    <link rel="icon" type="image/x-icon" href="{{URL::to('/images/favicon.ico')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{URL::to('/images/favicon-apple.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{URL::to('/images/favicon32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{URL::to('/images/favicon.png')}}">
    {!! generate_hreflang_tags() !!}
    <meta name="contconcord" content="niOB7jcOCtDY4m6kkX4YFoYMQQIbvarT">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css"
        integrity="sha512-GQGU0fMMi238uA+a/bdWJfpUGKUkBdgfFdgBm72SUQ6BeyWjoY/ton0tEjH+OSH9iP4Dfh+7HM0I9f5eR0L/4w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <header>
        <div class="container-lg d-none d-md-block">
            <div class="row text-end">
                <ul class="list-inline my-1">
                    @if(Auth::check())
                        <li class="list-inline-item text-secondary">
                            <a href="{{route('user.index')}}" class="text-decoration-none text-secondary fs-14">My
                                Account</a>
                        </li>
                        <li class="list-inline-item border-start border-end px-2 text-secondary"><a
                                href="{{ route('logout') }}" class="text-decoration-none text-secondary fs-14"
                                style="line-height: 1.6rem;height: 1.6rem;"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </li>
                    @else
                        <li class="list-inline-item border-start border-end px-2 text-secondary"><a
                                class="text-decoration-none text-secondary" href="#" data-bs-toggle="modal"
                                data-bs-target="#loginModal">Login</a></li>
                    @endif
                    <li class="list-inline-item socialIcon">
                        <a target="_blank" href="https://www.facebook.com/mobilekishop/" rel="noreferrer"
                            title="Facebook">
                            <img src="{{URL::to('/images/icons/facebook.png')}}" alt="facebook" width="24" height="24">
                        </a>
                    </li>
                    <li class="list-inline-item socialIcon">
                        <a target="_blank" href="https://twitter.com/mobilekishop" rel="noreferrer" title="Twitter">
                            <img src="{{URL::to('/images/icons/twitter.png')}}" alt="twitter" width="24" height="24">
                        </a>
                    </li>
                    <li class="list-inline-item socialIcon">
                        <a target="_blank" href="https://www.instagram.com/mobilekishop/" rel="noreferrer"
                            title="Instagram">
                            <img src="{{URL::to('/images/icons/instagram.png')}}" alt="instagram" width="24"
                                height="24">
                        </a>
                    </li>
                    <li class="list-inline-item socialIcon">
                        <a href="https://www.youtube.com/@MobileKiShop" rel="noreferrer" target="_blank">
                            <img src="{{URL::to('/images/icons/youtube.png')}}" alt="youtube" width="24" height="24">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-top">
            <div class="container-lg">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand py-0" href="{{ URL::to('/') }}">
                    <!-- Mobile logo for screens with width <= 768px -->
                    <img src="{{ URL::to('/') }}/images/mobile-logo.png" alt="mobile-ki-shop-Logo"
                        class="img-fluid mks-logo mobile-logo" width="70" height="31" />

                    <!-- Default logo for screens with width > 768px -->
                    <img src="{{ URL::to('/') }}/images/logo.png" alt="mobile-ki-shop-Logo"
                        class="img-fluid mks-logo default-logo" width="110" height="48" />
                </a>


                <div>
                    <ul class="list-inline list-unstyled my-auto">
                        <li class="list-inline-item search-bar">
                            <form action="{{URL::to('/search')}}" class="form-inline" method="get">
                                <div class="input-group rounded-pill border border-3 m-2 bg-white">
                                    <input type="search" class="form-control rounded-pill  border-0" name="query"
                                        id="searchInput" placeholder="Search..." required>
                                    <div class="input-group-text bg-white rounded-pill border-0 border-start">
                                        <button class="bg-white border-0 px-0" type="submit"><img
                                                src="{{URL::to('/images/icons/search.png')}}" alt="search-icon"
                                                width="24" height="24"></button>
                                    </div>
                                </div><!-- End .header-search-wrapper -->
                            </form>
                        </li>
                        <li class="list-inline-item search-icon" data-id="0">
                            <a href="javascript:void(0)">
                                <img src="{{URL::to('/images/icons/search.png')}}" alt="search-icon" width="24"
                                    height="24">
                            </a>
                        </li>
                        <li class="list-inline-item">
                            @if(Auth::check())
                                <a href="{{route('user.index')}}">
                                    <img srcset="{{url('/')}}/images/icons/user.png 1x, {{url('/')}}/images/icons/user2x.png 2x, {{url('/')}}/images/icons/user3x.png 3x"
                                        src="{{url('/')}}/images/icons/user.png" alt="user-icon" width="24" height="24">
                                </a>
                            @else
                                <a href="#">
                                    <img srcset="{{url('/')}}/images/icons/user.png 1x, {{url('/')}}/images/icons/user2x.png 2x, {{url('/')}}/images/icons/user3x.png 3x"
                                        src="{{url('/')}}/images/icons/user.png" data-bs-toggle="modal"
                                        data-bs-target="#loginModal" alt="user-icon" width="24" height="24">
                                </a>
                            @endif
                        </li>
                        @php
                            $countries = App\Country::where("is_menu", 1)->get();
                            $currentCountry = App('App\Http\Controllers\CountryController')->getCountry();
                            $currentCountryCode = $currentCountry->country_code;
                            $currentCountryIcon = $currentCountry->icon;

                            // Get the current domain without subdomain
                            $baseDomain = request()->getHost();
                            $parts = explode('.', $baseDomain);
                            if (count($parts) > 2) {
                                array_shift($parts);
                            }
                            $baseDomain = implode('.', $parts);
                        @endphp

                        <li class="list-inline-item dropdown">
                            <a class="fw-bold dropdown-toggle text-uppercase px-1 text-dark text-decoration-none"
                                href="#" id="countryDropdown" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <span class="{{ $currentCountryIcon }}"></span> {{ strtoupper($currentCountryCode) }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="countryDropdown">
                                @foreach ($countries as $ctry)
                                    @php
                                        if (strtolower($ctry->country_code) !== 'pk') {
                                            $finalURL = 'https://' . strtolower($ctry->country_code) . '.' . $baseDomain;
                                        } else {
                                            $finalURL = 'https://' . $baseDomain;
                                        }
                                    @endphp

                                    @if (strtolower($ctry->country_code) !== $currentCountryCode)
                                        <li>
                                            <a class="dropdown-item text-uppercase" href="{{ $finalURL }}">
                                                <span class="{{ strtolower($ctry->icon) }}"></span>
                                                {{ strtoupper($ctry->country_code) }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <nav class="navbar navbar-expand-lg py-0 py-lg-2">
                <div class="container-fluid">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 mx-auto">
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" aria-current="page"
                                    href="{{ URL::to('/') }}">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteNamed('brands.by.category') ? 'active' : '' }}"
                                    href="{{ URL::to('/brands/all') }}">Brands</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteNamed('up.coming.mobiles') ? 'active' : '' }}"
                                    href="{{ URL::to('/up-coming-mobile-phones') }}">Up Coming</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link position-relative {{ Route::currentRouteNamed('comparison') ? 'active' : '' }}"
                                    href="{{ URL::to('/comparison') }}">Compares</a>
                            </li>
                            @php
                                $hasNews = \App\News::where('country_id', $country->id)->exists();
                            @endphp

                            @if($hasNews)
                                <li class="nav-item">
                                    <a class="nav-link position-relative {{ Request::is('news/*') ? 'active' : '' }}"
                                        href="{{url('/news')}}">News</a>
                                </li>
                            @endif
                        </ul>
                        <div class="mobileMenu d-none"></div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    @yield("content")
    <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
    @include("layouts.footer")