@extends("layouts.dashboard")
@section("title", "Redirections - MKS")
@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Redirect Management</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pt-sm-1">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Redirection Management
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center bg-white">
        <div class="col-12 col-md-10">
          <h1>Add Redirection</h1>
              @include("includes.info-bar")
          <form class="pageForm" method="post" action="{{route('dashboard.redirections.store')}}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label>From URL</label>
            <input type="text" class="form-control" placeholder="From URL" name="from_url" id="from_url">
          </div>
          <div class="form-group">
              <label>To URL</label>
            <input type="text" class="form-control" placeholder="To URL" name="to_url" id="to_url">
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


@stop
