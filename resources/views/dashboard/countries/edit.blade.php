@extends("layouts.dashboard") 
@section("title","Edit Country")
@section("content")
  <div class="page-wrapper">
    <!-- Breadcrumb -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Edit Country</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.countries.index') }}">Countries</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Edit
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    
    <div class="">
      
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Edit Country</h5>
          @include("includes.info-bar")
          <form action="{{ route('dashboard.countries.update', $country->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="country_name">Country Name</label>
                  <input type="text" class="form-control" id="country_name" name="country_name" value="{{ old('country_name', $country->country_name) }}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="country_code">Country Code</label>
                  <input type="text" class="form-control" id="country_code" name="country_code" value="{{ old('country_code', $country->country_code) }}" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="icon">Icon (URL or Path)</label>
                  <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon', $country->icon) }}" required>
                  <!-- Alternatively, use file upload if icons are stored locally -->
                  <!--
                  <input type="file" class="form-control-file" id="icon" name="icon">
                  -->
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="currency">Currency</label>
                  <input type="text" class="form-control" id="currency" name="currency" value="{{ old('currency', $country->currency) }}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="iso_currency">ISO Currency</label>
                  <input type="text" class="form-control" id="iso_currency" name="iso_currency" value="{{ old('iso_currency', $country->iso_currency) }}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="currency_name">Currency Name</label>
                  <input type="text" class="form-control" id="currency_name" name="currency_name" value="{{ old('currency_name', $country->currency_name) }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="domain">Domain</label>
                  <input type="text" class="form-control" id="domain" name="domain" value="{{ old('domain', $country->domain) }}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="locale">Locale</label>
                  <input type="text" class="form-control" id="locale" name="locale" value="{{ old('locale', $country->locale) }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="is_menu">Is Menu</label>
                  <select class="form-control" id="is_menu" name="is_menu" required>
                    <option value="1" {{ old('is_menu', $country->is_menu) == 1 ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('is_menu', $country->is_menu) == 0 ? 'selected' : '' }}>No</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="is_active">Is Active</label>
                  <select class="form-control" id="is_active" name="is_active" required>
                    <option value="1" {{ old('is_active', $country->is_active) == 1 ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('is_active', $country->is_active) == 0 ? 'selected' : '' }}>No</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="amazon_url">Amazon URL</label>
                  <input type="url" class="form-control" id="amazon_url" name="amazon_url" value="{{ old('amazon_url', $country->amazon_url) }}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="amazon_tag">Amazon Tag</label>
                  <input type="text" class="form-control" id="amazon_tag" name="amazon_tag" value="{{ old('amazon_tag', $country->amazon_tag) }}">
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Country</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@stop
