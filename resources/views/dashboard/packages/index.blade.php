@extends('layouts.dashboard')
@section('title', 'Packages')

@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Packages</h1>
      <div class="breadcrumb-nav">
        <a href="{{ route('dashboard.index') }}">Dashboard</a>
        <span class="separator">/</span>
        Packages
      </div>
    </div>
    <a href="{{ route('dashboard.packages.create') }}" class="btn-admin-primary">
      <i class="fas fa-plus"></i> Add Package
    </a>
  </div>

  {{-- Filter Panel --}}
  <div class="admin-filter-panel">
    <form action="" method="get">
      <div class="admin-filter-grid">
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">Network</label>
          <select class="admin-form-control select-filter" name="filter_network">
            <option value="">All Networks</option>
            <option value="jazz" {{ Request::get('filter_network') == 'jazz' ? 'selected' : '' }}>Jazz</option>
            <option value="zong" {{ Request::get('filter_network') == 'zong' ? 'selected' : '' }}>Zong</option>
            <option value="ufone" {{ Request::get('filter_network') == 'ufone' ? 'selected' : '' }}>Ufone</option>
            <option value="telenor" {{ Request::get('filter_network') == 'telenor' ? 'selected' : '' }}>Telenor</option>
          </select>
        </div>
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">Type</label>
          <select class="admin-form-control select-filter" name="filter_type">
            <option value="">All Types</option>
            <option value="call" {{ Request::get('filter_type') == 'voice' ? 'selected' : '' }}>Voice/Call</option>
            <option value="sms" {{ Request::get('filter_type') == 'sms' ? 'selected' : '' }}>SMS</option>
            <option value="data" {{ Request::get('filter_type') == 'data' ? 'selected' : '' }}>Data</option>
            <option value="hybrid" {{ Request::get('filter_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
          </select>
        </div>
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">Validity</label>
          <select class="admin-form-control select-filter" name="filter_validity">
            <option value="">All</option>
            <option value="hourly" {{ Request::get('filter_validity') == 'hourly' ? 'selected' : '' }}>Hourly</option>
            <option value="daily" {{ Request::get('filter_validity') == 'daily' ? 'selected' : '' }}>Daily</option>
            <option value="weekly" {{ Request::get('filter_validity') == 'weekly' ? 'selected' : '' }}>Weekly</option>
            <option value="monthly" {{ Request::get('filter_validity') == 'monthly' ? 'selected' : '' }}>Monthly</option>
          </select>
        </div>
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">Data Limit</label>
          <select class="admin-form-control select-filter" name="filter_data">
            <option value="">All</option>
            <option value="0" {{ Request::get('filter_data') == '0' ? 'selected' : '' }}>None</option>
            <option value="1" {{ Request::get('filter_data') == '1' ? 'selected' : '' }}>≤ 1GB</option>
            <option value="5" {{ Request::get('filter_data') == '5' ? 'selected' : '' }}>≤ 5GB</option>
            <option value="10" {{ Request::get('filter_data') == '10' ? 'selected' : '' }}>≤ 10GB</option>
            <option value="25" {{ Request::get('filter_data') == '25' ? 'selected' : '' }}>≤ 25GB</option>
            <option value="100" {{ Request::get('filter_data') == '100' ? 'selected' : '' }}>≤ 100GB</option>
          </select>
        </div>
        <div class="admin-form-group" style="margin-bottom:0;">
          <label class="admin-form-label">Package Type</label>
          <select class="admin-form-control select-filter" name="type">
            <option value="">All</option>
            <option value="0" {{ Request::get('type') == '0' ? 'selected' : '' }}>Prepaid</option>
            <option value="1" {{ Request::get('type') == '1' ? 'selected' : '' }}>Postpaid</option>
          </select>
        </div>
      </div>
    </form>
    <form method="get" action="" style="margin-top:16px;">
      <div style="display:flex; gap:10px; max-width:400px;">
        <input type="text" name="search" class="admin-form-control" placeholder="Search packages..."
          value="{{ Request::get('search') }}">
        <button class="btn-admin-primary" type="submit"><i class="fas fa-search"></i></button>
      </div>
    </form>
  </div>

  @include('includes.info-bar')

  <div class="admin-card">
    <div class="admin-card-header">
      <h2>
        <span style="opacity:0.5; font-weight:400;">Total:</span> {{ $packages->total() }} packages
      </h2>
    </div>
    <div class="admin-card-body no-padding">
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Network</th>
              <th>Type</th>
              <th>Data</th>
              <th>Package</th>
              <th>Price</th>
              <th>Validity</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($packages as $package)
              <tr>
                <td class="td-title">{{ $package->name }}</td>
                <td><span class="admin-badge badge-info">{{ ucfirst($package->filter_network) }}</span></td>
                <td>{{ ucfirst($package->filter_type) }}</td>
                <td>{{ $package->data }}</td>
                <td><span
                    class="admin-badge {{ $package->type == 0 ? 'badge-success' : 'badge-warning' }}">{{ $package->type == 0 ? 'Prepaid' : 'Postpaid' }}</span>
                </td>
                <td class="td-title">{{ $package->price }}</td>
                <td>{{ $package->validity }}</td>
                <td>
                  <div class="admin-action-group">
                    <a href="{{ route('dashboard.packages.edit', $package->id) }}" class="btn-admin-icon btn-edit"
                      title="Edit">
                      <i class="fas fa-pen"></i>
                    </a>
                    <a href="{{ route('package.show', [$package->filter_network, $package->slug]) }}" target="_blank"
                      class="btn-admin-icon btn-view" title="View">
                      <i class="fas fa-eye"></i>
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8">
                  <div class="admin-empty-state">
                    <i class="fas fa-box"></i>
                    <h3>No packages found</h3>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($packages->hasPages())
        <div class="admin-pagination-wrap">{{ $packages->links() }}</div>
      @endif
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    $(document).ready(function () {
      $('.select-filter').on('change', function () { $(this).closest('form').submit(); });
    });
  </script>
@endsection