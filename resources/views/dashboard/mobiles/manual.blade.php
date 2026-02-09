@extends("layouts.dashboard")

@section("content")
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
        <form  id="form_validation"  action="{{ route("dashboard.mobile.store") }}" method="post"  class="needs-validation" novalidate enctype="multipart/form-data">
            @csrf
            <div class="row">
            <div class="col-12 col-sm-6">
                <div class="form-group">
                    <label for="">Brand</label>
                    <select class="form-control" name="brand_id" id="brand_id" required>
                        <option value="">Select Brand</option>
                        @if($brands = App\Models\Brand::all())
                        @foreach($brands as $brand)
                        <option value="{{$brand->id}}">{{$brand->name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="form-group">
                    <label for="model">Model</label>
                    <input type="text" name="model" id="model" class="form-control" required>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="form-group">
                    <label for="">Price in PKR</label>
                    <input type="text" name="price_in_pkr" class="form-control" required>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="form-group">
                    <label for="">Price in Dollar</label>
                    <input type="text" name="price_in_dollar" class="form-control" required>
                </div>
            </div>
            @if(count($mobiles))
            @foreach($mobiles as $mobile)
            <div class="col-12 col-sm-6">
                <div class="form-group">
                    <label for="{{$mobile}}">{{Str::title($mobile)}}</label>
                    <input type="text" name="" class="form-control">
                </div>
            </div>
            @endforeach
            @endif

                <div class="form-group col-12 col-md-6">
                    <label for="release_date">Release Date</label>
                    <input type="date" name="release_date" class="form-control" required>
                </div>

                <div class="form-group col-12 col-md-6">
                    <label for="pixels">Camera Pixels</label>
                    <select name="pixels" id="pixels" class="form-control" required>
                        <option value="">Select Pixels</option>
                        @foreach(\App\MobilePixel::orderBy("pixels", "asc")->get() as $pixel)
                            <option value="{{ $pixel->pixels }}" {{(old('pixels') == $pixel->pixels) ? "selected" : ""}}>{{ $pixel->pixels . " px"}} </option>
                        @endforeach
                        <option value="N/A" {{(old('pixels') == $pixel->pixels) ? "selected" : ""}}>N/A</option>
                    </select>
                </div>

                <div class="form-group col-12 col-md-6">
                    <label for="">No of Cameras</label>
                    <select name="no_of_cameras" id="no_of_cameras" class="form-control" required>
                        <option value="">Select Number</option>
                        <option value="1" {{(old('no_of_cameras') == 1) ? "selected" : ""}}>1</option>
                        <option value="2" {{(old('no_of_cameras') == 2) ? "selected" : ""}}>2</option>
                        <option value="3" {{(old('no_of_cameras') == 3) ? "selected" : ""}}>3</option>
                        <option value="4" {{(old('no_of_cameras') == 4) ? "selected" : ""}}>4</option>
                        <option value="5" {{(old('no_of_cameras') == 5) ? "selected" : ""}}>5</option>
                        <option value="6" {{(old('no_of_cameras') == 6) ? "selected" : ""}}>6</option>
                        <option value="7" {{(old('no_of_cameras') == 7) ? "selected" : ""}}>7</option>
                        <option value="8" {{(old('no_of_cameras') == 8) ? "selected" : ""}}>8</option>
                        <option value="1" {{(old('no_of_cameras') == 'N/A') ? "selected" : ""}}>N/A</option>
                    </select>
                </div>

                <div class="form-group col-12 col-md-6">
                    <label for="">Screen Size</label>
                    <input type="text" value="{{old('screen_size') ? old('screen_size') : ""}}"  name="screen_size" class="form-control" autocomplete="off" required>
                    <small class="text-danger">Only numbers without inches</small>
                </div>

                <div class="form-group col-12 col-md-6">
                    <label for="">Ram in GB</label>
                    <select name="ram_in_gb" id="ram_in_gb" class="form-control" required>
                    <option value="">Select GB</option>
                    <option value="256">256 MB</option>
                    <option value="512">512 MB</option>
                    <option value="1">1 GB<option>
                    <option value="1.5">1.5 GB<option>
                    <option value="3">3 GB</option>
                        @for($i=2; $i <= 24; $i = $i + 2)
                            <option value="{{ $i }}" {{old('ram_in_gb') == $i ? 'selected' : ''}}>{{ $i . " GB"}} </option>
                        @endfor
                    </select>
                </div>

                <div class="form-group col-12 col-md-6">
                    <label for="">Rom in GB</label>
                    <select name="rom_in_gb" id="rom_in_gb" class="form-control" required>
                        <option value="">Select GB</option>
                        @for($i=4; $i <= 512; $i = $i * 2)
                            <option value="{{ $i }}" {{old('ram_in_gb') == $i ? 'selected' : ''}}>{{ $i . " GB"}} </option>
                        @endfor
                        <option value="1024" {{old('ram_in_gb') == 1024 ? 'selected' : ''}}>1024 GB</option>
                        <option value="2048" {{old('ram_in_gb') == 2048 ? 'selected' : ''}}>2048 GB</option>
                        <option value="3072" {{old('ram_in_gb') == 3072 ? 'selected' : ''}}>3072 GB</option>
                    </select>
                </div>

                <div class="form-group col-12 col-md-6">
                    <label for="">Operating System</label>
                    <select name="operating_system" class="form-control" id="operating_system" required>
                        <option value="">Select OS</option>
                        <option value="android">Android</option>
                        <option value="symbian">Symbian</option>
                        <option value="ios">IOS</option>
                        <option value="windows">Windows</option>
                        <option value="huawei">Huawei</option>
                    </select>
                </div>

                <div class="form-group col-12 col-md-6">
                    <label for="">No of SIM</label>
                    <select name="no_of_sims" id="no_of_sims" class="form-control" required>
                        <option value="">Select SIM</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="3">4</option>
                        <option value="N/A">N/A</option>
                    </select>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label for="">Body</label>
                        <textarea class="form-control" name="body" id="ckeditor" rows="5" required>{!! old('body') ? old('body') : ''!!} </textarea>
                    </div>
                </div>
                <div class="col-12 bg-white mb-0 pt-2">
                    <div class="form-group">
                        <label for="">Featured</label>
                        <input type="checkbox" name="is_featured" id="is_featured">
                    </div>
                </div>
                <div class="col-12 bg-white mb-0 pt-2">
                    <div class="form-group">
                        <label for="">New</label>
                        <input type="checkbox" name="is_new" id="is_new">
                    </div>
                </div>
                <div class="col-12 bg-white mb-0 pt-2">
                    <div class="form-group">
                        <label for="">Hot</label>
                        <input type="checkbox" name="is_hot" id="is_hot">
                    </div>
                </div>
                <div class="col-12 bg-white p-2">
                    <div class="form-group">
                        <label for="">Thumbnail</label>
                        <input type="file" name="thumbnail" id="thumbnail" required>
                    </div>
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

            <div class="col-12 bg-white py-2 my-2">
                <div class="form-group">
                   <button class="btn btn-success btn-block">Submit</button>
                </div>
            </div>
        </div>
    </form>
    </div>
  </div>
</br></br></br></br></br>
@stop

@section('scripts')
    <script src="{{ URL::to("admin/js/pages/forms/editors.js") }}"></script>
    <script type="text/javascript">
      $(".plusSign").click(function(){
        console.log("Asdf");
        $(".images").append($(".bigImages:first").clone().val(""));
      });
    </script>
@stop