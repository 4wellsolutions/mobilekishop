<!-- Login/Register Modal -->
<div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm" id="loginModal">
  <div
    class="bg-white rounded-2xl shadow-2xl ring-1 ring-slate-200 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto dark:bg-slate-900 dark:ring-slate-800">
    <div class="flex items-center justify-end p-3">
      <button type="button" onclick="document.getElementById('loginModal').classList.replace('flex','hidden')"
        class="p-1 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <div class="px-6 pb-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="validation-errors"></div>
        <div>
          <h4 class="text-lg font-bold text-primary uppercase mb-4">Login</h4>
          <form action="#" method="post" id="loginForm" data-url="{{ url('auth/login') }}">
            @csrf
            <div class="mb-3">
              <label for="login-email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email
                address <span class="text-red-500">*</span></label>
              <input type="email" name="login_email" id="login-email" required
                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white" />
            </div>
            <div class="mb-3">
              <label for="login-password"
                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password <span
                  class="text-red-500">*</span></label>
              <input type="password" name="login_password" id="login-password" required
                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white" />
            </div>
            <button type="submit"
              class="w-full px-4 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-lg hover:bg-slate-800 transition login_button dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100">Login</button>

            <label class="flex items-center gap-2 mt-3 cursor-pointer">
              <input type="checkbox" name="remember" id="lost-password"
                class="size-4 rounded border-slate-300 text-primary focus:ring-primary">
              <span class="text-sm text-slate-600 dark:text-slate-400">Remember Me</span>
            </label>

            <div class="mt-3">
              <a href="#" data-href="{{ URL::to('/google/redirect') }}" id="sign-with-google">
                <img src="{{ URL::to('/images/login-with-google.png') }}" alt="login-with-google"
                  class="max-w-full h-auto">
              </a>
            </div>
            <a href="#" class="text-xs text-slate-500 hover:text-primary mt-2 inline-block forget-password">Forgot your
              password?</a>
          </form>
        </div>

        <div>
          <h4 class="text-lg font-bold text-primary uppercase mb-4">Register</h4>
          <form action="{{ url('register') }}" method="post" data-url="{{ url('auth/register') }}" id="registerForm">
            @csrf
            <div class="mb-3">
              <label for="register-name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Full
                Name <span class="text-red-500">*</span></label>
              <input type="text" name="name" id="register-name" required
                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
            </div>
            <div class="mb-3">
              <label for="register-phone"
                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Phone Number <span
                  class="text-red-500">*</span></label>
              <input type="text" name="phone_number" id="register-phone" required
                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
            </div>
            <div class="mb-3">
              <label for="register-email"
                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email address <span
                  class="text-red-500">*</span></label>
              <input type="email" name="email" id="register-email" required
                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
            </div>
            <div class="mb-3">
              <label for="register-password"
                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password <span
                  class="text-red-500">*</span></label>
              <input type="password" name="password" id="register-password" required
                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
            </div>
            <button type="submit"
              class="w-full px-4 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-lg hover:bg-slate-800 transition register_button dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100">Register</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="bg-slate-900 py-8 text-slate-300">
  <div class="max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div>
        <h3 class="text-sm font-bold text-white uppercase tracking-wide mb-3">About Us</h3>
        @php
          $pathSegments = explode('/', trim(request()->path(), '/'));
          $firstSegment = $pathSegments[0] ?? null;
          $allowedCountries = ['us', 'uk', 'bd', 'ae', 'in'];
          $countryCode = in_array($firstSegment, $allowedCountries) ? $firstSegment : 'pk';
          $homeUrl = $countryCode === 'pk' ? url('/') : url('/' . $countryCode);
        @endphp
        <a href="{{ $homeUrl }}" class="inline-block mb-2">
          <span class="text-xl font-bold text-white">MOBILE KI SHOP</span>
        </a>
        <p class="text-sm text-slate-400 leading-relaxed">
          MKS is leading source for the latest mobile phone, smart watches, tablets and other electronic devices
          prices, specs, reviews, and comparisons. Stay informed and make the best mobile choices with us.
        </p>
      </div>

      <div>
        <h3 class="text-sm font-bold text-white uppercase tracking-wide mb-3">Featured Links</h3>
        <ul class="space-y-1.5 text-sm">
          @php
            $urlPrefix = $countryCode === 'pk' ? '' : '/' . $countryCode;
          @endphp
          @if($categories = App\Models\Category::has('products')->where("is_active", 1)->get())
            @foreach($categories as $categori)
              <li>
                <a href="{{ url($urlPrefix . '/category/' . $categori->slug) }}"
                  class="text-slate-400 hover:text-white transition">{{ $categori->category_name }}</a>
              </li>
            @endforeach
          @endif
          @if(session('country_code') == 'pk')
            <li>
              <a href="{{ url($urlPrefix . '/packages') }}"
                class="text-slate-400 hover:text-white transition">Packages</a>
            </li>
          @endif
        </ul>
      </div>

      <div>
        <h3 class="text-sm font-bold text-white uppercase tracking-wide mb-3">Other Links</h3>
        <ul class="space-y-1.5 text-sm">
          <li><a href="{{ url($urlPrefix . '/about-us') }}" class="text-slate-400 hover:text-white transition">About
              Us</a></li>
          <li><a href="{{ url($urlPrefix . '/privacy-policy') }}"
              class="text-slate-400 hover:text-white transition">Privacy Policy</a></li>
          <li><a href="{{ url($urlPrefix . '/terms-and-conditions') }}"
              class="text-slate-400 hover:text-white transition">Terms and Conditions</a></li>
          <li><a href="{{ url($urlPrefix . '/html-sitemap') }}"
              class="text-slate-400 hover:text-white transition">Sitemap</a></li>
          <li><a href="{{ url($urlPrefix . '/contact') }}" class="text-slate-400 hover:text-white transition">Contact
              Us</a></li>
        </ul>
        <div class="flex items-center gap-3 mt-4">
          <a href="https://www.facebook.com/mobilekishop/" rel="noreferrer" target="_blank" title="Facebook"
            class="hover:opacity-80 transition">
            <img src="{{ URL::to('/images/icons/facebook.png') }}" alt="facebook" width="24" height="24">
          </a>
          <a href="https://twitter.com/mobilekishop" rel="noreferrer" target="_blank" title="Twitter"
            class="hover:opacity-80 transition">
            <img src="{{ URL::to('/images/icons/twitter.png') }}" alt="twitter" width="24" height="24">
          </a>
          <a href="https://www.instagram.com/mobilekishop/" rel="noreferrer" target="_blank" title="Instagram"
            class="hover:opacity-80 transition">
            <img src="{{ URL::to('/images/icons/instagram.png') }}" alt="instagram" width="24" height="24">
          </a>
          <a href="https://www.youtube.com/@MobileKiShop" rel="noreferrer" target="_blank"
            class="hover:opacity-80 transition">
            <img src="{{ URL::to('/images/icons/youtube.png') }}" alt="youtube" width="24" height="24">
          </a>
        </div>
      </div>
    </div>

    <div class="border-t border-slate-800 mt-8 pt-6 flex items-center justify-between">
      <p class="text-sm text-slate-500">Copyright © {{ date('Y') }}. All Rights Reserved</p>
    </div>
  </div>
</footer>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.2.3/css/flag-icons.min.css" />
@if (App::environment('production'))
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=GT-KFLGKWJ"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag('js', new Date());

    gtag('config', 'GT-KFLGKWJ');
    gtag('config', 'G-1TRC97HYME');
  </script>
@endif

@yield("script")

@yield("style")

</body>

</html>