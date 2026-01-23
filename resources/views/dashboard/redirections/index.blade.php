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
    
    @if(!$redirections->isEmpty())
        <div class="row bg-white py-3 px-3">
          @include("includes.info-bar")
          <h1>Redirections</h1>
          <div class="col me-auto">
            <form>
              <div class="input-group border">
                <div class="col-auto">
                  <select name="url_type" class="form-control">
                    <option value="from" {{Request::get("url_type") == "from" ? "selected" : ''}}>From URL</option>
                    <option value="to" {{Request::get("url_type") == "to" ? "selected" : ''}}>To URL</option>
                  </select>
                </div>
                  <input type="text" name="search" value="{{Request::has('search') ? Request::get('search') : ''}}" class="form-control border rounded {{Request::has('search') ? 'border-end-0' : ''}}" id="search">
                @if(Request::has("search"))
                <a href="{{route('dashboard.redirections.index')}}"><span class="btn border-top border-bottom"><i class="fa-solid fa-xmark"></i></span></a>
                @endif
                <button class="btn btn-secondary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
              </div>
            </form>
        </div>
        <div class="col-auto mx-3">
            <a href="{{route('dashboard.redirections.create')}}" class="btn btn-dark">Add Redirection</a>
          </div>
        </div>
        <div class="row bg-white pt-5">
          <div class="col-12">
            <div class="mx-3">
              <h1>Redirections</h1>
              <div class="table-responsive">
                <table class="table table-striped table-sm">
                  <tr>
                    <th>Sr.#</th>
                    <th>URL From</th>
                    <th>URL To</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Action</th>
                  </tr>
                  @foreach($redirections as $redirection)
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$redirection->from_url}}</td>
                    <td>{{$redirection->to_url}}</td>
                    <td>{{isset($redirection->user->name) ? $redirection->user->name : 'N/A'}}</td>
                    <td>{{\Carbon\Carbon::parse($redirection->created_at)->format("H:i:s d-m-Y")}}</td>
                    <td>
                      <a class="btn btn-info btn-sm" href="{{route('dashboard.redirections.edit',$redirection->id)}}">Edit</a>
                      <form method="POST" action="{{ route('dashboard.redirections.destroy', $redirection) }}">
                          {{ csrf_field() }}
                          @method('DELETE')
                          <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure!')">Del</button>
                      </form>
                    </td>
                  </tr>
                  @endforeach
                </table>
              </div>
              {{$redirections->links()}}
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
