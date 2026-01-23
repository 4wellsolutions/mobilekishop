<aside class="left-sidebar" data-sidebarbg="skin5">
  <!-- Sidebar scroll-->
  <div class="scroll-sidebar">
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav" >
      <ul id="sidebarnav" class="pt-4" style="padding-bottom: 50px;">
        <li class="sidebar-item">
          <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('dashboard.index')}}" aria-expanded="false">
            <i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span>
          </a>
        </li>
        <!-- Main Products Menu -->
        <li class="sidebar-item">
          <a class="sidebar-link has-arrow waves-effect waves-dark {{ \Request::routeIs('dashboard.products.*') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false">
            <i class="fas fa-mobile-alt"></i><span class="hide-menu">Products</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level {{ \Request::routeIs('dashboard.products.*') ? 'in' : '' }}">
            <li class="sidebar-item">
              <a href="{{route('dashboard.products.index')}}" class="sidebar-link {{ \Request::routeIs('dashboard.products.index') ? 'active' : '' }}">
                <i class="mdi mdi-view-list"></i><span class="hide-menu">Index</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="{{route('dashboard.products.create')}}" class="sidebar-link {{ \Request::routeIs('dashboard.products.create') ? 'active' : '' }}">
                <i class="mdi mdi-plus-circle"></i><span class="hide-menu">Create</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="{{route('dashboard.products.scrap')}}" class="sidebar-link {{ \Request::routeIs('dashboard.products.scrap') ? 'active' : '' }}">
                <i class="mdi mdi-plus-circle"></i><span class="hide-menu">Scrap</span>
              </a>
            </li>
          </ul>
        </li>

        <!-- Categories Menu -->
        <li class="sidebar-item">
          <a class="sidebar-link has-arrow waves-effect waves-dark {{ \Request::routeIs('dashboard.categories.*') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false">
            <i class="mdi mdi-folder"></i><span class="hide-menu">Categories</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level {{ \Request::routeIs('dashboard.categories.*') ? 'in' : '' }}">
            <li class="sidebar-item">
              <a href="{{route('dashboard.categories.index')}}" class="sidebar-link {{ \Request::routeIs('dashboard.categories.index') ? 'active' : '' }}">
                <i class="mdi mdi-view-list"></i><span class="hide-menu">All Categories</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="{{route('dashboard.categories.create')}}" class="sidebar-link {{ \Request::routeIs('dashboard.categories.create') ? 'active' : '' }}">
                <i class="mdi mdi-plus-circle"></i><span class="hide-menu">Create Category</span>
              </a>
            </li>
          </ul>
        </li>

        <!-- Attributes Menu -->
        <li class="sidebar-item">
          <a class="sidebar-link has-arrow waves-effect waves-dark {{ \Request::routeIs('dashboard.attributes.*') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false">
            <i class="mdi mdi-settings"></i><span class="hide-menu">Attributes</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level {{ \Request::routeIs('dashboard.attributes.*') ? 'in' : '' }}">
            <li class="sidebar-item">
              <a href="{{route('dashboard.attributes.index')}}" class="sidebar-link {{ \Request::routeIs('dashboard.attributes.index') ? 'active' : '' }}">
                <i class="mdi mdi-view-list"></i><span class="hide-menu">All Attributes</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="{{route('dashboard.attributes.create')}}" class="sidebar-link {{ \Request::routeIs('dashboard.attributes.create') ? 'active' : '' }}">
                <i class="mdi mdi-plus-circle"></i><span class="hide-menu">Create Attribute</span>
              </a>
            </li>
          </ul>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
            <i class="mdi mdi-package-variant-closed"></i><span class="hide-menu">Packages</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="{{route('dashboard.packages.create')}}" class="sidebar-link">
                <i class="mdi mdi-plus-circle"></i><span class="hide-menu">Create</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="{{route('dashboard.packages.index')}}" class="sidebar-link">
                <i class="mdi mdi-view-list"></i><span class="hide-menu">Index</span>
              </a>
            </li>
          </ul>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
            <i class="mdi mdi-chart-bubble"></i><span class="hide-menu">PTA Tax</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="{{route('dashboard.taxes.create')}}" class="sidebar-link">
                <i class="mdi mdi-plus-circle"></i><span class="hide-menu">Create</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="{{route('dashboard.taxes.index')}}" class="sidebar-link">
                <i class="mdi mdi-view-list"></i><span class="hide-menu">Index</span>
              </a>
            </li>
          </ul>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
            <i class="mdi mdi-tag-multiple"></i><span class="hide-menu">Brands</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="{{route('dashboard.brands.create')}}" class="sidebar-link">
                <i class="mdi mdi-plus-circle"></i><span class="hide-menu">Create</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="{{route('dashboard.brands.index')}}" class="sidebar-link">
                <i class="mdi mdi-view-list"></i><span class="hide-menu">Index</span>
              </a>
            </li>
          </ul>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
            <i class="mdi mdi-content-copy"></i><span class="hide-menu">Compare</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="{{route('dashboard.compares.create')}}" class="sidebar-link">
                <i class="mdi mdi-plus-circle"></i><span class="hide-menu">Create</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="{{route('dashboard.compares.index')}}" class="sidebar-link">
                <i class="mdi mdi-view-list"></i><span class="hide-menu">Index</span>
              </a>
            </li>
          </ul>
        </li>

        <!-- Pages Menu -->
        <li class="sidebar-item">
          <a class="sidebar-link has-arrow waves-effect waves-dark {{ \Request::routeIs('dashboard.pages.*') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false">
            <i class="mdi mdi-file-document"></i><span class="hide-menu">Pages</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level {{ \Request::routeIs('dashboard.pages.*') ? 'in' : '' }}">
            <li class="sidebar-item">
              <a href="{{route('dashboard.pages.index')}}" class="sidebar-link {{ \Request::routeIs('dashboard.pages.index') ? 'active' : '' }}">
                <i class="mdi mdi-view-list"></i><span class="hide-menu">All Pages</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="{{route('dashboard.pages.create')}}" class="sidebar-link {{ \Request::routeIs('dashboard.pages.create') ? 'active' : '' }}">
                <i class="mdi mdi-plus-circle"></i><span class="hide-menu">Create Page</span>
              </a>
            </li>
          </ul>
        </li>


        <li class="sidebar-item">
          <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
            <i class="mdi mdi-palette"></i><span class="hide-menu">Colors</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="{{route('dashboard.colors.create')}}" class="sidebar-link">
                <i class="mdi mdi-plus-circle"></i><span class="hide-menu">Create</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="{{route('dashboard.colors.index')}}" class="sidebar-link">
                <i class="mdi mdi-view-list"></i><span class="hide-menu">Index</span>
              </a>
            </li>
          </ul>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
            <i class="mdi mdi-tune"></i><span class="hide-menu">Variants</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="{{route('dashboard.variants.create')}}" class="sidebar-link">
                <i class="mdi mdi-plus-circle"></i><span class="hide-menu">Create</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="{{route('dashboard.variants.index')}}" class="sidebar-link">
                <i class="mdi mdi-view-list"></i><span class="hide-menu">Index</span>
              </a>
            </li>
          </ul>
        </li>

        <!-- New Redirections Menu -->
        <li class="sidebar-item">
          <a class="sidebar-link has-arrow waves-effect waves-dark {{ \Request::routeIs('dashboard.redirections.*') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false">
            <i class="mdi mdi-link"></i><span class="hide-menu">Redirections</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level {{ \Request::routeIs('dashboard.redirections.*') ? 'in' : '' }}">
            <li class="sidebar-item">
              <a href="{{route('dashboard.redirections.index')}}" class="sidebar-link {{ \Request::routeIs('dashboard.redirections.index') ? 'active' : '' }}">
                <i class="mdi mdi-view-list"></i><span class="hide-menu">All Redirections</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="{{route('dashboard.redirections.create')}}" class="sidebar-link {{ \Request::routeIs('dashboard.redirections.create') ? 'active' : '' }}">
                <i class="mdi mdi-plus-circle"></i><span class="hide-menu">Create Redirection</span>
              </a>
            </li>
          </ul>
        </li>
        <!-- Error Logs Menu -->
        <li class="sidebar-item">
          <a class="sidebar-link has-arrow waves-effect waves-dark {{ \Request::routeIs('dashboard.error_logs.*') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false">
            <i class="mdi mdi-alert-circle"></i><span class="hide-menu">Error Logs</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level {{ \Request::routeIs('dashboard.error_logs.*') ? 'in' : '' }}">
            <li class="sidebar-item">
              <a href="{{ route('dashboard.error_logs.index') }}" class="sidebar-link {{ \Request::routeIs('dashboard.error_logs.index') ? 'active' : '' }}">
                <i class="mdi mdi-view-list"></i><span class="hide-menu">Index</span>
              </a>
            </li>
          </ul>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
            <i class="mdi mdi-star"></i><span class="hide-menu">Reviews</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="{{route('dashboard.reviews.index')}}" class="sidebar-link">
                <i class="mdi mdi-view-list"></i><span class="hide-menu">Index</span>
              </a>
            </li>
          </ul>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
            <i class="mdi mdi-filter-variant"></i><span class="hide-menu">Filters</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="{{route('dashboard.filters.index')}}" class="sidebar-link">
                <i class="mdi mdi-view-list"></i><span class="hide-menu">Index</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="{{route('dashboard.filters.create')}}" class="sidebar-link">
                <i class="mdi mdi-plus-circle"></i><span class="hide-menu">Create</span>
              </a>
            </li>
          </ul>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
            <i class="mdi mdi-earth"></i><span class="hide-menu">Countries</span>
          </a>
          <ul aria-expanded="false" class="collapse first-level">
            <li class="sidebar-item">
              <a href="{{route('dashboard.countries.index')}}" class="sidebar-link">
                <i class="mdi mdi-view-list"></i><span class="hide-menu">Index</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a href="{{route('dashboard.countries.create')}}" class="sidebar-link">
                <i class="mdi mdi-plus-circle"></i><span class="hide-menu">Create</span>
              </a>
            </li>
          </ul>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                <i class="mdi mdi-robot"></i><span class="hide-menu">Robots</span>
            </a>
            <ul aria-expanded="false" class="collapse first-level">
                <li class="sidebar-item">
                    <a href="{{ route('dashboard.robots.index') }}" class="sidebar-link">
                        <i class="mdi mdi-view-list"></i><span class="hide-menu">Index</span>
                    </a>
                </li>
                <!-- Add other sub-menu items if needed -->
            </ul>
        </li>


      </ul>
    </nav>
    <!-- End Sidebar navigation -->
  </div>
  <!-- End Sidebar scroll-->
</aside>
