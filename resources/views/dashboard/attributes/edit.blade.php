@extends("layouts.dashboard")
@section("title", "Edit Attribute - Dashboard")
@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Attributes</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pt-sm-1">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Attributes
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center bg-white">
      <div class="col-12 col-md-10">
        <h1>Add Attribute</h1>
        @include("includes.info-bar")
        <form class="pageForm" method="post" action="{{route('dashboard.attributes.update',$attribute)}}" enctype="multipart/form-data">
          @csrf
          @method("put")
          <div class="row">
            <div class="col-12 col-md-12">
              <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name',$attribute->name) }}">
              </div>
              <div class="form-group">
                <label>Label</label>
                <input type="text" name="label" class="form-control" value="{{ old('label',$attribute->label) }}">
              </div>
              
              <div class="form-group">
                <label>Category</label>
                <select class="form-control" name="category_id" id="category_id">
                  <option>Select Category</option>
                  @if($categories = App\Category::all())
                  @foreach($categories as $category)
                  <option value="{{$category->id}}" {{$attribute->category_id == $category->id ? 'selected' : ''}}>{{$category->category_name}}</option>
                  @endforeach
                  @endif
                </select>
              </div>
              <div class="form-group">
                <label>Sequence</label>
                <input type="number" name="sequence" class="form-control" value="{{ old('sequence',$attribute->sequence) }}">
              </div>
              <div class="form-group">
                <label>Sequence</label>
                <select class="form-control" name="type" id="type">
                  <option value="input">Input</option>
                  <option value="text">Text</option>
                  <option value="number">Number</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <button class="btn btn-dark formButton">Update</button>
            </div>
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
  }
  .table td, .table th {
    padding: 0.5rem !important;
  }
</style>
@stop

@section('scripts')


@stop
