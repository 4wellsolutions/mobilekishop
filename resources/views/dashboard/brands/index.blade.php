@extends('layouts.dashboard')
@section('title', 'Brands')

@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Brands</h1>
      <div class="breadcrumb-nav">
        <a href="{{ route('dashboard.index') }}">Dashboard</a>
        <span class="separator">/</span>
        Brands
      </div>
    </div>
    <a href="{{ route('dashboard.brands.create') }}" class="btn-admin-primary">
      <i class="fas fa-plus"></i> Add Brand
    </a>
  </div>

  @include('includes.info-bar')

  <div class="admin-card">
    <div class="admin-card-header">
      <h2>All Brands</h2>
    </div>
    <div class="admin-card-body no-padding">
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Products</th>
              <th>Category</th>
              <th>Added</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($brands as $brand)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="td-title">{{ $brand->name }}</td>
                <td>
                  <span class="admin-badge badge-info">{{ count($brand->products) }}</span>
                </td>
                <td>{{ $brand->categories->pluck('category_name')->join(', ') }}</td>
                <td>{{ date('d M Y', strtotime($brand->created_at)) }}</td>
                <td>
                  <div class="admin-action-group">
                    <a href="{{ route('dashboard.brands.edit', $brand->id) }}" class="btn-admin-icon btn-edit" title="Edit">
                      <i class="fas fa-pen"></i>
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6">
                  <div class="admin-empty-state">
                    <i class="fas fa-tags"></i>
                    <h3>No brands found</h3>
                    <p>Create your first brand to get started.</p>
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