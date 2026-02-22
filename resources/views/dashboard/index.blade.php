@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    {{-- Page Header --}}
    <div class="admin-page-header">
        <div>
            <h1>Dashboard</h1>
            <p class="text-admin-secondary" style="margin:4px 0 0; font-size:14px;">
                Welcome back! Here's what's happening today.
            </p>
        </div>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('dashboard.products.create') }}" class="btn-admin-primary">
                <i class="fas fa-plus"></i> Add Product
            </a>
            <a href="{{ URL::to('/') }}" target="_blank" class="btn-admin-secondary">
                <i class="fas fa-external-link-alt"></i> View Site
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="admin-stats-grid">
        <div class="admin-stat-card accent-indigo" style="animation-delay: 0.05s;">
            <div class="admin-stat-info">
                <h3>{{ number_format($stats['products']) }}</h3>
                <p>Total Products</p>
            </div>
            <div class="admin-stat-icon bg-indigo">
                <i class="fas fa-mobile-alt"></i>
            </div>
        </div>

        <div class="admin-stat-card accent-emerald" style="animation-delay: 0.1s;">
            <div class="admin-stat-info">
                <h3>{{ number_format($stats['brands']) }}</h3>
                <p>Brands</p>
            </div>
            <div class="admin-stat-icon bg-emerald">
                <i class="fas fa-tags"></i>
            </div>
        </div>

        <div class="admin-stat-card accent-amber" style="animation-delay: 0.15s;">
            <div class="admin-stat-info">
                <h3>{{ number_format($stats['categories']) }}</h3>
                <p>Categories</p>
            </div>
            <div class="admin-stat-icon bg-amber">
                <i class="fas fa-folder-open"></i>
            </div>
        </div>

        <div class="admin-stat-card accent-cyan" style="animation-delay: 0.2s;">
            <div class="admin-stat-info">
                <h3>{{ number_format($stats['reviews']) }}</h3>
                <p>Reviews</p>
            </div>
            <div class="admin-stat-icon bg-cyan">
                <i class="fas fa-star"></i>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div style="margin-bottom: 28px;">
        <h2 style="font-size:16px; font-weight:600; margin-bottom:16px;">Quick Actions</h2>
        <div class="admin-quick-actions">
            <a href="{{ route('dashboard.products.create') }}" class="admin-quick-action">
                <i class="fas fa-plus-circle" style="color:var(--admin-accent);"></i> New Product
            </a>
            <a href="{{ route('dashboard.brands.create') }}" class="admin-quick-action">
                <i class="fas fa-tag" style="color:var(--admin-success);"></i> New Brand
            </a>
            <a href="{{ route('dashboard.categories.create') }}" class="admin-quick-action">
                <i class="fas fa-folder-plus" style="color:var(--admin-warning);"></i> New Category
            </a>
            <a href="{{ route('dashboard.cache.index') }}" class="admin-quick-action">
                <i class="fas fa-bolt" style="color:var(--admin-danger);"></i> Manage Cache
            </a>
            <a href="{{ route('dashboard.sitemap.index') }}" class="admin-quick-action">
                <i class="fas fa-sitemap" style="color:var(--admin-info);"></i> Sitemap
            </a>
        </div>
    </div>

    {{-- Recent Products --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2><i class="fas fa-clock" style="margin-right:8px; opacity:0.5;"></i>Recent Products</h2>
            <a href="{{ route('dashboard.products.index') }}" class="btn-admin-secondary btn-admin-sm">
                View All <i class="fas fa-arrow-right" style="font-size:11px; margin-left:4px;"></i>
            </a>
        </div>
        <div class="admin-card-body no-padding">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentProducts as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ $product->thumbnail }}" alt="" class="td-image"
                                        style="width:40px;height:40px;border-radius:8px;object-fit:cover;background:#0f172a;">
                                </td>
                                <td class="td-title">{{ Str::limit(Str::title($product->name), 40) }}</td>
                                <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                <td>{{ $product->category->category_name ?? 'N/A' }}</td>
                                <td>{{ $product->created_at->diffForHumans() }}</td>
                                <td>
                                    <div class="admin-action-group">
                                        <a href="{{ route('dashboard.products.edit', $product->id) }}"
                                            class="btn-admin-icon btn-edit" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <a href="{{ route('product.show', $product->slug) }}" target="_blank"
                                            class="btn-admin-icon btn-view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="admin-empty-state" style="padding:40px 20px;">
                                        <i class="fas fa-box-open"></i>
                                        <p>No products yet</p>
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