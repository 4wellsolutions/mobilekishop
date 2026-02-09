@extends("layouts.dashboard")
@section('title',"Edit Product Prices - Dashboard")
@section("content")

  <div class="page-wrapper bg-white">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Update Product Prices</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Products
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    
    <div class="container bg-white my-2">
    @include("includes.info-bar")
        
        <form action="{{ route('dashboard.products.price.store', $product->id) }}" method="post">
        @csrf
        <h1>{{$product->name}}</h1>
        @if($countries = App\Models\Country::all())
        @foreach($countries as $country)
            @php
                $price = $product->prices()->where('country_id', $country->id)->first();
            @endphp

            <div class="row">
                <div class="form-group col-12 col-md-6 col-lg-6">
                    <label>Country</label>
                    <input type="text" value="{{ $country->country_name }}" class="form-control" readonly>
                    <input type="hidden" name="country_id[]" value="{{ $country->id }}">
                </div>
                <div class="form-group col-12 col-md-6 col-lg-6">
                    <label>Price {{ $country->currency }}</label>
                    <input type="number" name="price[]" value="{{ $price ? $price->price : '' }}" class="form-control">
                </div>
            </div>
        @endforeach
        @endif
        <button type="submit" class="btn btn-primary">Update Prices</button>
    </form>

    </div>
  </div>
@stop

@section('scripts')

@stop

@section('styles')
<style type="text/css">
    .ck-editor__editable_inline {
        min-height: 200px;
    }
</style>
@stop