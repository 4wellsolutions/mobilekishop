@extends('layouts.dashboard')
@section('title', 'Categories')

@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Categories</h1>
      <div class="breadcrumb-nav">
        <a href="{{ route('dashboard.index') }}">Dashboard</a>
        <span class="separator">/</span>
        Categories
      </div>
    </div>
    <a href="{{ route('dashboard.categories.create') }}" class="btn-admin-primary">
      <i class="fas fa-plus"></i> Add Category
    </a>
  </div>

  @include('includes.info-bar')

  <div class="admin-card">
    <div class="admin-card-header">
      <h2>All Categories</h2>
    </div>
    <div class="admin-card-body no-padding">
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Slug</th>
              <th>Added</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($categories as $category)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="td-title">{{ $category->category_name }}</td>
                <td><code style="color:var(--admin-accent); font-size:12px;">{{ $category->slug }}</code></td>
                <td>{{ date('d M Y', strtotime($category->created_at)) }}</td>
                <td>
                  <div class="admin-action-group">
                    <a href="{{ route('dashboard.categories.edit', $category->id) }}" class="btn-admin-icon btn-edit"
                      title="Edit">
                      <i class="fas fa-pen"></i>
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5">
                  <div class="admin-empty-state">
                    <i class="fas fa-folder-open"></i>
                    <h3>No categories found</h3>
                    <p>Create your first category to organize products.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection