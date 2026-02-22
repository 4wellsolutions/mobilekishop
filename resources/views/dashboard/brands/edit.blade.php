@extends('layouts.dashboard')
@section('title', 'Edit Brand')
@section('content')
    <div class="admin-page-header">
        <div>
            <h1>Edit {{ $brand->name }}</h1>
            <div class="breadcrumb-nav">
                <a href="{{ route('dashboard.index') }}">Dashboard</a>
                <span class="separator">/</span>
                <a href="{{ route('dashboard.brands.index') }}">Brands</a>
                <span class="separator">/</span>
                Edit
            </div>
        </div>
    </div>
    @include('includes.info-bar')
    <div class="admin-card">
        <div class="admin-card-header">
            <h2>Brand Details</h2>
        </div>
        <div class="admin-card-body">
            <form action="{{ route('dashboard.brands.update', $brand->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="admin-form-grid">
                    <div class="admin-form-group"><label class="admin-form-label">Categories</label>
                        <select name="categories[]" class="admin-form-control" multiple style="height:90px;">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ in_array($category->id, $brand->categories->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="admin-form-group"><label class="admin-form-label">Name</label>
                        <input type="text" name="name" class="admin-form-control" value="{{ old('name', $brand->name) }}">
                    </div>
                    <div class="admin-form-group"><label class="admin-form-label">Slug</label>
                        <input type="text" name="slug" class="admin-form-control" value="{{ old('slug', $brand->slug) }}">
                    </div>
                    <div class="admin-form-group"><label class="admin-form-label">Title</label>
                        <input type="text" name="title" class="admin-form-control"
                            value="{{ old('title', $brand->title) }}">
                    </div>
                    <div class="admin-form-group"><label class="admin-form-label">Description</label>
                        <input type="text" name="description" class="admin-form-control"
                            value="{{ old('description', $brand->description) }}">
                    </div>
                    <div class="admin-form-group" style="grid-column:1/-1;"><label class="admin-form-label">Keywords</label>
                        <textarea name="keywords" class="admin-form-control"
                            rows="2">{{ old('keywords', $brand->keywords) }}</textarea>
                    </div>
                    <div class="admin-form-group" style="grid-column:1/-1;"><label class="admin-form-label">Body</label>
                        <textarea name="body" id="body" class="admin-form-control"
                            rows="5">{!! old('body', $brand->body) !!}</textarea>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">
                            <input type="checkbox" name="is_featured" {{ $brand->is_featured ? 'checked' : '' }}
                                style="accent-color:var(--admin-accent);">
                            Featured Brand
                        </label>
                    </div>
                    @if($brand->thumbnail)
                        <div class="admin-form-group">
                            <label class="admin-form-label">Current Thumbnail</label>
                            <img src="{{ $brand->thumbnail }}"
                                style="max-height:100px; border-radius:var(--admin-radius); background:var(--admin-bg);">
                        </div>
                    @endif
                    <div class="admin-form-group"><label class="admin-form-label">Thumbnail (160×160)</label>
                        <input type="file" name="thumbnail" class="admin-form-control" {{ !$brand->thumbnail ? 'required' : '' }}>
                    </div>
                </div>
                <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i>
                        Update Brand</button></div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
    <script>CKEDITOR.replace('body');</script>
@endsection