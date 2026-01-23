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
      <div class="px-5 py-3">
        <form action="{{\Request::fullUrl()}}" action="get">
          <div class="row">
            <div class="col-12">
              @include("includes.info-bar")
            </div>
            <div class="col-12 col-md-3 col-lg-3">
              <div class="form-group">
                <label>Date Filter</label>
                <select class="form-control" name="date_filter" id="date_filter" >
                  <option value="">Select Filter</option>
                <option value="created_at" {{\Request::get('date_filter') == "created_at" ? "selected" : ''}}>Created By</option>
                <option value="updated_at" {{\Request::get('date_filter') == "updated_at" ? "selected" : ''}}>Updated By</option>
              </select>
              </div>
            </div>
            <div class="col-12 col-md-3 col-lg-3">
              <div class="form-group">
                <label>Date1</label>
                <input type="date" name="date1" class="form-control" value="{{\Request::has('date1') ? \Request::get('date1') : \Carbon\Carbon::now()->format('Y-m-d')}}">
              </div>
            </div>
            <div class="col-12 col-md-3 col-lg-3">
              <div class="form-group">
                <label>Date1</label>
                <input type="date" name="date2" class="form-control" value="{{\Request::has('date2') ? \Request::get('date2') : \Carbon\Carbon::now()->format('Y-m-d')}}">
              </div>
            </div>
            <div class="row mt-3">
            <div class="col-12 col-md-4 col-lg-4">
              <label>Filter By</label>
              <select class="form-control" name="filter_key" id="filter_key">
                <option value="">Filter</option>
                <option value="id" {{\Request::get('filter_key') == "id" ? "selected" : ''}}>ID</option>
                <option value="views" {{\Request::get('filter_key') == "views" ? "selected" : ''}}>Views</option>
                <option value="release_date" {{\Request::get('filter_key') == "release_date" ? "selected" : ''}}>Release Date</option>
                <option value="created_at" {{\Request::get('filter_key') == "created_at" ? "selected" : ''}}>Created By</option>
                <option value="updated_at" {{\Request::get('filter_key') == "updated_at" ? "selected" : ''}}>Updated By</option>
              </select>
            </div>
            <div class="col-9 col-md-5 col-lg-4">
              <label>OrderBy</label>
              <select class="form-control" name="filter_value" id="filter_value">
                <option value="">Order By</option>
                <option value="ASC" {{\Request::get('filter_value') == "ASC" ? "selected" : ''}}>Ascending</option>
                <option value="DESC" {{\Request::get('filter_value') == "DESC" ? "selected" : ''}}>Descending</option>
              </select>
            </div>
            <div class="col-3 col-md-2 mt-auto">
              <button class="btn btn-primary" type="submit">Submit</button>
            </div>
          </div>
          </div>
        </form>
      </div>
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
    <div class="">
        
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Total Mobiles: {{App\Mobile::count()}}, Mobiles Found: {{$total}}, Today: ({{isset($today) ? $today : 0}})</h5>
            <div class="table-responsive">
              <table id="zero_config" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Sr.#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Views</th>
                    <th>Release Date</th>
                    <th>Date Added</th>
                    <th>Last Updated</th>
                    <th>Action</th>
                  </tr>
                </thead>
                @if(!$mobiles->isEmpty())
                @foreach($mobiles as $mobile)

                <tbody>
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td><img src="{{$mobile->thumbnail}}" class="img-fluid" style="max-height: 70px;"></td>
                    <td>{{Str::title($mobile->name)}}</td>
                    <td>{{isset($mobile->brand->name) ? $mobile->brand->name : 'N/A'}}</td>
                    <td>Rs.{{number_format($mobile->price_in_pkr)}}</td>
                    <td>{{$mobile->views}}</td>
                    <td>{{date("d-m-Y",strtotime($mobile->release_date))}}</td>
                    <td>{{date("d-m-Y H:i:s", strtotime('+5 hours', strtotime($mobile->created_at)))}}</td>
                    <td>{{date("d-m-Y H:i:s", strtotime('+5 hours', strtotime($mobile->updated_at)))}}</td>
                    <td><a href="{{route('dashboard.mobile.edit',$mobile->id)}}"><i class="far fa-edit text-success fa-2x"></i></a> <a href="{{route('mobile.show',['brand' => $mobile->brand->slug,'slug' => $mobile->slug])}}" target="_blank"><i class="fas fa-eye fa-2x text-warning"></i></a></td>
                  </tr>
                </tbody>
                @endforeach
                @endif
                <tfoot>
                  <tr>
                    <th>Sr.#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Views</th>
                    <th>Release Date</th>
                    <th>Date Added</th>
                    <th>Last Updated</th>
                    <th>Action</th>
                  </tr>
                </tfoot>
              </table>
              {{$mobiles->withQueryString()->links()}}
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

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/0.11.1/typeahead.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
    </script>
    <script type="text/javascript">
        var route = "{{ route('autocomplete.search') }}";
        var base_url = "{{URL::to('/dashboard/mobile')}}";
        // Set the Options for "Bloodhound" suggestion engine
        var engine = new Bloodhound({
            remote: {
                url: route+"?query=%QUERY%",
                wildcard: '%QUERY%'
            },
            datumTokenizer: Bloodhound.tokenizers.whitespace('query'),
            queryTokenizer: Bloodhound.tokenizers.whitespace
        });

        $("#search").typeahead({
            hint: false,
            highlight: true,
            minLength: 1
        }, {
            source: engine.ttAdapter(),
            limit: 8+1,

            // This will be appended to "tt-dataset-" to form the class name of the suggestion menu.
            name: 'usersList',
            displayKey: 'name',
            // the key from the array we want to display (name,id,email,etc...)
            templates: {
                empty: [
                    '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                ],
                header: [
                    '<div class="list-group search-results-dropdown">'
                ],
                suggestion: function (data) {
                    console.log(data.brand.slug);
                     return '<a target="_blank" href="'+base_url+'/'+data.id+'/edit"><div class="row bg-white border-bottom"><div class="col-2"><img src="'+data.thumbnail+'" class="img-fluid searchImage my-1"></div><div class="col-10 text-uppercase" style="font-weight:600;">'+data.name+'</div></div></a>'
          }
            }
        });

        $('#searchInput').bind('typeahead:select', function(ev, suggestion) {
            console.log('Selection: ' + $(this).attr("id"));
            $("#input-"+$(this).attr("id")).val(suggestion.slug);
            if($("#searchInput").val() != ""){
                param1 = $("#searchInput").val();
            }
        });
    </script>

@stop