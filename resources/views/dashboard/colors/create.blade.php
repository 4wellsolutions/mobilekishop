@extends('layouts.dashboard')
@section('title', 'Add Color')
@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Add Color</h1>
      <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span class="separator">/</span><a
          href="{{ route('dashboard.colors.index') }}">Colors</a><span class="separator">/</span>Create</div>
    </div>
  </div>
  @include('includes.info-bar')
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Color Details</h2>
    </div>
    <div class="admin-card-body">
      <form method="post" action="{{ route('dashboard.colors.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="admin-form-group" style="max-width:400px;">
          <label class="admin-form-label">Name</label>
          <input type="text" name="name" class="admin-form-control" value="{{ old('name') }}" required>
        </div>
        <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i> Create
            Color</button></div>
      </form>
    </div>
  </div>
@endsection