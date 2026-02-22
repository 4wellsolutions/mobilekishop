@extends('layouts.dashboard')
@section('title', 'Edit Attribute')
@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Edit Attribute</h1>
      <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span class="separator">/</span><a
          href="{{ route('dashboard.attributes.index') }}">Attributes</a><span class="separator">/</span>Edit</div>
    </div>
  </div>
  @include('includes.info-bar')
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Attribute Details</h2>
    </div>
    <div class="admin-card-body">
      <form method="post" action="{{ route('dashboard.attributes.update', $attribute) }}" enctype="multipart/form-data">
        @csrf @method('put')
        <div class="admin-form-grid">
          <div class="admin-form-group"><label class="admin-form-label">Name</label>
            <input type="text" name="name" class="admin-form-control" value="{{ old('name', $attribute->name) }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Label</label>
            <input type="text" name="label" class="admin-form-control" value="{{ old('label', $attribute->label) }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Category</label>
            <select class="admin-form-control" name="category_id">
              <option value="">Select Category</option>
              @foreach(App\Models\Category::all() as $category)
                <option value="{{ $category->id }}" {{ $attribute->category_id == $category->id ? 'selected' : '' }}>
                  {{ $category->category_name }}</option>
              @endforeach
            </select>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Sequence</label>
            <input type="number" name="sequence" class="admin-form-control"
              value="{{ old('sequence', $attribute->sequence) }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Type</label>
            <select class="admin-form-control" name="type">
              <option value="input" {{ $attribute->type == 'input' ? 'selected' : '' }}>Input</option>
              <option value="text" {{ $attribute->type == 'text' ? 'selected' : '' }}>Text</option>
              <option value="number" {{ $attribute->type == 'number' ? 'selected' : '' }}>Number</option>
            </select>
          </div>
        </div>
        <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i> Update
            Attribute</button></div>
      </form>
    </div>
  </div>
@endsection