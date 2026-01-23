@extends("layouts.dashboard")

@section("content")
  <div class="page-wrapper bg-white">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Mobiles Filters</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pt-sm-1">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Mobiles Filters
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    @include("includes.info-bar")
    <div class="container bg-white">
      <div class="row bg-white pt-3">
          <form action="" method="get" class="w-100">
              <div class="col-12">
                  <h3>Search</h3>
                  <div class="input-group mb-3">
                      <input type="text" name="search" id="search" class="form-control" placeholder="Search" value="{{ old('search',Request::get('search')) }}">
                      <select class="form-control" id="search_by" name="search_by" style="max-width: 20%;">
                          <option value="page_url" {{ old('search_by',Request::get('search_by')) == 'page_url' ? 'selected' : '' }}>Page URL</option>
                          <option value="url" {{ old('search_by',Request::get('search_by')) == 'url' ? 'selected' : '' }}>Filter URL</option>
                      </select>
                      <div class="input-group-append">
                          <button class="btn btn-primary" type="submit">Search</button>
                      </div>
                  </div>
              </div>
          </form>
      </div>
  </div>

  
    @if(!$filters->isEmpty())
        <div class="row bg-white pt-5">
          <div class="col-12">
            <div class="mx-3">
              <h1>Filters</h1>
              <div class="table-responsive">
                <table class="table table-striped table-sm">
                  <tr>
                    <th>Sr.#</th>
                    <th>Page URL</th>
                    <th>Title</th>
                    <th>Filter URL</th>
                    <th>Created At</th>
                    <th>Action</th>
                  </tr>
                  @foreach($filters as $filter)
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$filter->page_url}}</td>
                    <td>{{$filter->title}}</td>
                    <td>{{$filter->url}}</td>
                    <td>{{\Carbon\Carbon::parse($filter->created_at)->diffForHumans()}}</td>
                    <td>
                      <a class="btn btn-info btn-sm" href="{{route('dashboard.filters.edit',$filter->id)}}">Edit</a>
                      <a href="{{$filter->url}}" class="btn btn-warning btn-sm" target="_blank">View</a></td>
                  </tr>
                  @endforeach
                </table>
              </div>
              
            </div>
          </div>
        </div> 
      
    @endif

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
