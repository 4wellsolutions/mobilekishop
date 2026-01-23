@if(Auth::User())
  <a href="#" class="btn-icon-wish fs-14">
    <img src="{{(Auth::User()->wishlists()->where('product_id', $product->id)->whereType(1)->first()) ? URL::to('/images/icons/heart-fill.png') : URL::to('/images/icons/heart.png') }}" alt="heart" width="16" height="16">
    <i class="fa-heart" data-id="{{$product->id}}" data-type="0"></i>
  </a>
@else
  <a href="#" class="btn-icon-wish fs-14">
    <img src="{{URL::to('/images/icons/heart.png')}}" data-id="{{$product->id}}" data-type="0" alt="heart" width="16" height="16">
  </a>
@endif
