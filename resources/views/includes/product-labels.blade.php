@php
$attribute = $product->category->attributes()->where("name","release_date")->first();
$release_date = optional($product->attributes()->where('attribute_id', $attribute->id)->first())->value;
@endphp
@if(\Carbon\Carbon::now()->lte($release_date))
  <div class="label-groups">
    <span class="product-label label-upcoming fs-10">Upcoming</span>
  </div>
@elseif(\Carbon\Carbon::now()->subDays(90)->lte($release_date))
  <div class="label-groups group-new">
    <span class="product-label label-upcoming fs-10">New</span>
  </div>
@endif
