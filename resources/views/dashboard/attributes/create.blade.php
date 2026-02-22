@extends('layouts.dashboard')
@section('title', 'Add Attribute')
@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Add Attribute</h1>
      <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span class="separator">/</span><a
          href="{{ route('dashboard.attributes.index') }}">Attributes</a><span class="separator">/</span>Create</div>
    </div>
  </div>
  @include('includes.info-bar')
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Attribute Details</h2>
    </div>
    <div class="admin-card-body">
      <form method="post" action="{{ route('dashboard.attributes.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="admin-form-grid">
          <div class="admin-form-group"><label class="admin-form-label">Name</label>
            <input type="text" name="name" class="admin-form-control" value="{{ old('name') }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Label</label>
            <input type="text" name="label" class="admin-form-control" value="{{ old('label') }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Category</label>
            <select class="admin-form-control" name="category_id">
              <option value="">Select Category</option>
              @foreach(App\Models\Category::all() as $category)
                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i> Create
            Attribute</button></div>
      </form>
    </div>
  </div>
@endsection