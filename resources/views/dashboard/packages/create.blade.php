@extends("layouts.dashboard")
@section("title", "Packages - MKS")
@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Packages</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pt-sm-1">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Packages
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center bg-white">
        <div class="col-12 col-md-10">
          <h1>Add Package</h1>
          @include("includes.info-bar")
          <form class="pageForm" method="post" action="{{route('dashboard.packages.store')}}" enctype="multipart/form-data">
            @csrf
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Filter Network</label>
                <select class="select-filter form-control rounded py-1" name="filter_network">
                  <option value="jazz" {{ old('filter_network') == 'jazz' ? 'selected' : '' }}>Jazz</option>
                  <option value="zong" {{ old('filter_network') == 'zong' ? 'selected' : '' }}>Zong</option>
                  <option value="ufone" {{ old('filter_network') == 'ufone' ? 'selected' : '' }}>Ufone</option>
                  <option value="telenor" {{ old('filter_network') == 'telenor' ? 'selected' : '' }}>Telenor</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Filter Type</label>
                <select class="select-filter form-control rounded py-1" name="filter_type">
                  <option value="call" {{ old('filter_type') == 'voice' ? 'selected' : '' }}>Voice/Call</option>
                  <option value="sms" {{ old('filter_type') == 'sms' ? 'selected' : '' }}>Sms</option>
                  <option value="data" {{ old('filter_type') == 'data' ? 'selected' : '' }}>Data</option>
                  <option value="hybrid" {{ old('filter_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Filter Validity</label>
                <select class="select-filter form-control rounded py-1" name="filter_validity">
                  <option value="hourly" {{ old('filter_validity') == 'hourly' ? 'selected' : '' }}>Hourly</option>
                  <option value="daily" {{ old('filter_validity') == 'daily' ? 'selected' : '' }}>Daily</option>
                  <option value="weekly" {{ old('filter_validity') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                  <option value="monthly" {{ old('filter_validity') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Filter Data</label>
                <select class="select-filter form-control rounded py-1" name="filter_data">
                  <option value="0" {{ old('filter_data') == '0' ? 'selected' : '' }}>0</option>
                  <option value="1" {{ old('filter_data') == '1' ? 'selected' : '' }}>1GB or Less</option>
                  <option value="5" {{ old('filter_data') == '5' ? 'selected' : '' }}>5GB or Less</option>
                  <option value="10" {{ old('filter_data') == '10' ? 'selected' : '' }}>10GB or Less</option>
                  <option value="25" {{ old('filter_data') == '25' ? 'selected' : '' }}>25GB or Less</option>
                  <option value="100" {{ old('filter_data') == '100' ? 'selected' : '' }}>100GB or Less</option>
                  <option value="1000" {{ old('filter_data') == '1000' ? 'selected' : '' }}>1000GB or Less</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Package Type</label>
                <select class="select-filter form-control rounded py-1" name="type">
                  <option value="0" {{ old('type') == '0' ? 'selected' : '' }}>Prepaid</option>
                  <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Postpaid</option>
                </select>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Price (only amount)</label>
                <input type="text" name="price" class="form-control" value="{{ old('price') }}">
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Onnet Min</label>
                <input type="text" name="onnet" class="form-control" value="{{ old('onnet') }}">
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Offnet</label>
                <input type="text" name="offnet" class="form-control" value="{{ old('offnet') }}">
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Sms</label>
                <input type="text" name="sms" class="form-control" value="{{ old('sms') }}">
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Data</label>
                <input type="text" name="data" class="form-control" value="{{ old('data') }}">
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Validity</label>
                <select class="form-control" name="validity">
                  <option value="hourly">Hourly</option>
                  <option value="daily">Daily</option>
                  <option value="weekly">Weekly</option>
                  <option value="fortnightly">Fortnightly</option>
                  <option value="monthly">Monthly</option>
                  <option value="3_months">3 Months</option>
                  <option value="6_months">6 Months</option>
                  <option value="12_months">12 Months</option>
                  <option value="unlimited">Unlimited</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Description</label>
                <textarea class="form-control" name="description" id="description"></textarea>
              </div>
            </div>
            <div class="col-12 col-md-12">
              <div class="form-group">
                <label>Subscribe Method</label>
                <textarea class="form-control" name="method" id="method"></textarea>
              </div>
            </div>
            <div class="col-12 col-md-12">
              <div class="form-group">
                <label>Meta Title</label>
                <input type="text" name="meta_title" class="form-control">
              </div>
            </div>
            <div class="col-12 col-md-12">
              <div class="form-group">
                <label>Meta Description</label>
                <textarea class="form-control" name="meta_description" id="meta_description"></textarea>
              </div>
            </div>
          </div>
          <div class="form-group my-3">
              <button class="btn btn-dark formButton">Submit</button>
          </div>
          </form>
        </div>
      </div>

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
  }.table td, .table th {
    padding: 0.5rem !important;
  }
</style>
@stop

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script>
<script type="text/javascript">
    ClassicEditor
        .create( document.querySelector( '#method' ) )
        .then( editor => {
                console.log( editor );
        } )
        .catch( error => {
                console.error( error );
        } );

    </script>
@stop


