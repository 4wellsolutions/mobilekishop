@extends("layouts.dashboard")
@section("title","PTA Taxes")
@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">PTA Taxes</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pt-sm-1">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  PTA Taxes
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    
    @if(!$taxes->isEmpty())
        <div class="row bg-white py-3">
          <div class="px-5">
        <form action="{{\Request::fullUrl()}}" method="get">
          <div class="row">
            <div class="col-9 col-md-9 col-lg-6">
              <label>Search</label>
              <input type="text" name="search" id="search" class="form-control" required>
            </div>
            <div class="col-3 col-md-3 mt-auto">
              <button class="btn btn-primary" type="submit">Submit</button>
            </div>
          </div>
        </form>
      </div>
        </div>
        <div class="row bg-white pt-5">
          <div class="col-12">
            @include("includes.info-bar")
            <div class="mx-3">
              <h1>PTA Taxes</h1>
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                      <tr>
                          <th>Brand</th>
                          <th>Product</th>
                          <th>Variant</th>
                          <th>Tax on Passport</th>
                          <th>Tax on CNIC</th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach($taxes as $tax)
                          <tr>
                              <td>{{ $tax->brand->name ?? 'N/A' }}</td> {{-- Assuming brand relationship --}}
                              <td>{{ $tax->product->name ?? 'N/A' }}</td> {{-- Assuming product relationship --}}
                              <td>{{ $tax->variant }}</td>
                              <td>{{ $tax->tax_on_passport }}</td>
                              <td>{{ $tax->tax_on_cnic }}</td>
                              <td>
                                <a href="{{route('dashboard.taxes.edit',$tax->id)}}" class="btn btn-success text-white btn-sm">Edit</a>
                                <a href="{{route('dashboard.taxes.destroy',$tax->id)}}" class="btn btn-danger text-white btn-sm" onclick="return confirm('Are you sure!')">Del</a>
                              </td>
                          </tr>
                      @endforeach
                  </tbody>
              </table>
              </div>
              {{$taxes->links()}}
            </div>
          </div>
        </div> 
    @else
    <div class="container">
      <div class="row bg-white pt-5">
        <h3 class="">No record found</h3>
      </div>
    </div>
    @endif

  </div>
@stop

@section('styles')
<style type="text/css">
  .twitter-typeahead{
    width: 100% !important;
  }
  .tt-menu{
    width: inherit !important;
    position: inherit !important;
  }
</style>
@stop
