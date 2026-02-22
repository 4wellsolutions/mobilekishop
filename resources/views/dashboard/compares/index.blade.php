@extends('layouts.dashboard')
@section('title', 'Comparisons')

@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Comparisons</h1>
      <div class="breadcrumb-nav">
        <a href="{{ route('dashboard.index') }}">Dashboard</a>
        <span class="separator">/</span>
        Comparisons
      </div>
    </div>
    <a href="{{ route('dashboard.compares.create') }}" class="btn-admin-primary">
      <i class="fas fa-plus"></i> Add Comparison
    </a>
  </div>

  @include('includes.info-bar')

  {{-- Search --}}
  <div class="admin-filter-panel">
    <form action="{{ Request::fullUrl() }}" method="get">
      <div style="display:flex; gap:10px; max-width:450px;">
        <input type="text" name="search" class="admin-form-control" placeholder="Search comparisons..." required>
        <button class="btn-admin-primary" type="submit"><i class="fas fa-search"></i> Search</button>
      </div>
    </form>
  </div>

  <div class="admin-card">
    <div class="admin-card-header">
      <h2>All Comparisons</h2>
    </div>
    <div class="admin-card-body no-padding">
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Link</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($compares as $compare)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="td-title">{{ $compare->link }}</td>
                <td>{{ \Carbon\Carbon::parse($compare->created_at)->diffForHumans() }}</td>
                <td>
                  <div class="admin-action-group">
                    <a href="{{ route('dashboard.compares.edit', $compare->id) }}" class="btn-admin-icon btn-edit"
                      title="Edit">
                      <i class="fas fa-pen"></i>
                    </a>
                    <a href="{{ $compare->link }}" target="_blank" class="btn-admin-icon btn-view" title="View">
                      <i class="fas fa-eye"></i>
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4">
                  <div class="admin-empty-state">
                    <i class="fas fa-balance-scale"></i>
                    <h3>No comparisons found</h3>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($compares->hasPages())
        <div class="admin-pagination-wrap">{{ $compares->links('pagination::bootstrap-5') }}</div>
      @endif
    </div>
  </div>
@endsection