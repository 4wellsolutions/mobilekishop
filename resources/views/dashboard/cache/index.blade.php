@extends('layouts.dashboard')
@section('title', 'Cache Management')

@section('content')
    <div class="admin-page-header">
        <div>
            <h1>Cache Management</h1>
            <div class="breadcrumb-nav">
                <a href="{{ route('dashboard.index') }}">Dashboard</a>
                <span class="separator">/</span>
                Cache
            </div>
        </div>
    </div>

    @include('includes.info-bar')

    {{-- Cache Stats --}}
    <div class="admin-stats-grid" style="margin-bottom:28px;">
        <div class="admin-stat-card accent-indigo">
            <div class="admin-stat-info">
                <h3>{{ $cache_files ?? 0 }}</h3>
                <p>Cache Files</p>
            </div>
            <div class="admin-stat-icon bg-indigo">
                <i class="fas fa-file"></i>
            </div>
        </div>
        <div class="admin-stat-card accent-emerald">
            <div class="admin-stat-info">
                <h3>{{ $cache_size ?? '0 KB' }}</h3>
                <p>Cache Size</p>
            </div>
            <div class="admin-stat-icon bg-emerald">
                <i class="fas fa-database"></i>
            </div>
        </div>
        <div class="admin-stat-card accent-amber">
            <div class="admin-stat-info">
                <h3>
                    @if($route_cached ?? false)
                        <span style="color:var(--admin-success);">Active</span>
                    @else
                        <span style="color:var(--admin-text-muted);">Inactive</span>
                    @endif
                </h3>
                <p>Route Cache</p>
            </div>
            <div class="admin-stat-icon bg-amber">
                <i class="fas fa-route"></i>
            </div>
        </div>
        <div class="admin-stat-card accent-cyan">
            <div class="admin-stat-info">
                <h3>
                    @if($config_cached ?? false)
                        <span style="color:var(--admin-success);">Active</span>
                    @else
                        <span style="color:var(--admin-text-muted);">Inactive</span>
                    @endif
                </h3>
                <p>Config Cache</p>
            </div>
            <div class="admin-stat-icon bg-cyan">
                <i class="fas fa-cog"></i>
            </div>
        </div>
    </div>

    {{-- Cache Actions --}}
    <div class="admin-card" style="margin-bottom:28px;">
        <div class="admin-card-header">
            <h2><i class="fas fa-broom" style="margin-right:8px; opacity:0.5;"></i>Clear Cache</h2>
        </div>
        <div class="admin-card-body">
            <form action="{{ route('dashboard.cache.clear') }}" method="POST">
                @csrf
                <div
                    style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:16px; margin-bottom:24px;">
                    <label class="admin-checkbox-card">
                        <input type="checkbox" name="cache_types[]" value="views" checked>
                        <div class="checkbox-card-content">
                            <i class="fas fa-eye" style="color:var(--admin-accent); font-size:20px;"></i>
                            <span>View Cache</span>
                        </div>
                    </label>
                    <label class="admin-checkbox-card">
                        <input type="checkbox" name="cache_types[]" value="cache" checked>
                        <div class="checkbox-card-content">
                            <i class="fas fa-database" style="color:var(--admin-success); font-size:20px;"></i>
                            <span>Application Cache</span>
                        </div>
                    </label>
                    <label class="admin-checkbox-card">
                        <input type="checkbox" name="cache_types[]" value="route">
                        <div class="checkbox-card-content">
                            <i class="fas fa-route" style="color:var(--admin-warning); font-size:20px;"></i>
                            <span>Route Cache</span>
                        </div>
                    </label>
                    <label class="admin-checkbox-card">
                        <input type="checkbox" name="cache_types[]" value="config">
                        <div class="checkbox-card-content">
                            <i class="fas fa-cog" style="color:var(--admin-danger); font-size:20px;"></i>
                            <span>Config Cache</span>
                        </div>
                    </label>
                </div>
                <button type="submit" class="btn-admin-primary">
                    <i class="fas fa-bolt"></i> Clear Selected Caches
                </button>
            </form>
        </div>
    </div>

    {{-- Build Cache --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2><i class="fas fa-hammer" style="margin-right:8px; opacity:0.5;"></i>Build Cache</h2>
        </div>
        <div class="admin-card-body">
            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <form action="{{ route('dashboard.cache.build.route') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-admin-secondary">
                        <i class="fas fa-route"></i> Cache Routes
                    </button>
                </form>
                <form action="{{ route('dashboard.cache.build.config') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-admin-secondary">
                        <i class="fas fa-cog"></i> Cache Config
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .admin-checkbox-card {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            background: var(--admin-surface);
            border: 1px solid var(--admin-surface-border);
            border-radius: var(--admin-radius);
            padding: 16px;
            transition: all 0.2s;
        }

        .admin-checkbox-card:hover {
            border-color: var(--admin-accent);
        }

        .admin-checkbox-card input[type="checkbox"] {
            accent-color: var(--admin-accent);
            width: 18px;
            height: 18px;
        }

        .admin-checkbox-card input[type="checkbox"]:checked~.checkbox-card-content span {
            color: var(--admin-text-primary);
        }

        .checkbox-card-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-card-content span {
            font-size: 14px;
            font-weight: 500;
            color: var(--admin-text-secondary);
        }
    </style>
@endsection