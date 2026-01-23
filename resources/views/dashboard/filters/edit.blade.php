@extends("layouts.dashboard")
@section('title',"Edit Filter")
@section("content")
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Edit Mobile Filter</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Mobile Filters
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row bg-white pt-5">
        <div class="col-12">
            <div class="mx-3">
                @include("includes.info-bar")
                <!-- Ensure the action points to your update route for the filter, and use the PUT method -->
                <form action="{{route('dashboard.filters.update', $filter->id)}}" method="post" class="row">
                    @csrf
                    @method('PUT') <!-- Method Spoofing for PUT request -->
                    
                    <div class="col-12 col-md-12">
                        <div class="form-group">
                            <label>Page URL</label>
                            <input type="url" name="page_url" id="page_url" class="form-control" value="{{ old('page_url',$filter->page_url) }}">
                        </div>
                    </div>

                    <div class="col-12 col-md-12">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $filter->title) }}">
                        </div>
                    </div>

                    <div class="col-12 col-md-12">
                        <div class="form-group">
                            <label>URL</label>
                            <input type="text" name="url" id="url" class="form-control" value="{{ old('url', $filter->url) }}">
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group pt-1">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </div>
                </form>
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
  }
</style>
@stop

@section('scripts')
    
@stop
