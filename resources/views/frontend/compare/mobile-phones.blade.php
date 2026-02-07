@php
    $layout = ($country->country_code == 'pk') ? 'layouts.techspec' : 'layouts.techspec';
@endphp

@extends($layout)

@section('title', $metas->title)
@section('description', $metas->description)
@section("keywords", "Mobiles prices, mobile specification, mobile phone features ")
@section("canonical", \URL::full())

@section("content")
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
            'release_date' => 80
        ];

        foreach ($attributeMap as $variableName => $attributeId) {
            $$variableName = optional($attributes->get($attributeId))->pivot->value ?? null;
        }

        $release_date_fmt = \Carbon\Carbon::parse($product->release_date)->format("M Y");

        // Helper functions for product 2 and 3
        if ($product1) {
            $price1 = $product1->getFirstVariantPriceForCountry($product1->id, $country->id);
            $attributes1 = $product1->attributes()->get()->keyBy('id');
            foreach ($attributeMap as $variableName => $attributeId) {
                ${$variableName . '1'} = optional($attributes1->get($attributeId))->pivot->value ?? null;
            }
            $release_date_fmt1 = \Carbon\Carbon::parse($product1->release_date)->format("M Y");
        }

        if ($product2) {
            $price2 = $product2->getFirstVariantPriceForCountry($product2->id, $country->id);
            $attributes2 = $product2->attributes()->get()->keyBy('id');
            foreach ($attributeMap as $variableName => $attributeId) {
                ${$variableName . '2'} = optional($attributes2->get($attributeId))->pivot->value ?? null;
            }
            $release_date_fmt2 = \Carbon\Carbon::parse($product2->release_date)->format("M Y");
        }
        
        $isPk = ($country->country_code ?? 'pk') == 'pk';
        $countryPrefix = $isPk ? '' : 'country.';
        $routeParams = $isPk ? [] : ['country_code' => $country->country_code];
        $comparisonRoute = route($countryPrefix . 'comparison', $routeParams);
    @endphp

    <div class="mb-8 text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-text-main mb-3 tracking-tight">
            {{ ($metas->h1) ? $metas->h1 : "Compare Devices" }}</h1>
        <p class="text-text-muted max-w-2xl mx-auto">Compare specifications, features, and prices side-by-side to make the
            best choice.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-card border border-border-light overflow-hidden">
        <div class="overflow-x-auto no-scrollbar">
            <div class="min-w-[800px]">
                <!-- Sticky Header -->
                <div class="grid grid-cols-4 sticky top-0 bg-white z-10 border-b border-border-light shadow-sm">
                    <div class="p-4 flex items-center bg-gray-50/50 backdrop-blur-sm">
                        <span class="text-sm font-bold text-text-muted uppercase tracking-wider">Device Info</span>
                    </div>

                    <!-- Product 1 -->
                    <div class="p-6 text-center border-l border-border-light relative group">
                        <div class="h-40 mb-4 flex items-center justify-center">
                            <img src="{{ $product->thumbnail }}" alt="{{ $product->name }}"
                                class="h-full object-contain drop-shadow transition-transform group-hover:scale-105">
                        </div>
                        <a href="{{ route('product.show', $product->slug) }}"
                            class="text-lg font-bold text-text-main hover:text-primary mb-2 block">{{ $product->name }}</a>
                        <p class="text-primary font-bold text-lg mb-3">{{ $country->currency }}
                            {{ $price ? number_format($price) : 'N/A' }}</p>
                        <a href="{{ route('product.show', $product->slug) }}"
                            class="inline-block w-full py-2 px-4 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary-hover transition-colors shadow-sm">View
                            Details</a>
                    </div>

                    <!-- Product 2 -->
                    <div class="p-6 text-center border-l border-border-light relative group">
                        @if($product1)
                            <div class="h-40 mb-4 flex items-center justify-center">
                                <img src="{{ $product1->thumbnail }}" alt="{{ $product1->name }}"
                                    class="h-full object-contain drop-shadow transition-transform group-hover:scale-105">
                            </div>
                            <a href="{{ route('product.show', $product1->slug) }}"
                                class="text-lg font-bold text-text-main hover:text-primary mb-2 block">{{ $product1->name }}</a>
                            <p class="text-primary font-bold text-lg mb-3">{{ $country->currency }}
                                {{ $price1 ? number_format($price1) : 'N/A' }}</p>
                            <a href="{{ route('product.show', $product1->slug) }}"
                                class="inline-block w-full py-2 px-4 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary-hover transition-colors shadow-sm">View
                                Details</a>
                            <a href="#" class="absolute top-2 right-2 text-text-muted hover:text-red-500 transition-colors"
                                title="Remove"><span class="material-symbols-outlined text-[20px]">close</span></a>
                        @else
                            <div class="h-full flex flex-col items-center justify-center text-text-muted gap-2 min-h-[200px]">
                                <span class="material-symbols-outlined text-4xl opacity-20">add_circle</span>
                                <a href="{{ $comparisonRoute }}" class="text-sm font-medium hover:text-primary">Add
                                    Device</a>
                            </div>
                        @endif
                    </div>

                    <!-- Product 3 -->
                    <div class="p-6 text-center border-l border-border-light relative group">
                        @if($product2)
                            <div class="h-40 mb-4 flex items-center justify-center">
                                <img src="{{ $product2->thumbnail }}" alt="{{ $product2->name }}"
                                    class="h-full object-contain drop-shadow transition-transform group-hover:scale-105">
                            </div>
                            <a href="{{ route('product.show', $product2->slug) }}"
                                class="text-lg font-bold text-text-main hover:text-primary mb-2 block">{{ $product2->name }}</a>
                            <p class="text-primary font-bold text-lg mb-3">{{ $country->currency }}
                                {{ $price2 ? number_format($price2) : 'N/A' }}</p>
                            <a href="{{ route('product.show', $product2->slug) }}"
                                class="inline-block w-full py-2 px-4 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary-hover transition-colors shadow-sm">View
                                Details</a>
                            <a href="#" class="absolute top-2 right-2 text-text-muted hover:text-red-500 transition-colors"
                                title="Remove"><span class="material-symbols-outlined text-[20px]">close</span></a>
                        @else
                            <div class="h-full flex flex-col items-center justify-center text-text-muted gap-2 min-h-[200px]">
                                <span class="material-symbols-outlined text-4xl opacity-20">add_circle</span>
                                <a href="{{ $comparisonRoute }}" class="text-sm font-medium hover:text-primary">Add
                                    Device</a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Specs Table Body -->
                <div class="divide-y divide-border-light">

                    <!-- Section: Launch -->
                    <div class="group">
                        <div
                            class="bg-gray-50/80 px-4 py-2 text-xs font-bold text-text-muted uppercase tracking-wider sticky top-[calc(theme(spacing.4)+280px)]">
                            Launch</div>
                        <div class="grid grid-cols-4 divide-x divide-border-light hover:bg-gray-50/50 transition-colors">
                            <div class="p-4 text-sm font-medium text-text-muted">Status</div>
                            <div class="p-4 text-sm text-text-main">Available. Released {{ $release_date_fmt }}</div>
                            <div class="p-4 text-sm text-text-main">
                                {{ isset($release_date_fmt1) ? 'Available. Released ' . $release_date_fmt1 : '-' }}</div>
                            <div class="p-4 text-sm text-text-main">
                                {{ isset($release_date_fmt2) ? 'Available. Released ' . $release_date_fmt2 : '-' }}</div>
                        </div>
                    </div>

                    <!-- Section: Body -->
                    <div class="group">
                        <div class="bg-gray-50/80 px-4 py-2 text-xs font-bold text-text-muted uppercase tracking-wider">Body
                        </div>
                        @foreach(['dimensions' => 'Dimensions', 'weight' => 'Weight', 'buildd' => 'Build', 'sim' => 'SIM', 'colors' => 'Colors'] as $key => $label)
                            <div
                                class="grid grid-cols-4 divide-x divide-border-light hover:bg-gray-50/50 transition-colors border-t border-border-light first:border-t-0">
                                <div class="p-4 text-sm font-medium text-text-muted">{{ $label }}</div>
                                <div class="p-4 text-sm text-text-main">{{ $$key ?? '-' }}</div>
                                <div class="p-4 text-sm text-text-main">{{ ${$key . '1'} ?? '-' }}</div>
                                <div class="p-4 text-sm text-text-main">{{ ${$key . '2'} ?? '-' }}</div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Section: Display -->
                    <div class="group">
                        <div class="bg-gray-50/80 px-4 py-2 text-xs font-bold text-text-muted uppercase tracking-wider">
                            Display</div>
                        @foreach(['technology' => 'Type', 'size' => 'Size', 'resolution' => 'Resolution', 'protection' => 'Protection', 'extra_features' => 'Features'] as $key => $label)
                            <div
                                class="grid grid-cols-4 divide-x divide-border-light hover:bg-gray-50/50 transition-colors border-t border-border-light first:border-t-0">
                                <div class="p-4 text-sm font-medium text-text-muted">{{ $label }}</div>
                                <div class="p-4 text-sm text-text-main">{{ $$key ?? '-' }}</div>
                                <div class="p-4 text-sm text-text-main">{{ ${$key . '1'} ?? '-' }}</div>
                                <div class="p-4 text-sm text-text-main">{{ ${$key . '2'} ?? '-' }}</div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Section: Platform -->
                    <div class="group">
                        <div class="bg-gray-50/80 px-4 py-2 text-xs font-bold text-text-muted uppercase tracking-wider">
                            Platform</div>
                        @foreach(['os' => 'OS', 'chipset' => 'Chipset', 'cpu' => 'CPU', 'gpu' => 'GPU'] as $key => $label)
                            <div
                                class="grid grid-cols-4 divide-x divide-border-light hover:bg-gray-50/50 transition-colors border-t border-border-light first:border-t-0">
                                <div class="p-4 text-sm font-medium text-text-muted">{{ $label }}</div>
                                <div class="p-4 text-sm text-text-main">{{ $$key ?? '-' }}</div>
                                <div class="p-4 text-sm text-text-main">{{ ${$key . '1'} ?? '-' }}</div>
                                <div class="p-4 text-sm text-text-main">{{ ${$key . '2'} ?? '-' }}</div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Section: Memory -->
                    <div class="group">
                        <div class="bg-gray-50/80 px-4 py-2 text-xs font-bold text-text-muted uppercase tracking-wider">
                            Memory</div>
                        @foreach(['card' => 'Card Slot', 'built_in' => 'Internal'] as $key => $label)
                            <div
                                class="grid grid-cols-4 divide-x divide-border-light hover:bg-gray-50/50 transition-colors border-t border-border-light first:border-t-0">
                                <div class="p-4 text-sm font-medium text-text-muted">{{ $label }}</div>
                                <div class="p-4 text-sm text-text-main">{{ $$key ?? '-' }}</div>
                                <div class="p-4 text-sm text-text-main">{{ ${$key . '1'} ?? '-' }}</div>
                                <div class="p-4 text-sm text-text-main">{{ ${$key . '2'} ?? '-' }}</div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Section: Camera -->
                    <div class="group">
                        <div class="bg-gray-50/80 px-4 py-2 text-xs font-bold text-text-muted uppercase tracking-wider">
                            Camera</div>
                        @foreach(['main' => 'Main', 'features' => 'Features', 'front' => 'Selfie'] as $key => $label)
                            <div
                                class="grid grid-cols-4 divide-x divide-border-light hover:bg-gray-50/50 transition-colors border-t border-border-light first:border-t-0">
                                <div class="p-4 text-sm font-medium text-text-muted">{{ $label }}</div>
                                <div class="p-4 text-sm text-text-main">{{ $$key ?? '-' }}</div>
                                <div class="p-4 text-sm text-text-main">{{ ${$key . '1'} ?? '-' }}</div>
                                <div class="p-4 text-sm text-text-main">{{ ${$key . '2'} ?? '-' }}</div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Section: Communications -->
                    <div class="group">
                        <div class="bg-gray-50/80 px-4 py-2 text-xs font-bold text-text-muted uppercase tracking-wider">
                            Comms</div>
                        @foreach(['wlan' => 'WLAN', 'bluetooth' => 'Bluetooth', 'gps' => 'GPS', 'nfc' => 'NFC', 'radio' => 'Radio', 'usb' => 'USB'] as $key => $label)
                            <div
                                class="grid grid-cols-4 divide-x divide-border-light hover:bg-gray-50/50 transition-colors border-t border-border-light first:border-t-0">
                                <div class="p-4 text-sm font-medium text-text-muted">{{ $label }}</div>
                                <div class="p-4 text-sm text-text-main">{{ $$key ?? '-' }}</div>
                                <div class="p-4 text-sm text-text-main">{{ ${$key . '1'} ?? '-' }}</div>
                                <div class="p-4 text-sm text-text-main">{{ ${$key . '2'} ?? '-' }}</div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Section: Battery -->
                    <div class="group">
                        <div class="bg-gray-50/80 px-4 py-2 text-xs font-bold text-text-muted uppercase tracking-wider">
                            Battery</div>
                        @foreach(['capacity' => 'Type', 'extra' => 'Charging'] as $key => $label)
                            <div
                                class="grid grid-cols-4 divide-x divide-border-light hover:bg-gray-50/50 transition-colors border-t border-border-light first:border-t-0">
                                <div class="p-4 text-sm font-medium text-text-muted">{{ $label }}</div>
                                <div class="p-4 text-sm text-text-main">{{ $$key ?? '-' }}</div>
                                <div class="p-4 text-sm text-text-main">{{ ${$key . '1'} ?? '-' }}</div>
                                <div class="p-4 text-sm text-text-main">{{ ${$key . '2'} ?? '-' }}</div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection