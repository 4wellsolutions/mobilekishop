@extends('layouts.dashboard')
@section('title', 'Edit Review')
@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Edit Review</h1>
      <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span class="separator">/</span><a
          href="{{ route('dashboard.review.index') }}">Reviews</a><span class="separator">/</span>Edit</div>
    </div>
  </div>
  @include('includes.info-bar')
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>{{ $review->product->name }}</h2>
    </div>
    <div class="admin-card-body">
      <form action="{{ route('dashboard.review.update', $review->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="user_id" value="{{ $review->user_id }}">
        <input type="hidden" name="product_id" value="{{ $review->product_id }}">
        <div class="admin-form-grid">
          <div class="admin-form-group"><label class="admin-form-label">Name</label>
            <input type="text" name="name" class="admin-form-control" value="{{ old('name', $review->name) }}" readonly
              style="opacity:0.7;">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Email</label>
            <input type="text" name="email" class="admin-form-control" value="{{ $review->email ?? '' }}" readonly
              style="opacity:0.7;">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Phone Number</label>
            <input type="text" name="phone_number" class="admin-form-control" value="{{ $review->phone_number }}">
          </div>
          <div class="admin-form-group" style="grid-column:1/-1;"><label class="admin-form-label">Review</label>
            <textarea name="review" class="admin-form-control" rows="4">{!! $review->review !!}</textarea>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Stars</label>
            <div style="display:flex;align-items:center;gap:4px;padding:10px 0;">
              @for($i = 1; $i <= $review->stars; $i++)
                <i class="fas fa-star" style="color:#f59e0b;font-size:18px;"></i>
              @endfor
              @for($i = $review->stars + 1; $i <= 5; $i++)
                <i class="far fa-star" style="color:#d1d5db;font-size:18px;"></i>
              @endfor
              <span style="margin-left:8px;color:var(--admin-text-secondary);">{{ $review->stars }}/5</span>
            </div>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Active</label>
            <div style="padding:10px 0;">
              <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                <input type="checkbox" name="is_active" {{ $review->is_active ? 'checked' : '' }}
                  style="width:18px;height:18px;">
                <span>{{ $review->is_active ? 'Active' : 'Inactive' }}</span>
              </label>
            </div>
          </div>
        </div>
        <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i> Update
            Review</button></div>
      </form>
    </div>
  </div>
@endsection