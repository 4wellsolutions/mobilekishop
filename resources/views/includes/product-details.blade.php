<div class="card border-0">

  <!-- Product Image -->
  <div class="row">
    <div class="col-12 text-center px-2 py-1">
      <a
        href="{{ route(($country->country_code == 'pk' ? '' : 'country.') . 'product.show', ($country->country_code == 'pk' ? $product->slug : ['country_code' => $country->country_code, 'product' => $product->slug])) }}">
        <img loading="eager" src="{{$product->thumbnail}}" alt="{{$product->slug}}" class="img-fluid mobileImage"
          width="155" height="205" />
      </a>
    </div>
  </div>

  <!-- Product Details -->
  <div class="row">
    <div class="col-12 px-2">
      <!-- Category and Wishlist -->
      <div class="row">
        <div class="col-6">
          <div class="category">
            <a href="{{ route(($country->country_code == 'pk' ? '' : 'country.') . 'brand.show', ($country->country_code == 'pk' ? [$product->brand->slug, $product->category->slug] : ['country_code' => $country->country_code, 'brand' => $product->brand->slug, 'categorySlug' => $product->category->slug])) }}"
              class="text-dark fs-12">{{$product->brand->name}}</a>
          </div>
        </div>
        <div class="col-6 text-end">
          @include('includes.wishlist-button', ['product' => $product])
        </div>
      </div>

      <!-- Product Name -->
      <div class="row">
        <div class="col-12 text-center">
          <a
            href="{{ route(($country->country_code == 'pk' ? '' : 'country.') . 'product.show', ($country->country_code == 'pk' ? $product->slug : ['country_code' => $country->country_code, 'product' => $product->slug])) }}">
            <h2 class="fw-normal fs-6 text-dark mb-1">
              {{Str::title($product->name)}}
            </h2>
          </a>
        </div>
      </div>

      <div class="row">
        <div class="col-12 text-center">
          <span class="text-danger">{{$country->currency}}
            {{ $product->getFirstVariantPriceForCountry($product, $country->id) }}</span>
        </div>
      </div>
    </div>
  </div>
</div>