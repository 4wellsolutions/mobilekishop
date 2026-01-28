@php
    $layout = ($country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

@section('title', Str::title($product->name) . ": Price, Specs & Deals in {$country->country_name} | MobileKiShop")

@section('description', "Discover the powerful {$product->name} at MobileKiShop. Compare specs, explore features, read user reviews, and find the best price in {$country->country_name}. Shop smart today!")

@section("keywords", "Mobiles prices, mobile specification, mobile phone features")

@section("canonical", ($country->country_code == 'pk') ? route('product.show', [$product->slug]) : route('country.product.show', [$country->country_code, $product->slug]))

@section("content")

@php
    $nowDate = Carbon\Carbon::now();
    $price_in_pkr = $product->getFirstVariantPriceForCountry($product->id, $country->id);

    $attributes = $product->attributes()->get()->keyBy(function ($item) {
        return strtolower(str_replace([' ', '(', ')'], ['_', '', ''], $item->label));
    });

    // Specification variables
    $watts = $attributes->get('watts')->pivot->value ?? null;
    $compatible_mobile_models = $attributes->get('compatible_phone_models')->pivot->value ?? null;
    $compatible_devices = $attributes->get('compatible_devices')->pivot->value ?? null;
    $mounting_type = $attributes->get('mounting_type')->pivot->value ?? null;
    $input_voltage = $attributes->get('input_voltage')->pivot->value ?? null;
    $color = $attributes->get('color')->pivot->value ?? null;
    $special_feature = $attributes->get('special_feature')->pivot->value ?? null;
    $included_components = $attributes->get('included_components')->pivot->value ?? null;
    $connector_type = $attributes->get('connector_type')->pivot->value ?? null;
    $connectivity_technology = $attributes->get('connectivity_technology')->pivot->value ?? null;
    $review_count = $attributes->get('review_count')->pivot->value ?? null;
    $reviewer = $attributes->get('reviewer')->pivot->value ?? null;
    $rating = $attributes->get('rating')->pivot->value ?? null;

    $release_date = \Carbon\Carbon::parse($product->release_date)->format("M-Y");

@endphp

@section("style") @stop

@section("script")

@if($product->reviews->isEmpty())
    <script type="application/ld+json">
                    {
                        "@@context": "https://schema.org/",
                        "@type": "Product",
                        "name": "{{Str::title($product->name)}}",
                        "image": [
                        "{{$product->thumbnail}}"
                        ],
                        "description": "Get all the specifications, features, reviews, comparison, and price of {{Str::title($product->name)}} on the mks.com.pk in {$country->country_name}.",
                        "sku": "{{$product->id}}",
                        "mpn": "{{$product->id}}",
                        "color": "{{ isset($colors) && !empty(explode(',', $colors)[0]) ? explode(',', $colors)[0] : 'default_color' }}",
                        "material": "Plastic",
                        "pattern": "N/A",
                        "brand": {
                            "@type": "Brand",
                            "name": "{{$product->brand->name}}"
                        },
                        "review": {
                            "@type": "Review",
                            "reviewRating": {
                                "@type": "Rating",
                                "ratingValue": "{{$rating}}",
                                "bestRating": "5"
                            },
                            "author": {
                                "@type": "Person",
                                "name": "{{$reviewer}}"
                            }
                        },
                        "aggregateRating": {
                            "@type": "AggregateRating",
                            "ratingValue": "{{$rating}}",
                            "reviewCount": "{{$review_count}}"
                        },
                        "offers": {
                            "@type": "Offer",
                            "url": "#",
                            "priceCurrency": "{{$country->iso_currency}}",
                            "price": "{{ $price_in_pkr }}",
                            "priceValidUntil": "{{Carbon\Carbon::now()->addMonths(6)->format('d-m-Y')}}",
                            "itemCondition": "https://schema.org/NewCondition",
                            "availability": "https://schema.org/InStock",
                            "seller": {
                                "@type": "Organization",
                                "name": "MKS"
                            },
                            "hasMerchantReturnPolicy": {
                              "@type": "MerchantReturnPolicy",
                              "applicableCountry": "CH",
                              "returnPolicyCategory": "https://schema.org/MerchantReturnFiniteReturnWindow",
                              "merchantReturnDays": 60,
                              "returnMethod": "https://schema.org/ReturnByMail",
                              "returnFees": "https://schema.org/FreeReturn"
                            },
                            "shippingDetails": {
                              "@type": "OfferShippingDetails",
                              "shippingRate": {
                                "@type": "MonetaryAmount",
                                "value": "0",
                                "currency": "{{$country->iso_currency}}"
                              },
                              "shippingDestination": 
                                {
                                  "@type": "DefinedRegion",
                                  "addressCountry": "{{$country->country_code}}"
                                },
                                "deliveryTime": {
                                    "@type": "ShippingDeliveryTime",
                                    "handlingTime": {
                                      "@type": "QuantitativeValue",
                                      "minValue": 0,
                                      "maxValue": 1,
                                      "unitCode": "DAY"
                                    },
                                    "transitTime": {
                                      "@type": "QuantitativeValue",
                                      "minValue": 1,
                                      "maxValue": 5,
                                      "unitCode": "DAY"
                                    }
                                }
                            }
                        }
                    }
                </script>
@else
    <script type="application/ld+json">
                    {
                        "@@context": "https://schema.org/",
                        "@type": "Product",
                        "name": "{{Str::title($product->name)}}",
                        "image": [
                        "{{$product->thumbnail}}"
                        ],
                        "description": "Get all the specifications, features, reviews, comparison, and price of {{Str::title($product->name)}} on the Mobilekishop in {{$country->country_name}}.",
                        "sku": "{{$product->id}}",
                        "mpn": "{{$product->id}}",
                        "color": "{{str_replace(',', '', $colors)}}",
                        "material": "N/A",
                        "pattern": "N/A",
                        "brand": {
                            "@type": "Brand",
                            "name": "{{$product->brand->name}}"
                        },
                        "review": [
                            @foreach($product->reviews as $review)
                                {
                                    "@type": "Review",
                                    "reviewRating": {
                                        "@type": "Rating",
                                        "ratingValue": "{{ $review->stars }}",
                                        "bestRating": "5"
                                    },
                                    "author": {
                                        "@type": "Person",
                                        "name": "{{ $review->name }}"
                                    }
                                }@if(!$loop->last),@endif
                            @endforeach
                        ],
                        "aggregateRating": {
                            "@type": "AggregateRating",
                            "ratingValue": "{{ round($product->reviews()->avg('stars'), 1) }}",
                            "reviewCount": "{{ $product->reviews->count() }}"
                        },
                        "offers": {
                            "@type": "Offer",
                            "url": "#",
                            "priceCurrency": "{{$country->iso_currency}}",
                            "price": "{{$price_in_pkr}}",
                            "priceValidUntil": "{{Carbon\Carbon::now()->addMonths(6)->format('d-m-Y')}}",
                            "itemCondition": "https://schema.org/NewCondition",
                            "availability": "https://schema.org/InStock",
                            "seller": {
                                "@type": "Organization",
                                "name": "MKS"
                            },
                            "hasMerchantReturnPolicy": {
                              "@type": "MerchantReturnPolicy",
                              "applicableCountry": "{{$country->country_code}}",
                              "returnPolicyCategory": "https://schema.org/MerchantReturnFiniteReturnWindow",
                              "merchantReturnDays": 60,
                              "returnMethod": "https://schema.org/ReturnByMail",
                              "returnFees": "https://schema.org/FreeReturn"
                            },
                            "shippingDetails": {
                              "@type": "OfferShippingDetails",
                              "shippingRate": {
                                "@type": "MonetaryAmount",
                                "value": "0",
                                "currency": "{{$country->iso_currency}}"
                              },
                              "shippingDestination": 
                                {
                                  "@type": "DefinedRegion",
                                  "addressCountry": "{{$country->country_code}}"
                                },
                                "deliveryTime": {
                                    "@type": "ShippingDeliveryTime",
                                    "handlingTime": {
                                      "@type": "QuantitativeValue",
                                      "minValue": 0,
                                      "maxValue": 1,
                                      "unitCode": "DAY"
                                    },
                                    "transitTime": {
                                      "@type": "QuantitativeValue",
                                      "minValue": 1,
                                      "maxValue": 5,
                                      "unitCode": "DAY"
                                    }
                                }
                            }
                        }
                    }
                </script>
@endif
<script type="application/ld+json">
    {
        "@@context": "https://schema.org/",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "{{ URL::to('/') }}"
            },
            {
                "@type": "ListItem",
                "position": 2,
                "name": "{{ $product->category->category_name }}",
                "item": "{{ url('/category/' . $product->category->slug) }}"
            },
            {
                "@type": "ListItem",
                "position": 3,
                "name": "{{ $product->brand->name }}",
                "item": "{{ url('/brand/' . $product->brand->slug . '/' . $product->category->slug) }}"
            },
            {
                "@type": "ListItem",
                "position": 4,
                "name": "{{ Str::title($product->name) }}",
                "item": "{{ url('/product/' . $product->slug) }}"
            }
        ]
    }
</script>
@stop