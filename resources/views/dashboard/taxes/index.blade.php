@extends('layouts.dashboard')
@section('title', 'PTA Taxes')

@section('content')
  <div class="admin-page-header">
    <div>
      <h1>PTA Taxes</h1>
      <div class="breadcrumb-nav">
        <a href="{{ route('dashboard.index') }}">Dashboard</a>
        <span class="separator">/</span>
        PTA Taxes
      </div>
    </div>
    <a href="{{ route('dashboard.taxes.create') }}" class="btn-admin-primary">
      <i class="fas fa-plus"></i> Add Tax
    </a>
  </div>

  {{-- Search --}}
  <div class="admin-filter-panel">
    <form action="{{ Request::fullUrl() }}" method="get">
      <div style="display:flex; gap:10px; max-width:500px;">
        <input type="text" name="search" class="admin-form-control" placeholder="Search by product name..." required>
        <button class="btn-admin-primary" type="submit"><i class="fas fa-search"></i> Search</button>
      </div>
    </form>
  </div>

  @include('includes.info-bar')

  <div class="admin-card">
    <div class="admin-card-header">
      <h2>All PTA Taxes</h2>
    </div>
    <div class="admin-card-body no-padding">
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Brand</th>
              <th>Product</th>
              <th>Variant</th>
              <th>Tax on Passport</th>
              <th>Tax on CNIC</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($taxes as $tax)
              <tr>
                <td>{{ $tax->brand->name ?? 'N/A' }}</td>
                <td class="td-title">{{ $tax->product->name ?? 'N/A' }}</td>
                <td>{{ $tax->variant }}</td>
                <td><span class="admin-badge badge-warning">{{ $tax->tax_on_passport }}</span></td>
                <td><span class="admin-badge badge-info">{{ $tax->tax_on_cnic }}</span></td>
                <td>
                  <div class="admin-action-group">
                    <a href="{{ route('dashboard.taxes.edit', $tax->id) }}" class="btn-admin-icon btn-edit" title="Edit">
                      <i class="fas fa-pen"></i>
                    </a>
                    <form action="{{ route('dashboard.taxes.destroy', $tax->id) }}" method="POST" style="display:inline;"
                      onsubmit="return confirm('Are you sure?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn-admin-icon btn-delete" title="Delete">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6">
                  <div class="admin-empty-state">
                    <i class="fas fa-calculator"></i>
                    <h3>No tax records found</h3>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($taxes->hasPages())
        <div class="admin-pagination-wrap">{{ $taxes->links() }}</div>
      @endif
    </div>
  </div>
@endsection