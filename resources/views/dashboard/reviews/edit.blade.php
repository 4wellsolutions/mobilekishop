@extends("layouts.dashboard")
@section('title','Review Edit ')
@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title text-uppercase">{{$review->product->name}}</h4>
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
                  <h5 class="card-title text-uppercase"> Edit {{$review->name}}</h5>
                  <form action="{{route('dashboard.review.update',$review->id)}}" method="post"  enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user_id" value="{{$review->user_id}}">
                    <input type="hidden" name="product_id" value="{{$review->product_id}}">
                    <div class="col-12 col-sm-12">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" value="{{old('name') ? old('name') : $review->name}}" id="name" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12">
                        <div class="form-group">
                            <label for="name">Email</label>
                            <input type="text" name="email" value="{{$review->email ? $review->email : ''}}" id="email" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12">
                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input type="text" name="phone_number" value="{{$review->phone_number}}" id="phone_number" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-sm-12">
                        <div class="form-group">
                            <label for="review">Review</label>
                            <textarea class="form-control" name="review">{!! $review->review !!}</textarea>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12">
                        <div class="form-group">
                            <label for="keywords">Stars</label>
                            @for($i=1;$i<=$review->stars;$i++)
                            <img src="{{URL::to('/images/icons/star.png')}}" class="img-fluid">
                            @endfor
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 my-3">
                        <input type="checkbox" name="is_active" data-toggle="toggle" {{($review->is_active) ? "checked" : ""}}>
                    </div>
                    <div class="col-12 col-sm-12">
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Update</button>
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
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
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