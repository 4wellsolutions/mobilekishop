@extends("layouts.dashboard")
@section("title", "Brands")
@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">All Brands</h4>
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
                  <h5 class="card-title">Brands</h5>
                  <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Sr.#</th>
                          <th>Name</th>
                          <th>Products</th>
                          <th>Category</th>
                          <th>Added Date</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      @if(!$brands->isEmpty())
                      @foreach($brands as $brand)
                      <tbody>
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          <td>{{$brand->name}}</td>
                          <td>{{count($brand->products)}}</td>
                          <td>{{ $brand->categories->pluck('category_name')->join(', ') }}</td>
                          <td>{{date("d-m-Y",strtotime($brand->created_at))}}</td>
                          <td>
                            <a href="{{route('dashboard.brands.edit',$brand->id)}}">
                              <i class="far fa-edit text-success fa-2x"></i></a> 
                          </td>
                        </tr>
                      </tbody>
                      @endforeach
                      @endif
                      <tfoot>
                        <tr>
                          <th>Sr.#</th>
                          <th>Name</th>
                          <th>Featured</th>
                          <th>Release Date</th>
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
