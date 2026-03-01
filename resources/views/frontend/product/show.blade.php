@extends('layouts.frontend')

@section('title', Str::title($product->name) . ": Price, Specs & Deals in {$country->country_name} | MobileKiShop")

@section('description', "Discover the powerful {$product->name} at MobileKiShop. Compare specs, explore features, read user reviews, and find the best price in {$country->country_name}. Shop smart today!")

@section("keywords", "Mobiles prices, mobile specification, mobile phone features")

@section("canonical", ($country->country_code == 'pk') ? route('product.show', [$product->slug]) : route('country.product.show', [$country->country_code, $product->slug]))


@section('content')
    @php
        $nowDate = Carbon\Carbon::now();
        $price_in_pkr = $product->getFirstVariantPriceForCountry($product->id, $country->id);

        // Attribute Extraction Helper
        $getAttribute = function ($key) use ($product) {
            $attr = $product->attributes->first(function ($item) use ($key) {
                $slug = strtolower(str_replace([' ', '(', ')'], ['_', '', ''], $item->label));
                return $slug === $key;
            });
            return $attr ? $attr->pivot->value : null;
        };

        // Product Attributes
        $screen_size = $getAttribute('size') ?? $getAttribute('screen_size');
        $resolution = $getAttribute('resolution') ?? $getAttribute('screen_resolution');
        $chipset = $getAttribute('chipset');
        $cpu = $getAttribute('cpu');
        $gpu = $getAttribute('gpu');
        $main_camera = $getAttribute('main');
        $front_camera = $getAttribute('front');
        $battery = $getAttribute('battery') ?? $getAttribute('battery_new') ?? $getAttribute('capacity');
        $ram = $getAttribute('ram_in_gb');
        $rom = $getAttribute('rom_in_gb');
        $display_type = $getAttribute('technology');
        $os = $getAttribute('os');
        $video = $getAttribute('video');
        $charging = $getAttribute('extra') ?? $getAttribute('charging');

        // SEO / Schema Attributes
        $g4_band = $getAttribute('4g_band');
        $g5_band = $getAttribute('5g_band');
        $colors = $getAttribute('colors');
        $reviewer = $getAttribute('reviewer');
        $rating = $getAttribute('rating');
        $review_count = $getAttribute('review_count');

        // Mock Stores Data for UI demo
        $stores = [
            [
                'name' => 'MobileKiShop',
                'price' => $price_in_pkr,
                'shipping' => 'Free Shipping',
                'stock' => 'In Stock',
                'link' => '#',
                'logo_bg' => 'bg-white',
                'logo_text' => 'text-primary',
                'icon' => 'storefront'
            ],
            // Add some dummy stores for visual comparison if price > 0
            [
                'name' => 'Amazon',
                'price' => $price_in_pkr * 1.05,
                'shipping' => 'Calculated at checkout',
                'stock' => 'In Stock',
                'link' => ($country->amazon_url && $country->amazon_tag) ? $country->amazon_url . 's?k=' . urlencode($product->name) . '&tag=' . $country->amazon_tag : '#',
                'target' => ($country->amazon_url && $country->amazon_tag) ? '_blank' : '_self',
                'logo_bg' => 'bg-white',
                'logo_text' => 'text-black',
                'icon' => 'shopping_bag'
            ],
            [
                'name' => 'Best Buy',
                'price' => $price_in_pkr * 1.02,
                'shipping' => 'Store Pickup',
                'stock' => 'Low Stock',
                'link' => '#',
                'logo_bg' => 'bg-blue-600',
                'logo_text' => 'text-white',
                'icon' => 'local_shipping'
            ]
        ];

        $release_date = \Carbon\Carbon::parse($product->release_date);
    @endphp

    @section('script')
        <script type="application/ld+json">
                    {
                          "@@context": "https://schema.org",
                          "@type": "FAQPage",
                          "mainEntity": 
                          [
                            {
                              "@type": "Question",
                              "name": "What is the Release Date of {{Str::title($product->name)}}?",
                              "acceptedAnswer": {
                                "@type": "Answer",
                                "text": "{{Str::title($product->name)}} was released on {{$release_date->format('M-Y')}}."
                              }
                            },{
                              "@type": "Question",
                              "name": "What is the Price of {{Str::title($product->name)}} in {$country->country_name}?",
                              "acceptedAnswer": {
                                "@type": "Answer",
                                "text": "This phone comes with plenty of options at price range of Rs.{{$price_in_pkr}}."
                              }
                            },{
                              "@type": "Question",
                              "name": "Is {{Str::title($product->name)}} Have 5G Network Connectivity?",
                              "acceptedAnswer": {
                                "@type": "Answer",
                                "text": "{{Str::title($product->name)}} comes with {{isset($g4_band) ? '4G LTE' : ''}} {{isset($g5_band) ? 'and 5G' : ''}} high speed network to enjoy fast internet anywhere in {$country->country_name}."
                              }
                            },{
                              "@type": "Question",
                              "name": "How Much Ram and Storage does a {{Str::title($product->name)}} have?",
                              "acceptedAnswer": {
                                "@type": "Answer",
                                "text": "{{Str::title($product->name)}} has {{$ram}}GB RAM and {{$rom}}GB storage."
                              }
                            },{
                              "@type": "Question",
                              "name": "What Processor Is in {{Str::title($product->name)}}?",
                              "acceptedAnswer": {
                                "@type": "Answer",
                                "text": "{{Str::title($product->name)}} is powered by Android 11 OS partner with CPU {{trim($cpu)}} and {{$chipset}} Chipset."
                                }
                              },{
                              "@type": "Question",
                              "name": "What is the Screen Size of {{Str::title($product->name)}}?",
                              "acceptedAnswer": {
                                "@type": "Answer",
                                "text": "The {{Str::title($product->name)}} has a screen size of approximately {{$screen_size}} inches and a resolution of {{$resolution}} pixels."
                                }
                              },{
                              "@type": "Question",
                              "name": "What is the Camera of {{Str::title($product->name)}}?",
                              "acceptedAnswer": {
                                "@type": "Answer",
                                "text": "The {{Str::title($product->name)}} features {{$main_camera}} main and {{$front_camera}} selfie cameras"
                                }
                              },{
                              "@type": "Question",
                              "name": "What is the Battery of {{Str::title($product->name)}}?",
                              "acceptedAnswer": {
                                "@type": "Answer",
                                "text": "The {{Str::title($product->name)}} is equipped with a battery capacity of around {{$battery}} mAh."
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
    @endsection

    <div class="w-full max-w-[1280px] px-4 lg:px-10 py-8 mx-auto">
        <!-- Breadcrumbs -->
        <div class="flex flex-wrap items-center gap-2 mb-8 text-sm">
            <a href="{{ url('/') }}" class="text-primary font-medium hover:underline">Home</a>
            <span class="text-text-muted">/</span>
            <a href="{{ route(($country->country_code == 'pk' ? '' : 'country.') . 'category.show', ($country->country_code == 'pk' ? $product->category->slug : ['country_code' => $country->country_code, 'category' => $product->category->slug])) }}"
                class="text-text-muted hover:text-primary font-medium hover:underline">{{ $product->category->category_name }}</a>
            <span class="text-text-muted">/</span>
            <span class="text-text-main font-semibold">{{ $product->name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
            <!-- Left Column: Gallery -->
            <div class="lg:col-span-5 flex flex-col gap-4">
                <div
                    class="bg-white rounded-2xl p-8 flex items-center justify-center aspect-[4/3] relative overflow-hidden group border border-border-light shadow-soft">
                    <img id="mainImage" src="{{ $product->thumbnail }}" alt="{{ $product->name }}"
                        class="object-contain h-full w-auto drop-shadow-xl transition-transform duration-500 group-hover:scale-105 z-10">
                    <div
                        class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-blue-50/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                </div>

                @if($product->images->isNotEmpty())
                    <div class="flex gap-3 overflow-x-auto no-scrollbar pb-2">
                        <div onclick="document.getElementById('mainImage').src='{{ $product->thumbnail }}'"
                            class="w-20 h-20 rounded-lg bg-white border-2 border-primary p-2 flex-shrink-0 cursor-pointer shadow-sm hover:border-primary transition-colors">
                            <img src="{{ $product->thumbnail }}" alt="Main" class="w-full h-full object-contain">
                        </div>
                        @foreach($product->images->take(5) as $image)
                            <div onclick="document.getElementById('mainImage').src='{{ $image->path ?? $image->url }}'"
                                class="w-20 h-20 rounded-lg bg-white border border-border-light hover:border-text-muted p-2 flex-shrink-0 cursor-pointer transition-colors shadow-sm">
                                <img src="{{ $image->path ?? $image->url }}" alt="Gallery" class="w-full h-full object-contain">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right Column: Details -->
            <div class="lg:col-span-7 flex flex-col justify-between">
                <div>
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-8">
                        <div>
                            <h1 class="text-4xl md:text-5xl font-bold text-text-main mb-3 tracking-tight">
                                {{ $product->name }}
                            </h1>
                            <div class="flex items-center gap-4 text-sm text-text-muted">
                                <span class="flex items-center gap-1"><span
                                        class="material-symbols-outlined text-[16px]">calendar_today</span> Released
                                    {{ $release_date->format('Y, M d') }}</span>
                            </div>
                        </div>
                        <!-- Score Badge -->
                        <div class="flex flex-col items-end gap-2">
                            <div
                                class="flex items-center gap-3 bg-white px-4 py-2 rounded-xl border border-border-light shadow-sm">
                                <span class="text-3xl font-bold text-primary">N/A</span>
                                <div class="flex flex-col leading-none">
                                    <span
                                        class="text-[10px] text-text-muted uppercase font-bold tracking-wider">TechSpecs</span>
                                    <span class="text-xs font-bold text-text-main">Score</span>
                                </div>
                            </div>
                            <!-- Star Rating -->
                            @if($product->reviews->isNotEmpty())
                                <div class="flex items-center gap-1 text-yellow-500 text-sm mt-1">
                                    <span
                                        class="font-bold text-text-main mr-1">{{ round($product->reviews->avg('stars'), 1) }}</span>
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="material-symbols-outlined text-[18px] fill-current">star</span>
                                    @endfor
                                    <span class="text-text-muted ml-1">({{ $product->reviews->count() }} Reviews)</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Key Specs Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                        <div
                            class="bg-white border border-border-light rounded-xl p-5 flex flex-col gap-3 hover:border-primary/50 hover:shadow-md transition-all group shadow-sm">
                            <span
                                class="material-symbols-outlined text-primary text-3xl group-hover:scale-110 transition-transform">smartphone</span>
                            <div>
                                <p class="text-xs text-text-muted font-medium uppercase tracking-wide">Display</p>
                                <p class="text-text-main font-bold text-lg leading-tight">{{ $screen_size ?? 'N/A' }}</p>
                                <p class="text-xs text-text-muted mt-0.5 line-clamp-1">{{ $display_type ?? '-' }}</p>
                            </div>
                        </div>
                        <div
                            class="bg-white border border-border-light rounded-xl p-5 flex flex-col gap-3 hover:border-primary/50 hover:shadow-md transition-all group shadow-sm">
                            <span
                                class="material-symbols-outlined text-primary text-3xl group-hover:scale-110 transition-transform">memory</span>
                            <div>
                                <p class="text-xs text-text-muted font-medium uppercase tracking-wide">Chipset</p>
                                <p class="text-text-main font-bold text-lg leading-tight line-clamp-1"
                                    title="{{ $chipset }}">{{ $chipset ?? 'N/A' }}</p>
                                <p class="text-xs text-text-muted mt-0.5 line-clamp-1">{{ $ram ? $ram . ' RAM' : '-' }}</p>
                            </div>
                        </div>
                        <div
                            class="bg-white border border-border-light rounded-xl p-5 flex flex-col gap-3 hover:border-primary/50 hover:shadow-md transition-all group shadow-sm">
                            <span
                                class="material-symbols-outlined text-primary text-3xl group-hover:scale-110 transition-transform">photo_camera</span>
                            <div>
                                <p class="text-xs text-text-muted font-medium uppercase tracking-wide">Camera</p>
                                <p class="text-text-main font-bold text-lg leading-tight line-clamp-1"
                                    title="{{ $main_camera }}">{{ $main_camera ?? 'N/A' }}</p>
                                <p class="text-xs text-text-muted mt-0.5 line-clamp-1">Video Support</p>
                            </div>
                        </div>
                        <div
                            class="bg-white border border-border-light rounded-xl p-5 flex flex-col gap-3 hover:border-primary/50 hover:shadow-md transition-all group shadow-sm">
                            <span
                                class="material-symbols-outlined text-primary text-3xl group-hover:scale-110 transition-transform">battery_charging_full</span>
                            <div>
                                <p class="text-xs text-text-muted font-medium uppercase tracking-wide">Battery</p>
                                <p class="text-text-main font-bold text-lg leading-tight">{{ $battery ?? 'N/A' }}</p>
                                <p class="text-xs text-text-muted mt-0.5">Li-Ion</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price Info -->
                <div
                    class="bg-white border border-border-light rounded-xl p-5 md:p-6 flex flex-col md:flex-row items-center justify-between gap-4 shadow-soft">
                    <div class="flex flex-col">
                        <span class="text-text-muted text-sm font-medium">Starting Price</span>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-text-main">{{ $country->currency }}
                                {{ $price_in_pkr ? number_format($price_in_pkr) : 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="flex w-full md:w-auto gap-3">
                        <a href="#stores"
                            class="flex-1 md:flex-none h-12 px-6 bg-primary hover:bg-primary-hover text-white rounded-lg font-bold flex items-center justify-center gap-2 transition-colors shadow-md shadow-blue-500/20 decoration-0">
                            <span class="material-symbols-outlined text-[20px]">shopping_cart</span>
                            Check Prices
                        </a>
                        <button
                            class="flex-1 md:flex-none h-12 px-6 bg-white border border-gray-300 hover:border-primary hover:text-primary text-text-main rounded-lg font-bold flex items-center justify-center gap-2 transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-[20px]">favorite_border</span>
                            Save
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Navigation Tabs -->
        <div
            class="sticky top-[65px] z-40 bg-white/95 backdrop-blur-sm border-b border-border-light mb-8 -mx-4 px-4 lg:-mx-10 lg:px-10 shadow-sm">
            <div class="flex gap-8 overflow-x-auto no-scrollbar justify-center">
                <a href="#specs" class="py-4 text-primary border-b-2 border-primary font-bold text-sm tracking-wide">FULL
                    SPECS</a>
                <a href="#stores"
                    class="py-4 text-text-muted hover:text-primary border-b-2 border-transparent hover:border-gray-200 font-medium text-sm tracking-wide transition-colors">STORES
                    & PRICE</a>
                <a href="#reviews"
                    class="py-4 text-text-muted hover:text-primary border-b-2 border-transparent hover:border-gray-200 font-medium text-sm tracking-wide transition-colors">USER
                    REVIEWS</a>
                <a href="#compare"
                    class="py-4 text-text-muted hover:text-primary border-b-2 border-transparent hover:border-gray-200 font-medium text-sm tracking-wide transition-colors">COMPARE</a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Specs & Details -->
            <div class="lg:col-span-8 flex flex-col gap-6" id="specs">

                <!-- Full Specs Loop -->
                @php
                    $groupedAttributes = $product->attributes->groupBy('group');
                @endphp

                @foreach($groupedAttributes as $groupName => $attributes)
                    @if($groupName && $attributes->isNotEmpty())
                        <div class="bg-white border border-border-light rounded-xl overflow-hidden shadow-card">
                            <div class="bg-gray-50 border-b border-border-light px-6 py-4 flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary">settings</span>
                                <h3 class="text-lg font-bold text-text-main uppercase tracking-wide">{{ $groupName }}</h3>
                            </div>
                            <div class="p-0">
                                @foreach($attributes as $attr)
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-[160px_1fr] border-b border-border-light last:border-0 hover:bg-gray-50/50 transition-colors">
                                        <div class="px-6 py-3 text-sm text-text-muted font-medium md:border-r border-border-light">
                                            {{ $attr->label }}
                                        </div>
                                        <div class="px-6 py-3 text-sm text-text-main">{{ $attr->pivot->value }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach

                <!-- Fallback to basic attributes if groups not present (or as redundancy) -->
                @if($groupedAttributes->isEmpty() && $product->attributes->isNotEmpty())
                    <div class="bg-white border border-border-light rounded-xl overflow-hidden shadow-card">
                        <div class="bg-gray-50 border-b border-border-light px-6 py-4 flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">list</span>
                            <h3 class="text-lg font-bold text-text-main uppercase tracking-wide">General Specifications</h3>
                        </div>
                        <div class="p-0">
                            @foreach($product->attributes as $attr)
                                <div
                                    class="grid grid-cols-1 md:grid-cols-[160px_1fr] border-b border-border-light last:border-0 hover:bg-gray-50/50 transition-colors">
                                    <div class="px-6 py-3 text-sm text-text-muted font-medium md:border-r border-border-light">
                                        {{ $attr->label }}
                                    </div>
                                    <div class="px-6 py-3 text-sm text-text-main">{{ $attr->pivot->value }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <!-- Sidebar / Additional Info -->
            <div class="lg:col-span-4">
                <div class="flex flex-col gap-8 lg:sticky lg:top-[120px]">
                    <!-- Stores Section (Mock) -->
                    <div class="bg-white border border-border-light rounded-xl p-6 shadow-card" id="stores">
                        <h3 class="text-lg font-bold text-text-main mb-4">Current Deals</h3>
                        <div class="space-y-3">
                            @foreach($stores as $store)
                                @if($store['price'] > 0)
                                    <a href="{{ $store['link'] }}" target="{{ $store['target'] ?? '_self' }}"
                                        class="flex items-center justify-between p-3 rounded-lg bg-page-bg border border-border-light hover:border-primary hover:shadow-sm transition-all group decoration-0">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full {{ $store['logo_bg'] }} border border-gray-100 flex items-center justify-center p-1 shadow-sm">
                                                <span
                                                    class="material-symbols-outlined {{ $store['logo_text'] }} text-[22px]">{{ $store['icon'] }}</span>
                                            </div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-sm font-bold text-text-main group-hover:text-primary transition-colors">{{ $store['name'] }}</span>
                                                <span class="text-xs text-text-muted">{{ $store['shipping'] }}</span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <span class="text-sm font-bold text-text-main">{{ $country->currency }}
                                                {{ number_format($store['price']) }}</span>
                                            <span
                                                class="text-xs bg-green-100 text-green-700 font-bold px-1.5 py-0.5 rounded border border-green-200">{{ $store['stock'] }}</span>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                        <button
                            class="w-full mt-5 py-2.5 text-sm text-primary border border-primary/30 rounded-lg hover:bg-primary hover:text-white transition-all font-bold shadow-sm">
                            View all prices
                        </button>
                        @if($country && !empty($country->amazon_url) && !empty($country->amazon_tag))
                            <p class="text-xs text-slate-400 mt-2 px-1">
                                Disclosure: As an Amazon Associate, I earn from qualifying purchases.
                            </p>
                        @endif
                    </div>

                    {{-- Expert Rating Card --}}
                    @if($product->expertRating)
                        @php $er = $product->expertRating; @endphp
                        <div class="bg-white border border-border-light rounded-xl overflow-hidden shadow-card"
                            id="expert-rating">
                            <div
                                class="bg-gradient-to-r from-primary to-indigo-600 px-6 py-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-white text-2xl">stars</span>
                                    <h3 class="text-lg font-bold text-white">Expert Rating</h3>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="text-3xl font-black text-white leading-none">{{ $er->overall }}</span>
                                    <span class="text-xs text-white/80 font-medium">/ 10</span>
                                </div>
                            </div>
                            <div class="p-6 space-y-4">
                                @php
                                    $criteria = [
                                        ['key' => 'design', 'label' => 'Design & Build', 'icon' => 'palette'],
                                        ['key' => 'display', 'label' => 'Display', 'icon' => 'smartphone'],
                                        ['key' => 'performance', 'label' => 'Performance', 'icon' => 'memory'],
                                        ['key' => 'camera', 'label' => 'Camera', 'icon' => 'photo_camera'],
                                        ['key' => 'battery', 'label' => 'Battery', 'icon' => 'battery_full'],
                                        ['key' => 'value_for_money', 'label' => 'Value for Money', 'icon' => 'sell'],
                                    ];
                                @endphp

                                @foreach($criteria as $c)
                                    @php
                                        $score = $er->{$c['key']};
                                        $pct = $score * 10;
                                        $color = $score >= 8 ? 'bg-emerald-500' : ($score >= 6 ? 'bg-blue-500' : ($score >= 4 ? 'bg-yellow-500' : 'bg-red-500'));
                                    @endphp
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="flex items-center gap-2 text-sm text-text-muted">
                                                <span class="material-symbols-outlined text-[16px]">{{ $c['icon'] }}</span>
                                                {{ $c['label'] }}
                                            </div>
                                            <span class="text-sm font-bold text-text-main">{{ $score }}</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2">
                                            <div class="{{ $color }} h-2 rounded-full transition-all duration-500"
                                                style="width: {{ $pct }}%"></div>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Verdict --}}
                                @if($er->verdict)
                                    <div class="mt-4 pt-4 border-t border-border-light">
                                        <p class="text-sm font-bold text-text-main mb-1">Verdict</p>
                                        <p class="text-sm text-text-muted leading-relaxed">{{ $er->verdict }}</p>
                                    </div>
                                @endif

                                {{-- Rated By --}}
                                @if($er->rated_by)
                                    <div class="flex items-center gap-2 text-xs text-text-muted mt-2">
                                        <span class="material-symbols-outlined text-[14px]">person</span>
                                        Rated by {{ $er->rated_by }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Description -->
                    <div class="bg-white border border-border-light rounded-xl p-6 shadow-card">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-text-main">Expert Review</h3>
                        </div>
                        <div class="prose prose-sm max-w-none text-text-muted">
                            {!! $product->body !!}
                        </div>
                    </div>

                    <!-- Related Devices -->
                    <div class="bg-white border border-border-light rounded-xl p-6 shadow-card" id="compare">
                        <h3 class="text-lg font-bold text-text-main mb-4">Compare With</h3>
                        <div class="space-y-4">
                            @foreach($products as $related)
                                <a href="{{ route('product.show', $related->slug) }}"
                                    class="flex items-center gap-3 group cursor-pointer p-2 -mx-2 rounded-lg hover:bg-gray-50 transition-colors decoration-0">
                                    <img src="{{ $related->thumbnail }}" alt="{{ $related->name }}"
                                        class="w-12 h-16 object-contain bg-white border border-gray-100 rounded-md p-1 shadow-sm">
                                    <div>
                                        <h4 class="text-sm font-bold text-text-main group-hover:text-primary transition-colors">
                                            {{ $related->name }}
                                        </h4>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                </div>{{-- end sticky wrapper --}}
            </div>
        </div>

        <!-- User Opinions Section -->
        <div class="mt-12" id="reviews">
            <div class="bg-white border border-border-light rounded-xl p-6 shadow-card">
                <h3 class="text-lg font-bold text-text-main mb-6">User Ratings & Reviews</h3>
                @if($product->reviews->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-text-muted mb-4">No reviews yet. Be the first to review this product!</p>
                        <button onclick="openReviewModal()"
                            class="px-6 py-2 bg-primary text-white rounded-lg font-bold shadow-sm hover:bg-primary-hover transition-colors">Write
                            a Review</button>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($product->reviews as $review)
                            <div class="border-b border-border-light pb-6 last:border-0">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-primary font-bold">
                                            {{ substr($review->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-text-main">{{ $review->name }}</p>
                                            <div class="flex text-yellow-500 text-xs">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span
                                                        class="material-symbols-outlined text-[14px] fill-current text-{{ $i <= $review->stars ? 'yellow-500' : 'gray-300' }}">star</span>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-xs text-text-muted">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-text-muted">{{ $review->review }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        @include('includes.review-modal')

    </div>
@endsection

@section('structured_data')
    @include('includes.product-schema', ['product' => $product, 'price' => $price_in_pkr, 'country' => $country])
    @php
        $isPk = ($country->country_code ?? 'pk') == 'pk';
        $prefix = $isPk ? '' : 'country.';
        $params = $isPk ? [] : ['country_code' => $country->country_code];
        $categoryUrl = $product->category ? route($prefix . 'category.show', array_merge($params, ['category' => $product->category->slug])) : url('/');
    @endphp
    @include('includes.breadcrumb-schema', [
        'breadcrumbs' => [
            ['name' => 'Home', 'url' => url('/')],
            ['name' => $product->category ? $product->category->name : 'Products', 'url' => $categoryUrl],
            ['name' => $product->name, 'url' => url()->current()]
        ]
    ])
    {{-- Sticky Mobile Bottom Bar --}}

                   <div class="fixed bottom-0 left-0 right-0 z-50 lg:hidden bg-white border-t border-border-lig
                   ht shadow-[0_-4px_20px_rgba(0,0,0,0.1)] px-4 py-3" id="mobileBottomBar">
        <div class="flex items-center justify-between gap-3 max-w-[600px] mx-auto">
            <div class="flex flex-col min-w-0">
                <span class="text-xs text-text-muted font-medium">Best Price</span>
                <span class="text-xl font-bold text-text-main truncate">{{ $country->currency }} {{ $price_in_pkr ? number_format($price_in_pkr) : 'N/A' }}</span>
        </div>
        <a href="#stores"
            class="flex-shrink-0 flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-primary to-blue-600 hover:from-primary-hover hover:to-blue-700 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-500/25 transition-all active:scale-95 decoration-0">
            <span class="material-symbols-outlined text-[18px]">shopping_cart</span>
                    Check Prices
                </a>
                </div>
            </div>
            {{-- Bottom padding to prevent content being hidden behind sticky bar --}}
            <div class="h-20 lg:hidden"></div>
@endsection