@extends("layouts.dashboard") 
@section("title","View Country")
@section("content")
  <div class="page-wrapper">
    <!-- Breadcrumb -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">View Country</h4>
          <div class="ms-auto text-end">
            <a href="{{ route('countries.index') }}" class="btn btn-secondary">Back to Countries</a>
          </div>
        </div>
      </div>
    </div>
    
    <div class="">
      
      <div class="card">
        <div class="card-body">
          @include("includes.info-bar")
          <h5 class="card-title">Country Details</h5>
          <div class="row">
            <div class="col-md-6">
              <strong>Country Name:</strong>
              <p>{{ $country->country_name }}</p>
            </div>
            <div class="col-md-6">
              <strong>Country Code:</strong>
              <p>{{ $country->country_code }}</p>
            </div>
            <div class="col-md-6">
              <strong>Icon:</strong>
              <p><img src="{{ asset('icons/' . $country->icon) }}" alt="{{ $country->country_name }}" width="50"></p>
            </div>
            <div class="col-md-6">
              <strong>Currency:</strong>
              <p>{{ $country->currency }}</p>
            </div>
            <div class="col-md-6">
              <strong>ISO Currency:</strong>
              <p>{{ $country->iso_currency }}</p>
            </div>
            <div class="col-md-6">
              <strong>Currency Name:</strong>
              <p>{{ $country->currency_name }}</p>
            </div>
            <div class="col-md-6">
              <strong>Domain:</strong>
              <p>{{ $country->domain }}</p>
            </div>
            <div class="col-md-6">
              <strong>Locale:</strong>
              <p>{{ $country->locale }}</p>
            </div>
            <div class="col-md-6">
              <strong>Is Menu:</strong>
              <p>{{ $country->is_menu ? 'Yes' : 'No' }}</p>
            </div>
            <div class="col-md-6">
              <strong>Is Active:</strong>
              <p>{{ $country->is_active ? 'Yes' : 'No' }}</p>
            </div>
            <div class="col-md-6">
              <strong>Amazon URL:</strong>
              <p>{{ $country->amazon_url }}</p>
            </div>
            <div class="col-md-6">
              <strong>Amazon Tag:</strong>
              <p>{{ $country->amazon_tag }}</p>
            </div>
            <div class="col-md-6">
              <strong>Created At:</strong>
              <p>{{ $country->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div class="col-md-6">
              <strong>Updated At:</strong>
              <p>{{ $country->updated_at->format('d M Y, H:i') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@stop
