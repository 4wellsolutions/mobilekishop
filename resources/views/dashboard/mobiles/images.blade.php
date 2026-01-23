@extends("layouts.dashboard")

@section("title")Mobiles @stop

@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">All Mobiles</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Mobiles
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <div class="row bg-white">
      <div class="px-5">
        <form action="{{\Request::fullUrl()}}" method="get">
          <div class="row">
            <div class="col-9 col-md-5 col-lg-5">
              <label>Limit (Mobile Results per page)</label>
              <input type="text" name="limit" id="limit" class="form-control" value="{{\Request::get('limit') ? \Request::get('limit') : '50'}}" required>
            </div>
            <div class="col-9 col-md-5 col-lg-5">
              <label>Offset (Starting Point)</label>
              <input type="text" name="offset" id="offset" class="form-control" value="{{\Request::get('offset') ? \Request::get('offset') : '0'}}" required>
            </div>
            <div class="col-3 col-md-2 mt-auto">
              <button class="btn btn-primary" type="submit">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="">
        @include("includes.info-bar")
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Mobile Images</h5>
            <div class="table-responsive">
              <table id="zero_config" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Sr.#</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Brand</th>
                    <th>Height</th>
                    <th>Alt Tag</th>
                    <th>Last Updated</th>
                    <th>Action</th>
                  </tr>
                </thead>
                @if(!$mobiles->isEmpty())
                @php 
                  $i=1;
                @endphp
                @foreach($mobiles as $mobile)
                  @foreach($mobile->images as $image)
                  @php
                    $img = URL::to('/mobiles/')."/".$image->name;
                    $imag = \Image::make($img);
                  @endphp
                  <tbody>
                    <tr>
                      <td>{{$i}}</td>
                      <td>{{$mobile->id}}</td>
                      <td>{{Str::title($mobile->name)}}</td>
                      <td><img src="{{$img}}" class="img-fluid" style="max-height: 70px;"></td>
                      <td>{{$mobile->brand->name}}</td>
                      <td>{{$imag->height()}}x{{$imag->width()}} {{$imag->filesize()}}</td>
                      <td>{{$image->alt}}</td>
                      <td>{{date("d-m-Y H:i:s", strtotime('+5 hours', strtotime($mobile->updated_at)))}}</td>
                      <td><a href="{{route('dashboard.mobile.edit',$mobile->id)}}"><i class="far fa-edit text-success fa-2x"></i></a> <a href="{{route('mobile.show',['brand' => $mobile->brand->slug,'slug' => $mobile->slug])}}" target="_blank"><i class="fas fa-eye fa-2x text-warning"></i></a></td>
                    </tr>
                  </tbody>
                  @php
                    $i++;
                  @endphp
                  @endforeach
                @endforeach
                @endif
                <tfoot>
                  <tr>
                    <th>Sr.#</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Brand</th>
                    <th>Date Added</th>
                    <th>Last Updated</th>
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
<style type="text/css">
  .twitter-typeahead{
    width: 100% !important;
  }
  .tt-menu{
    width: inherit !important;
  }
</style>
@stop
