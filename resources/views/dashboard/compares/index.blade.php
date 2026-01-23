@extends("layouts.dashboard")
@section("title", "Add Compare")
@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Compare Mobiles</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pt-sm-1">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Compare Mobiles
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    @include("includes.info-bar")
    @if(!$compares->isEmpty())
        <div class="row bg-white py-3">
          <div class="px-5">
        <form action="{{\Request::fullUrl()}}" method="get">
          <div class="row">
            <div class="col-9 col-md-9 col-lg-6">
              <label>Search</label>
              <input type="text" name="search" id="search" class="form-control" required>
            </div>
            <div class="col-3 col-md-3 mt-auto">
              <button class="btn btn-primary" type="submit">Submit</button>
            </div>
          </div>
        </form>
      </div>
        </div>
        <div class="row bg-white pt-5">
          <div class="col-12">
            <div class="mx-3">
              <h1>Compare Links</h1>
              <div class="table-responsive">
                <table class="table table-striped table-sm">
                  <tr>
                    <th>Sr.#</th>
                    <th>Link</th>
                    <th>Created At</th>
                    <th>Action</th>
                  </tr>
                  @foreach($compares as $compare)
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$compare->link}}</td>
                    <td>{{\Carbon\Carbon::parse($compare->created_at)->diffForHumans()}}</td>
                    <td>
                      <a class="btn btn-info btn-sm" href="{{route('dashboard.compares.edit',$compare->id)}}">Edit</a>
                      <a href="{{$compare->link}}" class="btn btn-warning btn-sm" target="_blank">View</a></td>
                  </tr>
                  @endforeach
                </table>
              </div>
              {{$compares->links()}}
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
  }
</style>
@stop
