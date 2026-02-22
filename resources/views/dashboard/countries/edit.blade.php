@extends('layouts.dashboard')
@section('title', 'Edit Country')
@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Edit Country</h1>
      <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span class="separator">/</span><a
          href="{{ route('dashboard.countries.index') }}">Countries</a><span class="separator">/</span>Edit</div>
    </div>
  </div>
  @include('includes.info-bar')
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Country Details</h2>
    </div>
    <div class="admin-card-body">
      <form action="{{ route('dashboard.countries.update', $country->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="admin-form-grid">
          <div class="admin-form-group"><label class="admin-form-label">Country Name</label>
            <input type="text" name="country_name" class="admin-form-control"
              value="{{ old('country_name', $country->country_name) }}" required>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Country Code</label>
            <input type="text" name="country_code" class="admin-form-control"
              value="{{ old('country_code', $country->country_code) }}" required>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Icon (URL or Path)</label>
            <input type="text" name="icon" class="admin-form-control" value="{{ old('icon', $country->icon) }}" required>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Currency Symbol</label>
            <input type="text" name="currency" class="admin-form-control"
              value="{{ old('currency', $country->currency) }}" required>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">ISO Currency</label>
            <input type="text" name="iso_currency" class="admin-form-control"
              value="{{ old('iso_currency', $country->iso_currency) }}" required>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Currency Name</label>
            <input type="text" name="currency_name" class="admin-form-control"
              value="{{ old('currency_name', $country->currency_name) }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Domain</label>
            <input type="text" name="domain" class="admin-form-control" value="{{ old('domain', $country->domain) }}"
              required>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Locale</label>
            <input type="text" name="locale" class="admin-form-control" value="{{ old('locale', $country->locale) }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Is Menu</label>
            <select name="is_menu" class="admin-form-control" required>
              <option value="1" {{ old('is_menu', $country->is_menu) == 1 ? 'selected' : '' }}>Yes</option>
              <option value="0" {{ old('is_menu', $country->is_menu) == 0 ? 'selected' : '' }}>No</option>
            </select>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Is Active</label>
            <select name="is_active" class="admin-form-control" required>
              <option value="1" {{ old('is_active', $country->is_active) == 1 ? 'selected' : '' }}>Yes</option>
              <option value="0" {{ old('is_active', $country->is_active) == 0 ? 'selected' : '' }}>No</option>
            </select>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Amazon URL</label>
            <input type="url" name="amazon_url" class="admin-form-control"
              value="{{ old('amazon_url', $country->amazon_url) }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Amazon Tag</label>
            <input type="text" name="amazon_tag" class="admin-form-control"
              value="{{ old('amazon_tag', $country->amazon_tag) }}">
          </div>
        </div>
        <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i> Update
            Country</button></div>
      </form>
    </div>
  </div>
@endsection