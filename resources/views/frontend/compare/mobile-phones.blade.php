@extends('layouts.frontend')

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

    <div class="mb-6 text-center">
        <h1 class="text-2xl md:text-4xl font-bold text-text-main mb-2 tracking-tight">
            {{ ($metas->h1) ? $metas->h1 : "Compare Devices" }}
        </h1>
        <p class="text-text-muted text-sm md:text-base max-w-2xl mx-auto">Compare specifications, features, and prices
            side-by-side to make the best choice.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-card border border-border-light overflow-hidden">

        {{-- ===== DEVICE HEADER ===== --}}
        <div class="grid grid-cols-2 md:grid-cols-4 sticky top-0 bg-white z-10 border-b-2 border-border-light shadow-sm">

            {{-- Label column — desktop only --}}
            <div class="hidden md:flex p-4 items-center bg-gray-50/60 backdrop-blur-sm">
                <span class="text-xs font-bold text-text-muted uppercase tracking-widest">Device Info</span>
            </div>

            {{-- Product 1 --}}
            <div
                class="p-3 md:p-5 text-center border-r border-border-light relative group bg-gradient-to-b from-slate-50/60 to-white">
                <a href="{{ route('product.show', $product->slug) }}" class="block">
                    <div class="h-28 md:h-36 mb-3 flex items-center justify-center">
                        <img src="{{ $product->thumbnail }}" alt="{{ $product->name }}"
                            class="h-full object-contain drop-shadow-md transition-transform duration-300 group-hover:scale-105">
                    </div>
                    <p class="text-sm font-bold text-text-main hover:text-primary mb-1 line-clamp-2 leading-snug">
                        {{ $product->name }}</p>
                    <p class="text-primary font-semibold text-sm mb-3">{{ $country->currency }}
                        {{ $price ? number_format($price) : 'N/A' }}</p>
                    <span
                        class="inline-block w-full py-1.5 bg-primary text-white text-xs font-semibold rounded-lg hover:bg-primary-hover transition-colors">View
                        Details</span>
                </a>
            </div>

            {{-- Product 2 --}}
            <div
                class="p-3 md:p-5 text-center border-r border-border-light relative group bg-gradient-to-b from-slate-50/60 to-white">
                @if($product1)
                    <a href="{{ route('product.show', $product1->slug) }}" class="block">
                        <div class="h-28 md:h-36 mb-3 flex items-center justify-center">
                            <img src="{{ $product1->thumbnail }}" alt="{{ $product1->name }}"
                                class="h-full object-contain drop-shadow-md transition-transform duration-300 group-hover:scale-105">
                        </div>
                        <p class="text-sm font-bold text-text-main hover:text-primary mb-1 line-clamp-2 leading-snug">
                            {{ $product1->name }}</p>
                        <p class="text-primary font-semibold text-sm mb-3">{{ $country->currency }}
                            {{ $price1 ? number_format($price1) : 'N/A' }}</p>
                        <span
                            class="inline-block w-full py-1.5 bg-primary text-white text-xs font-semibold rounded-lg hover:bg-primary-hover transition-colors">View
                            Details</span>
                    </a>
                    <a href="#" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition-colors z-10"
                        title="Remove">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </a>
                @else
                    <div
                        class="h-full flex flex-col items-center justify-center text-text-muted gap-2 min-h-[180px] md:min-h-[210px] add-device-slot">
                        <span class="material-symbols-outlined text-3xl opacity-30">smartphone</span>
                        <p class="text-xs font-semibold text-text-muted">Add a Device</p>
                        <div class="relative w-full mt-1">
                            <input type="text"
                                class="add-device-search w-full px-3 py-2 pl-8 text-xs border-2 border-dashed border-border-light rounded-xl bg-slate-50 focus:bg-white focus:border-primary outline-none transition-all placeholder-gray-400"
                                placeholder="Search device..." autocomplete="off">
                            <span
                                class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-[14px] text-gray-400 pointer-events-none">search</span>
                            <div
                                class="add-device-results absolute z-50 top-full left-0 right-0 mt-1 bg-white border border-border-light rounded-xl shadow-xl max-h-52 overflow-y-auto hidden">
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Product 3 — desktop only --}}
            <div
                class="hidden md:block p-5 text-center border-r border-border-light relative group bg-gradient-to-b from-slate-50/60 to-white">
                @if($product2)
                    <a href="{{ route('product.show', $product2->slug) }}" class="block">
                        <div class="h-36 mb-3 flex items-center justify-center">
                            <img src="{{ $product2->thumbnail }}" alt="{{ $product2->name }}"
                                class="h-full object-contain drop-shadow-md transition-transform duration-300 group-hover:scale-105">
                        </div>
                        <p class="text-lg font-bold text-text-main hover:text-primary mb-2 line-clamp-2">{{ $product2->name }}
                        </p>
                        <p class="text-primary font-semibold text-lg mb-3">{{ $country->currency }}
                            {{ $price2 ? number_format($price2) : 'N/A' }}</p>
                        <span
                            class="inline-block w-full py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-hover transition-colors">View
                            Details</span>
                    </a>
                    <a href="#" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition-colors z-10"
                        title="Remove">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </a>
                @else
                    <div
                        class="h-full flex flex-col items-center justify-center text-text-muted gap-3 min-h-[210px] add-device-slot">
                        <span class="material-symbols-outlined text-4xl opacity-30">smartphone</span>
                        <p class="text-sm font-semibold text-text-muted">Add a Device</p>
                        <div class="relative w-full mt-1">
                            <input type="text"
                                class="add-device-search w-full px-3 py-2 pl-9 text-sm border-2 border-dashed border-border-light rounded-xl bg-slate-50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all"
                                placeholder="Search device..." autocomplete="off">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[16px] text-gray-400 pointer-events-none">search</span>
                            <div
                                class="add-device-results absolute z-50 top-full left-0 right-0 mt-1 bg-white border border-border-light rounded-xl shadow-xl max-h-52 overflow-y-auto hidden">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ===== SPECS TABLE ===== --}}
        {{-- Mobile: grid-cols-[5rem_1fr_1fr] — narrow label + 2 equal data cols
        Desktop: grid-cols-4 — label + 3 data cols --}}
        @php
            $specSections = [
                'Body' => ['dimensions' => 'Dimensions', 'weight' => 'Weight', 'buildd' => 'Build', 'sim' => 'SIM', 'colors' => 'Colors'],
                'Display' => ['technology' => 'Type', 'size' => 'Size', 'resolution' => 'Resolution', 'protection' => 'Protection', 'extra_features' => 'Features'],
                'Platform' => ['os' => 'OS', 'chipset' => 'Chipset', 'cpu' => 'CPU', 'gpu' => 'GPU'],
                'Memory' => ['card' => 'Card Slot', 'built_in' => 'Internal'],
                'Camera' => ['main' => 'Main', 'features' => 'Features', 'front' => 'Selfie'],
                'Comms' => ['wlan' => 'WLAN', 'bluetooth' => 'Bluetooth', 'gps' => 'GPS', 'nfc' => 'NFC', 'radio' => 'Radio', 'usb' => 'USB'],
                'Battery' => ['capacity' => 'Type', 'extra' => 'Charging'],
            ];
        @endphp

        <div class="divide-y divide-border-light">

            {{-- Launch --}}
            <div class="group">
                <div class="bg-gray-50/80 px-3 md:px-4 py-2 text-xs font-bold text-text-muted uppercase tracking-widest">
                    Launch</div>
                <div
                    class="grid grid-cols-[5rem_1fr_1fr] md:grid-cols-4 divide-x divide-border-light hover:bg-blue-50/20 transition-colors">
                    <div class="p-2 md:p-4 text-xs font-semibold text-text-muted self-start pt-3">Status</div>
                    <div class="p-2 md:p-4 text-xs md:text-sm text-text-main">Available. Released {{ $release_date_fmt }}
                    </div>
                    <div class="p-2 md:p-4 text-xs md:text-sm text-text-main">
                        {{ isset($release_date_fmt1) ? 'Available. Released ' . $release_date_fmt1 : '-' }}</div>
                    <div class="hidden md:block p-4 text-sm text-text-main">
                        {{ isset($release_date_fmt2) ? 'Available. Released ' . $release_date_fmt2 : '-' }}</div>
                </div>
            </div>

            @foreach($specSections as $sectionName => $fields)
                <div class="group">
                    <div class="bg-gray-50/80 px-3 md:px-4 py-2 text-xs font-bold text-text-muted uppercase tracking-widest">
                        {{ $sectionName }}</div>
                    @foreach($fields as $key => $fieldLabel)
                        <div
                            class="grid grid-cols-[5rem_1fr_1fr] md:grid-cols-4 divide-x divide-border-light hover:bg-blue-50/20 transition-colors border-t border-border-light first:border-t-0">
                            <div class="p-2 md:p-4 text-xs font-semibold text-text-muted self-start pt-3">{{ $fieldLabel }}</div>
                            <div class="p-2 md:p-4 text-xs md:text-sm text-text-main">{{ $$key ?? '-' }}</div>
                            <div class="p-2 md:p-4 text-xs md:text-sm text-text-main">{{ ${$key . '1'} ?? '-' }}</div>
                            <div class="hidden md:block p-4 text-sm text-text-main">{{ ${$key . '2'} ?? '-' }}</div>
                        </div>
                    @endforeach
                </div>
            @endforeach

        </div>
    </div>
@endsection

@section('script')
    <script>
        (function () {
            const API_URL = "{{ url('product/autocomplete') }}";
            const currentSlug = "{{ request()->route('slug') }}";
            const compareBase = "{{ url('compare') }}";
            let debounceTimer;

            document.querySelectorAll('.add-device-slot').forEach(slot => {
                const input = slot.querySelector('.add-device-search');
                const results = slot.querySelector('.add-device-results');
                if (!input || !results) return;

                input.addEventListener('input', function () {
                    const term = this.value.trim();
                    clearTimeout(debounceTimer);
                    if (term.length < 2) { results.classList.add('hidden'); results.innerHTML = ''; return; }

                    debounceTimer = setTimeout(() => {
                        fetch(API_URL + '?term=' + encodeURIComponent(term))
                            .then(r => r.json())
                            .then(products => {
                                if (!products.length) {
                                    results.innerHTML = '<div class="px-4 py-3 text-sm text-text-muted text-center">No devices found</div>';
                                    results.classList.remove('hidden');
                                    return;
                                }
                                results.innerHTML = products.map(p => `
                                    <button type="button" class="add-device-item flex items-center gap-3 w-full px-3 py-2.5 text-left hover:bg-primary/5 transition-colors"
                                        data-slug="${p.slug}">
                                        <img src="${p.thumbnail || '/images/placeholder.png'}" alt=""
                                            class="w-10 h-10 object-contain rounded-lg bg-slate-50 border border-border-light shrink-0"
                                            onerror="this.src='/images/placeholder.png'">
                                        <span class="text-sm font-medium text-text-main truncate">${p.name}</span>
                                    </button>
                                `).join('');
                                results.classList.remove('hidden');

                                results.querySelectorAll('.add-device-item').forEach(item => {
                                    item.addEventListener('click', function () {
                                        const slug = this.dataset.slug;
                                        window.location.href = compareBase + '/' + currentSlug + '-vs-' + slug;
                                    });
                                });
                            })
                            .catch(() => {
                                results.innerHTML = '<div class="px-4 py-3 text-sm text-red-500 text-center">Search failed</div>';
                                results.classList.remove('hidden');
                            });
                    }, 300);
                });

                input.addEventListener('focus', function () {
                    if (results.innerHTML.trim()) results.classList.remove('hidden');
                });
            });

            document.addEventListener('click', function (e) {
                if (!e.target.closest('.add-device-slot')) {
                    document.querySelectorAll('.add-device-results').forEach(r => r.classList.add('hidden'));
                }
            });
        })();
    </script>
@endsection