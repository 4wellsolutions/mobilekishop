@extends('layouts.dashboard')
@section('title', 'Filters')

@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Filters</h1>
      <div class="breadcrumb-nav">
        <a href="{{ route('dashboard.index') }}">Dashboard</a>
        <span class="separator">/</span>
        Filters
      </div>
    </div>
    <a href="{{ route('dashboard.filters.create') }}" class="btn-admin-primary">
      <i class="fas fa-plus"></i> Add Filter
    </a>
  </div>

  @include('includes.info-bar')

  {{-- Search --}}
  <div class="admin-filter-panel">
    <form action="" method="get">
      <div class="admin-filter-grid">
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">Search</label>
          <input type="text" name="search" class="admin-form-control" placeholder="Search..."
            value="{{ old('search', Request::get('search')) }}">
        </div>
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">Search By</label>
          <select class="admin-form-control" name="search_by">
            <option value="page_url" {{ old('search_by', Request::get('search_by')) == 'page_url' ? 'selected' : '' }}>Page
              URL</option>
            <option value="url" {{ old('search_by', Request::get('search_by')) == 'url' ? 'selected' : '' }}>Filter URL
            </option>
          </select>
        </div>
        <div class="admin-filter-actions">
          <button class="btn-admin-primary" type="submit"><i class="fas fa-search"></i> Search</button>
        </div>
      </div>
    </form>
  </div>

  <div class="admin-card">
    <div class="admin-card-header">
      <h2>All Filters</h2>
    </div>
    <div class="admin-card-body no-padding">
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Page URL</th>
              <th>Title</th>
              <th>Filter URL</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($filters as $filter)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td><code style="color:var(--admin-accent); font-size:12px;">{{ $filter->page_url }}</code></td>
                <td class="td-title">{{ $filter->title }}</td>
                <td><code style="color:var(--admin-text-secondary); font-size:12px;">{{ $filter->url }}</code></td>
                <td>{{ \Carbon\Carbon::parse($filter->created_at)->diffForHumans() }}</td>
                <td>
                  <div class="admin-action-group">
                    <a href="{{ route('dashboard.filters.edit', $filter->id) }}" class="btn-admin-icon btn-edit"
                      title="Edit">
                      <i class="fas fa-pen"></i>
                    </a>
                    <a href="{{ $filter->url }}" target="_blank" class="btn-admin-icon btn-view" title="View">
                      <i class="fas fa-eye"></i>
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6">
                  <div class="admin-empty-state">
                    <i class="fas fa-filter"></i>
                    <h3>No filters found</h3>
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