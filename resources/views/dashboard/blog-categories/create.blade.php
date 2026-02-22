@extends('layouts.dashboard')
@section('title', 'Create Blog Category')

@section('content')
    <div class="admin-page-header">
        <div>
            <h1>Create Blog Category</h1>
            <div class="breadcrumb-nav">
                <a href="{{ route('dashboard.index') }}">Dashboard</a>
                <span class="separator">/</span>
                <a href="{{ route('dashboard.blog-categories.index') }}">Blog Categories</a>
                <span class="separator">/</span>
                Create
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-body">
            <form action="{{ route('dashboard.blog-categories.store') }}" method="POST">
                @csrf

                <div class="admin-form-group">
                    <label class="admin-form-label">Name <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="name" class="admin-form-input" value="{{ old('name') }}"
                        placeholder="e.g. Mobile Reviews" required>
                    @error('name')
                        <span class="admin-form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Slug</label>
                    <input type="text" name="slug" class="admin-form-input" value="{{ old('slug') }}"
                        placeholder="Auto-generated from name if empty">
                    @error('slug')
                        <span class="admin-form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="admin-form-group">
                    <label class="admin-form-label">Description</label>
                    <textarea name="description" class="admin-form-input" rows="3"
                        placeholder="Brief description of this category">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="admin-form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display:flex; gap:12px; margin-top:24px;">
                    <button type="submit" class="btn-admin-primary">
                        <i class="fas fa-save" style="margin-right:6px;"></i>Create Category
                    </button>
                    <a href="{{ route('dashboard.blog-categories.index') }}" class="btn-admin-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection