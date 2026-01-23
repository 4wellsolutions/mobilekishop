@extends("layouts.dashboard")
@section("title", "Edit Color Varaint")
@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Color</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pt-sm-1">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Color
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center bg-white pt-4">
      <div class="col-12 col-md-10">
        <h1>Edit Color</h1>
            @include("includes.info-bar")
        <form class="pageForm" method="post" action="{{route('dashboard.colors.update',$color)}}" enctype="multipart/form-data">
          @csrf
          <div class="form-group col-12 col-md-12 col-lg-6">
              <label for="">Name</label>
              <input type="text" name="name" value="{{old('name',$color->name)}}" class="form-control" required>
          </div>
          <div class="form-group my-3">
              <button class="btn btn-dark formButton">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@stop

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 on product_id
    $('#product_id').select2({
        placeholder: "Select a product",
        allowClear: true
    });
    $('#specification_id').select2({
        placeholder: "Select a specification",
        allowClear: true
    });
});
</script>
@stop