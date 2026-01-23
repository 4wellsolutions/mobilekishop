@extends("layouts.dashboard")
@section("title","Add Product")
@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Create Page</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Pages
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    
    <div class="">
      
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Create Page</h5>
          @include("includes.info-bar")
          <form action="{{ route('dashboard.pages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="title">Title</label>
                  <input type="text" class="form-control" id="title" name="title" value="{{old('title')}}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="description">Description</label>
                  <input type="text" class="form-control" id="description" name="description" value="{{old('description')}}" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="canonical">Canonical</label>
                  <input type="text" class="form-control" id="canonical" name="canonical" value="{{old('canonical')}}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="slug">Slug</label>
                  <input type="text" class="form-control" id="slug" name="slug" value="{{old('slug')}}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="h1">H1</label>
                  <input type="text" class="form-control" id="h1" name="h1" value="{{old('h1')}}" required>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="body">Body</label>
              <textarea class="form-control" id="body" name="body" rows="5">{{old('body')}}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@stop

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script>
<script type="text/javascript">
    ClassicEditor
        .create( document.querySelector( '#body' ) )
        .then( editor => {
                console.log( editor );
        } )
        .catch( error => {
                console.error( error );
        } );

    </script>
@stop

@section('styles')
<style type="text/css">
    .ck-editor__editable_inline {
        min-height: 200px;
    }
</style>
@stop