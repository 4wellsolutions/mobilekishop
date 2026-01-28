@props(['product', 'country'])

@php
    $isPk = $country->country_code === 'pk';
    $productUrl = $isPk
        ? route('product.show', [$product->slug])
        : route('country.product.show', [$country->country_code, $product->slug]);

    $brandUrl = $isPk
        ? route('brand.show', [optional($product->brand)->slug ?? 'unknown', optional($product->category)->slug ?? 'unknown'])
        : route('country.brand.show', [$country->country_code, optional($product->brand)->slug ?? 'unknown', optional($product->category)->slug ?? 'unknown']);

    $releaseDate = $product->attributes()->where('attribute_id', 80)->first()?->pivot?->value;
    $now = \Carbon\Carbon::now();
    $isUpcoming = $releaseDate && $now->lte($releaseDate);
    $isNew = $releaseDate && !$isUpcoming && $now->subDays(90)->lte($releaseDate);

    $price = $product->getFirstVariantPriceForCountry($product, $country->id);
@endphp

<div class="p-2 text-center position-relative h-100 product-card-container">
    @if($isUpcoming)
        <div class="label-groups">
            <span class="product-label label-upcoming fs-10">Upcoming</span>
        </div>
    @elseif($isNew)
        <div class="label-groups group-new">
            <span class="product-label label-upcoming fs-10">New</span>
        </div>
    @endif

    <a href="{{ $productUrl }}">
        <img src="{{ URL::to('/images/thumbnail.png') }}" data-echo="{{ $product->thumbnail }}"
            alt="{{ $product->name }}" class="img-fluid mx-auto w-auto mobileImage" loading="lazy" />
    </a>

    <div class="product-details mt-2">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <div class="category text-start">
                <a href="{{ $brandUrl }}" class="text-dark fs-12 text-decoration-none opacity-75">
                    {{ $product->brand->name }}
                </a>
            </div>
            <div class="wishlist-container">
                @include('includes.wishlist-button', ['product' => $product])
            </div>
        </div>

        <a href="{{ $productUrl }}" class="text-dark text-decoration-none d-block mb-1">
            <h2 class="fs-16 fw-normal mb-0 line-clamp-2">
                {{ Str::title($product->name) }}
            </h2>
        </a>

        <div class="price-box">
            @if($price > 0)
                <span class="fw-bold text-danger">
                    {{ $country->currency }} {{ number_format($price) }}
                </span>
            @else
                <span class="text-muted fs-12">Coming Soon</span>
            @endif
        </div>
    </div>
</div>