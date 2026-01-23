@php
$nowDate = Carbon\Carbon::now();
$price_in_pkr    = $product->price_in_pkr;
$price_in_dollar = $product->price_in_dollar;

$attributes = $product->attributes()->get()->keyBy('attribute_id');

$release_date    = optional($attributes->get(80))->value;
$dimensions      = optional($attributes->get(22))->value;
$os              = optional($attributes->get(20))->value;
$ui              = optional($attributes->get(21))->value;
$resolution      = optional($attributes->get(38))->value;
$battery         = optional($attributes->get(68))->value;
$pixels          = optional($attributes->get(73))->value;
$screen_size     = optional($attributes->get(75))->value;
$ram_in_gb       = optional($attributes->get(76))->value;
$rom_in_gb       = optional($attributes->get(77))->value;
$release_date    = \Carbon\Carbon::parse($release_date);

@endphp
<style type="text/css">
    a{
        text-decoration: none;
    }
    .container {
  width: 100%;
  padding-right: .75rem;
  padding-left: .75rem;
  margin-right: auto;
  margin-left: auto;
}

.my-2, .py-2 {
  margin-top: .5rem!important;
  margin-bottom: .5rem!important;
}

.row {
  display: flex;
  flex-wrap: wrap;
  margin-right: -.75rem;
  margin-left: -.75rem;
}

/* For column classes, the CSS would be generated dynamically, so this is a general representation */
.col-12, .col-5, .col-7, .col-sm-3, .col-md-3, .col-md-9 {
  position: relative;
  width: 100%;
  padding-right: .75rem;
  padding-left: .75rem;
}

.bg-dark {
  background-color: #212529!important;
}

.text-white, .text-light {
  color: #fff!important;
}

.rounded-0 {
  border-radius: 0!important;
}

.d-flex, .d-md-block {
  display: flex!important;
}

.px-2 {
  padding-right: .5rem!important;
  padding-left: .5rem!important;
}

.justify-content-between {
  justify-content: space-between!important;
}

.text-decoration-none {
  text-decoration: none!important;
}

.fs-5 {
  font-size: 1.25rem;
}

.my-auto {
  margin-top: auto!important;
  margin-bottom: auto!important;
}

.text-center {
  text-align: center!important;
}

.img-fluid {
  max-width: 100%;
  height: auto;
}

.d-none {
  display: none!important;
}
@media(max-width: 768px){
  .specBlock{
    width: 50% !important
  }
  .border-end {
    border-right-style: solid !important;
    border-right-width: 1px !important;
  }
}
@media (min-width: 769px) {
  .d-md-block {
    display: block!important;
  }
  .specBlock{
    width: 25% !important
  }
}

.mb-0 {
  margin-bottom: 0!important;
}

.fw-bold {
  font-weight: 700!important;
}

.fw-light, .text-dark {
  font-weight: 300!important;
  color: #212529!important;
}

.border-end {
  border-end-style: solid;
  border-end-width: 1px;
}
.mobileEmbed p{
  padding-bottom: 0px !important;
}
.mobileEmbed p img{
  margin-top: 0px !important;
  margin-bottom: 0px !important;
}
.specBlock img{
  margin-top: 0px !important;
  margin-bottom: 0px !important;
}
.thumbnailBlock{
  width: 35% !important;
}
.specificationBlock{
  width: 65% !important;
}
</style>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<div class="container my-2 mobileEmbed">
    <div class="row test">
        
        <div class="col-12 bg-dark text-white rounded-0 d-flex px-2 justify-content-between test">
            <a href="{{route('product.show',[$product->brand->slug,$product->slug])}}" class="text-decoration-none">
                <h3 class="fs-5 my-auto py-2 text-white">{{Str::title($product->name)}}</h3>
            </a>
            <h4 class="fs-5 my-auto py-2 text-white">Rs.{{number_format($product->price_in_pkr)}}/${{$product->price_in_dollar}}</h4>
            
        </div>
        <div class="row">
            <div class="col-5 col-sm-3 col-md-3 my-auto text-center thumbnailBlock px-0">
                <a href="{{route('product.show',[$product->brand->slug,$product->slug])}}">
                    <img src="{{$product->thumbnail}}" class="img-fluid mobile_image my-2" width="120" height="160" alt="{{$product->slug}}">
                </a>
            </div>
            <div class="col-7 col-sm-9 col-md-9 my-auto w-75 specificationBlock px-0">
                <div class="d-none d-md-block mt-2">
                    <p class="mb-0"><img src="{{URL::to('/images/icons/calendar.png')}}" alt="calendar" width="20" height="20"> Released {{ $release_date->format('d-M-y') }}</p>
                    <p class="mb-0"><img src="{{URL::to('/images/icons/screen_size.png')}}" alt="screen_size" width="20" height="20"> {{$dimensions}}</p>  
                    <p class="mb-0"><img src="{{URL::to('/images/icons/os.png')}}" alt="mobile-os" width="20" height="20">  {{$os}}</p>  
                    <p class="mb-0"><img src="{{URL::to('/images/icons/storage.png')}}" alt="storage-icon" width="20" height="20"> {{$rom_in_gb . "GB Storage"}}</p>
                </div>
                <hr class="my-2 d-none d-sm-block">
                <div class="row my-auto pt-2">
                    <div class="col-6 col-sm-3 col-md-3 text-center border-end screenBlock specBlock">
                        <img src="{{URL::to('/images/icons/screen.png')}}" alt="screen" width="25" height="25">
                        <p class="mb-0 fw-bold fs-5">{{number_format($screen_size,1)}}"</p>
                        <p class="mb-0 fw-light text-dark">{{$resolution}}</p>
                    </div>
                    <div class="col-6 col-sm-3 col-md-3 text-center border-end cameraBlock specBlock">
                        <img src="{{URL::to('/images/icons/camera.png')}}" alt="screen" width="25" height="25">
                        <p class="mb-0  fw-bold fs-5">{{$pixels}}MP</p>
                        <p class="mb-0 fw-light text-dark">{{isset($pixels) ? "Camera" : ''}}</p>
                    </div>
                    <div class="col-6 col-sm-3 col-md-3 text-center border-end specBlock">
                        <img src="{{URL::to('/images/icons/ram.png')}}" alt="screen" width="25" height="25">
                        <p class="mb-0 fw-bold fs-5">{{$ram_in_gb}}GB</p>
                        <p class="mb-0 fw-light text-dark">RAM</p>
                    </div>
                    <div class="col-6 col-sm-3 col-md-3 text-center specBlock">
                        <img src="{{URL::to('/images/icons/battery.png')}}" alt="screen" width="25" height="25">
                        <p class="mb-0 fw-bold fs-5">{{isset($battery) ? $battery : "N/A"}}</p>
                        <p class="mb-0 fw-light text-dark">{{isset($battery) ? "mAh" : ''}}</p>
                    </div>
                </div>  
           </div> 
        </div>
    </div>
</div><!-- End .container -->


