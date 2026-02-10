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
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
                        <h4 class="page-title mb-0">Sitemap Management</h4>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary" id="btnCreateIndex">
                                <i class="mdi mdi-sitemap me-1"></i>Create Index
                            </button>
                            <button type="button" class="btn btn-primary" id="btnGenerateAll">
                                <i class="mdi mdi-refresh me-1"></i>Regenerate All
                            </button>
                        </div>
                    </div>

                    {{-- AJAX Alert Container --}}
                    <div id="ajax-alert" class="d-none"></div>

                    {{-- Master Index URL --}}
                    <div class="alert alert-light border d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <i class="mdi mdi-sitemap text-primary me-2"></i>
                            <strong>Master Index:</strong>
                            <a href="{{ asset('sitemap.xml') }}" target="_blank" id="masterIndexUrl">{{ asset('sitemap.xml') }}</a>
                            @if(!file_exists(public_path('sitemap.xml')))
                                <span class="badge bg-warning text-dark ms-2">Not created yet</span>
                            @else
                                <span class="badge bg-success ms-2">Exists</span>
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="navigator.clipboard.writeText('{{ asset('sitemap.xml') }}').then(function(){var b=event.target.closest('button');b.innerHTML='<i class=\'mdi mdi-check\'></i> Copied!';setTimeout(function(){b.innerHTML='<i class=\'mdi mdi-content-copy\'></i> Copy URL'},1500)})">
                            <i class="mdi mdi-content-copy"></i> Copy URL
                        </button>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-check-circle me-1"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-alert me-1"></i>{{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Overview Stats --}}
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h1 class="text-primary mb-1">{{ number_format($totalFiles) }}</h1>
                                    <p class="text-muted mb-0">Total Sitemap Files</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h1 class="text-info mb-1">{{ formatSitemapBytes($totalSize) }}</h1>
                                    <p class="text-muted mb-0">Total Size</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h1 class="text-success mb-1">{{ count($countryData) }}</h1>
                                    <p class="text-muted mb-0">Countries</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Per-Country Cards --}}
                    @foreach($countryData as $i => $entry)
                        @php
                            $c = $entry['country'];
                            $files = $entry['files'];
                            $countrySize = collect($files)->sum('size');
                            $countryUrls = collect($files)->sum('url_count');
                        @endphp
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fi fi-{{ $c->country_code }}" style="font-size: 20px;"></span>
                                    <h5 class="card-title mb-0">{{ $c->country_name }} <small
                                            class="text-muted">({{ strtoupper($c->country_code) }})</small></h5>
                                    <span class="badge bg-secondary ms-2">{{ count($files) }} files</span>
                                    <span class="badge bg-info ms-1">{{ number_format($countryUrls) }} URLs</span>
                                    <span class="badge bg-light text-dark ms-1">{{ formatSitemapBytes($countrySize) }}</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-primary btn-generate-country"
                                        data-country-id="{{ $c->id }}" data-country-name="{{ $c->country_name }}">
                                        <i class="mdi mdi-refresh me-1"></i>Regenerate
                                    </button>

                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#country-{{ $c->country_code }}">
                                        <i class="mdi mdi-chevron-down"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="collapse" id="country-{{ $c->country_code }}">
                                <div class="card-body p-0">
                                    @if(count($files) === 0)
                                        <div class="p-4 text-center text-muted">
                                            <i class="mdi mdi-file-outline fs-1 d-block mb-2"></i>
                                            No sitemaps generated yet. Click <strong>Regenerate</strong> to create them.
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>File</th>
                                                        <th class="text-center">URLs</th>
                                                        <th class="text-center">Size</th>
                                                        <th class="text-center">Last Modified</th>
                                                        <th class="text-end">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($files as $file)
                                                        <tr>
                                                            <td>
                                                                @if($file['name'] === 'sitemap.xml')
                                                                    <i class="mdi mdi-sitemap text-primary me-1"></i>
                                                                @else
                                                                    <i class="mdi mdi-file-xml text-muted me-1"></i>
                                                                @endif
                                                                <code>{{ $file['name'] }}</code>
                                                            </td>
                                                            <td class="text-center">
                                                                <span
                                                                    class="badge bg-light text-dark">{{ number_format($file['url_count']) }}</span>
                                                            </td>
                                                            <td class="text-center">{{ formatSitemapBytes($file['size']) }}</td>
                                                            <td class="text-center">
                                                                {{ \Carbon\Carbon::createFromTimestamp($file['modified'])->format('M d, Y H:i') }}
                                                            </td>
                                                            <td class="text-end">
                                                                <a href="{{ asset("sitemaps/{$c->country_code}/{$file['name']}") }}"
                                                                    target="_blank" class="btn btn-sm btn-outline-info" title="View">
                                                                    <i class="mdi mdi-open-in-new"></i>
                                                                </a>
                                                                <form action="{{ route('dashboard.sitemap.destroy') }}" method="POST"
                                                                    class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <input type="hidden" name="country_code"
                                                                        value="{{ $c->country_code }}">
                                                                    <input type="hidden" name="file" value="{{ $file['name'] }}">
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                        title="Delete"
                                                                        onclick="return confirm('Delete {{ $file['name'] }}?')">
                                                                        <i class="mdi mdi-delete"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            const csrfToken = '{{ csrf_token() }}';
            const alertBox = $('#ajax-alert');

            function showAlert(type, message) {
                const icon = type === 'success' ? 'check-circle' : (type === 'warning' ? 'alert' : 'close-circle');
                const alertClass = type === 'error' ? 'danger' : type;
                alertBox.removeClass('d-none').html(
                    '<div class="alert alert-' + alertClass + ' alert-dismissible fade show" role="alert">' +
                    '<i class="mdi mdi-' + icon + ' me-1"></i>' + message +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>'
                );
                $('html, body').animate({ scrollTop: 0 }, 300);
            }

            function setButtonLoading(btn, loading) {
                if (loading) {
                    btn.data('original-html', btn.html());
                    btn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm me-1" role="status"></span>Generating...'
                    );
                } else {
                    btn.prop('disabled', false).html(btn.data('original-html'));
                }
            }

            // Per-country generate
            $('.btn-generate-country').on('click', function () {
                const btn = $(this);
                const countryId = btn.data('country-id');
                const countryName = btn.data('country-name');

                if (!confirm('Regenerate all sitemaps for ' + countryName + '? This may take a moment.')) return;

                setButtonLoading(btn, true);

                $.ajax({
                    url: '{{ route("dashboard.sitemap.generate") }}',
                    method: 'POST',
                    data: { _token: csrfToken, country_id: countryId },
                    success: function (res) {
                        showAlert('success', res.message);
                        setTimeout(function () { location.reload(); }, 1500);
                    },
                    error: function (xhr) {
                        const msg = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred.';
                        showAlert('error', msg);
                        setButtonLoading(btn, false);
                    }
                });
            });

            // Generate All
            $('#btnGenerateAll').on('click', function () {
                const btn = $(this);

                if (!confirm('Regenerate sitemaps for ALL countries? This may take several minutes.')) return;

                setButtonLoading(btn, true);

                $.ajax({
                    url: '{{ route("dashboard.sitemap.generate-all") }}',
                    method: 'POST',
                    data: { _token: csrfToken },
                    success: function (res) {
                        showAlert('success', res.message);
                        setTimeout(function () { location.reload(); }, 1500);
                    },
                    error: function (xhr) {
                        const msg = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred.';
                        showAlert('error', msg);
                        setButtonLoading(btn, false);
                    },
                    complete: function (xhr) {
                        if (xhr.status === 207) {
                            const res = xhr.responseJSON;
                            showAlert('warning', res.message);
                            setTimeout(function () { location.reload(); }, 2000);
                        }
                    }
                });
            });

            // Create Master Index
            $('#btnCreateIndex').on('click', function () {
                const btn = $(this);

                if (!confirm('Create master sitemap index (public/sitemap.xml)?')) return;

                setButtonLoading(btn, true);

                $.ajax({
                    url: '{{ route("dashboard.sitemap.create-index") }}',
                    method: 'POST',
                    data: { _token: csrfToken },
                    success: function (res) {
                        showAlert('success', res.message);
                        setButtonLoading(btn, false);
                    },
                    error: function (xhr) {
                        const msg = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred.';
                        showAlert('error', msg);
                        setButtonLoading(btn, false);
                    }
                });
            });
        });
    </script>
@endsection