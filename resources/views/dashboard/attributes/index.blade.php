@extends('layouts.dashboard')
@section('title', 'Attributes')

@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Attributes</h1>
      <div class="breadcrumb-nav">
        <a href="{{ route('dashboard.index') }}">Dashboard</a>
        <span class="separator">/</span>
        Attributes
      </div>
    </div>
    <a href="{{ route('dashboard.attributes.create') }}" class="btn-admin-primary">
      <i class="fas fa-plus"></i> Add Attribute
    </a>
  </div>

  {{-- Search/Filter --}}
  <div class="admin-filter-panel">
    <form action="" method="get">
      <div class="admin-filter-grid">
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">Search</label>
          <input type="text" name="search" class="admin-form-control" placeholder="Search attributes..."
            value="{{ Request::get('search') }}">
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
        <div class="admin-filter-actions">
          <button class="btn-admin-primary" type="submit"><i class="fas fa-search"></i> Search</button>
        </div>
      </div>
    </form>
  </div>

  @include('includes.info-bar')

  <div class="admin-card">
    <div class="admin-card-header">
      <h2>All Attributes</h2>
    </div>
    <div class="admin-card-body no-padding">
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Label</th>
              <th>Sequence</th>
              <th>Category</th>
              <th>Created</th>
              <th>Updated</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($attributes as $attribute)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="td-title">{{ $attribute->name }}</td>
                <td>{{ $attribute->label }}</td>
                <td><span class="admin-badge badge-info">{{ $attribute->sequence }}</span></td>
                <td>{{ $attribute->category->category_name }}</td>
                <td>{{ \Carbon\Carbon::parse($attribute->created_at)->diffForHumans() }}</td>
                <td>{{ \Carbon\Carbon::parse($attribute->updated_at)->diffForHumans() }}</td>
                <td>
                  <div class="admin-action-group">
                    <a href="{{ route('dashboard.attributes.edit', $attribute) }}" class="btn-admin-icon btn-edit"
                      title="Edit">
                      <i class="fas fa-pen"></i>
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8">
                  <div class="admin-empty-state">
                    <i class="fas fa-sliders-h"></i>
                    <h3>No attributes found</h3>
                    <p>Create your first attribute.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($attributes->hasPages())
        <div class="admin-pagination-wrap">{{ $attributes->links() }}</div>
      @endif
    </div>
  </div>
@endsection