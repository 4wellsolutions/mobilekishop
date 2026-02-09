@php
      // Check if the URL contains a country code
      $url = $compare->link;
      $countryCode = request()->segment(1); 
      $countries = App\Models\Country::pluck('country_code')->toArray();
      if (in_array($countryCode, $countries)) {
          $url = url("/$countryCode" . parse_url($compare->link, PHP_URL_PATH));
      } else {
          $url = url(parse_url($compare->link, PHP_URL_PATH));
      }
    @endphp
<div class="col-12 col-sm-6 col-md-4 col-lg-4">
  <div class="card border-0 shadow h-100 p-1">
    <a href="{{$url}}">
        <img class="img-fluid card-img compareImage" width="398" height="224" src="{{$compare->thumbnail}}" alt="{{$compare->alt}}">
    </a>
    

    <div class="card-body p-2" style="min-height: auto;">
        <a href="{{ $url }}" class="text-decoration-none text-dark">
            <h2 class="text-center fw-normal fs-6">
                {!! Str::title(Str::of($compare->product1 . " VS ". $compare->product2 . ($compare->product3 ? " <strong>Vs</strong> ". $compare->product3 : ""))->replace('-', ' ')) !!}
            </h2>
        </a>
    </div>

  </div>
</div>