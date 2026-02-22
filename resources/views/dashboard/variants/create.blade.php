@extends('layouts.dashboard')
@section('title', 'Add Variant')
@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Add Variant</h1>
      <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span class="separator">/</span><a
          href="{{ route('dashboard.variants.index') }}">Variants</a><span class="separator">/</span>Create</div>
    </div>
  </div>
  @include('includes.info-bar')
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Variant Details</h2>
    </div>
    <div class="admin-card-body">
      <form method="post" action="{{ route('dashboard.variants.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="admin-form-group" style="max-width:400px;">
          <label class="admin-form-label">Name</label>
          <input type="text" name="variant_name" class="admin-form-control" value="{{ old('variant_name') }}">
        </div>
        <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i> Create
            Variant</button></div>
      </form>
    </div>
  </div>
@endsection