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
            @if($categories = App\Models\Category::has('products')->where("is_active", 1)->get())
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
@if (App::environment('production'))
  <!-- Google tag (gtag.js) -->
  <script async defer src="https://www.googletagmanager.com/gtag/js?id=G-1TRC97HYME"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag('js', new Date());
    gtag('config', 'G-1TRC97HYME');
  </script>
@endif

@yield("script")

@yield("style")


<!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css"> -->

@yield("style")

</body>

</html>