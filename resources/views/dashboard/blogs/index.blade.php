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
                                            <div style="font-size:12px; color:var(--admin-text-muted);">/blog/{{ $blog->slug }}
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
                                    <div style="display:flex; gap:8px;">
                                        <a href="{{ url('/blog/' . $blog->slug) }}" target="_blank" class="btn-admin-sm"
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
                                    No blog posts yet. <a href="{{ route('dashboard.blogs.create') }}">Create your first
                                        post</a>.
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
            {{ $blogs->links() }}
        </div>
    @endif
@endsection