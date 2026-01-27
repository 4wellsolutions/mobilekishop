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

    $attributes = $product->Attributes()->get()->keyBy(function ($item) {
        return strtolower(str_replace([' ', '(', ')'], ['_', '', ''], $item->label));
    });

    // Summary variables
    $camera_test = $attributes->get('pixels')->pivot->value ?? '';
    $battery_new = $attributes->get('battery_new')->pivot->value ?? $attributes->get('battery')->pivot->value ?? '';
    $ram = $attributes->get('ram_in_gb')->pivot->value ?? '';
    $rom = $attributes->get('rom_in_gb')->pivot->value ?? '';

    // Specification variables (used in product-specification.blade.php)
    $os = $attributes->get('os')->pivot->value ?? null;
    $ui = $attributes->get('ui')->pivot->value ?? null;
    $dimensions = $attributes->get('dimensions')->pivot->value ?? null;
    $weight = $attributes->get('weight')->pivot->value ?? null;
    $sim = $attributes->get('sim')->pivot->value ?? null;
    $colors = $attributes->get('colors')->pivot->value ?? null;
    $buildd = $attributes->get('build')->pivot->value ?? null;
    $technology = $attributes->get('technology')->pivot->value ?? null;
    $g2_band = $attributes->get('2g_band')->pivot->value ?? null;
    $g3_band = $attributes->get('3g_band')->pivot->value ?? null;
    $g4_band = $attributes->get('4g_band')->pivot->value ?? null;
    $g5_band = $attributes->get('5g_band')->pivot->value ?? null;
    $cpu = $attributes->get('cpu')->pivot->value ?? null;
    $chipset = $attributes->get('chipset')->pivot->value ?? null;
    $gpu = $attributes->get('gpu')->pivot->value ?? null;
    $display = $attributes->get('display')->pivot->value ?? null;
    $size = $attributes->get('size')->pivot->value ?? null;
    $resolution = $attributes->get('resolution')->pivot->value ?? $attributes->get('screen_resolution')->pivot->value ?? null;
    $protection = $attributes->get('protection')->pivot->value ?? null;
    $extra_features = $attributes->get('extra_features')->pivot->value ?? null;
    $built_in = $attributes->get('built_in')->pivot->value ?? null;
    $card = $attributes->get('card')->pivot->value ?? null;
    $main = $attributes->get('main')->pivot->value ?? null;
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
    $models = $attributes->get('models')->pivot->value ?? null;
    $sar = $attributes->get('sar')->pivot->value ?? null;
    $sar_eu = $attributes->get('sar_eu')->pivot->value ?? null;
    $battery_old = $attributes->get('battery_old')->pivot->value ?? null;
    $loudspeaker = $attributes->get('loudspeaker')->pivot->value ?? null;
    $geekbench = $attributes->get('geekbench')->pivot->value ?? null;
    $antutu = $attributes->get('antutu')->pivot->value ?? null;

    $release_date = \Carbon\Carbon::parse($product->release_date)->format("M-Y");

@endphp


<main class="main container-lg">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="my-1" style="font-size: 12px;">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item">
                    <a href="{{ url('/' . ($country->country_code === 'pk' ? '' : $country->country_code)) }}"
                        class="text-decoration-none text-secondary">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url(($country->country_code === 'pk' ? '' : $country->country_code) . '/category/' . $product->category->slug) }}"
                        class="text-decoration-none text-secondary">
                        {{ $product->category->category_name }}
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url(($country->country_code === 'pk' ? '' : $country->country_code) . '/brand/' . $product->brand->slug . '/' . $product->category->slug) }}"
                        class="text-decoration-none text-secondary">
                        {{ $product->brand->name }}
                    </a>
                </li>
                <li class="breadcrumb-item text-secondary active" aria-current="page">{{ Str::title($product->name) }}
                </li>
            </ol>
        </div>
    </nav>

    <div class="row">
        <div class="col-12 col-md-3 pe-1">
            @include("includes.sidebar_" . $product->category->slug, ['category' => $product->category])
        </div>
        <div class="col-12 col-md-9">
            @include("includes.info-bar")

            <div class="row">
                <div class="col-12 mb-2">
                    <h1 class="fs-3 bg-light p-2">{{ Str::title($product->name) }}</h1>
                    <p class="bg-light p-2 mb-0 rounded-bottom">The {{ $product->name }} released on {{ $release_date }}
                        at price {{ $country->currency }} {{ $price_in_pkr }} in {{ $country->country_name }} with
                        {{ $camera_test ?? '' }} , {{ $battery_new ?? '' }} battery, {{ $rom ?? '' }} storage, and
                        {{ $ram ?? '' }} RAM.
                    </p>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-5 col-md-4 text-center my-auto">
                    <a href="#images"><img src="{{ URL::to('/images/thumbnail.png') }}"
                            data-echo="{{ $product->thumbnail }}" class="img-fluid mobile_image rounded mb-2"
                            alt="{{ $product->slug }}"></a>
                    <div class="d-flex justify-content-center d-none">
                        <img id="shareIcon" src="{{ URL::to('images/icons/share.png') }}" class="img-fluid mx-2"
                            width="30" height="30" title="share">
                        <img id="heartIcon" src="{{ URL::to('images/icons/heart.png') }}" class="img-fluid mx-2"
                            width="30" height="30" title="heart">
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
                        <div class="col-6 col-md-auto mb-2">
                            <a class="btn btn-primary btn-custom w-100" id="nav-compare-tab"
                                data-href="{{URL::to('/compare/') . '/' . $product->slug}}">Compare</a>
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
            <div class="row my-3 my-md-4">
                <div class="col-12">
                    {!! $product->body !!}
                </div>
            </div>
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
@stop