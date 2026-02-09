@extends('layouts.frontend')

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
    $attributes = $product->attributes()->get()->keyBy('attribute_id');
    $attributeMap = [
        'os' => 103,
        'ui' => 104,
        'dimensions' => 105,
        'weight' => 109,
        'sim' => 110,
        'colors' => 111,
        'g2_band' => 112,
        'g3_band' => 113,
        'g4_band' => 114,
        'g5_band' => 115,
        'cpu' => 116,
        'chipset' => 117,
        'gpu' => 118,
        'technology' => 119,
        'size' => 120,
        'resolution' => 121,
        'protection' => 122,
        'extra_features' => 123,
        'built_in' => 124,
        'buildd' => 125,
        'card' => 127,
        'main' => 128,
        'sms' => 129,
        'features' => 130,
        'front' => 131,
        'wlan' => 132,
        'bluetooth' => 133,
        'gps' => 134,
        'radio' => 135,
        'usb' => 136,
        'nfc' => 137,
        'infrared' => 138,
        'data' => 139,
        'sensors' => 140,
        'audio' => 141,
        'browser' => 142,
        'messaging' => 143,
        'games' => 146,
        'torch' => 147,
        'extra' => 148,
        'capacity' => 149,
        'body' => 150,
        'battery' => 151,
        'pixels' => 156,
        'screen_size' => 158,
        'ram_in_gb' => 159,
        'rom_in_gb' => 160,
        'release_date' => 163,
    ];

    foreach ($attributeMap as $variableName => $attributeId) {
        $$variableName = optional($attributes->get($attributeId))->value ?? null;
    }

    if ($product1) {
        $attributes1 = $product1->attributes()->get()->keyBy('attribute_id');
        foreach ($attributeMap as $variableName => $attributeId) {
            ${$variableName . '1'} = optional($attributes1->get($attributeId))->value ?? null;
        }
    }
    if ($product2) {
        $attributes2 = $product2->attributes()->get()->keyBy('attribute_id');
        foreach ($attributeMap as $variableName => $attributeId) {
            ${$variableName . '2'} = optional($attributes2->get($attributeId))->value ?? null;
        }
    }
@endphp

<main class="main">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                @include("includes.sidebar-unified", ['category' => $product->category])
            </div>

            <div class="col-md-9">
                <div class="my-2 my-md-3">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h1 class="py-2 mb-4 fs-4">
                                {{($metas->h1) ? $metas->h1 : "Compare Smart Watches"}}
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
                                <td colspan="3">{{ $release_date }}</td>
                                <td colspan="3">{{ $release_date1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $release_date2 ?? '' }}</td>
                            </tr>
                            <!-- ... (Rest of comparison rows) ... -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@stop

@section("script") @stop
@section("style") @stop