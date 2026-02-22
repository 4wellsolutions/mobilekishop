@extends('layouts.dashboard')
@section('title', 'Redirections')

@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Redirections</h1>
      <div class="breadcrumb-nav">
        <a href="{{ route('dashboard.index') }}">Dashboard</a>
        <span class="separator">/</span>
        Redirections
      </div>
    </div>
    <a href="{{ route('dashboard.redirections.create') }}" class="btn-admin-primary">
      <i class="fas fa-plus"></i> Add Redirection
    </a>
  </div>

  @include('includes.info-bar')

  {{-- Search --}}
  <div class="admin-filter-panel">
    <form action="" method="get">
      <div class="admin-filter-grid">
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">Search By</label>
          <select name="url_type" class="admin-form-control">
            <option value="from" {{ Request::get('url_type') == 'from' ? 'selected' : '' }}>From URL</option>
            <option value="to" {{ Request::get('url_type') == 'to' ? 'selected' : '' }}>To URL</option>
          </select>
        </div>
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">Search</label>
          <input type="text" name="search" class="admin-form-control" placeholder="Search URLs..."
            value="{{ Request::get('search') }}">
        </div>
        <div class="admin-filter-actions">
          <button class="btn-admin-primary" type="submit"><i class="fas fa-search"></i> Search</button>
          @if(Request::has('search'))
            <a href="{{ route('dashboard.redirections.index') }}" class="btn-admin-secondary"><i class="fas fa-times"></i>
              Clear</a>
          @endif
        </div>
      </div>
    </form>
  </div>

  <div class="admin-card">
    <div class="admin-card-header">
      <h2>All Redirections</h2>
    </div>
    <div class="admin-card-body no-padding">
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>#</th>
              <th>From URL</th>
              <th>To URL</th>
              <th>Created By</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($redirections as $redirection)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td><code style="color:var(--admin-danger); font-size:12px;">{{ $redirection->from_url }}</code></td>
                <td><code style="color:var(--admin-success); font-size:12px;">{{ $redirection->to_url }}</code></td>
                <td>{{ $redirection->user->name ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($redirection->created_at)->format('H:i d M Y') }}</td>
                <td>
                  <div class="admin-action-group">
                    <a href="{{ route('dashboard.redirections.edit', $redirection->id) }}" class="btn-admin-icon btn-edit"
                      title="Edit">
                      <i class="fas fa-pen"></i>
                    </a>
                    <form method="POST" action="{{ route('dashboard.redirections.destroy', $redirection) }}"
                      style="display:inline;" onsubmit="return confirm('Are you sure?')">
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
                <td colspan="6">
                  <div class="admin-empty-state">
                    <i class="fas fa-directions"></i>
                    <h3>No redirections found</h3>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($redirections->hasPages())
        <div class="admin-pagination-wrap">{{ $redirections->links('pagination::bootstrap-5') }}</div>
      @endif
    </div>
  </div>
@endsection