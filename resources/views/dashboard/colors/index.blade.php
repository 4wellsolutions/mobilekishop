@extends("layouts.dashboard")
@section("title","Colors")
@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">All Colors</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Colors
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
                  <h5 class="card-title">Colors</h5>
                  <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Sr.#</th>
                          <th>Color</th>
                          <th>Created</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      @if(!$colors->isEmpty())
                      @foreach($colors as $color)
                      <tbody>
                        <tr>
                          <td>{{ $loop->iteration}}</td>
                          <td>{{ $color->name}}</td>
                          <td>{{ \Carbon\Carbon::parse($color->created_at)->diffForHumans() }}</td>
                          <td>
                            <a href="{{route('dashboard.colors.edit',$color)}}" class="btn btn-success btn-sm text-white">Edit</a>
                            <a href="{{route('dashboard.colors.destroy',$color)}}" class="btn btn-danger btn-sm" onclick="return confirm('Are your sure!')">Del</a>
                            </td>
                        </tr>
                      </tbody>
                      @endforeach
                      @endif
                      <tfoot>
                        <tr>
                          <th>Sr.#</th>
                          <th>color</th>
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
