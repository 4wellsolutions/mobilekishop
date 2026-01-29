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
    }

    if ($product2) {
        $price2 = $product2->getFirstVariantPriceForCountry($product2->id, $country->id);
        $attributes2 = $product2->attributes()->get()->keyBy('id');
        foreach ($attributeMap as $variableName => $attributeId) {
            ${$variableName . '2'} = optional($attributes2->get($attributeId))->pivot->value ?? null;
        }
    }
@endphp

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
                                <td colspan="3">{{ date("M Y", strtotime($release_date)) }}</td>
                                <td colspan="3">
                                    {{ isset($release_date1) ? date("M Y", strtotime($release_date1)) : '' }}
                                </td>
                                <td colspan="3" class="thirdMobile">
                                    {{ isset($release_date2) ? date("M Y", strtotime($release_date2)) : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td rowspan="2"
                                    class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">
                                    Price</td>
                                <th>Price In {{$country->currency}}</th>
                                <td colspan="3">{{$country->currency}} {{ isset($price) ? $price : 'N/A'}} </td>
                                <td colspan="3">{{$country->currency}} {{ isset($price1) ? $price1 : 'N/A'}} </td>
                                <td colspan="3" class="thirdMobile">
                                    {{ isset($price2) ? $country->currency . ' ' . $price2 : ''}} </td>
                            </tr>
                            <tr>
                                <td class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee"
                                    rowspan="6">Build</td>
                                <th>OS</th>
                                <td colspan="3">{{ $os }}</td>
                                <td colspan="3">{{ isset($os1) ? $os1 : '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ isset($os2) ? $os2 : '' }}</td>
                            </tr>
                            <tr>
                                <th>UI</th>
                                <td colspan="3">{{ $ui }}</td>
                                <td colspan="3">{{ isset($ui1) ? $ui1 : '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ isset($ui2) ? $ui2 : '' }}</td>
                            </tr>
                            <tr>
                                <th>Dimensions</th>
                                <td colspan="3">{{ $dimensions }}</td>
                                <td colspan="3">{{ isset($dimensions1) ? $dimensions1 : '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ isset($dimensions2) ? $dimensions2 : '' }}</td>
                            </tr>
                            <tr>
                                <th>Weight</th>
                                <td colspan="3">{{ $weight }}</td>
                                <td colspan="3">{{ isset($weight1) ? $weight1 : '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ isset($weight2) ? $weight2 : '' }}</td>
                            </tr>

                            <tr>
                                <th>SIM</th>
                                <td colspan="3">{{ $sim }}</td>
                                <td colspan="3">{{ $sim1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $sim2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Colors</th>
                                <td colspan="3">{{ $colors }}</td>
                                <td colspan="3">{{ $colors1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $colors2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="4"
                                    class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">
                                    Frequency</td>
                                <th>2G Band</th>
                                <td colspan="3">{{ $g2_band }}</td>
                                <td colspan="3">{{ $g2_band1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $g2_band2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>3G Band</th>
                                <td colspan="3">{{ $g3_band }}</td>
                                <td colspan="3">{{ $g3_band1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $g3_band2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>4G Band</th>
                                <td colspan="3">{{ $g4_band }}</td>
                                <td colspan="3">{{ $g4_band1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $g4_band2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>5G Band</th>
                                <td colspan="3">{{ $g5_band }}</td>
                                <td colspan="3">{{ $g5_band1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $g5_band2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="3"
                                    class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">
                                    Processor</td>
                                <th>CPU</th>
                                <td colspan="3">{{ $cpu }}</td>
                                <td colspan="3">{{ $cpu1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $cpu2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Chipset</th>
                                <td colspan="3">{{ $chipset }}</td>
                                <td colspan="3">{{ $chipset1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $chipset2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>GPU</th>
                                <td colspan="3">{{ $gpu }}</td>
                                <td colspan="3">{{ $gpu1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $gpu2 ?? '' }}</td>
                            </tr>

                            <tr>
                                <td rowspan="5"
                                    class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">
                                    Display</td>
                                <th>Technology</th>
                                <td colspan="3">{{ $technology }}</td>
                                <td colspan="3">{{ $technology1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $technology2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Size</th>
                                <td colspan="3">{{ $size }}</td>
                                <td colspan="3">{{ $size1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $size2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Resolution</th>
                                <td colspan="3">{{ $resolution }}</td>
                                <td colspan="3">{{ $resolution1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $resolution2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Protection</th>
                                <td colspan="3">{{ $protection }}</td>
                                <td colspan="3">{{ $protection1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $protection2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Extra Features</th>
                                <td colspan="3">{{ $extra_features }}</td>
                                <td colspan="3">{{ $extra_features1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $extra_features2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="2"
                                    class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">
                                    Memory</td>
                                <th>Built-in</th>
                                <td colspan="3">{{ $built_in }}</td>
                                <td colspan="3">{{ $built_in1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $built_in2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Card</th>
                                <td colspan="3">{{ $card }}</td>
                                <td colspan="3">{{ $card1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $card2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="3" class="text-danger text-uppercase font-weight-bold v-align-middle">
                                    Camera</td>
                                <th>Main</th>
                                <td colspan="3">{{ $main }}</td>
                                <td colspan="3">{{ $main1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $main2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Features</th>
                                <td colspan="3">{{ $features }}</td>
                                <td colspan="3">{{ $features1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $features2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Front</th>
                                <td colspan="3">{{ $front }}</td>
                                <td colspan="3">{{ $front1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $front2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="7"
                                    class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">
                                    Connectivity</td>
                                <th>WLAN</th>
                                <td colspan="3">{{ $wlan }}</td>
                                <td colspan="3">{{ $wlan1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $wlan2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Bluetooth</th>
                                <td colspan="3">{{ $bluetooth }}</td>
                                <td colspan="3">{{ $bluetooth1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $bluetooth2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>GPS</th>
                                <td colspan="3">{{ $gps }}</td>
                                <td colspan="3">{{ $gps1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $gps2 ?? '' }}</td>
                            </tr>

                            <tr>
                                <th>Radio</th>
                                <td colspan="3">{{ $radio }}</td>
                                <td colspan="3">{{ $radio1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $radio2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>USB</th>
                                <td colspan="3">{{ $usb }}</td>
                                <td colspan="3">{{ $usb1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $usb2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>NFC</th>
                                <td colspan="3">{{ $nfc }}</td>
                                <td colspan="3">{{ $nfc1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $nfc2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Data</th>
                                <td colspan="3">{{ $data }}</td>
                                <td colspan="3">{{ $data1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $data2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="7"
                                    class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">
                                    Features</td>
                                <th>Sensors</th>
                                <td colspan="3">{{ $sensors }}</td>
                                <td colspan="3">{{ $sensors1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $sensors2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Audio</th>
                                <td colspan="3">{{ $audio }}</td>
                                <td colspan="3">{{ $audio1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $audio2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Browser</th>
                                <td colspan="3">{{ $browser }}</td>
                                <td colspan="3">{{ $browser1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $browser2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Messaging</th>
                                <td colspan="3">{{ $messaging }}</td>
                                <td colspan="3">{{ $messaging1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $messaging2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Games</th>
                                <td colspan="3">{{ $games }}</td>
                                <td colspan="3">{{ $games1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $games2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Torch</th>
                                <td colspan="3">{{ $torch }}</td>
                                <td colspan="3">{{ $torch1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $torch2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Extra</th>
                                <td colspan="3">{{ $extra }}</td>
                                <td colspan="3">{{ $extra1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $extra2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <td class="text-danger text-uppercase font-weight-bold v-align-middle">Battery</td>
                                <th>Capacity</th>
                                <td colspan="3">{{ $capacity }}</td>
                                <td colspan="3">{{ $capacity1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $capacity2 ?? '' }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div><!-- End .container -->
</main><!-- End .main -->
@stop

@section("script") @stop
@section("style") @stop