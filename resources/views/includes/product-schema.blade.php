{{--
Product JSON-LD Structured Data
Usage: @include('includes.product-schema', ['product' => $product, 'price' => $price, 'country' => $country])
--}}
@php
    $schemaPrice = $price ?? 0;
    $schemaCurrency = $country->iso_currency ?? 'PKR';
    $schemaAvailability = $schemaPrice > 0 ? 'https://schema.org/InStock' : 'https://schema.org/PreOrder';
    $brandName = $product->brand ? $product->brand->name : 'Unknown';
    $reviewCount = $product->reviews_count ?? $product->activeReviews->count();
    $avgRating = $product->avg_rating ?? 0;
@endphp

<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ $product->name }}",
    "description": "{{ Str::limit(strip_tags($product->description ?? $product->name . ' specifications and price'), 200) }}",
    "image": "{{ $product->thumbnail }}",
    "brand": {
        "@type": "Brand",
        "name": "{{ $brandName }}"
    },
    "sku": "{{ $product->slug }}",
    "url": "{{ url()->current() }}",
    @if($product->release_date)
        "releaseDate": "{{ $product->release_date->format('Y-m-d') }}",
    @endif
    @if($schemaPrice > 0)
        "offers": {
            "@type": "Offer",
            "url": "{{ url()->current() }}",
            "priceCurrency": "{{ $schemaCurrency }}",
            "price": "{{ $schemaPrice }}",
            "availability": "{{ $schemaAvailability }}",
            "seller": {
                "@type": "Organization",
                "name": "MobileKiShop"
            }
        },
    @endif
    @if($reviewCount > 0 && $avgRating > 0)
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "{{ number_format($avgRating, 1) }}",
            "bestRating": "5",
            "worstRating": "1",
            "reviewCount": "{{ $reviewCount }}"
        },
    @endif
    "category": "{{ $product->category ? $product->category->name : 'Mobile Phones' }}"
}
</script>