@extends('layouts.dashboard')
@section('title', 'Add Filter')
@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Add Filter</h1>
      <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span class="separator">/</span><a
          href="{{ route('dashboard.filters.index') }}">Filters</a><span class="separator">/</span>Create</div>
    </div>
  </div>
  @include('includes.info-bar')
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Filter Details</h2>
    </div>
    <div class="admin-card-body">
      <form action="{{ route('dashboard.filters.store') }}" method="post">
        @csrf
        <div class="admin-form-grid" style="grid-template-columns:1fr;">
          <div class="admin-form-group"><label class="admin-form-label">Page URL</label>
            <input type="url" name="page_url" class="admin-form-control" value="{{ old('page_url') }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Name</label>
            <input type="text" name="title" class="admin-form-control" value="{{ old('title') }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">URL</label>
            <input type="text" name="url" class="admin-form-control" value="{{ old('url') }}">
          </div>
        </div>
        <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i> Create
            Filter</button></div>
      </form>
    </div>
  </div>
@endsection