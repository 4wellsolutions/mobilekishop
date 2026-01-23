@extends("layouts.dashboard")
@section("title", "Create Category")
@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Categories</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pt-sm-1">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Categories
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center bg-white">
        <div class="col-12 col-md-10">
          <h1>Add Category</h1>
          @include("includes.info-bar")
          <form class="pageForm" method="post" action="{{route('dashboard.categories.store')}}" enctype="multipart/form-data">
            @csrf
          <div class="row">
            <div class="col-12 col-md-12">
              <div class="form-group">
                <label>Name</label>
                <input type="text" name="category_name" class="form-control" value="{{ old('category_name') }}">
              </div>
              <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}">
              </div>
              <div class="form-group">
                <label>Description</label>
                <input type="text" name="description" class="form-control" value="{{ old('description') }}">
              </div>
            </div>
          <div class="form-group">
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
