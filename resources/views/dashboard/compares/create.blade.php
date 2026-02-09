@extends("layouts.dashboard")
@section("title", "Create Compare")
@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Compare Mobiles</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Compare Mobiles
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row bg-white pt-5">
      <div class="col-12">
        <div class="mx-3">
          @include("includes.info-bar")
          @if(\Request::has('product1'))
          @php            
            if(App\Models\Compare::whereProduct1(\Request::get('product1'))->whereProduct2(\Request::get('product2'))->whereProduct3(null)->first()){
                echo "<div class='alert alert-danger'>Compare Already exist!</div>";
            }elseif(App\Models\Compare::whereProduct1(\Request::get('product1'))->whereProduct2(\Request::get('product2'))->whereProduct3(\Request::get('product3'))->first()){
                echo "<div class='alert alert-danger'>Compare Already exist!</div>";
            }
          @endphp
          @endif
          <a href="{{route('dashboard.compares.create')}}" class="btn btn-primary my-3">Add New Compare</a>
          <form action="#" method="get" class="row">
            <input type="hidden" name="search1" value="{{\Request::has('search1') ? \Request::get('search1') : ''}}" id="input-search1">
            <input type="hidden" name="search2" value="{{\Request::has('search2') ? \Request::get('search2') : ''}}" id="input-search2">
            <input type="hidden" name="search3" value="{{\Request::has('search3') ? \Request::get('search3') : null}}" id="input-search3">
            <div class="col-12 col-md-3">
              <div class="form-group">
                <label>Compare 1</label>
                <input type="text" id="search1" value="{{\Request::has('search1') ? \Request::get('search1') : ''}}" class="form-control searchInput" required>
              </div>
            </div>

            <div class="col-12 col-md-3">
              <div class="form-group">
                <label>Compare 2</label>
                <input type="text"  value="{{\Request::has('search2') ? \Request::get('search2') : ''}}" id="search2" class="form-control searchInput" required>
              </div>
            </div>

            <div class="col-12 col-md-3">
              <div class="form-group">
                <label>Compare 3 (Optional)</label>
                <input type="text"  value="{{\Request::has('search3') ? \Request::get('search3') : ''}}" id="search3" class="form-control searchInput">
              </div>
            </div>

            <div class="col-12 col-md-3 mt-4">
              <div class="form-group pt-1">
                <button class="btn btn-primary" type="submit">Generate URL</button>
              </div>
            </div>
          </form>
      </div>
      </div>
    </div>
    @if(\Request::has('search1') && \Request::has('search2'))

    <div class="row bg-white pt-5">
      <div class="col-12">
        <div class="container">
          <form action="{{route('dashboard.compares.store')}}" method="post" class="row" enctype="multipart/form-data">
            <input type="hidden" name="thumbnail" id="thumbnail" value="{{$image}}">
            <input type="hidden" name="product1" value="{{\Request::get('search1')}}">
            <input type="hidden" name="product2" value="{{\Request::get('search2')}}">
            <input type="hidden" name="product3" value="{{\Request::get('search3')}}">
            @csrf
            @php
              $base_url = URL::to('/compare')."/";
              $url = \Request::get('search1')."-vs-".\Request::get('search2');
              if(\Request::get('search3')){
              $url = $url. "-vs-". \Request::get('search3');
              }
            $final_url = $base_url . $url;
            @endphp
            <input type="hidden" name="alt" value="{{Str::slug($url)}}">
            <input type="hidden" name="name" value="{{Str::slug($url)}}.jpg">
            <div class="col-12 col-md-12">

              <div class="form-group">
                <label>Compare URL</label>
                <input type="text" name="url" value="{{$final_url}}" class="form-control searchInput" readonly required>
              </div>
            </div>
            <div class="col-12 col-md-12">
              <div class="form-group">
                <h4>Thumbnail</h4>
                <img src="{{$image}}" class="img-fluid border">
              </div>
            </div>
            <div class="col-12 col-md-12">
              <div class="form-group">
                <label>Custom Thumbnail (Optional) (640*360)</label>
                <input type="file" id="thumbnail1" name="thumbnail1" class="form-control">
              </div>
            </div>

            <div class="col-12 col-md-3 mt-4">
              <div class="form-group pt-1">
                <button class="btn btn-primary" type="submit">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>    
    @endif


    @if(!$compares->isEmpty())
        <div class="row bg-white pt-5">
          <div class="col-12">
            <div class="mx-3">
              <h3>Last 10 Links</h3>
              <div class="table-responsive">
                <table class="table">
                  <tr>
                    <th>Sr.#</th>
                    <th>Link</th>
                    <th>Image</th>
                    <th>Action</th>
                  </tr>
                  @foreach($compares as $compare)
                  <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$compare->link}}</td>
                    <td><img src="{{$compare->thumbnail}}" height="80" class="img-fluid" style="max-height: 80px;"></td>
                    <td><a class="btn btn-info btn-sm" href="{{route('dashboard.compares.edit',$compare->id)}}">Edit</a></td>
                  </tr>
                  @endforeach
                </table>
              </div>
            </div>
          </div>
        </div> 
      
    @endif

  </div>
@stop

@section('styles')
<style type="text/css">
  .twitter-typeahead{
    width: 100% !important;
  }
  .tt-menu{
    width: inherit !important;
    position: inherit !important;
  }
</style>
@stop

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/0.11.1/typeahead.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
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
        
        $(".searchInput").typeahead({
            hint: false,
            highlight: true,
            minLength: 1
        }, {
            name: 'mobiles',
            source: engine.ttAdapter(),
            limit: 9999,

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
                    
                     return '<div class="row bg-white border-bottom"><div class="col-4 col-md-3"><img src="'+data.thumbnail+'" class="img-fluid searchImage my-1"></div><div class="col-8 col-md-9 text-dark text-uppercase" style="font-weight:600; ">'+data.name+'</div></div>'
          }
            }
        });

        $('.searchInput').bind('typeahead:select', function(ev, suggestion) {
            console.log('Selection: ' + $(this).attr("id"));
            $("#input-"+$(this).attr("id")).val(suggestion.slug);
            if($("#searchInput").val() != ""){
                param1 = $("#searchInput").val();
            }
        });
    </script>

@stop