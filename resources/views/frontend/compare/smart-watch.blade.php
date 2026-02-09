@extends('layouts.techspec')

@section('title', $metas->title)
@section('description', $metas->description)
@section("canonical", \URL::full())

@section("content")
    @if(!$product1)
        <meta name="robots" content="noindex" />
    @endif

    @php
        $attributes = $product->attributes()->get()->keyBy('id');
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
            $attributes1 = $product1->attributes()->get()->keyBy('id');
            foreach ($attributeMap as $variableName => $attributeId) {
                ${$variableName . '1'} = optional($attributes1->get($attributeId))->value ?? null;
            }
        }
        if ($product2) {
            $attributes2 = $product2->attributes()->get()->keyBy('id');
            foreach ($attributeMap as $variableName => $attributeId) {
                ${$variableName . '2'} = optional($attributes2->get($attributeId))->value ?? null;
            }
        }
    @endphp

    <main class="max-w-[1400px] mx-auto px-4 md:px-6 lg:px-8 py-4">
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-12 gap-4">
            {{-- Sidebar --}}
            <div class="md:col-span-1 lg:col-span-3">
                @include("includes.sidebar-unified", ['category' => $product->category])
            </div>

            {{-- Main Content --}}
            <div class="md:col-span-3 lg:col-span-9">
                <div class="my-2 md:my-3">
                    <h1 class="text-lg font-bold text-center text-text-main py-2 mb-4">
                        {{ ($metas->h1) ? $metas->h1 : "Compare Smart Watches" }}
                    </h1>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-center">
                        <div class="compareBox">
                            @include('includes.compare-mobile', ['product' => $product, 'isReadOnly' => true, "id" => 1])
                        </div>
                        <div class="compareBox">
                            @include('includes.compare-mobile', ['product' => $product1, 'isReadOnly' => false, "id" => 2])
                        </div>
                        <div class="compareBox hidden md:block">
                            @include('includes.compare-mobile', ['product' => $product2, 'isReadOnly' => false, "id" => 3])
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table border="0"
                        class="table custom-table mobileTable table-bordered table-sm w-full bg-surface-alt text-text-main text-sm"
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
    </main>
@endsection