@extends("layouts.dashboard")

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
                          <th>Mobile</th>
                          <th>Reviewer Name</th>
                          <th>Review</th>
                          <th>Status</th>
                          <th>Added Date</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      @if(!$reviews->isEmpty())
                      @foreach($reviews as $review)
                      <tbody>
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          <td>{{ optional($review->product)->name}}</td>
                          <td>{{$review->user ? $review->user->name : $review->name}}</td>
                          <td>{{$review->review}}</td>
                          <td>{!! ($review->is_active) ? '<button class="btn btn-success btn-sm text-white">Approved</button>' : '<button class="btn btn-danger btn-sm">Pending</button>' !!}</td>
                          <td>{{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</td>
                          <td>
                            <a href="{{route('dashboard.review.edit',$review->id)}}">
                              <i class="far fa-edit text-success fa-2x"></i></a> 
                          </td>
                        </tr>
                      </tbody>
                      @endforeach
                      @endif
                      <tfoot>
                        <tr>
                          <th>Sr.#</th>
                          <th>Mobile</th>
                          <th>Reviewer Name</th>
                          <th>Review</th>
                          <th>Status</th>
                          <th>Added Date</th>
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
