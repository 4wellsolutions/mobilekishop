<aside class="admin-sidebar" id="adminSidebar">
  {{-- Brand --}}
  <div class="sidebar-brand">
    <div class="brand-logo">M</div>
    <div class="brand-text">MKS <span>Admin</span></div>
  </div>

  {{-- Navigation --}}
  <div class="sidebar-scroll">

    {{-- Dashboard --}}
    <ul class="sidebar-nav-list">
      <li class="sidebar-nav-item">
        <a href="{{ route('dashboard.index') }}"
          class="sidebar-nav-link {{ Request::routeIs('dashboard.index') ? 'active' : '' }}">
          <i class="fas fa-th-large"></i>
          <span>Dashboard</span>
        </a>
      </li>
    </ul>

    {{-- Catalog --}}
    <p class="sidebar-group-label">Catalog</p>
    <ul class="sidebar-nav-list">
      {{-- Products (with all sub-items) --}}
      <li class="sidebar-nav-item">
        <a href="javascript:void(0)"
          class="sidebar-nav-link has-sub {{ Request::routeIs('dashboard.products.*') || Request::routeIs('dashboard.expert-ratings.*') || Request::routeIs('dashboard.categories.*') || Request::routeIs('dashboard.brands.*') || Request::routeIs('dashboard.attributes.*') || Request::routeIs('dashboard.variants.*') || Request::routeIs('dashboard.colors.*') ? 'open' : '' }}">
          <i class="fas fa-mobile-alt"></i>
          <span>Products</span>
        </a>
        <ul
          class="sidebar-sub-list {{ Request::routeIs('dashboard.products.*') || Request::routeIs('dashboard.expert-ratings.*') || Request::routeIs('dashboard.categories.*') || Request::routeIs('dashboard.brands.*') || Request::routeIs('dashboard.attributes.*') || Request::routeIs('dashboard.variants.*') || Request::routeIs('dashboard.colors.*') ? 'open' : '' }}">
          <li class="sidebar-nav-item">
            <a href="{{ route('dashboard.products.index') }}"
              class="sidebar-nav-link {{ Request::routeIs('dashboard.products.index') ? 'active' : '' }}">
              All Products
            </a>
          </li>
          <li class="sidebar-nav-item">
            <a href="{{ route('dashboard.products.create') }}"
              class="sidebar-nav-link {{ Request::routeIs('dashboard.products.create') ? 'active' : '' }}">
              Add Product
            </a>
          </li>
          <li class="sidebar-nav-item">
            <a href="{{ route('dashboard.products.scrap') }}"
              class="sidebar-nav-link {{ Request::routeIs('dashboard.products.scrap') ? 'active' : '' }}">
              Scraper
            </a>
          </li>
          <li class="sidebar-nav-item">
            <a href="{{ route('dashboard.expert-ratings.index') }}"
              class="sidebar-nav-link {{ Request::routeIs('dashboard.expert-ratings.*') ? 'active' : '' }}">
              Expert Ratings
            </a>
          </li>
          <li class="sidebar-nav-item">
            <a href="{{ route('dashboard.categories.index') }}"
              class="sidebar-nav-link {{ Request::routeIs('dashboard.categories.*') ? 'active' : '' }}">
              Categories
            </a>
          </li>
          <li class="sidebar-nav-item">
            <a href="{{ route('dashboard.brands.index') }}"
              class="sidebar-nav-link {{ Request::routeIs('dashboard.brands.*') ? 'active' : '' }}">
              Brands
            </a>
          </li>
          <li class="sidebar-nav-item">
            <a href="{{ route('dashboard.attributes.index') }}"
              class="sidebar-nav-link {{ Request::routeIs('dashboard.attributes.*') ? 'active' : '' }}">
              Attributes
            </a>
          </li>
          <li class="sidebar-nav-item">
            <a href="{{ route('dashboard.variants.index') }}"
              class="sidebar-nav-link {{ Request::routeIs('dashboard.variants.*') ? 'active' : '' }}">
              Variants
            </a>
          </li>
          <li class="sidebar-nav-item">
            <a href="{{ route('dashboard.colors.index') }}"
              class="sidebar-nav-link {{ Request::routeIs('dashboard.colors.*') ? 'active' : '' }}">
              Colors
            </a>
          </li>
        </ul>

        {{-- Content --}}
        <p class="sidebar-group-label">Content</p>
        <ul class="sidebar-nav-list">
          {{-- Pages (with Filters) --}}
          <li class="sidebar-nav-item">
            <a href="javascript:void(0)"
              class="sidebar-nav-link has-sub {{ Request::routeIs('dashboard.pages.*') || Request::routeIs('dashboard.filters.*') ? 'open' : '' }}">
              <i class="fas fa-file-alt"></i>
              <span>Pages</span>
            </a>
            <ul
              class="sidebar-sub-list {{ Request::routeIs('dashboard.pages.*') || Request::routeIs('dashboard.filters.*') ? 'open' : '' }}">
              <li class="sidebar-nav-item">
                <a href="{{ route('dashboard.pages.index') }}"
                  class="sidebar-nav-link {{ Request::routeIs('dashboard.pages.index') ? 'active' : '' }}">
                  All Pages
                </a>
              </li>
              <li class="sidebar-nav-item">
                <a href="{{ route('dashboard.pages.create') }}"
                  class="sidebar-nav-link {{ Request::routeIs('dashboard.pages.create') ? 'active' : '' }}">
                  Add Page
                </a>
              </li>
              <li class="sidebar-nav-item">
                <a href="{{ route('dashboard.filters.index') }}"
                  class="sidebar-nav-link {{ Request::routeIs('dashboard.filters.*') ? 'active' : '' }}">
                  Filters
                </a>
              </li>
            </ul>
          </li>

          {{-- Compares --}}
          <li class="sidebar-nav-item">
            <a href="javascript:void(0)"
              class="sidebar-nav-link has-sub {{ Request::routeIs('dashboard.compares.*') ? 'open' : '' }}">
              <i class="fas fa-balance-scale"></i>
              <span>Comparisons</span>
            </a>
            <ul class="sidebar-sub-list {{ Request::routeIs('dashboard.compares.*') ? 'open' : '' }}">
              <li class="sidebar-nav-item">
                <a href="{{ route('dashboard.compares.index') }}"
                  class="sidebar-nav-link {{ Request::routeIs('dashboard.compares.index') ? 'active' : '' }}">
                  All Comparisons
                </a>
              </li>
              <li class="sidebar-nav-item">
                <a href="{{ route('dashboard.compares.create') }}"
                  class="sidebar-nav-link {{ Request::routeIs('dashboard.compares.create') ? 'active' : '' }}">
                  Add Comparison
                </a>
              </li>
            </ul>
          </li>

          {{-- Reviews --}}
          <li class="sidebar-nav-item">
            <a href="{{ route('dashboard.reviews.index') }}"
              class="sidebar-nav-link {{ Request::routeIs('dashboard.reviews.*') ? 'active' : '' }}">
              <i class="fas fa-star"></i>
              <span>Reviews</span>
            </a>
          </li>

          {{-- Blog --}}
          <li class="sidebar-nav-item">
            <a href="javascript:void(0)"
              class="sidebar-nav-link has-sub {{ Request::routeIs('dashboard.blogs.*') || Request::routeIs('dashboard.blog-categories.*') ? 'open' : '' }}">
              <i class="fas fa-blog"></i>
              <span>Blog</span>
            </a>
            <ul
              class="sidebar-sub-list {{ Request::routeIs('dashboard.blogs.*') || Request::routeIs('dashboard.blog-categories.*') ? 'open' : '' }}">
              <li class="sidebar-nav-item">
                <a href="{{ route('dashboard.blogs.index') }}"
                  class="sidebar-nav-link {{ Request::routeIs('dashboard.blogs.index') ? 'active' : '' }}">
                  All Posts
                </a>
              </li>
              <li class="sidebar-nav-item">
                <a href="{{ route('dashboard.blogs.create') }}"
                  class="sidebar-nav-link {{ Request::routeIs('dashboard.blogs.create') ? 'active' : '' }}">
                  Add Post
                </a>
              </li>
              <li class="sidebar-nav-item">
                <a href="{{ route('dashboard.blog-categories.index') }}"
                  class="sidebar-nav-link {{ Request::routeIs('dashboard.blog-categories.index') || Request::routeIs('dashboard.blog-categories.edit') ? 'active' : '' }}">
                  Categories
                </a>
              </li>
              <li class="sidebar-nav-item">
                <a href="{{ route('dashboard.blog-categories.create') }}"
                  class="sidebar-nav-link {{ Request::routeIs('dashboard.blog-categories.create') ? 'active' : '' }}">
                  Add Category
                </a>
              </li>
            </ul>
          </li>

          {{-- Media Library --}}
          <li class="sidebar-nav-item">
            <a href="{{ route('dashboard.media.index') }}"
              class="sidebar-nav-link {{ Request::routeIs('dashboard.media.*') ? 'active' : '' }}">
              <i class="fas fa-photo-video"></i>
              <span>Media Library</span>
            </a>
          </li>

          {{-- PTA Tax --}}
          <li class="sidebar-nav-item">
            <a href="javascript:void(0)"
              class="sidebar-nav-link has-sub {{ Request::routeIs('dashboard.taxes.*') ? 'open' : '' }}">
              <i class="fas fa-calculator"></i>
              <span>PTA Tax</span>
            </a>
            <ul class="sidebar-sub-list {{ Request::routeIs('dashboard.taxes.*') ? 'open' : '' }}">
              <li class="sidebar-nav-item">
                <a href="{{ route('dashboard.taxes.index') }}"
                  class="sidebar-nav-link {{ Request::routeIs('dashboard.taxes.index') ? 'active' : '' }}">
                  All Taxes
                </a>
              </li>
              <li class="sidebar-nav-item">
                <a href="{{ route('dashboard.taxes.create') }}"
                  class="sidebar-nav-link {{ Request::routeIs('dashboard.taxes.create') ? 'active' : '' }}">
                  Add Tax
                </a>
              </li>
            </ul>
          </li>

          {{-- Packages --}}
          <li class="sidebar-nav-item">
            <a href="javascript:void(0)"
              class="sidebar-nav-link has-sub {{ Request::routeIs('dashboard.packages.*') ? 'open' : '' }}">
              <i class="fas fa-box"></i>
              <span>Packages</span>
            </a>
            <ul class="sidebar-sub-list {{ Request::routeIs('dashboard.packages.*') ? 'open' : '' }}">
              <li class="sidebar-nav-item">
                <a href="{{ route('dashboard.packages.index') }}"
                  class="sidebar-nav-link {{ Request::routeIs('dashboard.packages.index') ? 'active' : '' }}">
                  All Packages
                </a>
              </li>
              <li class="sidebar-nav-item">
                <a href="{{ route('dashboard.packages.create') }}"
                  class="sidebar-nav-link {{ Request::routeIs('dashboard.packages.create') ? 'active' : '' }}">
                  Add Package
                </a>
              </li>
            </ul>
          </li>
        </ul>

        {{-- System --}}
        <p class="sidebar-group-label">System</p>
        <ul class="sidebar-nav-list">
          <li class="sidebar-nav-item">
            <a href="javascript:void(0)"
              class="sidebar-nav-link has-sub {{ Request::routeIs('dashboard.settings.*') || Request::routeIs('dashboard.cache.*') || Request::routeIs('dashboard.sitemap.*') || Request::routeIs('dashboard.error_logs.*') || Request::routeIs('dashboard.countries.*') || Request::routeIs('dashboard.redirections.*') ? 'open' : '' }}">
              <i class="fas fa-cog"></i>
              <span>Settings</span>
            </a>
            <ul
              class="sidebar-sub-list {{ Request::routeIs('dashboard.settings.*') || Request::routeIs('dashboard.cache.*') || Request::routeIs('dashboard.sitemap.*') || Request::routeIs('dashboard.error_logs.*') || Request::routeIs('dashboard.countries.*') || Request::routeIs('dashboard.redirections.*') ? 'open' : '' }}">
              <li class="sidebar-nav-item">
                <a href="{{ route('dashboard.settings.index') }}"
                  class="sidebar-nav-link {{ Request::routeIs('dashboard.settings.*') ? 'active' : '' }}">
                  General
                </a>
              </li>
              <li class="sidebar-nav-item">
                <a href="{{ route('dashboard.countries.index') }}"
                  class="sidebar-nav-link {{ Request::routeIs('dashboard.countries.*') ? 'active' : '' }}">
                  Countries
                </a>
              </li>
              <li class="sidebar-nav-item">
                <a href="{{ route('dashboard.redirections.index') }}"
                  class="sidebar-nav-link {{ Request::routeIs('dashboard.redirections.*') ? 'active' : '' }}">
                  Redirections
                </a>
              </li>
              <li class="sidebar-nav-item">
                <a href="{{ route('dashboard.cache.index') }}"
                  class="sidebar-nav-link {{ Request::routeIs('dashboard.cache.*') ? 'active' : '' }}">
                  Cache
                </a>
              </li>
              <li class="sidebar-nav-item">
                <a href="{{ route('dashboard.sitemap.index') }}"
                  class="sidebar-nav-link {{ Request::routeIs('dashboard.sitemap.*') ? 'active' : '' }}">
                  Sitemap
                </a>
              </li>
              <li class="sidebar-nav-item">
                <a href="{{ route('dashboard.error_logs.index') }}"
                  class="sidebar-nav-link {{ Request::routeIs('dashboard.error_logs.*') ? 'active' : '' }}">
                  Error / 404 Logs
                </a>
              </li>
            </ul>
          </li>
        </ul>
  </div>

  {{-- Sidebar Footer --}}
  <div class="sidebar-footer">
    <a href="{{ route('dashboard.profile.index') }}" class="sidebar-user-card">
      <div class="sidebar-user-avatar">
        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
      </div>
      <div class="sidebar-user-info">
        <div class="sidebar-user-name">{{ Auth::user()->name ?? 'Admin' }}</div>
        <div class="sidebar-user-role">Administrator</div>
      </div>
      <i class="fas fa-chevron-right" style="color: var(--admin-text-muted); font-size: 12px;"></i>
    </a>
  </div>
</aside>