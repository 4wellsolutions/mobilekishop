<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header w-auto"
        style="border-bottom: none;position: absolute;right: -10px;top: -15px;z-index: 100;">
        <button type="button" class="close btn-dark bg-white border-0 text-dark" aria-label="Close"
          data-bs-dismiss="modal">x</button>
      </div>
      <div class="modal-body">
        <div class="row row-sparse">
          <div class="col-11">
            <div class="validation-errors"></div>
          </div>
          <div class="col-md-6">
            <h4 class="fs-4 text-primary text-uppercase mt-2">Login</h4>
            <form action="#" method="post" id="loginForm" data-url="{{url('auth/login')}}">
              @csrf
              <label for="login-email">Email address <span class="required">*</span></label>
              <input type="email" name="login_email" class="form-control mb-2 rounded-0" id="login-email" required />

              <label for="login-password">Password <span class="required">*</span></label>
              <input type="password" name="login_password" class="form-control mb-2 rounded-0" id="login-password"
                required />

              <div class="form-footer my-1">
                <button type="submit" class="btn btn-dark btn-md rounded-0 w-100 login_button">Login</button>

                <div class="custom-control custom-checkbox form-footer-right">
                  <input type="checkbox" name="remember" class="custom-control-input" id="lost-password">
                  <label class="custom-control-label form-footer-right" for="lost-password">Remember Me</label>
                </div>

              </div>
              <div class="row">
                <div class="col-12 col-sm-6">
                  <a href="#" data-href="{{URL::to('/google/redirect')}}" id="sign-with-google">
                    <img src="{{URL::to('/images/login-with-google.png')}}" alt="login-with-google" class="img-fluid">
                  </a>
                </div>
                <div class="col-12 col-sm-6 d-none">
                  <a href="#" id="sign-with-facebook">
                    <img src="{{URL::to('/images/login-with-facebook.jpg')}}" alt="login-with-facebook"
                      class="img-fluid">
                  </a>
                </div>
              </div>
              <u>
                <small>
                  <a href="#" class="forget-password text-decoration-none text-secondary">Forgot your password?</a>
                </small>
              </u>
            </form>

          </div><!-- End .col-md-6 -->

          <div class="col-md-6">
            <h4 class="fs-4 text-primary text-uppercase mt-2">Register</h4>

            <form action="{{url('register')}}" method="post" data-url="{{url('auth/register')}}" id="registerForm">
              @csrf
              <label for="register-name">Full Name <span class="required">*</span></label>
              <input type="text" name="name" class="form-control mb-2 rounded-0" id="register-name" required>

              <label for="register-phone">Phone Number<span class="required">*</span></label>
              <input type="text" name="phone_number" class="form-control mb-2 rounded-0" id="register-phone" required>

              <label for="register-email">Email address <span class="required">*</span></label>
              <input type="email" name="email" class="form-control mb-2 rounded-0" id="register-email" required>

              <label for="register-password">Password <span class="required">*</span></label>
              <input type="password" name="password" class="form-control mb-2 rounded-0" id="register-password"
                required>

              <div class="custom-control custom-checkbox my-2">
                <input type="checkbox" name="newsletter" class="custom-control-input" id="newsletter-signup" checked>
                <label class="custom-control-label" for="newsletter-signup">Sign up our Newsletter</label>
              </div><!-- End .custom-checkbox -->

              <div class="form-footer">
                <button type="submit" class="btn btn-dark btn-md w-100 rounded-0 register_button">Register</button>
              </div><!-- End .form-footer -->
            </form>
          </div><!-- End .col-md-6 -->
        </div><!-- End .row -->
      </div>

    </div>
  </div>
</div>
<footer class="footer bg-dark py-3">
  <div class="container">
    <div class="footer-top d-flex flex-wrap">
      <div class="col-12 col-md-4 col-lg-5">
        <div class="widget widget-about pe-2">
          <p class="widget-title fs-6 fw-bolder">About Us</p>
          @php
            // Extract country from current URL path
            $pathSegments = explode('/', trim(request()->path(), '/'));
            $firstSegment = $pathSegments[0] ?? null;
            $allowedCountries = ['us', 'uk', 'bd', 'ae', 'in'];
            $countryCode = in_array($firstSegment, $allowedCountries) ? $firstSegment : 'pk';
            $homeUrl = $countryCode === 'pk' ? url('/') : url('/' . $countryCode);
          @endphp
          <a href="{{$homeUrl}}" class="logo mb-2">
            <p class="text-white fs-4 fw-bolder">MOBILE KI SHOP</p>
          </a>
          <p class="mb-3 text-white fw-normal">
            MKS is leading source for the latest mobile phone, smart watches, tablets and other electronic devices
            prices, specs, reviews, and comparisons. Stay informed and make the best mobile choices with us.
          </p>
        </div>
      </div>

      <div class="col-12 col-md-5 col-lg-5">
        <div class="widget">
          <p class="widget-title fs-6 fw-bolder">Featured Links</p>
          <ul class="contact-info list-unstyled">
            @php
              // Extract country from current URL path
              $pathSegments = explode('/', trim(request()->path(), '/'));
              $firstSegment = $pathSegments[0] ?? null;
              $allowedCountries = ['us', 'uk', 'bd', 'ae', 'in'];
              $countryCode = in_array($firstSegment, $allowedCountries) ? $firstSegment : 'pk';
              $urlPrefix = $countryCode === 'pk' ? '' : '/' . $countryCode;
            @endphp
            @if($categories = App\Category::has('products')->where("is_active", 1)->get())
              @foreach($categories as $categori)
                <li>
                  <a href="{{url($urlPrefix . '/category/' . $categori->slug)}}"
                    class="text-decoration-none">{{$categori->category_name}}</a>
                </li>
              @endforeach
            @endif
            @if(session('country_code') == 'pk')
              <li>
                <a href="{{url($urlPrefix . '/packages')}}" class="text-decoration-none">Packages</a>
              </li>
            @endif
          </ul>
        </div>

      </div>

      <div class="col-12 col-md-3 col-lg-2">
        <div class="widget">
          <p class="widget-title fs-6 fw-bolder">Other Links</p>
          <ul class="list-unstyled">

            <li>
              <a href="{{url($urlPrefix . '/about-us')}}" class="text-decoration-none">About Us</a>
            </li>
            <li>
              <a href="{{url($urlPrefix . '/privacy-policy')}}" class="text-decoration-none">Privacy Policy</a>
            </li>
            <li>
              <a href="{{url($urlPrefix . '/terms-and-conditions')}}" class="text-decoration-none">Terms and
                Conditions</a>
            </li>
            <li>
              <a href="{{url($urlPrefix . '/html-sitemap')}}" class="text-decoration-none">Sitemap</a>
            </li>
            <li>
              <a href="{{url($urlPrefix . '/contact')}}" class="text-decoration-none">Contact Us</a>
            </li>
          </ul>
        </div>
        <div class="social-icons">
          <a href="https://www.facebook.com/mobilekishop/" rel="noreferrer" class="text-decoration-none" target="_blank"
            title="Facebook">
            <img src="{{URL::to('/images/icons/facebook.png')}}" alt="facebook" width="24" height="24">
          </a>
          <a href="https://twitter.com/mobilekishop" rel="noreferrer" class="text-decoration-none" target="_blank"
            title="Twitter">
            <img src="{{URL::to('/images/icons/twitter.png')}}" alt="twitter" width="24" height="24">
          </a>
          <a href="https://www.instagram.com/mobilekishop/" rel="noreferrer" class="text-decoration-none"
            target="_blank" title="Instagram">
            <img src="{{URL::to('/images/icons/instagram.png')}}" alt="instagram" width="24" height="24">
          </a>
          <a href="https://www.youtube.com/@MobileKiShop" rel="noreferrer" class="text-decoration-none" target="_blank">
            <img src="{{URL::to('/images/icons/youtube.png')}}" alt="youtube" width="24" height="24">
          </a>
        </div>
      </div>
    </div>
    <div class="footer-bottom d-flex justify-content-between align-items-center flex-wrap py-3">
      <p class="footer-copyright mb-0 text-white">Copyright © {{date('Y')}}. All Rights Reserved</p>
    </div>
  </div>

</footer>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"
  integrity="sha512-pax4MlgXjHEPfCwcJLQhigY7+N8rt6bVvWLFyUMuxShv170X53TRzGPmPkZmGBhk+jikR8WBM4yl7A9WMHHqvg=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript">
  /*! echo-js v1.7.3 | (c) 2016 @toddmotto | https://github.com/toddmotto/echo */
  !function (t, e) { "function" == typeof define && define.amd ? define(function () { return e(t) }) : "object" == typeof exports ? module.exports = e : t.echo = e(t) }(this, function (t) { "use strict"; var e, n, o, r, c, a = {}, u = function () { }, d = function (t) { return null === t.offsetParent }, l = function (t, e) { if (d(t)) return !1; var n = t.getBoundingClientRect(); return n.right >= e.l && n.bottom >= e.t && n.left <= e.r && n.top <= e.b }, i = function () { (r || !n) && (clearTimeout(n), n = setTimeout(function () { a.render(), n = null }, o)) }; return a.init = function (n) { n = n || {}; var d = n.offset || 0, l = n.offsetVertical || d, f = n.offsetHorizontal || d, s = function (t, e) { return parseInt(t || e, 10) }; e = { t: s(n.offsetTop, l), b: s(n.offsetBottom, l), l: s(n.offsetLeft, f), r: s(n.offsetRight, f) }, o = s(n.throttle, 250), r = n.debounce !== !1, c = !!n.unload, u = n.callback || u, a.render(), document.addEventListener ? (t.addEventListener("scroll", i, !1), t.addEventListener("load", i, !1)) : (t.attachEvent("onscroll", i), t.attachEvent("onload", i)) }, a.render = function (n) { for (var o, r, d = (n || document).querySelectorAll("[data-echo], [data-echo-background]"), i = d.length, f = { l: 0 - e.l, t: 0 - e.t, b: (t.innerHeight || document.documentElement.clientHeight) + e.b, r: (t.innerWidth || document.documentElement.clientWidth) + e.r }, s = 0; i > s; s++)r = d[s], l(r, f) ? (c && r.setAttribute("data-echo-placeholder", r.src), null !== r.getAttribute("data-echo-background") ? r.style.backgroundImage = "url(" + r.getAttribute("data-echo-background") + ")" : r.src !== (o = r.getAttribute("data-echo")) && (r.src = o), c || (r.removeAttribute("data-echo"), r.removeAttribute("data-echo-background")), u(r, "load")) : c && (o = r.getAttribute("data-echo-placeholder")) && (null !== r.getAttribute("data-echo-background") ? r.style.backgroundImage = "url(" + o + ")" : r.src = o, r.removeAttribute("data-echo-placeholder"), u(r, "unload")); i || a.detach() }, a.detach = function () { document.removeEventListener ? t.removeEventListener("scroll", i) : t.detachEvent("onscroll", i), clearTimeout(n) }, a });
</script>
<script type="text/javascript">
  $(document).ready(function () {
    echo.init();
  });
  $('#sign-with-google').on('click', function (event) {
    event.preventDefault();
    var newHref = $(this).data('href');
    $(this).attr('href', newHref);
    window.location.href = newHref;
  });
  // Check if the screen size is less than 768px
  if (window.innerWidth < 768) {
    console.log("mobilemenu");
    $('.mobileMenu').append($('.sidebar').html()).removeClass("d-none");
  }

</script>
<script type="text/javascript">
  $('#reviewForm').on('submit', function (e) {
    var actionValue = $(this).attr('action');
    $(this).data('action', actionValue);
  });
  var loginForm = $("#loginForm");
  loginForm.submit(function (e) {
    // console.log("login");
    $(".login_button").html('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>');
    e.preventDefault();
    var formData = loginForm.serialize();
    // console.log(formData);
    $(".alert").html("").hide();
    var urlLogin = $(this).data("url");
    $.ajax({
      url: urlLogin,
      type: 'POST',
      data: formData,
      success: function (data) {
        // console.log(data.intended);
        $(".login_button").html('Login');
        if (data.auth) {
          // console.log(data.intended);
          location.reload();
        } else {
          $('.validation-errors').html("");
          // console.log(data.errors);
          $.each(data.errors, function (key, value) {
            $('.validation-errors').append('<div class="alert alert-danger py-2 rounded-0">' + value + '</div>');
          });
        }
      },
      error: function (data) {
        $('.validation-errors').append('<div class="alert alert-danger py-2 rounded-0">Contact Admin.</div>');
      }
    });
  });

  var registerForm = $("#registerForm");
  registerForm.submit(function (e) {
    // console.log("login");
    $(".alert").html("").hide();
    $(".register_button").html('<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>').attr("disabled", true);
    e.preventDefault();
    var formData = registerForm.serialize();
    var urlRegister = $(this).data("url");
    // console.log(formData);
    $.ajax({
      url: urlRegister,
      type: 'POST',
      data: formData,
      success: function (data) {
        if (data.success) {
          location.reload();
        } else {
          $('.validation-errors').html("");
          $.each(data.errors, function (key, value) {
            console.log(key);
            $('.validation-errors').append('<div class="alert alert-danger py-2 rounded-0">' + value + '</div>');
          });
        }
        $(".register_button").html('Register').attr("disabled", false);
      },
      error: function (data) {
        $(".register_button").html('Register').attr("disabled", false);
        $('.validation-errors').append('<div class="alert alert-danger py-2 rounded-0">Contact Admin.</div>');
      }
    });
  });

</script>
@if (App::environment('production'))
  <!-- Google tag (gtag.js) -->
  <script async defer src="https://www.googletagmanager.com/gtag/js?id=G-1TRC97HYME"></script>
  <script defer>
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag('js', new Date());
    gtag('config', 'G-1TRC97HYME');
  </script>
@endif

@yield("script")
<script type="text/javascript">
  $(".search-icon").click(function () {
    var id = $(this).data("id");
    if (id == 0) {
      $(this).data("id", 1);
      $(".search-bar").show();
    } else {
      $(this).data("id", 0);
      $(".search-bar").hide();
    }
  });
</script>
@php
  // 4429053821263976 is theseoteamorg account
  $numbers = ['9435537056478331', '4429053821263976'];
  $randomNumber = Arr::random($numbers);
@endphp
<script>
  // Function to load the AdSense script dynamically
  function loadAds() {
    // Avoid loading the script multiple times
    if (document.getElementById('adsense-script')) return;

    var script = document.createElement('script');
    script.id = 'adsense-script';
    script.async = true;
    script.src = "https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9435537056478331";
    script.setAttribute('crossorigin', 'anonymous');
    document.head.appendChild(script);

    // Remove the scroll event listener once the ad script is loaded
    window.removeEventListener('scroll', onScroll);
  }

  // Scroll event handler to check if user has scrolled half the viewport height
  function onScroll() {
    var scrollPos = window.scrollY || window.pageYOffset;
    var threshold = window.innerHeight / 2;

    if (scrollPos >= threshold) {
      loadAds();
    }
  }

  // Attach the scroll event listener
  window.addEventListener('scroll', onScroll);
</script>


<!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css"> -->

@yield("style")

</body>

</html>