@extends("layouts.dashboard")
@section("title", "Edit Compare")
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
                  Edit Compare Mobiles
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row bg-white pt-5">
      <div class="col-12">
        <div class="container">
          @include("includes.info-bar")
          <form action="{{route('dashboard.compares.update',$compare->id)}}" method="post" class="row" enctype="multipart/form-data">
            @csrf
            <div class="col-12 col-md-12">
              <div class="form-group">
                <label>URL</label>
                <input type="text" name="url" class="form-control" id="url" value="{{$compare->link}}" readonly>
              </div>
            </div>

            <div class="col-12 col-md-12">
              <div class="form-group">
                <h4>Thumbnail</h4>
                <img src="{{$compare->thumbnail}}" class="img-fluid">
              </div>
            </div>

            <div class="col-12 col-md-12">
              <div class="form-group">
                <label>Image (Optional)</label>
                <input type="file" name="thumbnail" id="thumbnail" class="form-control">
              </div>
            </div>

            <div class="col-12 col-md-12 mt-4">
              <div class="form-group pt-1">
                <button class="btn btn-primary" type="submit">Update</button>
              </div>
            </div>
          </form>
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
    position: inherit !important;
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

        $(".searchInput").typeahead({
            hint: false,
            highlight: true,
            minLength: 1
        }, {
            source: engine.ttAdapter(),
            limit: 10+1,

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