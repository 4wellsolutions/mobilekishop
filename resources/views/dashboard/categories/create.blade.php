@extends('layouts.dashboard')
@section('title', 'Add Category')
@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Add Category</h1>
      <div class="breadcrumb-nav">
        <a href="{{ route('dashboard.index') }}">Dashboard</a><span class="separator">/</span>
        <a href="{{ route('dashboard.categories.index') }}">Categories</a><span class="separator">/</span>Create
      </div>
    </div>
  </div>
  @include('includes.info-bar')
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Category Details</h2>
    </div>
    <div class="admin-card-body">
      <form method="post" action="{{ route('dashboard.categories.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="admin-form-grid">
          <div class="admin-form-group"><label class="admin-form-label">Name</label>
            <input type="text" name="category_name" class="admin-form-control" value="{{ old('category_name') }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Title</label>
            <input type="text" name="title" class="admin-form-control" value="{{ old('title') }}">
          </div>
          <div class="admin-form-group" style="grid-column:1/-1;"><label class="admin-form-label">Description</label>
            <input type="text" name="description" class="admin-form-control" value="{{ old('description') }}">
          </div>
        </div>
        <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i> Create
            Category</button></div>
      </form>
    </div>
  </div>
@endsection