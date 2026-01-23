@php
$nowDate = Carbon\Carbon::now();
$country = App\Country::where("country_code","pk")->first();

$price_in_pkr = $product->getFirstVariantPriceForCountry($product->id,$country->id);

$attributes = $product->attributes()->get()->keyBy('id');

$attributeMap = [
    'dimensions' => 22,
    'os' => 20,
    'ui' => 21,
    'resolution' => 38,
    'battery' => 68,
    'pixels' => 73,
    'screen_size' => 75,
    'ram_in_gb' => 76,
    'rom_in_gb' => 77,    
];

// Assign attribute values to variables dynamically
foreach ($attributeMap as $variableName => $attributeId) {
    $$variableName = optional($attributes->get($attributeId))->pivot->value ?? null;
}
$release_date    = \Carbon\Carbon::parse($product->release_date);

@endphp
<div id="contentDiv">
<div class="container my-2 mobileEmbed px-0">
<div class="row text-dark px-2" style=background:#cbe0f2>
<div class="col-12 col-md-12 d-flex justify-content-between">
<a href="{{route('product.show',$product->slug)}}" class=text-decoration-none>
<h3 class="fs-5 my-auto py-2 text-dark">{{Str::title($product->name)}}</h3>
</a>
<h4 class="fs-5 my-auto py-2 text-dark">Rs.{{$price_in_pkr}}</h4>
</div>
</div>
<div class=row>
<div class="col-5 col-sm-3 col-md-3 my-auto text-center thumbnailBlock px-0">
<a href="{{route('product.show',$product->slug)}}">
<img src="{{$product->thumbnail}}" class="img-fluid mobile_image my-2" width=120 height=160 alt="{{$product->slug}}">
</a>
</div>
<div class="col-7 col-sm-9 col-md-9 my-auto specificationBlock px-0">
<div class="d-none d-sm-block mt-2">
<p class="my-1 py-0"><img src="{{URL::to('/images/icons/calendar.png')}}" alt=calendar width=20 height=20> Released {{ $release_date->format('d-M-y') }}</p>
<p class="my-1 py-0"><img src="{{URL::to('/images/icons/screen_size.png')}}" alt=screen_size width=20 height=20> {{$dimensions}}</p>
<p class="my-1 py-0"><img src="{{URL::to('/images/icons/os.png')}}" alt=mobile-os width=20 height=20> {{$os}}</p>
<p class="my-1 py-0"><img src="{{URL::to('/images/icons/ram.png')}}" alt=storage-icon width=20 height=20> {{$ram_in_gb}} + {{$rom_in_gb}}</p>
</div>
<hr class="my-2 d-none d-sm-block">
<div class="row my-auto mobileViewOnly pt-2">
<div class="col-6 col-sm-3 col-md-3 text-center border-end border-bottom screenBlock specBlock">
<img src="{{URL::to('/images/icons/screen.png')}}" alt=screen width=25 height=25>
<p class="my-1 py-0 fw-bold fs-5">{{number_format($screen_size,1)}}"</p>
<p class="my-1 py-0 fw-light text-dark">{{$resolution}}</p>
</div>
<div class="col-6 col-sm-3 col-md-3 text-center border-sm-end border-bottom cameraBlock specBlock">
<img src="{{URL::to('/images/icons/camera.png')}}" alt=screen width=25 height=25>
<p class="my-1 py-0 fw-bold fs-5">{{$pixels}}MP</p>
<p class="my-1 py-0 fw-light text-dark">{{isset($pixels) ? "Camera" : ''}}</p>
</div>
<div class="col-6 col-sm-3 col-md-3 text-center border-end specBlock">
<img src="{{URL::to('/images/icons/ram.png')}}" alt=screen width=25 height=25>
<p class="my-1 py-0 fw-bold fs-5">{{$ram_in_gb}}GB</p>
<p class="my-1 py-0 fw-light text-dark">RAM</p>
</div>
<div class="col-6 col-sm-3 col-md-3 text-center specBlock">
<img src="{{URL::to('/images/icons/battery.png')}}" alt=screen width=25 height=25>
<p class="my-1 py-0 fw-bold fs-5">{{isset($battery) ? $battery : "N/A"}}</p>
<p class="my-1 py-0 fw-light text-dark">{{isset($battery) ? "mAh" : ''}}</p>
</div>
</div>
</div>
</div>
</div>
</div>