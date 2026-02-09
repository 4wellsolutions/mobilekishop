@extends("layouts.dashboard")

@section("title","Products - Dashboard")

@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">All Products</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Products
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <div class="row bg-white"> 
      <div class="px-5 py-3">
        <!-- Combined form action with a single button for submit -->
        <form action="{{ \Request::fullUrl() }}" method="get">
          <div class="row">
            <!-- First Row for Filters -->
            <div class="col-12 col-md-6 col-lg-3">
              <div class="form-group">
                <label>Date Filter</label>
                <select class="form-control" name="date_filter" id="date_filter">
                  <option value="">Select Filter</option>
                  <option value="created_at" {{ \Request::get('date_filter') == "created_at" ? "selected" : '' }}>Created By</option>
                  <option value="updated_at" {{ \Request::get('date_filter') == "updated_at" ? "selected" : '' }}>Updated By</option>
                </select>
              </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
              <div class="form-group">
                <label>Date1</label>
                <input type="date" name="date1" class="form-control" value="{{ \Request::has('date1') ? \Request::get('date1') : \Carbon\Carbon::now()->format('Y-m-d') }}">
              </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
              <div class="form-group">
                <label>Date2</label>
                <input type="date" name="date2" class="form-control" value="{{ \Request::has('date2') ? \Request::get('date2') : \Carbon\Carbon::now()->format('Y-m-d') }}">
              </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
              <div class="form-group">
                <label>Category (Optional)</label>
                <select class="form-control" name="category_id" id="category_id">
                  <option value="">Select Category</option>
                  @if($categories = App\Models\Category::all())
                    @foreach($categories as $category)
                      <option value="{{$category->id}}" {{ \Request::get("category_id") == $category->id ? 'selected' : '' }}>{{$category->category_name}}</option>
                    @endforeach
                  @endif
                </select>
              </div>
            </div>

            <!-- Second Row for Filter Options -->
            <div class="col-12 col-md-6 col-lg-3">
              <div class="form-group">
                <label>Filter By</label>
                <select class="form-control" name="filter_key" id="filter_key">
                  <option value="">Filter</option>
                  <option value="id" {{ \Request::get('filter_key') == "id" ? "selected" : '' }}>ID</option>
                  <option value="views" {{ \Request::get('filter_key') == "views" ? "selected" : '' }}>Views</option>
                  <option value="release_date" {{ \Request::get('filter_key') == "release_date" ? "selected" : '' }}>Release Date</option>
                  <option value="created_at" {{ \Request::get('filter_key') == "created_at" ? "selected" : '' }}>Created By</option>
                  <option value="updated_at" {{ \Request::get('filter_key') == "updated_at" ? "selected" : '' }}>Updated By</option>
                </select>
              </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
              <div class="form-group">
                <label>Order By</label>
                <select class="form-control" name="filter_value" id="filter_value">
                  <option value="">Order By</option>
                  <option value="ASC" {{ \Request::get('filter_value') == "ASC" ? "selected" : '' }}>Ascending</option>
                  <option value="DESC" {{ \Request::get('filter_value') == "DESC" ? "selected" : '' }}>Descending</option>
                </select>
              </div>
            </div>

            <div class="col-12 col-md-9 col-lg-4">
              <label>Search (Optional)</label>
              <input type="text" name="search" id="search" class="form-control" value="{{ \Request::get('search') }}">
            </div>

            <!-- Submit Button for Filters -->
            <div class="col-12 col-md-6 col-lg-2 pt-4 mt-1">
              <button class="btn btn-primary" type="submit">Apply Filters</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="">
        
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Total Products: {{App\Models\Product::count()}}, Mobiles Found: , Today: ({{isset($today) ? $today : 0}})</h5>
            <div class="table-responsive">
              <table id="zero_config" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Sr.#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Category</th>
                    <th>Release Date</th>
                    <th>Created At</th>
                    <th>Action</th>
                  </tr>
                </thead>
                @if(!$products->isEmpty())
                @foreach($products as $product)
                <tbody>
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td><img src="{{$product->thumbnail}}" class="img-fluid" style="max-height: 70px;"></td>
                    <td>{{Str::title($product->name)}}</td>
                    <td>{{isset($product->brand->name) ? $product->brand->name : 'N/A'}}</td>
                    <td>{{$product->category->category_name ?? 'N/A'}}</td>
                    <td>
                        @if($releaseDate = $product->getAttributeValue('release_date'))
                            {{ \Carbon\Carbon::parse($releaseDate)->format("d-M-Y") }}
                        @endif
                    </td>
                    <td>{{date("d-m-Y H:i:s", strtotime($product->created_at))}}</td>
                    <td><a href="{{route('dashboard.products.edit',$product->id)}}"><i class="far fa-edit text-success fa-2x"></i></a> <a href="{{route('product.show',$product->slug)}}" target="_blank"><i class="fas fa-eye fa-2x text-warning"></i></a> <a href="{{route('dashboard.products.price.create',$product->id)}}"><i class="fa-solid fa-money-bill-wave fa-2x"></i></a></td>
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
                    <th>Category</th>
                    <th>Date Added</th>
                    <th>Last Updated</th>
                    <th>Action</th>
                  </tr>
                </tfoot>
              </table>
              {{$products->withQueryString()->links()}}
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