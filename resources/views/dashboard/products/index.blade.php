@extends('layouts.dashboard')
@section('title', 'Products')

@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Products</h1>
      <div class="breadcrumb-nav">
        <a href="{{ route('dashboard.index') }}">Dashboard</a>
        <span class="separator">/</span>
        Products
      </div>
    </div>
    <a href="{{ route('dashboard.products.create') }}" class="btn-admin-primary">
      <i class="fas fa-plus"></i> Add Product
    </a>
  </div>

  {{-- Filter Panel --}}
  <div class="admin-filter-panel">
    <form action="{{ Request::fullUrl() }}" method="get">
      <div class="admin-filter-grid">
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">Date Filter</label>
          <select class="admin-form-control" name="date_filter">
            <option value="">Select Filter</option>
            <option value="created_at" {{ Request::get('date_filter') == 'created_at' ? 'selected' : '' }}>Created Date
            </option>
            <option value="updated_at" {{ Request::get('date_filter') == 'updated_at' ? 'selected' : '' }}>Updated Date
            </option>
          </select>
        </div>
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">From</label>
          <input type="date" name="date1" class="admin-form-control"
            value="{{ Request::get('date1', \Carbon\Carbon::now()->format('Y-m-d')) }}">
        </div>
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">To</label>
          <input type="date" name="date2" class="admin-form-control"
            value="{{ Request::get('date2', \Carbon\Carbon::now()->format('Y-m-d')) }}">
        </div>
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">Category</label>
          <select class="admin-form-control" name="category_id">
            <option value="">All Categories</option>
            @if($categories = App\Models\Category::all())
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ Request::get('category_id') == $category->id ? 'selected' : '' }}>
                  {{ $category->category_name }}</option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">Sort By</label>
          <select class="admin-form-control" name="filter_key">
            <option value="">Default</option>
            <option value="id" {{ Request::get('filter_key') == 'id' ? 'selected' : '' }}>ID</option>
            <option value="views" {{ Request::get('filter_key') == 'views' ? 'selected' : '' }}>Views</option>
            <option value="release_date" {{ Request::get('filter_key') == 'release_date' ? 'selected' : '' }}>Release Date
            </option>
            <option value="created_at" {{ Request::get('filter_key') == 'created_at' ? 'selected' : '' }}>Created Date
            </option>
            <option value="updated_at" {{ Request::get('filter_key') == 'updated_at' ? 'selected' : '' }}>Updated Date
            </option>
          </select>
        </div>
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">Order</label>
          <select class="admin-form-control" name="filter_value">
            <option value="">Default</option>
            <option value="ASC" {{ Request::get('filter_value') == 'ASC' ? 'selected' : '' }}>Ascending</option>
            <option value="DESC" {{ Request::get('filter_value') == 'DESC' ? 'selected' : '' }}>Descending</option>
          </select>
        </div>
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">Search</label>
          <input type="text" name="search" id="search" class="admin-form-control" placeholder="Search products..."
            value="{{ Request::get('search') }}">
        </div>
        <div class="admin-filter-actions">
          <button class="btn-admin-primary" type="submit">
            <i class="fas fa-search"></i> Filter
          </button>
        </div>
      </div>
    </form>
  </div>

  {{-- Products Table --}}
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>
        <span style="opacity:0.5; font-weight:400;">Total:</span> {{ App\Models\Product::count() }} products
        @if(isset($today) && $today > 0)
          <span class="admin-badge badge-success" style="margin-left:8px;">+{{ $today }} today</span>
        @endif
      </h2>
    </div>
    <div class="admin-card-body no-padding">
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Image</th>
              <th>Name</th>
              <th>Brand</th>
              <th>Category</th>
              <th>Release Date</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($products as $product)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                  <img src="{{ $product->thumbnail }}" alt="" class="td-image">
                </td>
                <td class="td-title">{{ Str::title($product->name) }}</td>
                <td>{{ $product->brand->name ?? 'N/A' }}</td>
                <td>
                  <span class="admin-badge badge-default">{{ $product->category->category_name ?? 'N/A' }}</span>
                </td>
                <td>
                  @if($releaseDate = $product->getAttributeValue('release_date'))
                    {{ \Carbon\Carbon::parse($releaseDate)->format('d M Y') }}
                  @else
                    <span class="text-admin-muted">—</span>
                  @endif
                </td>
                <td>{{ date('d M Y', strtotime($product->created_at)) }}</td>
                <td>
                  <div class="admin-action-group">
                    <a href="{{ route('dashboard.products.edit', $product->id) }}" class="btn-admin-icon btn-edit"
                      title="Edit">
                      <i class="fas fa-pen"></i>
                    </a>
                    <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="btn-admin-icon btn-view"
                      title="View">
                      <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('dashboard.products.price.create', $product->id) }}" class="btn-admin-icon btn-price"
                      title="Prices">
                      <i class="fas fa-dollar-sign"></i>
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8">
                  <div class="admin-empty-state">
                    <i class="fas fa-box-open"></i>
                    <h3>No products found</h3>
                    <p>Try adjusting your filters or add a new product.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($products->hasPages())
        <div class="admin-pagination-wrap">
          {{ $products->withQueryString()->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection

@section('styles')
  <style>
    .twitter-typeahead {
      width: 100% !important;
    }

    .tt-menu {
      width: inherit !important;
      background: var(--admin-surface);
      border: 1px solid var(--admin-surface-border);
      border-radius: var(--admin-radius);
    }

    .tt-suggestion {
      padding: 8px 12px;
      cursor: pointer;
      color: var(--admin-text-secondary);
    }

    .tt-suggestion:hover {
      background: rgba(99, 102, 241, 0.08);
      color: var(--admin-text-primary);
    }
  </style>
@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/0.11.1/typeahead.bundle.min.js"></script>
  <script>
    var route = "{{ route('autocomplete.search') }}";
    var base_url = "{{ URL::to('/dashboard/mobile') }}";
    var engine = new Bloodhound({
      remote: { url: route + "?query=%QUERY%", wildcard: '%QUERY%' },
      datumTokenizer: Bloodhound.tokenizers.whitespace('query'),
      queryTokenizer: Bloodhound.tokenizers.whitespace
    });
    $("#search").typeahead({ hint: false, highlight: true, minLength: 1 }, {
      source: engine.ttAdapter(), limit: 9, name: 'usersList', displayKey: 'name',
      templates: {
        empty: '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>',
        suggestion: function (data) {
          return '<div style="display:flex;align-items:center;gap:10px;padding:4px;"><img src="' + data.thumbnail + '" style="width:32px;height:32px;border-radius:6px;object-fit:cover;"><span>' + data.name + '</span></div>';
        }
      }
    });
  </script>
@endsection