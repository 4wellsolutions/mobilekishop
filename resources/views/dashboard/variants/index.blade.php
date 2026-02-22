@extends('layouts.dashboard')
@section('title', 'Variants')

@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Variants</h1>
      <div class="breadcrumb-nav">
        <a href="{{ route('dashboard.index') }}">Dashboard</a>
        <span class="separator">/</span>
        Variants
      </div>
    </div>
    <a href="{{ route('dashboard.variants.create') }}" class="btn-admin-primary">
      <i class="fas fa-plus"></i> Add Variant
    </a>
  </div>

  @include('includes.info-bar')

  <div class="admin-card">
    <div class="admin-card-header">
      <h2>All Variants</h2>
    </div>
    <div class="admin-card-body no-padding">
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($variants as $variant)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="td-title">{{ Str::title($variant->variant_name) }}</td>
                <td>{{ \Carbon\Carbon::parse($variant->created_at)->diffForHumans() }}</td>
                <td>
                  <div class="admin-action-group">
                    <a href="{{ route('dashboard.variants.edit', $variant) }}" class="btn-admin-icon btn-edit" title="Edit">
                      <i class="fas fa-pen"></i>
                    </a>
                    <form action="{{ route('dashboard.variants.destroy', $variant) }}" method="POST" style="display:inline;"
                      onsubmit="return confirm('Are you sure?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn-admin-icon btn-delete" title="Delete">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4">
                  <div class="admin-empty-state">
                    <i class="fas fa-layer-group"></i>
                    <h3>No variants found</h3>
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