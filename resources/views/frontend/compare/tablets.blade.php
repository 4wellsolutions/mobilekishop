@extends('layouts.frontend')

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
            'os' => 183,
            'ui' => 184,
            'dimensions' => 185,
            'weight' => 189,
            'sim' => 190,
            'colors' => 191,
            'g2_band' => 192,
            'g3_band' => 193,
            'g4_band' => 194,
            'g5_band' => 195,
            'cpu' => 196,
            'chipset' => 197,
            'gpu' => 198,
            'technology' => 199,
            'size' => 200,
            'resolution' => 201,
            'protection' => 202,
            'extra_features' => 203,
            'built_in' => 204,
            'buildd' => 206,
            'card' => 207,
            'main' => 208,
            'sms' => 209,
            'features' => 210,
            'front' => 211,
            'wlan' => 212,
            'bluetooth' => 213,
            'gps' => 214,
            'radio' => 215,
            'usb' => 216,
            'nfc' => 217,
            'infrared' => 218,
            'data' => 219,
            'sensors' => 220,
            'audio' => 221,
            'browser' => 222,
            'messaging' => 223,
            'games' => 226,
            'torch' => 227,
            'extra' => 228,
            'capacity' => 229,
            'body' => 230,
            'battery' => 231,
            'pixels' => 236,
            'screen_size' => 238,
            'ram_in_gb' => 239,
            'rom_in_gb' => 240,
            'release_date' => 243,
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
                        {{ ($metas->h1) ? $metas->h1 : "Compare Tablets" }}
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
                            <tr>
                                <td rowspan="2" class="text-red-500 uppercase font-bold align-middle border-bottom-eee">
                                    Price</td>
                                <th>Price In {{ $country->currency }}</th>
                                <td colspan="3">{{ $country->currency . ' ' . number_format($product->price_in_pkr) }}
                                </td>
                                <td colspan="3">
                                    {{ isset($product1) ? $country->currency . ' ' . number_format($product1->price_in_pkr) : '' }}
                                </td>
                                <td colspan="3" class="thirdMobile">
                                    {{ isset($product2) ? $country->currency . ' ' . number_format($product2->price_in_pkr) : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-red-500 uppercase font-bold align-middle border-bottom-eee" rowspan="6">
                                    Build</td>
                                <th>OS</th>
                                <td colspan="3">{{ $os }}</td>
                                <td colspan="3">{{ $os1 ?? '' }}</td>
                                <td colspan="3" class="thirdMobile">{{ $os2 ?? '' }}</td>
                            </tr>
                            <!-- ... (Rest of comparison rows) ... -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection