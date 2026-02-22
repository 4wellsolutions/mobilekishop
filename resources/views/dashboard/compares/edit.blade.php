@extends('layouts.dashboard')
@section('title', 'Edit Comparison')
@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Edit Comparison</h1>
      <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span class="separator">/</span><a
          href="{{ route('dashboard.compares.index') }}">Comparisons</a><span class="separator">/</span>Edit</div>
    </div>
  </div>
  @include('includes.info-bar')
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Comparison Details</h2>
    </div>
    <div class="admin-card-body">
      <form action="{{ route('dashboard.compares.update', $compare->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="admin-form-group" style="margin-bottom:16px;"><label class="admin-form-label">URL</label>
          <input type="text" name="url" class="admin-form-control" value="{{ $compare->link }}" readonly
            style="opacity:0.7;">
        </div>
        <div style="margin-bottom:16px;">
          <label class="admin-form-label">Current Thumbnail</label>
          <div style="margin-top:8px;"><img src="{{ $compare->thumbnail }}"
              style="max-height:200px;border-radius:8px;border:1px solid var(--admin-border);"></div>
        </div>
        <div class="admin-form-group" style="margin-bottom:16px;"><label class="admin-form-label">New Image
            (Optional)</label>
          <input type="file" name="thumbnail" class="admin-form-control">
        </div>
        <button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i> Update Comparison</button>
      </form>
    </div>
  </div>
@endsection