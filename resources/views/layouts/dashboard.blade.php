<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="robots" content="noindex,nofollow" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/x-icon" href="{{URL::to('/')}}/images/favicon.png">
  <title>@yield('title', 'Dashboard') — MKS Admin</title>

  {{-- Google Fonts: Inter --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  {{-- Font Awesome 6 --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  {{-- Bootstrap 5 CSS (minimal for grid/dropdown/pagination) --}}
  <link href="{{URL::to('/')}}/dist/css/style.min.css" rel="stylesheet" />

  {{-- Admin Modern Design System --}}
  <link href="{{URL::to('/')}}/css/admin-modern.css" rel="stylesheet" />
  <link href="{{URL::to('/')}}/css/html-editor.css" rel="stylesheet" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- Apply saved theme before paint to prevent flash --}}
  <script>!function () { var t = localStorage.getItem('admin-theme'); if (t) document.documentElement.setAttribute('data-theme', t); }();</script>

  @yield('styles')
</head>

<body>
  <div class="admin-layout">
    {{-- ====== SIDEBAR ====== --}}
    @include('includes.dashboard-sidebar')

    {{-- ====== SIDEBAR OVERLAY (Mobile) ====== --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- ====== MAIN CONTENT ====== --}}
    <div class="admin-main">
      {{-- ====== TOP HEADER ====== --}}
      <header class="admin-header">
        <div class="header-left">
          <button class="sidebar-toggle-btn" onclick="toggleSidebar()" title="Toggle menu">
            <i class="fas fa-bars"></i>
          </button>
          <div class="header-search">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search anything..." />
          </div>
        </div>
        <div class="header-right">
          <button class="header-icon-btn" id="themeToggleBtn" title="Toggle Dark Mode" onclick="toggleAdminTheme()">
            <i class="fas fa-moon" id="themeIcon"></i>
          </button>
          <a href="{{URL::to('/')}}" target="_blank" class="header-icon-btn" title="View Website">
            <i class="fas fa-external-link-alt"></i>
          </a>
          <div class="dropdown">
            <button class="header-user-btn" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="{{URL::to('/')}}/assets/images/users/1.jpg" alt="Admin" />
              <span>{{ Auth::user()->name ?? 'Admin' }}</span>
              <i class="fas fa-chevron-down" style="font-size:10px; opacity:0.5; margin-left:4px;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item" href="{{ route('dashboard.profile.index') }}">
                  <i class="fas fa-user me-2"></i>My Profile
                </a>
              </li>
              <li>
                <hr class="dropdown-divider" />
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('logout') }}"
                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
              </li>
            </ul>
          </div>
        </div>
      </header>

      {{-- ====== PAGE CONTENT ====== --}}
      <div class="admin-content">
        @yield('content')
      </div>
    </div>
  </div>

  {{-- Logout Form --}}
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
  </form>

  {{-- Core Scripts --}}
  <script src="{{URL::to('/')}}/assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="{{URL::to('/')}}/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{URL::to('/')}}/js/html-editor.js"></script>

  {{-- Sidebar Toggle Script --}}
  <script>
    function toggleSidebar() {
      document.querySelector('.admin-sidebar').classList.toggle('open');
      document.getElementById('sidebarOverlay').classList.toggle('active');
    }

    // Theme toggle
    function toggleAdminTheme() {
      var html = document.documentElement;
      var icon = document.getElementById('themeIcon');
      if (html.getAttribute('data-theme') === 'dark') {
        html.removeAttribute('data-theme');
        localStorage.setItem('admin-theme', '');
        icon.className = 'fas fa-moon';
      } else {
        html.setAttribute('data-theme', 'dark');
        localStorage.setItem('admin-theme', 'dark');
        icon.className = 'fas fa-sun';
      }
    }

    // Sub-menu toggles
    document.addEventListener('DOMContentLoaded', function () {
      // Set correct icon on load
      var icon = document.getElementById('themeIcon');
      if (icon) icon.className = document.documentElement.getAttribute('data-theme') === 'dark' ? 'fas fa-sun' : 'fas fa-moon';

      document.querySelectorAll('.sidebar-nav-link.has-sub').forEach(function (link) {
        link.addEventListener('click', function (e) {
          e.preventDefault();
          e.stopPropagation();
          this.classList.toggle('open');
          var parent = this.closest('.sidebar-nav-item');
          if (parent) {
            var subList = parent.querySelector('.sidebar-sub-list');
            if (subList) subList.classList.toggle('open');
          }
        });
      });

      // Dismiss alerts
      document.querySelectorAll('.admin-alert .alert-close').forEach(function (btn) {
        btn.addEventListener('click', function () {
          this.closest('.admin-alert').style.display = 'none';
        });
      });
    });
  </script>

  @yield('scripts')
</body>

</html>