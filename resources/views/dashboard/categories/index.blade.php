@extends("layouts.dashboard")

@section("title","Categories - Dashboard - MKS")

@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">All Categories</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
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
    
    <div class="">
        
        <div class="card">
          
          <div class="card-body">
            <div class="row">
              <div class="col-12 mb-2">
                <a class="btn btn-success text-white" href="{{route('dashboard.categories.create')}}">Create Category</a>
              </div>
            </div>
            <div class="col-12">
              @include("includes.info-bar")
            </div>
            <div class="table-responsive">
              <table id="zero_config" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Sr.#</th>
                    <th>Name</th>
                    <th>Attributes</th>
                    <th>Date Added</th>
                    <th>Date Updated</th>
                    <th>Action</th>
                  </tr>
                </thead>
                @if(!$categories->isEmpty())
                @foreach($categories as $category)

                <tbody>
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{Str::title($category->category_name)}}</td>
                    <td>{{$category->attributes()->count()}}</td>
                    <td>{{\Carbon\Carbon::parse($category->created_at)->diffForHumans()}}</td>
                    <td>{{\Carbon\Carbon::parse($category->updated_at)->diffForHumans()}}</td>
                    <td><a href="{{route('dashboard.categories.edit',$category->id)}}"><i class="far fa-edit text-success fa-2x"></i></a></td>
                  </tr>
                </tbody>
                @endforeach
                @endif
                <tfoot>
                  <tr>
                    <th>Sr.#</th>
                    <th>Name</th>
                    <th>Attributes</th>
                    <th>Date Added</th>
                    <th>Date Updated</th>
                    <th>Action</th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>    
    </div>
  </div>
@stop

@section('styles')

@stop

@section('scripts')
    
@stop