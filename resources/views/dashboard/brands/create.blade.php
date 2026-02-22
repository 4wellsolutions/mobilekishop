@extends('layouts.dashboard')
@section('title', 'Add Brand')
@section('content')
    <div class="admin-page-header">
        <div>
            <h1>Add Brand</h1>
            <div class="breadcrumb-nav">
                <a href="{{ route('dashboard.index') }}">Dashboard</a>
                <span class="separator">/</span>
                <a href="{{ route('dashboard.brands.index') }}">Brands</a>
                <span class="separator">/</span>
                Create
            </div>
        </div>
    </div>
    @include('includes.info-bar')
    <div class="admin-card">
        <div class="admin-card-header">
            <h2>Brand Details</h2>
        </div>
        <div class="admin-card-body">
            <form action="{{ route('dashboard.brands.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="admin-form-grid">
                    <div class="admin-form-group"><label class="admin-form-label">Categories</label>
                        <select name="categories[]" class="admin-form-control" multiple style="height:90px;">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="admin-form-group"><label class="admin-form-label">Name</label>
                        <input type="text" name="name" class="admin-form-control" value="{{ old('name') }}">
                    </div>
                    <div class="admin-form-group"><label class="admin-form-label">Slug</label>
                        <input type="text" name="slug" class="admin-form-control" value="{{ old('slug') }}">
                    </div>
                    <div class="admin-form-group"><label class="admin-form-label">Title</label>
                        <input type="text" name="title" class="admin-form-control" value="{{ old('title') }}">
                    </div>
                    <div class="admin-form-group"><label class="admin-form-label">Description</label>
                        <input type="text" name="description" class="admin-form-control" value="{{ old('description') }}">
                    </div>
                    <div class="admin-form-group" style="grid-column: 1/-1;"><label
                            class="admin-form-label">Keywords</label>
                        <textarea name="keywords" class="admin-form-control" rows="2">{{ old('keywords') }}</textarea>
                    </div>
                    <div class="admin-form-group" style="grid-column: 1/-1;"><label class="admin-form-label">Body</label>
                        <textarea name="body" id="body" class="admin-form-control" rows="5">{!! old('body') !!}</textarea>
                    </div>
                    <div class="admin-form-group"><label class="admin-form-label">Thumbnail (160×160)</label>
                        <input type="file" name="thumbnail" class="admin-form-control" required>
                    </div>
                </div>
                <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i>
                        Create Brand</button></div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
    <script>CKEDITOR.replace('body');</script>
@endsection