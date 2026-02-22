@extends('layouts.dashboard')
@section('title', 'Colors')

@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Colors</h1>
      <div class="breadcrumb-nav">
        <a href="{{ route('dashboard.index') }}">Dashboard</a>
        <span class="separator">/</span>
        Colors
      </div>
    </div>
    <a href="{{ route('dashboard.colors.create') }}" class="btn-admin-primary">
      <i class="fas fa-plus"></i> Add Color
    </a>
  </div>

  @include('includes.info-bar')

  <div class="admin-card">
    <div class="admin-card-header">
      <h2>All Colors</h2>
    </div>
    <div class="admin-card-body no-padding">
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Color</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($colors as $color)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="td-title">{{ $color->name }}</td>
                <td>{{ \Carbon\Carbon::parse($color->created_at)->diffForHumans() }}</td>
                <td>
                  <div class="admin-action-group">
                    <a href="{{ route('dashboard.colors.edit', $color) }}" class="btn-admin-icon btn-edit" title="Edit">
                      <i class="fas fa-pen"></i>
                    </a>
                    <form action="{{ route('dashboard.colors.destroy', $color) }}" method="POST" style="display:inline;"
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
                    <i class="fas fa-palette"></i>
                    <h3>No colors found</h3>
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