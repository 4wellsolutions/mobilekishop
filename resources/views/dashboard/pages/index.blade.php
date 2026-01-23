@extends("layouts.dashboard")
@section('title', 'Pages')
@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">All Pages</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Pages
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
                  <h5 class="card-title">Pages</h5>
                  @include("includes.info-bar")
                  <div class="row">
                    <div class="col mb-1">
                      <form action="" method="get">
                      <div class="input-group mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Search URL" value="{{\Request::get('search')}}">
                        <button type="submit" class="btn btn-dark">Search</button>
                      </div>
                    </form>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Sr.#</th>
                          <th>Title</th>
                          <th>URL</th>
                          <th>Added At</th>
                          <th>Added By</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      @if($pages->isNotEmpty())
                      @foreach($pages as $page)
                      <tbody>
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          <td>{{$page->title}}</td>
                          <td>{{$page->slug}}</td>
                          <td>{{date("d-m-Y",strtotime($page->created_at))}}</td>
                          <td>{{$page->User->name}}</td>
                          <td>
                            <a href="{{route('dashboard.pages.edit',$page->id)}}">
                              <i class="far fa-edit text-success fa-2x"></i></a> 
                            <a href="{{$page->slug}}" target="_blank"><i class="fas fa-eye fa-2x text-warning"></i>
                            </a>
                          </td>
                        </tr>
                      </tbody>
                      @endforeach
                      @endif
                      <tfoot>
                        <tr>
                          <th>Sr.#</th>
                          <th>Title</th>
                          <th>URL</th>
                          <th>Added At</th>
                          <th>Added By</th>
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
