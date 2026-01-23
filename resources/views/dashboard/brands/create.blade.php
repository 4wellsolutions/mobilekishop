@extends("layouts.dashboard")
@section("title", "Create Brand")
@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title text-uppercase">Add New Brand</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Brands
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    
    <div class="">
        @include("includes.info-bar")
        <div class="card">
                <div class="card-body">
                  <h5 class="card-title text-uppercase">Add New Brand</h5>
                  <form action="{{route('dashboard.brands.store')}}" method="post"  enctype="multipart/form-data">
                    @csrf
                    <div class="col-12 col-sm-12">
                        <label for="categories">Categories:</label>
                        <select name="categories[]" id="categories" size="3" class="form-select" aria-label="multiple select example" style="height: 90px;" multiple>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-sm-12">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" value="{{old('name')}}" id="name" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-sm-12">
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" value="{{old('slug')}}" id="slug" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-sm-12">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" value="{{old('title')}}" id="title" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-sm-12">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" name="description" id="description" value="{{old('description')}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-sm-12">
                        <div class="form-group">
                            <label for="keywords">Keywords</label>
                            <textarea name="keywords" id="keywords" class="form-control">{{old('keywords')}}</textarea>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12">
                        <div class="form-group">
                            <label for="body">Body</label>
                            <textarea name="body" id="body" class="form-control">{!! old('body') !!}</textarea>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 my-3">
                        <div class="form-group">
                            <label for="is_featured">
                            <input type="checkbox" name="is_featured" id="is_featured">
                            Featured</label>
                            
                        </div>
                    </div>
                    
                    <div class="col-12 col-sm-12 my-3">
                        <div class="form-group">
                            <label for="body">Thumbnail (160*160)</label>
                            <input type="file" name="thumbnail" accept="image/jpeg, image/png" id="thumbnail" required>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12">
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>    
    </div>
  </div>
@stop

@section('scripts')
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'body' );
    </script>
@stop

@section('styles')
<style type="text/css">
    .ck-editor__editable_inline {
        min-height: 200px;
    }
</style>
@stop
