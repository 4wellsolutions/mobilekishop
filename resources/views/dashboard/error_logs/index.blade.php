@extends('layouts.dashboard')
@section('title', '404 Logs')

@section('content')
  <div class="admin-page-header">
    <div>
      <h1>404 & Error Logs</h1>
      <div class="breadcrumb-nav">
        <a href="{{ route('dashboard.index') }}">Dashboard</a>
        <span class="separator">/</span>
        Error Logs
      </div>
    </div>
    <div style="display:flex; gap:8px; align-items:center;">
      @if($errorLogs->total() > 0)
        <form action="{{ route('dashboard.error_logs.clearAll') }}" method="POST"
          onsubmit="return confirm('Are you sure you want to clear ALL 404 error logs?')" style="display:inline;">
          @csrf @method('DELETE')
          <input type="hidden" name="code" value="404">
          <button type="submit" class="btn-admin-danger">
            <i class="fas fa-trash-alt"></i> Clear All 404s
          </button>
        </form>
      @endif
    </div>
  </div>

  @include('includes.info-bar')

  {{-- Filter Panel --}}
  <div class="admin-filter-panel">
    <form action="{{ route('dashboard.error_logs.index') }}" method="get">
      <div class="admin-filter-grid">
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">Search URL</label>
          <input type="text" name="search" class="admin-form-control" placeholder="Search by URL..."
            value="{{ request('search') }}">
        </div>
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">Error Code</label>
          <select name="error_code" class="admin-form-control">
            <option value="">All Codes</option>
            @foreach($errorCodes as $code)
              <option value="{{ $code }}" {{ request('error_code') == $code ? 'selected' : '' }}>{{ $code }}</option>
            @endforeach
          </select>
        </div>
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">Sort By</label>
          <select name="sort" class="admin-form-control">
            <option value="updated_at" {{ request('sort', 'updated_at') == 'updated_at' ? 'selected' : '' }}>Last Seen
            </option>
            <option value="hit_count" {{ request('sort') == 'hit_count' ? 'selected' : '' }}>Hit Count</option>
            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>First Seen</option>
          </select>
        </div>
        <div class="admin-filter-actions">
          <button class="btn-admin-primary" type="submit"><i class="fas fa-search"></i> Filter</button>
          @if(request()->hasAny(['search', 'error_code', 'sort']))
            <a href="{{ route('dashboard.error_logs.index') }}" class="btn-admin-secondary"><i class="fas fa-times"></i>
              Clear</a>
          @endif
        </div>
      </div>
    </form>
  </div>

  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Error Logs <span
          style="color:var(--admin-text-muted); font-weight:400; font-size:14px;">({{ $errorLogs->total() }} total)</span>
      </h2>
    </div>
    <div class="admin-card-body no-padding">
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>#</th>
              <th>URL</th>
              <th>Code</th>
              <th>Status</th>
              <th>Hits</th>
              <th>Referer</th>
              <th>Last Seen</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($errorLogs as $errorLog)
              <tr>
                <td>{{ $errorLogs->firstItem() + $loop->index }}</td>
                <td style="max-width:300px; word-break:break-all; position:relative;">
                  <code style="color:var(--admin-accent); font-size:12px;"
                    id="url-{{ $errorLog->id }}">{{ $errorLog->url }}</code>
                  <button onclick="copyToClipboard('url-{{ $errorLog->id }}')" class="btn-admin-icon"
                    style="padding:2px 5px; margin-left:5px; font-size:10px;" title="Copy URL">
                    <i class="fas fa-copy"></i>
                  </button>
                  @if($errorLog->message && $errorLog->message !== 'Page not found')
                    <br><small style="color:var(--admin-text-muted);">{{ Str::limit($errorLog->message, 60) }}</small>
                  @endif
                </td>
                <td>
                  <span class="admin-badge {{ $errorLog->error_code == 404 ? 'badge-warning' : 'badge-danger' }}">
                    {{ $errorLog->error_code }}
                  </span>
                </td>
                <td id="status-cell-{{ $errorLog->id }}">
                  @if($errorLog->last_checked_status)
                    @php
                      $statusClass = 'badge-secondary';
                      if ($errorLog->last_checked_status >= 200 && $errorLog->last_checked_status < 300)
                        $statusClass = 'badge-success';
                      elseif ($errorLog->last_checked_status >= 300 && $errorLog->last_checked_status < 400)
                        $statusClass = 'badge-info';
                      elseif ($errorLog->last_checked_status >= 400)
                        $statusClass = 'badge-danger';
                    @endphp
                    <span class="admin-badge {{ $statusClass }}"
                      title="Checked {{ $errorLog->last_checked_at ? $errorLog->last_checked_at->diffForHumans() : '' }}">
                      {{ $errorLog->last_checked_status }}
                    </span>
                  @else
                    <small style="color:var(--admin-text-muted);">—</small>
                  @endif
                </td>
                <td>
                  <span class="admin-badge badge-primary" style="font-weight:600;">
                    {{ $errorLog->hit_count ?? 1 }}
                  </span>
                </td>
                <td style="max-width:200px; word-break:break-all;">
                  @if($errorLog->referer)
                    <small style="color:var(--admin-text-muted);">{{ Str::limit($errorLog->referer, 40) }}</small>
                  @else
                    <small style="color:var(--admin-text-muted);">—</small>
                  @endif
                </td>
                <td>
                  <small title="{{ $errorLog->updated_at }}">
                    {{ $errorLog->updated_at ? $errorLog->updated_at->diffForHumans() : '—' }}
                  </small>
                </td>
                <td>
                  <div class="admin-action-group">
                    <a href="javascript:void(0)"
                      onclick="checkUrlStatus({{ $errorLog->id }}, '{{ route('dashboard.error_logs.check', $errorLog->id) }}')"
                      class="btn-admin-icon btn-view" title="Check Current Status" id="check-btn-{{ $errorLog->id }}">
                      <i class="fas fa-sync-alt"></i>
                    </a>
                    @if($errorLog->error_code == 404)
                      @php
                        $parsedUrl = parse_url($errorLog->url);
                        $fromPath = ($parsedUrl['path'] ?? '/') . (isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '');
                      @endphp
                      <a href="{{ route('dashboard.redirections.create') }}?from_url={{ urlencode($fromPath) }}"
                        class="btn-admin-icon btn-edit" title="Create Redirect">
                        <i class="fas fa-directions"></i>
                      </a>
                    @endif
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
                <td colspan="8">
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
@section('scripts')
  <script>
    function checkUrlStatus(id, url) {
      const btn = document.getElementById('check-btn-' + id);
      const cell = document.getElementById('status-cell-' + id);
      const icon = btn.querySelector('i');

      // Loading state
      icon.classList.add('fa-spin');
      btn.style.opacity = '0.5';
      btn.style.pointerEvents = 'none';

      fetch(url, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            let statusClass = 'badge-secondary';
            if (data.status >= 200 && data.status < 300) statusClass = 'badge-success';
            else if (data.status >= 300 && data.status < 400) statusClass = 'badge-info';
            else if (data.status >= 400) statusClass = 'badge-danger';

            cell.innerHTML = `
              <span class="admin-badge ${statusClass}" title="Checked ${data.checked_at}">
                ${data.status || '0'}
              </span>
            `;
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Failed to check status');
        })
        .finally(() => {
          icon.classList.remove('fa-spin');
          btn.style.opacity = '1';
          btn.style.pointerEvents = 'auto';
        });
    }

    function copyToClipboard(elementId) {
      var text = document.getElementById(elementId).innerText;
      navigator.clipboard.writeText(text).then(function () {
        alert('URL copied to clipboard');
      }).catch(function (err) {
        console.error('Unable to copy', err);
      });
    }
  </script>
@endsection