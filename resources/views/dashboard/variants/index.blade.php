@extends("layouts.dashboard")
@section("title","Variants")
@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">All Variants</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Variants
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
                  <h5 class="card-title">Variants</h5>
                  <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Sr.#</th>
                          <th>Name</th>
                          <th>Created</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      @if(!$variants->isEmpty())
                      @foreach($variants as $variant)
                      <tbody>
                        <tr>
                          <td>{{ $loop->iteration}}</td>
                          <td>{{ Str::title($variant->variant_name)}}</td>
                          <td>{{ \Carbon\Carbon::parse($variant->created_at)->diffForHumans() }}</td>
                          <td>
                            <a href="{{route('dashboard.variants.edit',$variant)}}" class="btn btn-success btn-sm text-white">Edit</a>
                            <a href="{{route('dashboard.variants.destroy',$variant)}}" class="btn btn-danger btn-sm" onclick="return confirm('Are your sure!')">Del</a>
                            </td>
                        </tr>
                      </tbody>
                      @endforeach
                      @endif
                      <tfoot>
                        <tr>
                          <th>Sr.#</th>
                          <th>Memory</th>
                          <th>Storage</th>
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
