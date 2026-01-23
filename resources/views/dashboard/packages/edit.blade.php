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
          <form class="pageForm" method="post" action="{{route('dashboard.packages.update',$package)}}">
            @csrf
            <div class="row">
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="filter_network">Filter Network</label>
                  <select class="select-filter form-control rounded py-1" name="filter_network" id="filter_network">
                      @foreach(['jazz', 'zong', 'ufone', 'telenor'] as $network)
                          <option value="{{ $network }}" {{ $package->filter_network == $network ? 'selected' : '' }}>
                              {{ ucfirst($network) }}
                          </option>
                      @endforeach
                  </select>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="filter_type">Filter Type</label>
                  <select class="select-filter form-control rounded py-1" name="filter_type" id="filter_type">
                      @foreach(['voice', 'sms', 'data', 'hybrid'] as $type)
                          <option value="{{ $type }}" {{ $package->filter_type == $type ? 'selected' : '' }}>
                              {{ ucfirst($type) }}/Call
                          </option>
                      @endforeach
                  </select>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label for="filter_data">Filter Data</label>
                  <select class="select-filter form-control rounded py-1" name="filter_data" id="filter_data">
                      @foreach([0, 1, 5, 10, 25, 100, 1000] as $data)
                          <option value="{{ $data }}" {{ $package->filter_data == $data ? 'selected' : '' }}>
                              @if($data === 0)
                                  0
                              @else
                                  {{ $data }}GB or Less
                              @endif
                          </option>
                      @endforeach
                  </select>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label>Filter Validity</label>
                  <select class="select-filter form-control rounded py-1" name="filter_validity">
                    <option value="hourly" {{ old('filter_validity') || $package->filter_validity == 'hourly' ? 'selected' : '' }}>Hourly</option>
                    <option value="daily" {{ old('filter_validity') || $package->filter_validity == 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="weekly" {{ old('filter_validity') || $package->filter_validity == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="monthly" {{ old('filter_validity') || $package->filter_validity == 'monthly' ? 'selected' : '' }}>Monthly</option>
                  </select>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label>Package Type</label>
                  <select class="select-filter form-control rounded py-1" name="type">
                    <option value="0" {{ $package->type == 0 ? 'selected' : '' }}>Prepaid</option>
                    <option value="1" {{ $package->type == 1 ? 'selected' : '' }}>Postpaid</option>
                  </select>
                </div>
              </div>
            </div>
          <hr>
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name',$package->name) }}">
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Price  (only amount)</label>
                <input type="text" name="price" class="form-control" value="{{ old('price',$package->price) }}">
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Onnet Min</label>
                <input type="text" name="onnet" class="form-control" value="{{ old('onnet',$package->onnet) }}">
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Offnet</label>
                <input type="text" name="offnet" class="form-control" value="{{ old('offnet',$package->offnet) }}">
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Sms</label>
                <input type="text" name="sms" class="form-control" value="{{ old('sms',$package->sms) }}">
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Data</label>
                <input type="text" name="data" class="form-control" value="{{ old('data',$package->data) }}">
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Validity</label>
                <select class="form-control" name="validity">
                <option value="Hourly" {{ $package->validity == 'Hourly' ? 'selected' : '' }}>Hourly</option>
                <option value="Daily" {{ $package->validity == 'Daily' ? 'selected' : '' }}>Daily</option>
                <option value="Weekly" {{ $package->validity == 'Weekly' ? 'selected' : '' }}>Weekly</option>
                <option value="Fortnightly" {{ $package->validity == 'Fortnightly' ? 'selected' : '' }}>Fortnightly</option>
                <option value="Monthly" {{ $package->validity == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="3 Months" {{ $package->validity == '3 Monthly' ? 'selected' : '' }}>3 Months</option>
                <option value="6 Months" {{ $package->alidity == '6 Months' ? 'selected' : '' }}>6 Months</option>
                <option value="12 months" {{ $package->validity == '12 months' ? 'selected' : '' }}>12 Months</option>
                <option value="unlimited" {{ $package->validity == 'unlimited' ? 'selected' : '' }}>Unlimited</option>
            </select>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Description</label>
                <textarea class="form-control" name="description" id="description">{{ old('description',$package->description) }}</textarea>
              </div>
            </div>
            <div class="col-12 col-md-12">
              <div class="form-group">
                <label>Subscribe Method</label>
                <textarea class="form-control" name="method" id="method">{{ old('method',$package->method) }}</textarea>
              </div>
            </div>
            <div class="col-12 col-md-12">
              <div class="form-group">
                <label>Meta Title</label>
                <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $package->meta_title) }}">
              </div>
            </div>
            <div class="col-12 col-md-12">
              <div class="form-group">
                <label>Meta Description</label>
                <textarea class="form-control" name="meta_description" id="meta_description">{{ old('meta_description',$package->meta_description) }}</textarea>
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
