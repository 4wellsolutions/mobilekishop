@extends('layouts.dashboard')
@section('title', 'Edit Page')
@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Edit Page</h1>
      <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span class="separator">/</span><a
          href="{{ route('dashboard.pages.index') }}">Pages</a><span class="separator">/</span>Edit</div>
    </div>
  </div>
  @include('includes.info-bar')
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Page Details</h2>
    </div>
    <div class="admin-card-body">
      <form action="{{ route('dashboard.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('put')
        <div class="admin-form-grid">
          <div class="admin-form-group"><label class="admin-form-label">Title</label>
            <input type="text" name="title" class="admin-form-control" value="{{ $page->title }}" required>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Description</label>
            <input type="text" name="description" class="admin-form-control" value="{{ $page->description }}" required>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Canonical</label>
            <input type="text" name="canonical" class="admin-form-control" value="{{ $page->canonical }}" required>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Slug</label>
            <input type="text" name="slug" class="admin-form-control" value="{{ $page->slug }}" required>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">H1</label>
            <input type="text" name="h1" class="admin-form-control" value="{{ $page->h1 }}" required>
          </div>
          <div class="admin-form-group" style="grid-column:1/-1;"><label class="admin-form-label">Body</label>
            <textarea name="body" id="body" class="admin-form-control" rows="5">{{ $page->body }}</textarea>
          </div>
        </div>
        <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i> Update
            Page</button></div>
      </form>
    </div>
  </div>
@endsection
@section('scripts')
  <script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script>
  <script>ClassicEditor.create(document.querySelector('#body')).catch(e => console.error(e));</script>
@endsection
@section('styles')
  <style>
    .ck-editor__editable_inline {
      min-height: 200px;
    }
  </style>
@endsection