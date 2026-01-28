<div class="row products-section pt-0 pb-1 mt-3" id="related_mobiles">
    <p class="ml-2 text-dark fw-bold related_mobiles">Related Mobile</p>
    @if(!$products->isEmpty())
        @foreach($products as $prod)
            <div class="col-6 col-sm-4 col-md-4 col-lg-3">
                <x-product-card :product="$prod" :country="$country" />
            </div>
        @endforeach
    @endif
</div>