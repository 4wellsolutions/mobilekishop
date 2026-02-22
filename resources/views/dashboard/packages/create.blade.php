@extends('layouts.dashboard')
@section('title', 'Add Package')
@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Add Package</h1>
      <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span class="separator">/</span><a
          href="{{ route('dashboard.packages.index') }}">Packages</a><span class="separator">/</span>Create</div>
    </div>
  </div>
  @include('includes.info-bar')

  {{-- Filters Card --}}
  <div class="admin-card" style="margin-bottom:24px;">
    <div class="admin-card-header">
      <h2><i class="fas fa-sliders-h" style="margin-right:8px;opacity:0.5;"></i>Filter Settings</h2>
    </div>
    <div class="admin-card-body">
      <div class="admin-form-grid">
        <div class="admin-form-group"><label class="admin-form-label">Network</label>
          <select class="admin-form-control" name="filter_network" form="package-form">
            @foreach(['jazz', 'zong', 'ufone', 'telenor'] as $n)
              <option value="{{ $n }}" {{ old('filter_network') == $n ? 'selected' : '' }}>{{ ucfirst($n) }}</option>
            @endforeach
          </select>
        </div>
        <div class="admin-form-group"><label class="admin-form-label">Type</label>
          <select class="admin-form-control" name="filter_type" form="package-form">
            <option value="call" {{ old('filter_type') == 'call' ? 'selected' : '' }}>Voice/Call</option>
            <option value="sms" {{ old('filter_type') == 'sms' ? 'selected' : '' }}>SMS</option>
            <option value="data" {{ old('filter_type') == 'data' ? 'selected' : '' }}>Data</option>
            <option value="hybrid" {{ old('filter_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
          </select>
        </div>
        <div class="admin-form-group"><label class="admin-form-label">Validity</label>
          <select class="admin-form-control" name="filter_validity" form="package-form">
            @foreach(['hourly', 'daily', 'weekly', 'monthly'] as $v)
              <option value="{{ $v }}" {{ old('filter_validity') == $v ? 'selected' : '' }}>{{ ucfirst($v) }}</option>
            @endforeach
          </select>
        </div>
        <div class="admin-form-group"><label class="admin-form-label">Data Volume</label>
          <select class="admin-form-control" name="filter_data" form="package-form">
            @foreach([0 => '0', 1 => '1GB or Less', 5 => '5GB or Less', 10 => '10GB or Less', 25 => '25GB or Less', 100 => '100GB or Less', 1000 => '1000GB or Less'] as $k => $l)
              <option value="{{ $k }}" {{ old('filter_data') == $k ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
          </select>
        </div>
        <div class="admin-form-group"><label class="admin-form-label">Package Type</label>
          <select class="admin-form-control" name="type" form="package-form">
            <option value="0" {{ old('type') == '0' ? 'selected' : '' }}>Prepaid</option>
            <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Postpaid</option>
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
      <form id="package-form" method="post" action="{{ route('dashboard.packages.store') }}"
        enctype="multipart/form-data">
        @csrf
        <div class="admin-form-grid">
          <div class="admin-form-group"><label class="admin-form-label">Name</label>
            <input type="text" name="name" class="admin-form-control" value="{{ old('name') }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Price (amount only)</label>
            <input type="text" name="price" class="admin-form-control" value="{{ old('price') }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">On-net Minutes</label>
            <input type="text" name="onnet" class="admin-form-control" value="{{ old('onnet') }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Off-net Minutes</label>
            <input type="text" name="offnet" class="admin-form-control" value="{{ old('offnet') }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">SMS</label>
            <input type="text" name="sms" class="admin-form-control" value="{{ old('sms') }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Data</label>
            <input type="text" name="data" class="admin-form-control" value="{{ old('data') }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Validity</label>
            <select class="admin-form-control" name="validity">
              @foreach(['hourly' => 'Hourly', 'daily' => 'Daily', 'weekly' => 'Weekly', 'fortnightly' => 'Fortnightly', 'monthly' => 'Monthly', '3_months' => '3 Months', '6_months' => '6 Months', '12_months' => '12 Months', 'unlimited' => 'Unlimited'] as $k => $l)
                <option value="{{ $k }}">{{ $l }}</option>
              @endforeach
            </select>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Description</label>
            <textarea class="admin-form-control" name="description" rows="3">{{ old('description') }}</textarea>
          </div>
          <div class="admin-form-group" style="grid-column:1/-1;"><label class="admin-form-label">Subscribe Method</label>
            <textarea class="admin-form-control" name="method" id="method" rows="4">{{ old('method') }}</textarea>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Meta Title</label>
            <input type="text" name="meta_title" class="admin-form-control" value="{{ old('meta_title') }}">
          </div>
          <div class="admin-form-group" style="grid-column:1/-1;"><label class="admin-form-label">Meta Description</label>
            <textarea class="admin-form-control" name="meta_description" rows="2">{{ old('meta_description') }}</textarea>
          </div>
        </div>
        <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i> Create
            Package</button></div>
      </form>
    </div>
  </div>
@endsection
@section('scripts')
  <script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script>
  <script>ClassicEditor.create(document.querySelector('#method')).catch(e => console.error(e));</script>
@endsection