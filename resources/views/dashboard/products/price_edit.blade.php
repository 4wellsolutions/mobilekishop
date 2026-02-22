@extends('layouts.dashboard')
@section('title', 'Edit Product Prices')
@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Edit Product Prices</h1>
      <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span class="separator">/</span><a
          href="{{ route('dashboard.products.index') }}">Products</a><span class="separator">/</span>Prices</div>
    </div>
  </div>
  @include('includes.info-bar')
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>{{ $product->name }}</h2>
    </div>
    <div class="admin-card-body">
      <form action="{{ route('dashboard.products.price.store', $product->id) }}" method="post">
        @csrf
        @if($countries = App\Models\Country::all())
          <div class="admin-form-grid">
            @foreach($countries as $country)
              @php $price = $product->prices()->where('country_id', $country->id)->first(); @endphp
              <div class="admin-form-group">
                <label class="admin-form-label">{{ $country->country_name }} ({{ $country->currency }})</label>
                <input type="hidden" name="country_id[]" value="{{ $country->id }}">
                <input type="number" name="price[]" value="{{ $price ? $price->price : '' }}" class="admin-form-control"
                  placeholder="0">
              </div>
            @endforeach
          </div>
        @endif
        <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i> Update
            Prices</button></div>
      </form>
    </div>
  </div>
@endsection