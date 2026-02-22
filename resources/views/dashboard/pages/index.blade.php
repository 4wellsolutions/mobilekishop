@extends('layouts.dashboard')
@section('title', 'Pages')

@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Pages</h1>
      <div class="breadcrumb-nav">
        <a href="{{ route('dashboard.index') }}">Dashboard</a>
        <span class="separator">/</span>
        Pages
      </div>
    </div>
    <a href="{{ route('dashboard.pages.create') }}" class="btn-admin-primary">
      <i class="fas fa-plus"></i> Add Page
    </a>
  </div>

  @include('includes.info-bar')

  {{-- Search --}}
  <div class="admin-filter-panel">
    <form action="" method="get">
      <div style="display:flex; gap:10px; max-width:450px;">
        <input type="text" name="search" class="admin-form-control" placeholder="Search by URL..."
          value="{{ Request::get('search') }}">
        <button class="btn-admin-primary" type="submit"><i class="fas fa-search"></i> Search</button>
      </div>
    </form>
  </div>

  <div class="admin-card">
    <div class="admin-card-header">
      <h2>All Pages</h2>
    </div>
    <div class="admin-card-body no-padding">
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>URL</th>
              <th>Added</th>
              <th>Author</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pages as $page)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="td-title">{{ $page->title }}</td>
                <td><code style="color:var(--admin-accent); font-size:12px;">{{ $page->slug }}</code></td>
                <td>{{ date('d M Y', strtotime($page->created_at)) }}</td>
                <td>{{ $page->User->name }}</td>
                <td>
                  <div class="admin-action-group">
                    <a href="{{ route('dashboard.pages.edit', $page->id) }}" class="btn-admin-icon btn-edit" title="Edit">
                      <i class="fas fa-pen"></i>
                    </a>
                    <a href="{{ $page->slug }}" target="_blank" class="btn-admin-icon btn-view" title="View">
                      <i class="fas fa-eye"></i>
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6">
                  <div class="admin-empty-state">
                    <i class="fas fa-file-alt"></i>
                    <h3>No pages found</h3>
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