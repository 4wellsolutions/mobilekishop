@extends('layouts.dashboard')
@section('title', 'Edit Package')
@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Edit Package</h1>
      <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span class="separator">/</span><a
          href="{{ route('dashboard.packages.index') }}">Packages</a><span class="separator">/</span>Edit</div>
    </div>
  </div>
  @include('includes.info-bar')

  {{-- Filter Settings --}}
  <div class="admin-card" style="margin-bottom:24px;">
    <div class="admin-card-header">
      <h2><i class="fas fa-sliders-h" style="margin-right:8px;opacity:0.5;"></i>Filter Settings</h2>
    </div>
    <div class="admin-card-body">
      <div class="admin-form-grid">
        <div class="admin-form-group"><label class="admin-form-label">Network</label>
          <select class="admin-form-control" name="filter_network" form="package-form">
            @foreach(['jazz', 'zong', 'ufone', 'telenor'] as $n)
              <option value="{{ $n }}" {{ $package->filter_network == $n ? 'selected' : '' }}>{{ ucfirst($n) }}</option>
            @endforeach
          </select>
        </div>
        <div class="admin-form-group"><label class="admin-form-label">Type</label>
          <select class="admin-form-control" name="filter_type" form="package-form">
            @foreach(['voice', 'sms', 'data', 'hybrid'] as $t)
              <option value="{{ $t }}" {{ $package->filter_type == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
            @endforeach
          </select>
        </div>
        <div class="admin-form-group"><label class="admin-form-label">Data Volume</label>
          <select class="admin-form-control" name="filter_data" form="package-form">
            @foreach([0, 1, 5, 10, 25, 100, 1000] as $d)
              <option value="{{ $d }}" {{ $package->filter_data == $d ? 'selected' : '' }}>
                {{ $d === 0 ? '0' : $d . 'GB or Less' }}</option>
            @endforeach
          </select>
        </div>
        <div class="admin-form-group"><label class="admin-form-label">Validity</label>
          <select class="admin-form-control" name="filter_validity" form="package-form">
            @foreach(['hourly', 'daily', 'weekly', 'monthly'] as $v)
              <option value="{{ $v }}" {{ $package->filter_validity == $v ? 'selected' : '' }}>{{ ucfirst($v) }}</option>
            @endforeach
          </select>
        </div>
        <div class="admin-form-group"><label class="admin-form-label">Package Type</label>
          <select class="admin-form-control" name="type" form="package-form">
            <option value="0" {{ $package->type == 0 ? 'selected' : '' }}>Prepaid</option>
            <option value="1" {{ $package->type == 1 ? 'selected' : '' }}>Postpaid</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  {{-- Package Details --}}
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Package Details</h2>
    </div>
    <div class="admin-card-body">
      <form id="package-form" method="post" action="{{ route('dashboard.packages.update', $package) }}">
        @csrf
        <div class="admin-form-grid">
          <div class="admin-form-group"><label class="admin-form-label">Name</label>
            <input type="text" name="name" class="admin-form-control" value="{{ old('name', $package->name) }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Price (amount only)</label>
            <input type="text" name="price" class="admin-form-control" value="{{ old('price', $package->price) }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">On-net Minutes</label>
            <input type="text" name="onnet" class="admin-form-control" value="{{ old('onnet', $package->onnet) }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Off-net Minutes</label>
            <input type="text" name="offnet" class="admin-form-control" value="{{ old('offnet', $package->offnet) }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">SMS</label>
            <input type="text" name="sms" class="admin-form-control" value="{{ old('sms', $package->sms) }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Data</label>
            <input type="text" name="data" class="admin-form-control" value="{{ old('data', $package->data) }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Validity</label>
            <select class="admin-form-control" name="validity">
              @foreach(['Hourly', 'Daily', 'Weekly', 'Fortnightly', 'Monthly', '3 Months', '6 Months', '12 months', 'unlimited'] as $v)
                <option value="{{ $v }}" {{ $package->validity == $v ? 'selected' : '' }}>{{ ucfirst($v) }}</option>
              @endforeach
            </select>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Description</label>
            <textarea class="admin-form-control" name="description"
              rows="3">{{ old('description', $package->description) }}</textarea>
          </div>
          <div class="admin-form-group" style="grid-column:1/-1;"><label class="admin-form-label">Subscribe Method</label>
            <textarea class="admin-form-control" name="method" id="method"
              rows="4">{{ old('method', $package->method) }}</textarea>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Meta Title</label>
            <input type="text" name="meta_title" class="admin-form-control"
              value="{{ old('meta_title', $package->meta_title) }}">
          </div>
          <div class="admin-form-group" style="grid-column:1/-1;"><label class="admin-form-label">Meta Description</label>
            <textarea class="admin-form-control" name="meta_description"
              rows="2">{{ old('meta_description', $package->meta_description) }}</textarea>
          </div>
        </div>
        <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i> Update
            Package</button></div>
      </form>
    </div>
  </div>
@endsection
@section('scripts')
  <script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script>
  <script>ClassicEditor.create(document.querySelector('#method')).catch(e => console.error(e));</script>
@endsection