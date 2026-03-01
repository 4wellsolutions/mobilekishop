@extends('layouts.dashboard')
@section('title', 'Add Redirection')
@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Add Redirection</h1>
      <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span class="separator">/</span><a
          href="{{ route('dashboard.redirections.index') }}">Redirections</a><span class="separator">/</span>Create</div>
    </div>
  </div>
  @include('includes.info-bar')
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Redirection Details</h2>
    </div>
    <div class="admin-card-body">
      <form method="post" action="{{ route('dashboard.redirections.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="admin-form-grid" style="grid-template-columns:1fr;">
          <div class="admin-form-group"><label class="admin-form-label">From URL</label>
            <input type="text" name="from_url" class="admin-form-control" placeholder="/blog/old-post-slug"
              value="{{ old('from_url', request('from_url')) }}">
            <small style="color:#6b7280; margin-top:4px; display:block;">Enter the path only, e.g.
              <code>/blog/old-post-slug</code> or full URL like
              <code>https://mobilekishop.net/blog/old-post-slug</code></small>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">To URL</label>
            <input type="text" name="to_url" class="admin-form-control"
              placeholder="https://mobilekishop.net/blogs/new-post-slug">
            <small style="color:#6b7280; margin-top:4px; display:block;">Enter the full destination URL, e.g.
              <code>https://mobilekishop.net/blogs/new-post-slug</code></small>
          </div>
        </div>
        <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i> Create
            Redirection</button></div>
      </form>
    </div>
  </div>
@endsection