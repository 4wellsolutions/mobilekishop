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
        <form action="" method="get" class="container">
          <div class="row mx-5">
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Filter Network</label>
                <select class="select-filter form-control rounded py-1" name="filter_network">
                  <option value="">Select Network</option>
                  <option value="jazz" {{ \Request::get('filter_network') == 'jazz' ? 'selected' : '' }}>Jazz</option>
                  <option value="zong" {{ \Request::get('filter_network') == 'zong' ? 'selected' : '' }}>Zong</option>
                  <option value="ufone" {{ \Request::get('filter_network') == 'ufone' ? 'selected' : '' }}>Ufone</option>
                  <option value="telenor" {{ \Request::get('filter_network') == 'telenor' ? 'selected' : '' }}>Telenor</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Filter Type</label>
                <select class="select-filter form-control rounded py-1" name="filter_type">
                  <option value="">Select Type</option>
                  <option value="call" {{ \Request::get('filter_type') == 'voice' ? 'selected' : '' }}>Voice/Call</option>
                  <option value="sms" {{ \Request::get('filter_type') == 'sms' ? 'selected' : '' }}>Sms</option>
                  <option value="data" {{ \Request::get('filter_type') == 'data' ? 'selected' : '' }}>Data</option>
                  <option value="hybrid" {{ \Request::get('filter_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Filter Validity</label>
                <select class="select-filter form-control rounded py-1" name="filter_validity">
                  <option value="">Select Validity</option>
                  <option value="hourly" {{ \Request::get('filter_validity') == 'hourly' ? 'selected' : '' }}>Hourly</option>
                  <option value="daily" {{ \Request::get('filter_validity') == 'daily' ? 'selected' : '' }}>Daily</option>
                  <option value="weekly" {{ \Request::get('filter_validity') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                  <option value="monthly" {{ \Request::get('filter_validity') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Filter Data</label>
                <select class="select-filter form-control rounded py-1" name="filter_data">
                  <option value="">Select Data</option>
                  <option value="0" {{ \Request::get('filter_data') == '0' ? 'selected' : '' }}>0</option>
                  <option value="1" {{ \Request::get('filter_data') == '1' ? 'selected' : '' }}>1GB or Less</option>
                  <option value="5" {{ \Request::get('filter_data') == '5' ? 'selected' : '' }}>5GB or Less</option>
                  <option value="10" {{ \Request::get('filter_data') == '10' ? 'selected' : '' }}>10GB or Less</option>
                  <option value="25" {{ \Request::get('filter_data') == '25' ? 'selected' : '' }}>25GB or Less</option>
                  <option value="100" {{ \Request::get('filter_data') == '100' ? 'selected' : '' }}>100GB or Less</option>
                  <option value="1000" {{ \Request::get('filter_data') == '1000' ? 'selected' : '' }}>1000GB or Less</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Package Type</label>
                <select class="select-filter form-control rounded py-1" name="type">
                  <option value="">Select Packge Type</option>
                  <option value="0" {{ \Request::get('type') == '0' ? 'selected' : '' }}>Prepaid</option>
                  <option value="1" {{ \Request::get('type') == '1' ? 'selected' : '' }}>Postpaid</option>
                </select>
              </div>
            </div>
          </div>
        </form>
        <form class="container" method="get" action="">
          <div class="row mx-5">
              <div class="col-12 col-md-6">
                  <div class="input-group mb-3">
                      <input type="text" name="search" value="{{\Request::get('search')}}" class="form-control" placeholder="Search" aria-label="Search" required>
                      <div class="input-group-append">
                          <button class="btn btn-primary" type="submit">Submit</button>
                      </div>
                  </div>
              </div>
          </div>
      </form>

        <div class="col-12 col-md-10">
          <h1>Packages ({{$packages->total()}})</h1>
          @include("includes.info-bar")
          <div class="table-responsive">
            <table class="table table-stripped">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Filter Network</th>
                  <th>Filter Type</th>
                  <th>Filter Data</th>
                  <th>Package Type</th>
                  <th>Price</th>
                  <th>Data</th>
                  <th>Validity</th>
                  <th style="width: 105px;">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($packages as $package)
                <tr>
                  <td>{{ $package->name }}</td>
                  <td>{{ $package->filter_network }}</td>
                  <td>{{ $package->filter_type }}</td>
                  <td>{{ $package->filter_data }}</td>
                  <td>{{ $package->type == 0 ? 'Prepaid' : 'PostPaid' }}</td>
                  <td>{{ $package->price }}</td>
                  <td>{{ $package->data }}</td>
                  <td>{{ $package->validity }}</td>
                  <td><a href="{{route('dashboard.packages.edit',$package->id)}}" class="btn btn-success text-white btn-sm">Edit</a> <a href="{{route('package.show',[$package->filter_network,$package->slug])}}" target="_blank" class="btn btn-primary btn-sm">View</a></td>
                </tr>
                @endforeach
              </tbody>
            </table>
            {{$packages->links()}}
          </div>

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
<script type="text/javascript">
  $(document).ready(function(){
    $('.select-filter').on('change', function(){
        $(this).closest('form').submit();
    });
});

</script>

@stop
