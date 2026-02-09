@extends("layouts.dashboard")

@section("title","Attributes - Dashboard")

@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">All Attributes</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Attributes
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
              <form action="" method="get">
                <div class="mb-3">
                    <label for="search" class="form-label">Search</label>
                    <div class="input-group">
                        <input type="text" id="search" name="search" class="form-control" value="{{\Request::get("search")}}" placeholder="Search...">
                        <select class="form-select" name="category_id" id="category_id">
                            <option value="">Select Category</option>
                            @if($categories = App\Models\Category::all())
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}" {{ \Request::get("category_id") == $category->id ? 'selected' : '' }}>
                                        {{$category->category_name}}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>

            </div>
            <div class="row">
              <div class="col-12 mb-2">
                <a class="btn btn-success text-white" href="{{route('dashboard.attributes.create')}}">Create Attribute</a>
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
                    <th>Sequence</th>
                    <th>Date Added</th>
                    <th>Date Updated</th>
                    <th>Action</th>
                  </tr>
                </thead>
                @if(!$attributes->isEmpty())
                @foreach($attributes as $attribute)

                <tbody>
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$attribute->name}}</td>
                    <td>{{$attribute->label}}</td>
                    <td>{{$attribute->sequence}}</td>
                    <td>{{$attribute->category->category_name}}</td>
                    <td>{{\Carbon\Carbon::parse($attribute->created_at)->diffForHumans()}}</td>
                    <td>{{\Carbon\Carbon::parse($attribute->updated_at)->diffForHumans()}}</td>
                    <td><a href="{{route('dashboard.attributes.edit',$attribute)}}"><i class="far fa-edit text-success fa-2x"></i></a></td>
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
              {{$attributes->links()}}
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