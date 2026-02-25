@extends('layouts.dashboard')
@section('title', 'Blog Posts')

@section('content')
    <div class="admin-page-header">
        <div>
            <h1>Blog Posts</h1>
            <div class="breadcrumb-nav">
                <a href="{{ route('dashboard.index') }}">Dashboard</a>
                <span class="separator">/</span>
                Blog Posts
            </div>
        </div>
        <a href="{{ route('dashboard.blogs.create') }}" class="btn-admin-primary">
            <i class="fas fa-plus" style="margin-right:6px;"></i>Add Blog Post
        </a>
    </div>

    @include('includes.info-bar')

    {{-- Filter Panel --}}
    <div class="admin-filter-panel">
        <form action="{{ route('dashboard.blogs.index') }}" method="get">
            <div class="admin-filter-grid">
                <div class="admin-form-group" style="margin-bottom:0;">
                    <label class="admin-form-label">Search</label>
                    <input type="text" name="search" class="admin-form-control" placeholder="Search by title or slug..."
                        value="{{ Request::get('search') }}">
                </div>
                <div class="admin-form-group" style="margin-bottom:0;">
                    <label class="admin-form-label">Status</label>
                    <select class="admin-form-control" name="status">
                        <option value="">All Statuses</option>
                        <option value="published" {{ Request::get('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ Request::get('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="admin-form-group" style="margin-bottom:0;">
                    <label class="admin-form-label">Category</label>
                    <select class="admin-form-control" name="category_id">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ Request::get('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="admin-form-group" style="margin-bottom:0;">
                    <label class="admin-form-label">Date Filter</label>
                    <select class="admin-form-control" name="date_filter">
                        <option value="">Select Filter</option>
                        <option value="published_at" {{ Request::get('date_filter') == 'published_at' ? 'selected' : '' }}>Published Date</option>
                        <option value="created_at" {{ Request::get('date_filter') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                        <option value="updated_at" {{ Request::get('date_filter') == 'updated_at' ? 'selected' : '' }}>Updated Date</option>
                    </select>
                </div>
                <div class="admin-form-group" style="margin-bottom:0;">
                    <label class="admin-form-label">From</label>
                    <input type="date" name="date1" class="admin-form-control"
                        value="{{ Request::get('date1', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                </div>
                <div class="admin-form-group" style="margin-bottom:0;">
                    <label class="admin-form-label">To</label>
                    <input type="date" name="date2" class="admin-form-control"
                        value="{{ Request::get('date2', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                </div>
                <div class="admin-form-group" style="margin-bottom:0;">
                    <label class="admin-form-label">Sort By</label>
                    <select class="admin-form-control" name="sort_by">
                        <option value="">Default</option>
                        <option value="id" {{ Request::get('sort_by') == 'id' ? 'selected' : '' }}>ID</option>
                        <option value="title" {{ Request::get('sort_by') == 'title' ? 'selected' : '' }}>Title</option>
                        <option value="published_at" {{ Request::get('sort_by') == 'published_at' ? 'selected' : '' }}>Published Date</option>
                        <option value="created_at" {{ Request::get('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                        <option value="updated_at" {{ Request::get('sort_by') == 'updated_at' ? 'selected' : '' }}>Updated Date</option>
                    </select>
                </div>
                <div class="admin-form-group" style="margin-bottom:0;">
                    <label class="admin-form-label">Order</label>
                    <select class="admin-form-control" name="sort_order">
                        <option value="">Default</option>
                        <option value="ASC" {{ Request::get('sort_order') == 'ASC' ? 'selected' : '' }}>Ascending</option>
                        <option value="DESC" {{ Request::get('sort_order') == 'DESC' ? 'selected' : '' }}>Descending</option>
                    </select>
                </div>
                <div class="admin-filter-actions">
                    <button class="btn-admin-primary" type="submit">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('dashboard.blogs.index') }}" class="btn-admin-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="admin-card">
        <div class="admin-card-body" style="padding:0;">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Published</th>
                            <th>Last Updated</th>
                            <th style="width:160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($blogs as $blog)
                            <tr>
                                <td>{{ $blog->id }}</td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        @if($blog->featured_image)
                                            <img src="{{ $blog->featured_image }}" alt=""
                                                style="width:48px; height:36px; object-fit:cover; border-radius:6px; border:1px solid var(--admin-surface-border);">
                                        @endif
                                        <div>
                                            <div style="font-weight:600;">{{ Str::limit($blog->title, 50) }}</div>
                                            <div style="font-size:12px; color:var(--admin-text-muted);">/blogs/{{ $blog->slug }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($blog->category)
                                        <span class="admin-badge"
                                            style="background:var(--admin-accent-soft); color:var(--admin-accent);">
                                            {{ $blog->category->name }}
                                        </span>
                                    @else
                                        <span style="color:var(--admin-text-muted);">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($blog->status === 'published')
                                        <span class="admin-badge" style="background:#dcfce7; color:#16a34a;">Published</span>
                                    @else
                                        <span class="admin-badge" style="background:#fef3c7; color:#d97706;">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $blog->published_at ? $blog->published_at->format('M d, Y') : '—' }}
                                </td>
                                <td>
                                    {{ $blog->updated_at ? $blog->updated_at->format('M d, Y h:i A') : '—' }}
                                </td>
                                <td>
                                    <div style="display:flex; gap:8px;">
                                        <a href="{{ url('/blogs/' . $blog->slug) }}" target="_blank" class="btn-admin-sm"
                                            title="View on frontend" style="color:var(--admin-accent);">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('dashboard.blogs.edit', $blog) }}" class="btn-admin-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('dashboard.blogs.destroy', $blog) }}" method="POST"
                                            onsubmit="return confirm('Delete this blog post?');">
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
                                    <i class="fas fa-blog"
                                        style="font-size:32px; margin-bottom:12px; display:block; opacity:0.3;"></i>
                                    No blog posts found. Try adjusting your filters or <a href="{{ route('dashboard.blogs.create') }}">create a new post</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($blogs->hasPages())
        <div style="margin-top:20px; display:flex; justify-content:center;">
            {{ $blogs->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    @endif
@endsection