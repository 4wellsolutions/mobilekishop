@extends('layouts.dashboard')

@section('title', 'Cache Management')

@section('content')
    @php
        function formatCacheBytes($bytes)
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
                        <h4 class="page-title mb-0">Cache Management</h4>
                        <form action="{{ route('dashboard.cache.clear-all') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Clear ALL caches? This may cause a brief performance dip while caches rebuild.')">
                                <i class="mdi mdi-delete-sweep me-1"></i>Clear All Caches
                            </button>
                        </form>
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

                    {{-- Cache Stats --}}
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h1 class="text-primary mb-1">{{ number_format($stats['cache_files']) }}</h1>
                                    <p class="text-muted mb-0">Cached Files</p>
                                    <small class="text-muted">{{ formatCacheBytes($stats['cache_size']) }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h1 class="text-info mb-1">{{ number_format($stats['view_files']) }}</h1>
                                    <p class="text-muted mb-0">Compiled Views</p>
                                    <small class="text-muted">{{ formatCacheBytes($stats['view_size']) }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h1 class="mb-1">
                                        @if($stats['route_cached'])
                                            <span class="text-success"><i class="mdi mdi-check-circle"></i></span>
                                        @else
                                            <span class="text-secondary"><i class="mdi mdi-close-circle"></i></span>
                                        @endif
                                    </h1>
                                    <p class="text-muted mb-0">Route Cache</p>
                                    <small class="text-muted">{{ $stats['route_cached'] ? 'Active' : 'Not cached' }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h1 class="mb-1">
                                        @if($stats['config_cached'])
                                            <span class="text-success"><i class="mdi mdi-check-circle"></i></span>
                                        @else
                                            <span class="text-secondary"><i class="mdi mdi-close-circle"></i></span>
                                        @endif
                                    </h1>
                                    <p class="text-muted mb-0">Config Cache</p>
                                    <small
                                        class="text-muted">{{ $stats['config_cached'] ? 'Active' : 'Not cached' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Selective Clear --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Selective Cache Clear</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('dashboard.cache.clear') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="types[]" value="page"
                                                id="cachePage">
                                            <label class="form-check-label" for="cachePage">
                                                <strong>Page Cache</strong>
                                                <br><small class="text-muted">Cached HTML pages served to guests. Clear when
                                                    content changes.</small>
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="types[]"
                                                value="application" id="cacheApp">
                                            <label class="form-check-label" for="cacheApp">
                                                <strong>Application Cache</strong>
                                                <br><small class="text-muted">General app cache (database queries, computed
                                                    values, etc.)</small>
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="types[]" value="views"
                                                id="cacheViews">
                                            <label class="form-check-label" for="cacheViews">
                                                <strong>Compiled Views</strong>
                                                <br><small class="text-muted">Blade template cache. Clear after modifying
                                                    .blade.php files.</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="types[]" value="routes"
                                                id="cacheRoutes">
                                            <label class="form-check-label" for="cacheRoutes">
                                                <strong>Route Cache</strong>
                                                <br><small class="text-muted">Compiled routes. Clear after changing
                                                    routes/web.php.</small>
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="types[]" value="config"
                                                id="cacheConfig">
                                            <label class="form-check-label" for="cacheConfig">
                                                <strong>Config Cache</strong>
                                                <br><small class="text-muted">Compiled config. Clear after changing .env or
                                                    config files.</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-2">
                                    <i class="mdi mdi-broom me-1"></i>Clear Selected
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Cache Info</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm mb-0">
                                <tr>
                                    <td class="fw-bold" width="200">Cache Driver</td>
                                    <td><code>{{ $stats['cache_driver'] }}</code></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Page Cache TTL</td>
                                    <td>60 minutes (default)</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Cache Path</td>
                                    <td><code>storage/framework/cache/data</code></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Views Path</td>
                                    <td><code>storage/framework/views</code></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection