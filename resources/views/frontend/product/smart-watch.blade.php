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
    $category_name = $product->category->category_name;

    $attributes = $product->Attributes()->get()->keyBy(function ($item) {
        return strtolower(str_replace([' ', '(', ')'], ['_', '', ''], $item->label));
    });

    // Specification variables
    $model = $attributes->get('model')->pivot->value ?? null;
    $connectivity = $attributes->get('connectivity')->pivot->value ?? null;
    $camera = $attributes->get('camera')->pivot->value ?? null;
    $memory = $attributes->get('memory')->pivot->value ?? null;
    $display = $attributes->get('display')->pivot->value ?? null;
    $processor = $attributes->get('processor')->pivot->value ?? null;
    $os = $attributes->get('os')->pivot->value ?? null;
    $ui = $attributes->get('ui')->pivot->value ?? null;
    $dimensions = $attributes->get('dimensions')->pivot->value ?? null;
    $weight = $attributes->get('weight')->pivot->value ?? null;
    $sim = $attributes->get('sim')->pivot->value ?? null;
    $colors = $attributes->get('colors')->pivot->value ?? null;
    $g2_band = $attributes->get('2g_band')->pivot->value ?? null;
    $g3_band = $attributes->get('3g_band')->pivot->value ?? null;
    $g4_band = $attributes->get('4g_band')->pivot->value ?? null;
    $g5_band = $attributes->get('5g_band')->pivot->value ?? null;
    $cpu = $attributes->get('cpu')->pivot->value ?? null;
    $chipset = $attributes->get('chipset')->pivot->value ?? null;
    $gpu = $attributes->get('gpu')->pivot->value ?? null;
    $technology = $attributes->get('technology')->pivot->value ?? null;
    $size = $attributes->get('size')->pivot->value ?? null;
    $resolution = $attributes->get('resolution')->pivot->value ?? $attributes->get('screen_resolution')->pivot->value ?? null;
    $protection = $attributes->get('protection')->pivot->value ?? null;
    $extra_features = $attributes->get('extra_features')->pivot->value ?? null;
    $built_in = $attributes->get('built_in')->pivot->value ?? null;
    $buildd = $attributes->get('build')->pivot->value ?? null;
    $card = $attributes->get('card')->pivot->value ?? null;
    $main = $attributes->get('main')->pivot->value ?? null;
    $sms = $attributes->get('sms')->pivot->value ?? null;
    $features = $attributes->get('features')->pivot->value ?? null;
    $front = $attributes->get('front')->pivot->value ?? null;
    $wlan = $attributes->get('wlan')->pivot->value ?? null;
    $bluetooth = $attributes->get('bluetooth')->pivot->value ?? null;
    $gps = $attributes->get('gps')->pivot->value ?? null;
    $radio = $attributes->get('radio')->pivot->value ?? null;
    $usb = $attributes->get('usb')->pivot->value ?? null;
    $nfc = $attributes->get('nfc')->pivot->value ?? null;
    $infrared = $attributes->get('infrared')->pivot->value ?? null;
    $data = $attributes->get('data')->pivot->value ?? null;
    $sensors = $attributes->get('sensors')->pivot->value ?? null;
    $audio = $attributes->get('audio')->pivot->value ?? null;
    $browser = $attributes->get('browser')->pivot->value ?? null;
    $messaging = $attributes->get('messaging')->pivot->value ?? null;
    $games = $attributes->get('games')->pivot->value ?? null;
    $torch = $attributes->get('torch')->pivot->value ?? null;
    $extra = $attributes->get('extra')->pivot->value ?? null;
    $capacity = $attributes->get('capacity')->pivot->value ?? null;
    $body = $attributes->get('body')->pivot->value ?? null;
    $battery = $attributes->get('battery')->pivot->value ?? null;
    $pixels = $attributes->get('pixels')->pivot->value ?? null;
    $screen_size = $attributes->get('screen_size')->pivot->value ?? null;
    $ram_in_gb = $attributes->get('ram_in_gb')->pivot->value ?? null;
    $rom_in_gb = $attributes->get('rom_in_gb')->pivot->value ?? null;
    $reviewer = $attributes->get('reviewer')->pivot->value ?? null;
    $rating = $attributes->get('rating')->pivot->value ?? null;
    $review_count = $attributes->get('review_count')->pivot->value ?? null;


    $models = null;
    $sar = null;
    $sar_eu = null;
    $battery_new = null;
    $battery_old = null;
    $loudspeaker = null;
    $geekbench = null;
    $antutu = null;
    $release_date = \Carbon\Carbon::parse($product->release_date)->format("M-Y");
@endphp

@section("style") @stop

@section("script")
<script type="application/ld+json">
    {
          "@@context": "https://schema.org",
          "@type": "FAQPage",
          "mainEntity": 
          [
            {
              "@type": "Question",
              "name": "When Did the {{Str::title($product->name)}} Come Out?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "{{Str::title($product->name)}} was released on {{date('d-M-Y', strtotime($release_date))}}."
              }
            },{
              "@type": "Question",
              "name": "What is the Price of {{Str::title($product->name)}}?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "This phone comes with plenty of options at price range of {{$country->currency}} {{$price_in_pkr}}."
              }
            },{
              "@type": "Question",
              "name": "Is {{Str::title($product->name)}} Have 5G Network Connectivity?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "{{Str::title($product->name)}} comes with {{isset($g4_band) ? '4G LTE' : ''}} {{isset($g5_band) ? 'and 5G' : ''}} high speed network to enjoy fast internet anywhere in Pakistan."
              }
            },{
              "@type": "Question",
              "name": "How much RAM and Storage does the {{Str::title($product->name)}} have?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "{{Str::title($product->name)}} has {{$ram_in_gb}}GB RAM and {{$rom_in_gb}}GB storage."
              }
            },{
              "@type": "Question",
              "name": "What Processor Is in {{Str::title($product->name)}}?",
              "acceptedAnswer": {
                "@type": "Answer",
                "text": "{{Str::title($product->name)}} is powered by Android 11 OS partner with CPU {{trim($cpu)}} and {{$chipset}} Chipset."
              }
            }
          ]
        }
</script>

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
                        "color": "{{ explode(',', $colors)[0] }}",
                        "material": "N/A",
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
                            "price": "{{ Str::replace(',', '', $price_in_pkr) }}",
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
                "item": "{{ url('/' . ($country->country_code === 'pk' ? '' : $country->country_code)) }}"
            },
            {
                "@type": "ListItem",
                "position": 2,
                "name": "{{ $product->category->category_name }}",
                "item": "{{ url(($country->country_code === 'pk' ? '' : $country->country_code) . '/category/' . $product->category->slug) }}"
            },
            {
                "@type": "ListItem",
                "position": 3,
                "name": "{{ $product->brand->name }}",
                "item": "{{ url(($country->country_code === 'pk' ? '' : $country->country_code) . '/brand/' . $product->brand->slug . '/' . $product->category->slug) }}"
            },
            {
                "@type": "ListItem",
                "position": 4,
                "name": "{{ Str::title($product->name) }}",
                "item": "{{ url(($country->country_code === 'pk' ? '' : $country->country_code) . '/product/' . $product->slug) }}"
            }
        ]
    }
</script>
@stop