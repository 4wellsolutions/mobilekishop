@extends('layouts.dashboard')
@section('title', 'Sitemap Management')

@section('content')
    @php
        function formatSitemapBytes($bytes)
        {
            if ($bytes >= 1048576)
                return number_format($bytes / 1048576, 1) . ' MB';
            if ($bytes >= 1024)
                return number_format($bytes / 1024, 1) . ' KB';
            return $bytes . ' B';
        }
      @endphp

    <div class="admin-page-header">
        <div>
            <h1>Sitemap Management</h1>
            <div class="breadcrumb-nav">
                <a href="{{ route('dashboard.index') }}">Dashboard</a>
                <span class="separator">/</span>
                Sitemap
            </div>
        </div>
        <div style="display:flex; gap:10px;">
            <button type="button" class="btn-admin-secondary" id="btnCreateIndex">
                <i class="fas fa-sitemap"></i> Create Index
            </button>
            <button type="button" class="btn-admin-primary" id="btnGenerateAll">
                <i class="fas fa-sync"></i> Regenerate All
            </button>
        </div>
    </div>

    {{-- AJAX Alert --}}
    <div id="ajax-alert" style="display:none;"></div>

    @if(session('success'))
        <div class="admin-alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button class="alert-close"><i class="fas fa-times"></i></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="admin-alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
            <button class="alert-close"><i class="fas fa-times"></i></button>
        </div>
    @endif

    {{-- Master Index --}}
    <div class="admin-card" style="margin-bottom:24px;">
        <div class="admin-card-body"
            style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
            <div>
                <i class="fas fa-sitemap" style="color:var(--admin-accent); margin-right:8px;"></i>
                <strong>Master Index:</strong>
                <a href="{{ asset('sitemap.xml') }}" target="_blank"
                    style="color:var(--admin-accent); margin-left:6px;">{{ asset('sitemap.xml') }}</a>
                @if(!file_exists(public_path('sitemap.xml')))
                    <span class="admin-badge badge-warning" style="margin-left:8px;">Not created</span>
                @else
                    <span class="admin-badge badge-success" style="margin-left:8px;">Exists</span>
                @endif
            </div>
            <button class="btn-admin-secondary btn-admin-sm"
                onclick="navigator.clipboard.writeText('{{ asset('sitemap.xml') }}'); this.innerHTML='<i class=\'fas fa-check\'></i> Copied!'; setTimeout(()=>this.innerHTML='<i class=\'fas fa-copy\'></i> Copy', 1500)">
                <i class="fas fa-copy"></i> Copy
            </button>
        </div>
    </div>

    {{-- Stats --}}
    <div class="admin-stats-grid" style="margin-bottom:28px;">
        <div class="admin-stat-card accent-indigo">
            <div class="admin-stat-info">
                <h3>{{ number_format($totalFiles) }}</h3>
                <p>Sitemap Files</p>
            </div>
            <div class="admin-stat-icon bg-indigo"><i class="fas fa-file-code"></i></div>
        </div>
        <div class="admin-stat-card accent-emerald">
            <div class="admin-stat-info">
                <h3>{{ formatSitemapBytes($totalSize) }}</h3>
                <p>Total Size</p>
            </div>
            <div class="admin-stat-icon bg-emerald"><i class="fas fa-database"></i></div>
        </div>
        <div class="admin-stat-card accent-amber">
            <div class="admin-stat-info">
                <h3>{{ count($countryData) }}</h3>
                <p>Countries</p>
            </div>
            <div class="admin-stat-icon bg-amber"><i class="fas fa-globe"></i></div>
        </div>
    </div>

    {{-- Per-Country --}}
    @foreach($countryData as $entry)
        @php
            $c = $entry['country'];
            $files = $entry['files'];
            $countrySize = collect($files)->sum('size');
            $countryUrls = collect($files)->sum('url_count');
        @endphp
        <div class="admin-card" style="margin-bottom:16px;">
            <div class="admin-card-header" style="cursor:pointer;"
                onclick="this.parentElement.querySelector('.collapse-body').classList.toggle('open')">
                <h2 style="display:flex; align-items:center; gap:10px;">
                    <span class="fi fi-{{ $c->country_code }}" style="font-size:18px;"></span>
                    {{ $c->country_name }}
                    <span class="admin-badge badge-default" style="font-size:11px;">{{ count($files) }} files</span>
                    <span class="admin-badge badge-info" style="font-size:11px;">{{ number_format($countryUrls) }} URLs</span>
                    <span class="admin-badge badge-default"
                        style="font-size:11px;">{{ formatSitemapBytes($countrySize) }}</span>
                </h2>
                <div style="display:flex; gap:8px; align-items:center;">
                    <button type="button" class="btn-admin-primary btn-admin-sm btn-generate-country"
                        data-country-id="{{ $c->id }}" data-country-name="{{ $c->country_name }}"
                        onclick="event.stopPropagation()">
                        <i class="fas fa-sync"></i> Regenerate
                    </button>
                    <i class="fas fa-chevron-down" style="opacity:0.4; font-size:12px;"></i>
                </div>
            </div>
            <div class="collapse-body">
                @if(count($files) === 0)
                    <div class="admin-card-body">
                        <div class="admin-empty-state" style="padding:30px;">
                            <i class="fas fa-file-code"></i>
                            <p>No sitemaps yet. Click <strong>Regenerate</strong> to create them.</p>
                        </div>
                    </div>
                @else
                    <div class="admin-card-body no-padding">
                        <div class="admin-table-wrap">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>File</th>
                                        <th>URLs</th>
                                        <th>Size</th>
                                        <th>Last Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($files as $file)
                                        <tr>
                                            <td>
                                                <code style="color:var(--admin-accent); font-size:12px;">{{ $file['name'] }}</code>
                                            </td>
                                            <td><span class="admin-badge badge-info">{{ number_format($file['url_count']) }}</span></td>
                                            <td>{{ formatSitemapBytes($file['size']) }}</td>
                                            <td>{{ \Carbon\Carbon::createFromTimestamp($file['modified'])->format('M d, Y H:i') }}</td>
                                            <td>
                                                <div class="admin-action-group">
                                                    <a href="{{ asset("sitemaps/{$c->country_code}/{$file['name']}") }}" target="_blank"
                                                        class="btn-admin-icon btn-view" title="View">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                    <form action="{{ route('dashboard.sitemap.destroy') }}" method="POST"
                                                        style="display:inline;"
                                                        onsubmit="return confirm('Delete {{ $file['name'] }}?')">
                                                        @csrf @method('DELETE')
                                                        <input type="hidden" name="country_code" value="{{ $c->country_code }}">
                                                        <input type="hidden" name="file" value="{{ $file['name'] }}">
                                                        <button type="submit" class="btn-admin-icon btn-delete" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@endsection

@section('styles')
    <style>
        .collapse-body {
            display: none;
        }

        .collapse-body.open {
            display: block;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            const csrfToken = '{{ csrf_token() }}';
            const alertBox = $('#ajax-alert');

            function showAlert(type, message) {
                const cls = type === 'error' ? 'danger' : type;
                alertBox.show().html(
                    '<div class="admin-alert alert-' + cls + '" style="margin-bottom:20px;">' +
                    '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-triangle') + '"></i> ' + message +
                    '<button class="alert-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button></div>'
                );
                $('html, body').animate({ scrollTop: 0 }, 300);
            }

            function setLoading(btn, loading) {
                if (loading) {
                    btn.data('orig', btn.html());
                    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generating...');
                } else {
                    btn.prop('disabled', false).html(btn.data('orig'));
                }
            }

            $('.btn-generate-country').on('click', function () {
                const btn = $(this);
                if (!confirm('Regenerate sitemaps for ' + btn.data('country-name') + '?')) return;
                setLoading(btn, true);
                $.ajax({
                    url: '{{ route("dashboard.sitemap.generate") }}',
                    method: 'POST',
                    data: { _token: csrfToken, country_id: btn.data('country-id') },
                    success: function (res) { showAlert('success', res.message); setTimeout(() => location.reload(), 1500); },
                    error: function (xhr) { showAlert('error', xhr.responseJSON?.message || 'Error occurred'); setLoading(btn, false); }
                });
            });

            $('#btnGenerateAll').on('click', function () {
                const btn = $(this);
                if (!confirm('Regenerate ALL sitemaps?')) return;
                setLoading(btn, true);
                $.ajax({
                    url: '{{ route("dashboard.sitemap.generate-all") }}',
                    method: 'POST',
                    data: { _token: csrfToken },
                    success: function (res) { showAlert('success', res.message); setTimeout(() => location.reload(), 1500); },
                    error: function (xhr) { showAlert('error', xhr.responseJSON?.message || 'Error occurred'); setLoading(btn, false); },
                    complete: function (xhr) { if (xhr.status === 207) { showAlert('warning', xhr.responseJSON.message); setTimeout(() => location.reload(), 2000); } }
                });
            });

            $('#btnCreateIndex').on('click', function () {
                const btn = $(this);
                if (!confirm('Create master sitemap index?')) return;
                setLoading(btn, true);
                $.ajax({
                    url: '{{ route("dashboard.sitemap.create-index") }}',
                    method: 'POST',
                    data: { _token: csrfToken },
                    success: function (res) { showAlert('success', res.message); setLoading(btn, false); },
                    error: function (xhr) { showAlert('error', xhr.responseJSON?.message || 'Error occurred'); setLoading(btn, false); }
                });
            });
        });
    </script>
@endsection