@extends('layouts.dashboard')
@section('title', 'Error Logs')

@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Error Logs</h1>
      <div class="breadcrumb-nav">
        <a href="{{ route('dashboard.index') }}">Dashboard</a>
        <span class="separator">/</span>
        Error Logs
      </div>
    </div>
  </div>

  @include('includes.info-bar')

  <div class="admin-card">
    <div class="admin-card-header">
      <h2>All Error Logs</h2>
    </div>
    <div class="admin-card-body no-padding">
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>#</th>
              <th>URL</th>
              <th>Error Code</th>
              <th>Message</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($errorLogs as $errorLog)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td><code style="color:var(--admin-accent); font-size:12px;">{{ $errorLog->url }}</code></td>
                <td><span class="admin-badge badge-danger">{{ $errorLog->error_code }}</span></td>
                <td>{{ Str::limit($errorLog->message, 80) }}</td>
                <td>
                  <div class="admin-action-group">
                    <form action="{{ route('dashboard.error_logs.destroy', $errorLog->id) }}" method="POST"
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
                <td colspan="5">
                  <div class="admin-empty-state">
                    <i class="fas fa-check-circle" style="color:var(--admin-success);"></i>
                    <h3>No errors logged</h3>
                    <p>Everything is running smoothly!</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($errorLogs->hasPages())
        <div class="admin-pagination-wrap">{{ $errorLogs->links('pagination::bootstrap-5') }}</div>
      @endif
    </div>
  </div>
@endsection