@extends('layouts.dashboard')
@section('title', 'Blog Categories')

@section('content')
    <div class="admin-page-header">
        <div>
            <h1>Blog Categories</h1>
            <div class="breadcrumb-nav">
                <a href="{{ route('dashboard.index') }}">Dashboard</a>
                <span class="separator">/</span>
                Blog Categories
            </div>
        </div>
        <a href="{{ route('dashboard.blog-categories.create') }}" class="btn-admin-primary">
            <i class="fas fa-plus" style="margin-right:6px;"></i>Add Category
        </a>
    </div>

    @include('includes.info-bar')

    <div class="admin-card">
        <div class="admin-card-body" style="padding:0;">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Posts</th>
                            <th style="width:140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td style="font-weight:600;">{{ $category->name }}</td>
                                <td>
                                    <span
                                        style="font-size:12px; color:var(--admin-text-muted);">/blogs/category/{{ $category->slug }}</span>
                                </td>
                                <td>{{ Str::limit($category->description, 60) ?: '—' }}</td>
                                <td>
                                    <span class="admin-badge"
                                        style="background:var(--admin-accent-soft); color:var(--admin-accent);">
                                        {{ $category->blogs_count }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display:flex; gap:8px;">
                                        <a href="{{ route('dashboard.blog-categories.edit', $category) }}" class="btn-admin-sm"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('dashboard.blog-categories.destroy', $category) }}" method="POST"
                                            onsubmit="return confirm('Delete this category? Posts in this category will become uncategorized.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-admin-sm btn-admin-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding:40px; color:var(--admin-text-muted);">
                                    <i class="fas fa-folder"
                                        style="font-size:32px; margin-bottom:12px; display:block; opacity:0.3;"></i>
                                    No categories yet. <a href="{{ route('dashboard.blog-categories.create') }}">Create your
                                        first category</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($categories->hasPages())
        <div style="margin-top:20px; display:flex; justify-content:center;">
            {{ $categories->links('pagination::bootstrap-5') }}
        </div>
    @endif
@endsection