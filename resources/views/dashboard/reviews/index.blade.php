@extends('layouts.dashboard')
@section('title', 'Reviews')

@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Reviews</h1>
      <div class="breadcrumb-nav">
        <a href="{{ route('dashboard.index') }}">Dashboard</a>
        <span class="separator">/</span>
        Reviews
      </div>
    </div>
  </div>

  @include('includes.info-bar')

  <div class="admin-card">
    <div class="admin-card-header">
      <h2>All Reviews</h2>
    </div>
    <div class="admin-card-body no-padding">
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Username</th>
              <th>Review</th>
              <th>Product</th>
              <th>Status</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($reviews as $review)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="td-title">{{ $review->user_name ?? 'Anonymous' }}</td>
                <td>{{ Str::limit($review->review, 60) }}</td>
                <td>{{ $review->product->name ?? 'N/A' }}</td>
                <td>
                  <span class="admin-badge {{ $review->status ? 'badge-success' : 'badge-warning' }}">
                    {{ $review->status ? 'Published' : 'Pending' }}
                  </span>
                </td>
                <td>{{ $review->created_at ? $review->created_at->format('d M Y') : 'N/A' }}</td>
                <td>
                  <div class="admin-action-group">
                    <a href="{{ route('dashboard.reviews.edit', $review->id) }}" class="btn-admin-icon btn-edit"
                      title="Edit">
                      <i class="fas fa-pen"></i>
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7">
                  <div class="admin-empty-state">
                    <i class="fas fa-star"></i>
                    <h3>No reviews yet</h3>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($reviews->hasPages())
        <div class="admin-pagination-wrap">{{ $reviews->links('pagination::bootstrap-5') }}</div>
      @endif
    </div>
  </div>
@endsection