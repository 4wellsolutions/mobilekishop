@php
    $layout = ($country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

@section('title', $metas->title)

@section('description', $metas->description)

@section("keywords", "Mobiles prices, mobile specification, mobile phone features ")

@section("canonical", \URL::full())

@section("og_graph") @stop

@section("content")
@if(!$product1)
    <meta name="robots" content="noindex" />
@endif

@php

    $price = $product->getFirstVariantPriceForCountry($product->id, $country->id);

    $attributes = $product->attributes()->get()->keyBy('id');

    $attributeMap = [
        'os' => 20,
        'ui' => 21,
        'dimensions' => 22,
        'weight' => 26,
        'sim' => 27,
        'colors' => 28,
        'g2_band' => 29,
        'g3_band' => 30,
        'g4_band' => 31,
        'g5_band' => 32,
        'cpu' => 33,
        'chipset' => 34,
        'gpu' => 35,
        'technology' => 36,
        'size' => 37,
        'resolution' => 38,
        'protection' => 39,
        'extra_features' => 40,
        'built_in' => 41,
        'buildd' => 42,
        'card' => 44,
        'main' => 45,
        'sms' => 46,
        'features' => 47,
        'front' => 48,
        'wlan' => 49,
        'bluetooth' => 50,
        'gps' => 51,
        'radio' => 52,
        'usb' => 53,
        'nfc' => 54,
        'infrared' => 55,
        'data' => 56,
        'sensors' => 57,
        'audio' => 58,
        'browser' => 59,
        'messaging' => 60,
        'games' => 63,
        'torch' => 64,
        'extra' => 65,
        'capacity' => 66,
        'body' => 67,
        'battery' => 68,
        'pixels' => 73,
        'screen_size' => 75,
        'ram_in_gb' => 76,
        'rom_in_gb' => 77,
        'release_date' => 80,
        'reviewer' => 86,
        'rating' => 87,
        'review_count' => 88,
    ];

    // Assign attribute values to variables dynamically
    foreach ($attributeMap as $variableName => $attributeId) {
        $$variableName = optional($attributes->get($attributeId))->pivot->value ?? null;
    }

    $release_date = \Carbon\Carbon::parse($product->release_date)->format("M-Y");

    if ($product1) {
        $price1 = $product1->getFirstVariantPriceForCountry($product1->id, $country->id);
        $attributes1 = $product1->attributes()->get()->keyBy('id');

        foreach ($attributeMap as $variableName => $attributeId) {
            ${$variableName . '1'} = optional($attributes1->get($attributeId))->pivot->value ?? null;
        }

        $release_date1 = \Carbon\Carbon::parse($release_date1);
    }

    if ($product2) {
        $price2 = $product2->getFirstVariantPriceForCountry($product2->id, $country->id);
        $attributes2 = $product2->attributes()->get()->keyBy('id');

        foreach ($attributeMap as $variableName => $attributeId) {
            ${$variableName . '2'} = optional($attributes2->get($attributeId))->pivot->value ?? null;
        }

        $release_date2 = \Carbon\Carbon::parse($release_date2);
    }

@endphp

<style type="text/css">
    .card:hover {
        box-shadow: 0 25px 35px -5px rgb(0 0 0 / 10%) !important;
    }

    .twitter-typeahead {
        width: 100% !important;
    }

    .tt-menu {
        width: inherit !important;
    }

    .icon-angle-right {
        background: #928989ad;
        margin-left: 10px;
        padding-left: 15px !important;
        padding-right: 12px !important;
        padding-bottom: 3px !important;
    }

    .icon-angle-left {
        background: #928989ad;
        margin-left: 10px;
        padding-left: 12px !important;
        padding-right: 15px !important;
        padding-bottom: 3px !important;
    }

    .mobileImage {
        height: 150px !important;
    }

    h1 {
        margin-bottom: 1.8rem;
        color: #222529;
        font-weight: 700;
        line-height: 1.1;
        font-family: Poppins, sans-serif;
    }

    h1,
    h2,
    h3,
    h4 {
        font-family: Poppins, sans-serif;
    }

    p i {
        width: 20px;
    }

    @media only screen and (max-width : 576px) {
        .cameraBlock {
            border-right: none !important;
        }

        .thirdMobile {
            display: none;
        }

        .thickness,
        .storage {
            display: none;
        }

        .mobileTable tr th {
            width: 85px !important;
            font-size: 14px;
        }

        .specTabs .nav-item {
            margin-left: auto !important;
            margin-right: auto !important;
        }
    }

    @media only screen and (max-width: 450px) {

        .releaseDate,
        .androidVersion {
            display: none;
        }
    }

    .specOutline p {
        font-family: Poppins, sans-serif !important;
    }

    @media(max-width: 420px) {
        .Price {
            margin-top: 5px !important;
        }
    }

    .mobileTable tr td:first-child {
        display: none;
    }

    .table-sm {
        table-layout: fixed;
    }

    .mobileTable tr th {
        color: #dc3545 !important;
    }

    .mobileTable tr td {
        word-wrap: break-word !important;
    }

    .compareBox {
        min-height: 235px !important;
    }

    .compareBox h2 {
        /*min-height: 55px !important;*/
    }

    .compareBox img {
        height: 160px;
        width: 120px !important;
        max-height: 160px !important;
    }

    .searchImage {
        height: 75px !important;
    }
</style>
<link rel="preload" href="{{$product->thumbnail}}" as="image">
@if($product1)
    <link rel="preload" href="{{$product1->thumbnail}}" as="image">
@endif
<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container my-1" style="font-size: 12px;">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{URL::to('/')}}" class="text-decoration-none text-secondary">
                        <img src="{{URL::to('/images/icons/home.png')}}" alt="home-icon" width="16" height="14">
                    </a></li>
                <li class="breadcrumb-item" aria-current="page"><a class="text-decoration-none text-secondary"
                        href="{{URL::To('/comparison')}}">Compares</a></li>
                <li class="breadcrumb-item text-secondary active" aria-current="page">{{ $product->name }}
                    @if($product1) VS {{ $product1->name }} @endif
                    @if($product2) VS {{ $product2->name }} @endif
                </li>
            </ol>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-3 pe-1">
                @include("includes.sidebar_mobile-phones")
            </div>

            <div class="col-md-9">
                <div class="my-2 my-md-3">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h1 class="py-2 mb-4 fs-4 test">
                                {{($metas->h1) ? $metas->h1 : "Compare Mobiles"}}
                            </h1>
                        </div>

                        <div class="col-6 col-md-4 col-sm-6 text-center compareBox">
                            @include('includes.compare-mobile', ['product' => $product, 'isReadOnly' => true, "id" => 1])
                        </div>

                        <div class="col-6 col-md-4 col-sm-6 text-center compareBox">
                            @include('includes.compare-mobile', ['product' => $product1, 'isReadOnly' => false, "id" => 2])
                        </div>

                        <div class="col-6 col-md-4 d-none d-md-block text-center compareBox">
                            @include('includes.compare-mobile', ['product' => $product2, 'isReadOnly' => false, "id" => 3])
                        </div>
                    </div>
                </div>


                <div class="table-resposive">
                    <table border="0"
                        class="table custom-table mobileTable table-bordered table-sm w-100 bg-light text-dark"
                        cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr class="border-bottom-eee">
                                <th>Date</th>
                                <td colspan="2">{{ date("M Y", strtotime($release_date)) }}</td>
                                <td colspan="2">
                                    {{ isset($release_date1) ? date("M Y", strtotime($release_date1)) : '' }}</td>
                                <td colspan="2" class="thirdMobile">
                                    {{ isset($release_date2) ? date("M Y", strtotime($release_date2)) : '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="2"
                                    class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">
                                    Price</td>
                                <th>Price In {{$country->currency}}</th>
                                <td colspan="2">{{$country->currency}} {{ isset($price) ? $price : 'N/A'}} </td>
                                <td colspan="2">{{$country->currency}} {{ isset($price1) ? $price1 : 'N/A'}} </td>
                                <td colspan="2" class="thirdMobile">{{ isset($price2) ? $country->currency : ''}}
                                    {{ isset($price2) ? $price2 : ''}} </td>
                            </tr>
                            <tr>
                                <td class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee"
                                    rowspan="6">Build</td>
                                <th>OS</th>
                                <td colspan="2">{{ $os }}</td>
                                <td colspan="2">{{ isset($os1) ? $os1 : '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ isset($os2) ? $os2 : '' }}</td>
                            </tr>
                            <tr>
                                <th>UI</th>
                                <td colspan="2">{{ $ui }}</td>
                                <td colspan="2">{{ isset($ui1) ? $ui1 : '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ isset($ui2) ? $ui2 : '' }}</td>
                            </tr>
                            <tr>
                                <th>Dimensions</th>
                                <td colspan="2">{{ $dimensions }}</td>
                                <td colspan="2">{{ isset($dimensions1) ? $dimensions1 : '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ isset($dimensions2) ? $dimensions2 : '' }}</td>
                            </tr>
                            <tr>
                                <th>Weight</th>
                                <td colspan="2">{{ $weight }}</td>
                                <td colspan="2">{{ isset($weight1) ? $weight1 : '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ isset($weight2) ? $weight2 : '' }}</td>
                            </tr>

                            <tr>
                                <th>SIM</th>
                                <td colspan="2">{{ $sim }}</td>
                                <td colspan="2">{{ $sim1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $sim2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Colors</th>
                                <td colspan="2">{{ $colors }}</td>
                                <td colspan="2">{{ $colors1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $colors2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="4"
                                    class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">
                                    Frequency</td>
                                <th>2G Band</th>
                                <td colspan="2">{{ $g2_band }}</td>
                                <td colspan="2">{{ $g2_band1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $g2_band2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>3G Band</th>
                                <td colspan="2">{{ $g3_band }}</td>
                                <td colspan="2">{{ $g3_band1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $g3_band2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>4G Band</th>
                                <td colspan="2">{{ $g4_band }}</td>
                                <td colspan="2">{{ $g4_band1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $g4_band2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>5G Band</th>
                                <td colspan="2">{{ $g5_band }}</td>
                                <td colspan="2">{{ $g5_band1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $g5_band2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="3"
                                    class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">
                                    Processor</td>
                                <th>CPU</th>
                                <td colspan="2">{{ $cpu }}</td>
                                <td colspan="2">{{ $cpu1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $cpu2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Chipset</th>
                                <td colspan="2">{{ $chipset }}</td>
                                <td colspan="2">{{ $chipset1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $chipset2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>GPU</th>
                                <td colspan="2">{{ $gpu }}</td>
                                <td colspan="2">{{ $gpu1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $gpu2 ?? '' }}</td>
                            </tr>

                            <tr>
                                <td rowspan="5"
                                    class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">
                                    Display</td>
                                <th>Technology</th>
                                <td colspan="2">{{ $technology }}</td>
                                <td colspan="2">{{ $technology1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $technology2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Size</th>
                                <td colspan="2">{{ $size }}</td>
                                <td colspan="2">{{ $size1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $size2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Resolution</th>
                                <td colspan="2">{{ $resolution }}</td>
                                <td colspan="2">{{ $resolution1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $resolution2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Protection</th>
                                <td colspan="2">{{ $protection }}</td>
                                <td colspan="2">{{ $protection1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $protection2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Extra Features</th>
                                <td colspan="2">{{ $extra_features }}</td>
                                <td colspan="2">{{ $extra_features1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $extra_features2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="2"
                                    class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">
                                    Memory</td>
                                <th>Built-in</th>
                                <td colspan="2">{{ $built_in }}</td>
                                <td colspan="2">{{ $built_in1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $built_in2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Card</th>
                                <td colspan="2">{{ $card }}</td>
                                <td colspan="2">{{ $card1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $card2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="3" class="text-danger text-uppercase font-weight-bold v-align-middle">
                                    Camera</td>
                                <th>Main</th>
                                <td colspan="2">{{ $main }}</td>
                                <td colspan="2">{{ $main1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $main2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Features</th>
                                <td colspan="2">{{ $features }}</td>
                                <td colspan="2">{{ $features1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $features2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Front</th>
                                <td colspan="2">{{ $front }}</td>
                                <td colspan="2">{{ $front1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $front2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="7"
                                    class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">
                                    Connectivity</td>
                                <th>WLAN</th>
                                <td colspan="2">{{ $wlan }}</td>
                                <td colspan="2">{{ $wlan1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $wlan2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Bluetooth</th>
                                <td colspan="2">{{ $bluetooth }}</td>
                                <td colspan="2">{{ $bluetooth1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $bluetooth2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>GPS</th>
                                <td colspan="2">{{ $gps }}</td>
                                <td colspan="2">{{ $gps1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $gps2 ?? '' }}</td>
                            </tr>

                            <tr>
                                <th>Radio</th>
                                <td colspan="2">{{ $radio }}</td>
                                <td colspan="2">{{ $radio1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $radio2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>USB</th>
                                <td colspan="2">{{ $usb }}</td>
                                <td colspan="2">{{ $usb1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $usb2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>NFC</th>
                                <td colspan="2">{{ $nfc }}</td>
                                <td colspan="2">{{ $nfc1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $nfc2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Data</th>
                                <td colspan="2">{{ $data }}</td>
                                <td colspan="2">{{ $data1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $data2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="7"
                                    class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">
                                    Features</td>
                                <th>Sensors</th>
                                <td colspan="2">{{ $sensors }}</td>
                                <td colspan="2">{{ $sensors1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $sensors2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Audio</th>
                                <td colspan="2">{{ $audio }}</td>
                                <td colspan="2">{{ $audio1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $audio2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Browser</th>
                                <td colspan="2">{{ $browser }}</td>
                                <td colspan="2">{{ $browser1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $browser2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Messaging</th>
                                <td colspan="2">{{ $messaging }}</td>
                                <td colspan="2">{{ $messaging1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $messaging2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Games</th>
                                <td colspan="2">{{ $games }}</td>
                                <td colspan="2">{{ $games1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $games2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Torch</th>
                                <td colspan="2">{{ $torch }}</td>
                                <td colspan="2">{{ $torch1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $torch2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Extra</th>
                                <td colspan="2">{{ $extra }}</td>
                                <td colspan="2">{{ $extra1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $extra2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <td class="text-danger text-uppercase font-weight-bold v-align-middle">Battery</td>
                                <th>Capacity</th>
                                <td colspan="2">{{ $capacity }}</td>
                                <td colspan="2">{{ $capacity1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $capacity2 ?? '' }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div><!-- End .container -->
</main><!-- End .main -->
@stop


@section("script")
<script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/0.11.1/typeahead.bundle.min.js"></script>
<script type="text/javascript">
    var route = "{{ route('autocomplete.search') }}";
    // Set the Options for "Bloodhound" suggestion engine
    var engine = new Bloodhound({
        remote: {
            url: route + "?query=%QUERY%&category_id={{$product->category->id}}",
            wildcard: '%QUERY%'
        },
        datumTokenizer: Bloodhound.tokenizers.whitespace('query'),
        queryTokenizer: Bloodhound.tokenizers.whitespace
    });

    $(".search").typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    }, {
        source: engine.ttAdapter(),
        limit: 10 + 1,

        // This will be appended to "tt-dataset-" to form the class name of the suggestion menu.
        name: 'usersList',
        displayKey: 'name',
        // the key from the array we want to display (name,id,email,etc...)
        templates: {
            empty: [
                '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
            ],
            header: [
                '<div class="list-group search-results-dropdown">'
            ],
            suggestion: function (data) {
                return '<div class="row bg-white border-bottom"><div class="col-4"><img src="' + data.thumbnail + '" class="img-fluid searchImage my-1"></div><div class="col-8 text-uppercase text-start" style="font-weight:600;">' + data.name + '</div></div>'
            }
        }
    });

    $('.search').bind('typeahead:select', function (ev, suggestion) {
        console.log('Selection: ' + $(this).attr("id"));
        $("#input-" + $(this).attr("id")).val(suggestion.slug);
        var base_url = "{{URL::to('/compare/')}}/";
        var param1 = "";
        var param2 = "";
        var param3 = "";
        var loc = "";
        if ($("#input-search1").val() != "") {
            param1 = $("#input-search1").val();
        }
        if ($("#input-search2").val() != "") {
            param2 = "-vs-" + $("#input-search2").val();
        }
        if ($("#input-search3").val() != "") {
            param3 = "-vs-" + $("#input-search3").val();
        }
        console.log(base_url + param1 + param2 + param3);
        loc = base_url + param1 + param2 + param3;
        window.location.replace(loc);
    });
</script>
@stop

@section("style")

@stop