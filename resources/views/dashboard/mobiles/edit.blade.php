@extends("layouts.dashboard")
@section('title')Edit Mobile - Dashboard @stop
@section("content")
@php
    $excludedKeys = ["id","thumbnail", "url", "created_at", "updated_at", "deleted_at", "images_count",
    "brand_id", "slug", "release_date", "body", "operating_system", "no_of_sims", "rom_in_gb", "ram_in_gb", "screen_size", "no_of_cameras", "pixels","battery"];
    $column12Keys = ["title", "description", "keywords" , "slug"];
@endphp
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Add What Mobile URL</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Library
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    
    <div class="container">
    @include("includes.info-bar")
        <form action="{{ route("dashboard.mobile.edit",$mobile->id) }}" method="get">
            <div class="row">
                <div class="col-sm-10">
                    <input type="url" name="url" value="{{ isset($_GET['url']) ? $_GET['url'] : "" }}" class="form-control" placeholder="Enter url" required>

                </div>
                <div class="col-sm-2">
                    <button class="btn btn-success">Re Fetch</button>
                </div>
            </div>

        </form>
        <hr>

        @php
            use Illuminate\Support\Facades\DB;
            $columns = DB::getSchemaBuilder()->getColumnListing("mobiles");
        @endphp
        
        <form id="form_validation" action="{{ route('dashboard.mobile.update', $mobile->id) }}" class="needs-validation"  enctype="multipart/form-data" novalidate method="post">
            @csrf
            @method("put")
             <div class="row">
                 <div class="form-group col-12 col-md-4">
                     <label for="">Brand</label>
                     <select name="brand_id" id="brand_id" class="form-control">

                         @foreach($brands as $b)
                            <option value="{{ $b->id }}" {{ $b->id == $mobile->brand_id ? "selected": "" }}>{{ $b->name }}</option>
                         @endforeach
                     </select>
                 </div>

                 @if((isset($_GET["url"])))
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label for="">Model</label>
                            <input type="text" name="model" value="{{ $name }}" class="form-control" required>
                        </div>
                    </div>
                     <div class="col-12 col-sm-4">
                         <div class="form-group">
                             <label for="">Price in PKR</label>
                             <input type="text" name="price_in_pkr" value="{{ $price_in_pkr }}" class="form-control" >
                         </div>
                     </div>
                     <div class="col-12 col-sm-4">
                         <div class="form-group">
                             <label for="">Price in Dollar</label>
                             <input type="text" name="price_in_dollar" value="{{ $price_in_dollar }}" class="form-control" >
                         </div>
                     </div>
                     @foreach($data as $d)
                         <div class="form-group {{ in_array($d[0], $column12Keys) ? "col-12" : "col-12 col-md-4"}}">
                             <div class="form-group">
                                 <label for="">{{ $d[0] }}</label>
                                 <input type="text" name="{{ \Illuminate\Support\Str::slug($d[0], "_") }}" value="{{ $d[1] }}" class="form-control">
                             </div>
                         </div>
                     @endforeach
                @else
                    @foreach($columns as $column)
                        @if(!in_array($column, $excludedKeys))
                            <div class="form-group {{ in_array($column, $column12Keys) ? "col-12" : "col-12 col-md-4"}}">
                                <label for="">{{ ucwords(Str::slug($column, "-")) }}</label>
                                <input type="text" name="{{ $column }}" value="{{ $mobile[$column] }}" class="form-control">
                            </div>
                        @endif
                    @endforeach
                 @endif

                 <div class="form-group col-12 col-md-4">
                     <label for="">Release Date</label>
                     <input type="date" name="release_date" value="{{ (date("Y-m-d", strtotime($mobile->release_date))) }}" class="form-control" required>
                 </div>

                 <div class="form-group col-12 col-md-4">
                     <label for="pixels">Camera Pixels</label>
                     <select name="pixels" id="pixels" class="form-control" required>
                         <option value="">Select Pixels</option>
                         @foreach(\App\MobilePixel::orderBy("pixels", "asc")->get() as $pixel)
                             <option value="{{ $pixel->pixels }}" {{ $mobile->pixels == $pixel->pixels ? "selected" : "" }}>{{ $pixel->pixels . " px"}} </option>
                         @endforeach
                     </select>
                 </div>

                 <div class="form-group col-12 col-md-4">
                     <label for="">No of Cameras</label>
                     <select name="no_of_cameras" id="no_of_cameras" class="form-control" required>
                         <option value="">Select Number</option>
                         <option value="1" {{ $mobile->no_of_cameras == 1 ? "selected" : "" }}>1</option>
                         <option value="2" {{ $mobile->no_of_cameras == 2 ? "selected" : "" }}>2</option>
                         <option value="3" {{ $mobile->no_of_cameras == 3 ? "selected" : "" }}>3</option>
                         <option value="4" {{ $mobile->no_of_cameras == 4 ? "selected" : "" }}>4</option>
                         <option value="5" {{ $mobile->no_of_cameras == 5 ? "selected" : "" }}>5</option>
                         <option value="6" {{ $mobile->no_of_cameras == 6 ? "selected" : "" }}>6</option>
                         <option value="7" {{ $mobile->no_of_cameras == 7 ? "selected" : "" }}>7</option>
                         <option value="8" {{ $mobile->no_of_cameras == 8 ? "selected" : "" }}>8</option>
                     </select>
                 </div>

                 <div class="form-group col-12 col-md-4">
                     <label for="">Screen Size</label>
                     <input type="text" value="{{ $mobile->screen_size }}" autocomplete="off" name="screen_size" class="form-control" required>
                     <small class="text-danger">Only numbers without inches</small>
                 </div>

                 <div class="form-group col-12 col-md-4">
                     <label for="">Battery (Capacity: {{$mobile->capacity}})</label>
                     <input type="text" value="{{ $mobile->battery }}" autocomplete="off" name="battery" class="form-control" required>
                     <small class="text-danger">Only numbers without inches</small>
                 </div>

                 <div class="form-group col-12 col-md-4">
                     <label for="">Ram in GB</label>
                     <select name="ram_in_gb" id="ram_in_gb" class="form-control" required>
                         <option value="">Select GB</option>
                         <option value="256" {{ $mobile->ram_in_gb == '256' ? "selected" : "" }}>256 MB</option>
                         <option value="512" {{ $mobile->ram_in_gb == '512' ? "selected" : "" }}>512 MB</option>
                         <option value="1" {{ $mobile->ram_in_gb == '1' ? "selected" : "" }}>1 GB</option>
                         <option value="1.5" {{ $mobile->ram_in_gb == '1.5' ? "selected" : "" }}>1.5 GB</option>
                         <option value="3" {{ $mobile->ram_in_gb == '3' ? "selected" : "" }}>3 GB</option>
                         @for($i=2; $i <= 24; $i = $i + 2)
                             <option value="{{ $i }}" {{ $mobile->ram_in_gb == $i ? "selected" : "" }}>{{ $i . " GB"}} </option>
                         @endfor
                     </select>
                 </div>

                 <div class="form-group col-12 col-md-4">
                     <label for="">Rom in GB</label>
                     <select name="rom_in_gb" id="rom_in_gb" class="form-control" required>
                         <option value="">Select GB</option>
                         @for($i=4; $i <= 512; $i = $i * 2)
                             <option value="{{ $i }}" {{ $mobile->rom_in_gb == $i ? "selected" : "" }}>{{ $i . " GB"}} </option>
                         @endfor
                        <option value="1024" {{$mobile->rom_in_gb == 1024 ? 'selected' : ''}}>1024 GB</option>
                        <option value="2048" {{$mobile->rom_in_gb == 2048 ? 'selected' : ''}}>2048 GB</option>
                        <option value="3072" {{$mobile->rom_in_gb == 3072 ? 'selected' : ''}}>3072 GB</option>
                     </select>
                 </div>

                 <div class="form-group col-12 col-md-4">
                     <label for="">Operating System</label>
                     <select name="operating_system" class="form-control" id="operating_system" required>
                         <option value="">Select OS</option>
                         <option value="android" {{ $mobile->operating_system == "android" ? "selected" : "" }}>Android</option>
                         <option value="symbian" {{ $mobile->operating_system == "symbian" ? "selected" : "" }}>Symbian</option>
                         <option value="ios" {{ $mobile->operating_system == "ios" ? "selected" : "" }}>IOS</option>
                         <option value="windows" {{ $mobile->operating_system == "windows" ? "selected" : "" }}>Windows</option>
                         <option value="blackberry" {{ $mobile->operating_system == "blackberry" ? "selected" : "" }}>Blackberry</option>
                         <option value="huawei" {{ $mobile->operating_system == "huawei" ? "selected" : "" }}>Huawei</option>
                     </select>
                 </div>

                 <div class="form-group col-12 col-md-4">
                     <label for="">No of SIM</label>
                     <select name="no_of_sims" id="no_of_sims" class="form-control" required>
                         <option value="">Select SIM</option>
                         <option value="1" {{ $mobile->no_of_sims == 1 ? "selected" : "" }}>1</option>
                         <option value="2" {{ $mobile->no_of_sims == 2 ? "selected" : "" }}>2</option>
                         <option value="3" {{ $mobile->no_of_sims == 3 ? "selected" : "" }}>3</option>
                         <option value="3" {{ $mobile->no_of_sims == 4 ? "selected" : "" }}>4</option>
                     </select>
                 </div>
                
                 <div class="col-12">
                    <div class="form-group ">
                        <label for="">Body</label>
                        <textarea class="form-control" name="body" id="editor" rows="5">{{$mobile->body}}
                        </textarea>
                    </div>
                </div>
            <div class="col-12 bg-white py-2">
                <h4>Thumbnail</h4>
                <img src="{{$mobile->thumbnail}}" class="img-fluid" style="max-height: 100px;">
            </div>
            <div class="col-12 bg-white p-2">
                    <div class="form-group">
                        <label for="">Thumbnail</label>
                        <input type="file" name="thumbnail" id="thumbnail" required>
                        <input type="hidden" name="image_exist" value="{{count($mobile->images)}}">
                    </div>
            </div>
            <div class="row col-12 bg-white p-2 mx-0">
                @if(!$mobile->images->isEmpty())
                @foreach($mobile->images as $img)
                <div class="col-6 col-md-4 col-lg-3 text-center">
                    <h4 class="mt-1">Image {{$loop->iteration}}</h4>
                    <img src="{{URL::to('/mobiles/')}}/{{$img->name}}" class="img-fluid border" style="max-height: 200px;" /> 
                    <p class="p-2 text-center"><a href="#" data-url="{{route('dashboard.mobile.delete.file',[$mobile->id,$img->name])}}" class="btn btn-danger deleteButton">Delete</a></p>
                </div>
                @endforeach
                @endif
            </div>            

            
                
            <div class="images bg-white py-4">
              <h3>Other Images</h3>
              <a  class="btn btn-primary plusSign m-0 py-1 px-2 pt-2"><i class="fas fa-plus-square fa-2x "></i></a>
              <div>
                <div class="col-12 p-2 bigImages">
                  <div class="form-group">
                      <label for="">Image</label>
                      <input type="file" name="image[]" id="image" required>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 bg-white py-4">
                <div class="form-group">
                    <button class="btn btn-success btn-block">Update</button>
                </div>
            </div>
        </div>
        </form>
            </div>
  </div>
</br></br></br></br></br>
@stop

@section('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script>
    <script type="text/javascript">
      $(".plusSign").click(function(){
        console.log("Asdf");
        $(".images").append($(".bigImages:first").clone().val(""));
      });

    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .then( editor => {
                console.log( editor );
        } )
        .catch( error => {
                console.error( error );
        } );

    $('.deleteButton').click(function() {
        if (confirm('Are you sure?')) {
          var url = $(this).data('url');
          $('.deleteButton').attr("href",url);
        }
    });

    </script>
@stop

@section('styles')
<style type="text/css">
    .ck-editor__editable_inline {
        min-height: 200px;
    }
</style>
@stop