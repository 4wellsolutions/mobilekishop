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

    // Assign attribute values to variables dynamically
    foreach ($attributeMap as $variableName => $attributeId) {
        $$variableName = optional($attributes->get($attributeId))->pivot->value ?? null;
    }
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

<style type="text/css">
    .offcanvas-backdrop {
        background: #FFF !important;
    }

    .mobileTable {
        font-size: 15px;
    }

    .widget a {
        text-decoration: none !important;
        color: #777;
    }

    .widget-title a {
        text-decoration: none !important;
        font-family: Poppins, sans-serif;
        color: #343a40;
        font-size: 18px;
    }

    .widget {
        border-bottom: 1px solid #e7e7e7;
        border-bottom-width: 1px;
        border-bottom-style: solid;
        border-bottom-color: rgb(231, 231, 231);
        border: 1px solid #dee2e6 !important;
        margin-top: 5px;
        margin-right: 5px;
        margin-left: 5px;
    }

    .widget-body li a {
        font-size: 14px;
    }

    .nav-tabs.nav-item a {
        text-decoration: none !important;
        color: #343a40;
    }

    .nav-tabs.nav-tabs .nav-link {
        color: #31343a;
        border: none;
    }

    .nav-tabs .nav-item.show .nav-link,
    .nav-tabs .nav-link.active {
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
        color: black !important;
        border-bottom: 2px solid #000000 !important;
    }

    .nav-tabs.nav-link:hover {
        border-bottom: 2px solid #000000 !important;
    }

    h1,
    .h1,
    h2,
    .h2,
    h3,
    .h3,
    h4,
    .h4,
    h5,
    .h5,
    h6,
    .h6 {
        font-weight: 700;
        line-height: 1.1;
        font-family: Poppins, sans-serif;
    }

    body {
        font-family: "Open Sans", sans-serif;
    }

    @media(max-width: 576px) {
        .mobileTable {
            font-size: 14px;
        }

        .cameraBlock {
            border: none !important;
            border-bottom: 1px solid #dee2e6 !important;
        }

        .screenBlock {
            border-bottom: 1px solid #dee2e6 !important;
        }

        .mobileTable tr td:first-child {
            display: none;
        }

        .mobile_content a {
            color: black;
        }

        .mobile_content h2 {
            font-size: 18px !important;
        }

        .mobileTable tr th {
            color: #dc3545 !important
        }

        .table {
            font-size: .8rem;
        }

        .imgDiv {
            height: 120px;
        }

        .detailDiv {
            height: 120px;
        }

        .product-title {
            font-size: 14px;
            font-weight: normal;
        }

        .product-price {
            font-size: 16px;
        }
    }

    @media(min-width: 577px) {
        .imgDiv {
            height: 130px;
        }

        .detailDiv {
            height: 130px;
        }

        .product-title {
            font-size: 18px;
            font-weight: normal;
        }

        .product-price {
            font-size: 20px;
        }
    }

    .nav-tabs .nav-link {
        font-size: .9rem;
        padding-right: 7px;
        padding-left: 7px;
    }

    .mobile_image {
        max-height: 160px;
        width: auto;
    }

    .stars {
        cursor: pointer;
    }
</style>
<main class="main container-lg">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="my-1" style="font-size: 12px;">
            <ol class="breadcrumb mb-1">
                <!-- Home -->
                <li class="breadcrumb-item">
                    <a href="{{ URL::to('/') }}" class="text-decoration-none text-secondary">
                        Home
                    </a>
                </li>
                <!-- Category -->
                <li class="breadcrumb-item">
                    <a href="{{ url('/category/' . $product->category->slug) }}"
                        class="text-decoration-none text-secondary">
                        {{ $product->category->category_name }}
                    </a>
                </li>
                <!-- Brand -->
                <li class="breadcrumb-item">
                    <a href="{{ url('/brand/' . $product->brand->slug . '/' . $product->category->slug) }}"
                        class="text-decoration-none text-secondary">
                        {{ $product->brand->name }}
                    </a>
                </li>
                <!-- Product -->
                <li class="breadcrumb-item text-secondary active" aria-current="page">
                    {{ Str::title($product->name) }}
                </li>
            </ol>
        </div>
    </nav>

    <div class="row">
        <div class="col-12 col-md-3 pe-1">
            @include("includes.sidebar_" . $category->slug, ['category' => $category])
        </div>
        <div class="col-12 col-md-9">
            @include("includes.info-bar")

            <div class="row">
                <div class="col-12 mb-2">
                    <h1 class="fs-3 bg-light p-2">{{ Str::title($product->name) }} Price, Specification</h1>
                    <p class="bg-light p-2 mb-0 rounded-bottom">The {{$product->name}} released on
                        {{ date("M Y", strtotime($release_date)) }} at price {{$country->currency}}
                        {{ $product->getFirstVariantPriceForCountry($product->id, $country->id) }} in
                        {{$country->country_name}}.</p>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-5 col-md-4 text-center">
                    <a href="#images"><img src="{{URL::to('/images/thumbnail.png')}}"
                            data-echo="{{$product->thumbnail}}" class="img-fluid mobile_image rounded mb-2"
                            alt="{{$product->slug}}"></a>
                    <div class="d-flex justify-content-center d-none">
                        <img id="shareIcon" src="{{URL::to('images/icons/share.png')}}" class="img-fluid mx-2"
                            width="30" height="30" title="share">
                        <img id="heartIcon" src="{{URL::to('images/icons/heart.png')}}" class="img-fluid mx-2"
                            width="30" height="30" alt="heart" title="Add to favourities">
                    </div>
                </div>
                <div class="col-7 col-md-8 d-sm-none">
                    @include("includes.product-summary")
                </div>

                <div class="col-12 col-md-8 pt-2">
                    <div class="mb-2 mb-md-4 row">
                        @if(!$product->images->isEmpty())
                            <div class="col-6 col-md-auto mb-2">
                                <a class="btn btn-primary btn-custom w-100" href="#images">Images</a>
                            </div>
                        @endif
                        <div class="col-6 col-md-auto mb-2">
                            <a class="btn btn-primary btn-custom w-100" href="#specification">Specifications</a>
                        </div>
                        @if($country->country_code == 'pk')
                            <div class="col-6 col-md-auto mb-2">
                                <a class="btn btn-primary btn-custom w-100" href="#installmentsDaraz">Installments</a>
                            </div>
                        @endif
                        <div class="col-6 col-md-auto mb-2">
                            <a class="btn btn-primary btn-custom w-100" href="#reviews">Reviews</a>
                        </div>
                    </div>
                    <div class="d-none d-sm-block">
                        @include("includes.product-summary")
                    </div>
                    @include("frontend.partials.amazon_button")
                </div>
            </div>

            @include("includes.product-specification")

            @include("includes.product-images")

            @include("includes.product-reviews")

            @include("includes.product-compare")

            @if($country->country_code == 'pk')
                @include("includes.product-daraz-installment", ["country" => $country, "name" => Str::title($product->name), "price" => $price_in_pkr])
            @endif

            @include("includes.product-related")
        </div>
    </div>
</main>
@stop


@section("script")
<script type="text/javascript">
    $(document).ready(function () {
        $('#mainIcon').click(function () {
            $('#socialIcons').slideToggle('fast');
        });
    });
    $(document).ready(function () {
        const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};

        $('.stars').on('click', function () {
            if (!isLoggedIn) {
                // Show login modal
                $('#loginModal').modal('show');
            } else {
                // Set the stars value
                $('#stars').val($(this).data('value'));

                // Update the UI to show selected stars
                $('.stars').attr('src', "{{ URL::to('/images/icons/star.png') }}"); // Reset all stars

                // Highlight selected stars
                for (let i = 1; i <= $(this).data('value'); i++) {
                    $('#star-' + i).attr('src', "{{ URL::to('/images/icons/star-fill.png') }}"); // Change to filled star image
                }

                // Hide stars error message
                $('#stars-error').hide();
            }
        });

        $('#reviewForm').on('submit', function (event) {
            event.preventDefault();

            // Validate form data
            const starsValue = $('#stars').val();
            const reviewText = $('#review').val();
            const product_id = $('#product_id').val();
            let isValid = true;

            if (!starsValue) {
                $('#stars-error').show();
                isValid = false;
            } else {
                $('#stars-error').hide();
            }

            if (!reviewText) {
                $('#review-error').show();
                $('#reviewTextarea').addClass('border-danger');
                isValid = false;
            } else {
                $('#review-error').hide();
                $('#reviewTextarea').removeClass('border-danger');
            }

            if (!isValid) {
                return;
            }
            var $button = $(".submitReview");
            $button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...').prop('disabled', true);
            // AJAX request
            $.ajax({
                url: '{{ route("review.post") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    stars: starsValue,
                    review: reviewText,
                    product_id: product_id
                },
                success: function (response) {

                    $('#stars').val('');
                    $('#review').val('');
                    $('.stars').attr('src', "{{ URL::to('/images/icons/star.png') }}");
                    $button.html('Submit').prop('disabled', false);
                    if (response.success) {
                        showToast('Success', response.message, 'bg-success text-white');
                        // Optionally, reset the form or update the UI accordingly
                    } else if (response.errors) {
                        var errorMessages = [];
                        $.each(response.errors, function (key, value) {
                            errorMessages.push(value[0]); // Collect all error messages
                        });
                        showToast('Error', errorMessages.join("\n"), 'bg-danger text-white');
                    } else if (response.error) {
                        showToast('Error', response.error, 'bg-danger text-white');
                    }
                },
                error: function (xhr) {
                    // Handle error
                    $button.html('Submit').prop('disabled', false);
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = xhr.responseJSON.error;

                    if (errorMessage) {
                        showToast('Error', errorMessage, 'bg-danger text-white');
                    } else if (errors) {
                        var errorMessages = [];
                        $.each(errors, function (key, value) {
                            errorMessages.push(value[0]); // Collect all error messages
                        });
                        showToast('Error', errorMessages.join("\n"), 'bg-danger text-white');
                    }
                }
            });
        });
    });
    $('.amazonButton').click(function () {
        $.ajax({
            url: "{{ route('store.user.info') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                "product_slug": "{{$product->slug}}",
                "url": "{{request()->fullUrl()}}",
            },
            success: function (response) {

            },
            error: function (xhr) {
                console.log(xhr.responseText);
            }
        });
    });
    function showToast(title, message, classes) {
        $('#toastContainer').html('');
        var toastHtml = `
            <div class="toast align-items-center ${classes} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}:</strong> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>`;
        $('#toastContainer').append(toastHtml);
        $('.toast').toast('show');
    }

    $("#nav-compare-tab").click(function () {
        window.location.replace($(this).data("href"));
    });
</script>
<script type="application/ld+json">
    {
          "@context": "https://schema.org",
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
            "@context": "https://schema.org/",
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
            "@context": "https://schema.org/",
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
        "@context": "https://schema.org/",
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