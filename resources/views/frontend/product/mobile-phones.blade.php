@php
    $layout = ($country->country_code == 'pk') ? 'layouts.techspec' : 'layouts.techspec';
@endphp

@extends($layout)

@section('title', Str::title($product->name) . ": Price, Specs & Deals in {$country->country_name} | MobileKiShop")

@section('description', "Discover the powerful {$product->name} at MobileKiShop. Compare specs, explore features, read user reviews, and find the best price in {$country->country_name}. Shop smart today!")

@section("keywords", "Mobiles prices, mobile specification, mobile phone features")

@section("canonical", ($country->country_code == 'pk') ? route('product.show', [$product->slug]) : route('country.product.show', [$country->country_code, $product->slug]))


@section('content')
    @php
        $nowDate = Carbon\Carbon::now();
        $price_in_pkr = $product->getFirstVariantPriceForCountry($product->id, $country->id);

        // Helper to get attribute value safely
        $getAttribute = function ($key) use ($product) {
            $attr = $product->attributes->first(function ($item) use ($key) {
                $slug = strtolower(str_replace([' ', '(', ')'], ['_', '', ''], $item->label));
                return $slug === $key;
            });
            return $attr ? $attr->pivot->value : null;
        };

        // Prepare attributes for easier access
        $screen_size = $getAttribute('size') ?? $getAttribute('screen_size');
        $resolution = $getAttribute('resolution') ?? $getAttribute('screen_resolution');
        $chipset = $getAttribute('chipset');
        $main_camera = $getAttribute('main');
        $battery = $getAttribute('battery') ?? $getAttribute('battery_new') ?? $getAttribute('capacity');
        $ram = $getAttribute('ram_in_gb');
        $rom = $getAttribute('rom_in_gb');
        $display_type = $getAttribute('technology');
        $os = $getAttribute('os');
        $video = $getAttribute('video'); // Assuming there might be a video attribute, otherwise placeholder
        $charging = $getAttribute('extra') ?? $getAttribute('charging'); // Heuristic

    @endphp

    <div class="flex flex-wrap items-center gap-2 mb-8 text-sm text-text-muted">
        <a href="{{ url('/') }}" class="hover:text-primary transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route(($country->country_code == 'pk' ? '' : 'country.') . 'category.show', ($country->country_code == 'pk' ? $product->category->slug : ['country_code' => $country->country_code, 'category' => $product->category->slug])) }}"
            class="hover:text-primary transition-colors">{{ $product->category->category_name }}</a>
        <span>/</span>
        <a href="{{ route(($country->country_code == 'pk' ? '' : 'country.') . 'brand.show', ($country->country_code == 'pk' ? ['brand' => $product->brand->slug, 'categorySlug' => $product->category->slug] : ['country_code' => $country->country_code, 'brand' => $product->brand->slug, 'categorySlug' => $product->category->slug])) }}"
            class="hover:text-primary transition-colors">{{ $product->brand->name }}</a>
        <span>/</span>
        <span class="text-text-main font-semibold">{{ $product->name }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
        <!-- Left Column: Images -->
        <div class="lg:col-span-5 flex flex-col gap-4">
            <div
                class="bg-white rounded-2xl p-8 flex items-center justify-center aspect-[4/3] relative overflow-hidden group border border-border-light shadow-soft">
                <img src="{{ $product->thumbnail }}" alt="{{ $product->name }}"
                    class="object-contain h-full w-auto drop-shadow-xl transition-transform duration-500 group-hover:scale-105 z-10">
            </div>

            @if($product->images->isNotEmpty())
                <div class="flex gap-3 overflow-x-auto no-scrollbar pb-2">
                    <div
                        class="w-20 h-20 rounded-lg bg-white border-2 border-primary p-2 flex-shrink-0 cursor-pointer shadow-sm">
                        <img src="{{ $product->thumbnail }}" alt="Main Image" class="w-full h-full object-contain">
                    </div>
                    @foreach($product->images->take(4) as $image)
                        <div
                            class="w-20 h-20 rounded-lg bg-white border border-border-light hover:border-text-muted p-2 flex-shrink-0 cursor-pointer transition-colors shadow-sm">
                            <img src="{{ $image->path ?? $image->url }}" alt="Gallery Image" class="w-full h-full object-contain">
                        </div>
                    @endforeach
                    @if($product->images->count() > 4)
                        <div
                            class="w-20 h-20 rounded-lg bg-white border border-border-light hover:border-text-secondary p-2 flex-shrink-0 cursor-pointer transition-colors flex items-center justify-center shadow-sm">
                            <span class="text-xs text-text-muted font-bold">+{{ $product->images->count() - 4 }}</span>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Right Column: Key Info & Highlights -->
        <div class="lg:col-span-7 flex flex-col justify-between">
            <div>
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold text-text-main mb-3 tracking-tight">{{ $product->name }}
                        </h1>
                        <div class="flex items-center gap-4 text-sm text-text-muted">
                            <span class="flex items-center gap-1"><span
                                    class="material-symbols-outlined text-[16px]">calendar_today</span> Released
                                {{ \Carbon\Carbon::parse($product->release_date)->format('Y, M d') }}</span>
                        </div>
                    </div>
                    <!-- Rating Placeholder -->
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
                    </div>
                </div>

                <!-- Key Specs Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div
                        class="bg-white border border-border-light rounded-xl p-5 flex flex-col gap-3 hover:border-primary/50 hover:shadow-md transition-all group shadow-sm">
                        <span
                            class="material-symbols-outlined text-primary text-3xl group-hover:scale-110 transition-transform">smartphone</span>
                        <div>
                            <p class="text-xs text-text-muted font-medium uppercase tracking-wide">Display</p>
                            <p class="text-text-main font-bold text-lg leading-tight">{{ $screen_size ?? 'N/A' }}</p>
                            <p class="text-xs text-text-muted mt-0.5 line-clamp-1" title="{{ $display_type }}">
                                {{ $display_type ?? '-' }}</p>
                        </div>
                    </div>
                    <div
                        class="bg-white border border-border-light rounded-xl p-5 flex flex-col gap-3 hover:border-primary/50 hover:shadow-md transition-all group shadow-sm">
                        <span
                            class="material-symbols-outlined text-primary text-3xl group-hover:scale-110 transition-transform">memory</span>
                        <div>
                            <p class="text-xs text-text-muted font-medium uppercase tracking-wide">Chipset</p>
                            <p class="text-text-main font-bold text-lg leading-tight line-clamp-2" title="{{ $chipset }}">
                                {{ $chipset ?? 'N/A' }}</p>
                            <p class="text-xs text-text-muted mt-0.5 line-clamp-1" title="{{ $ram }}">
                                {{ $ram ? $ram . ' RAM' : '-' }}</p>
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
                            <p class="text-xs text-text-muted mt-0.5">Video Support</p>
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

            <!-- Mobile Price Section -->
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
                    <button
                        class="flex-1 md:flex-none h-12 px-6 bg-primary hover:bg-primary-hover text-white rounded-lg font-bold flex items-center justify-center gap-2 transition-colors shadow-md shadow-blue-500/20">
                        <span class="material-symbols-outlined text-[20px]">shopping_cart</span>
                        Check Prices
                    </button>
                    <a href="{{ route(($country->country_code == 'pk' ? '' : 'country.') . 'compare.show', ($country->country_code == 'pk' ? $product->slug : ['country_code' => $country->country_code, 'slug' => $product->slug])) }}" class="flex-1 md::flex-none h-12 px-6 bg-white border border-gray-300 hover:border-primary hover:text-primary text-text-main rounded-lg font-bold flex items-center justify-center gap-2 transition-colors shadow-sm decoration-0">
                    <span class="material-symbols-outlined text-[20px]">compare_arrows</span>
                    Compare
                 </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Links -->
    <div
        class="sticky top-[65px] z-40 bg-white/95 backdrop-blur-sm border-b border-border-light mb-8 -mx-4 px-4 lg:-mx-8 lg:px-8 shadow-sm">
        <div class="flex gap-8 overflow-x-auto no-scrollbar justify-center">
            <a href="#specs"
                class="py-4 text-primary border-b-2 border-primary font-bold text-sm tracking-wide whitespace-nowrap">FULL
                SPECS</a>
            <a href="#reviews"
                class="py-4 text-text-muted hover:text-primary border-b-2 border-transparent hover:border-gray-200 font-medium text-sm tracking-wide transition-colors whitespace-nowrap">USER
                OPINIONS</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8 flex flex-col gap-6" id="specs">
            <!-- Full Specs Table (Iterating over attributes grouped by category if possible, currently using flat list logic mapping to groups) -->

            <!-- Network -->
            <div class="bg-white border border-border-light rounded-xl overflow-hidden shadow-card">
                <div class="bg-gray-50 border-b border-border-light px-6 py-4 flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">wifi</span>
                    <h3 class="text-lg font-bold text-text-main uppercase tracking-wide">Network & Connectivity</h3>
                </div>
                <div class="p-0">
                    @foreach(['2g_band' => '2G Bands', '3g_band' => '3G Bands', '4g_band' => '4G Bands', '5g_band' => '5G Bands', 'sim' => 'SIM', 'wlan' => 'WLAN', 'bluetooth' => 'Bluetooth', 'gps' => 'GPS', 'nfc' => 'NFC', 'radio' => 'Radio', 'usb' => 'USB'] as $key => $label)
                        @if($val = $getAttribute($key))
                            <div
                                class="grid grid-cols-1 md:grid-cols-[160px_1fr] border-b border-border-light last:border-0 hover:bg-gray-50/50 transition-colors">
                                <div class="px-6 py-3 text-sm text-text-muted font-medium md:border-r border-border-light">
                                    {{ $label }}</div>
                                <div class="px-6 py-3 text-sm text-text-main break-words">{{ $val }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Body -->
            <div class="bg-white border border-border-light rounded-xl overflow-hidden shadow-card">
                <div class="bg-gray-50 border-b border-border-light px-6 py-4 flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">smartphone</span>
                    <h3 class="text-lg font-bold text-text-main uppercase tracking-wide">Body</h3>
                </div>
                <div class="p-0">
                    @foreach(['dimensions' => 'Dimensions', 'weight' => 'Weight', 'build' => 'Build', 'colors' => 'Colors'] as $key => $label)
                        @if($val = $getAttribute($key))
                            <div
                                class="grid grid-cols-1 md:grid-cols-[160px_1fr] border-b border-border-light last:border-0 hover:bg-gray-50/50 transition-colors">
                                <div class="px-6 py-3 text-sm text-text-muted font-medium md:border-r border-border-light">
                                    {{ $label }}</div>
                                <div class="px-6 py-3 text-sm text-text-main break-words">{{ $val }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Display -->
            <div class="bg-white border border-border-light rounded-xl overflow-hidden shadow-card">
                <div class="bg-gray-50 border-b border-border-light px-6 py-4 flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">screenshot_monitor</span>
                    <h3 class="text-lg font-bold text-text-main uppercase tracking-wide">Display</h3>
                </div>
                <div class="p-0">
                    @foreach(['technology' => 'Technology', 'size' => 'Size', 'resolution' => 'Resolution', 'protection' => 'Protection', 'extra_features' => 'Extra Features'] as $key => $label)
                        @if($val = $getAttribute($key))
                            <div
                                class="grid grid-cols-1 md:grid-cols-[160px_1fr] border-b border-border-light last:border-0 hover:bg-gray-50/50 transition-colors">
                                <div class="px-6 py-3 text-sm text-text-muted font-medium md:border-r border-border-light">
                                    {{ $label }}</div>
                                <div class="px-6 py-3 text-sm text-text-main break-words">{{ $val }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Platform -->
            <div class="bg-white border border-border-light rounded-xl overflow-hidden shadow-card">
                <div class="bg-gray-50 border-b border-border-light px-6 py-4 flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">developer_board</span>
                    <h3 class="text-lg font-bold text-text-main uppercase tracking-wide">Platform</h3>
                </div>
                <div class="p-0">
                    @foreach(['os' => 'OS', 'chipset' => 'Chipset', 'cpu' => 'CPU', 'gpu' => 'GPU'] as $key => $label)
                        @if($val = $getAttribute($key))
                            <div
                                class="grid grid-cols-1 md:grid-cols-[160px_1fr] border-b border-border-light last:border-0 hover:bg-gray-50/50 transition-colors">
                                <div class="px-6 py-3 text-sm text-text-muted font-medium md:border-r border-border-light">
                                    {{ $label }}</div>
                                <div class="px-6 py-3 text-sm text-text-main break-words">{{ $val }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Memory -->
            <div class="bg-white border border-border-light rounded-xl overflow-hidden shadow-card">
                <div class="bg-gray-50 border-b border-border-light px-6 py-4 flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">memory</span>
                    <h3 class="text-lg font-bold text-text-main uppercase tracking-wide">Memory</h3>
                </div>
                <div class="p-0">
                    @foreach(['built_in' => 'Built-in', 'card' => 'Card Slot'] as $key => $label)
                        @if($val = $getAttribute($key))
                            <div
                                class="grid grid-cols-1 md:grid-cols-[160px_1fr] border-b border-border-light last:border-0 hover:bg-gray-50/50 transition-colors">
                                <div class="px-6 py-3 text-sm text-text-muted font-medium md:border-r border-border-light">
                                    {{ $label }}</div>
                                <div class="px-6 py-3 text-sm text-text-main break-words">{{ $val }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Camera -->
            <div class="bg-white border border-border-light rounded-xl overflow-hidden shadow-card">
                <div class="bg-gray-50 border-b border-border-light px-6 py-4 flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">camera</span>
                    <h3 class="text-lg font-bold text-text-main uppercase tracking-wide">Camera</h3>
                </div>
                <div class="p-0">
                    @foreach(['main' => 'Main Camera', 'features' => 'Features', 'front' => 'Selfie Camera'] as $key => $label)
                        @if($val = $getAttribute($key))
                            <div
                                class="grid grid-cols-1 md:grid-cols-[160px_1fr] border-b border-border-light last:border-0 hover:bg-gray-50/50 transition-colors">
                                <div class="px-6 py-3 text-sm text-text-muted font-medium md:border-r border-border-light">
                                    {{ $label }}</div>
                                <div class="px-6 py-3 text-sm text-text-main break-words">{{ $val }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Battery -->
            <div class="bg-white border border-border-light rounded-xl overflow-hidden shadow-card">
                <div class="bg-gray-50 border-b border-border-light px-6 py-4 flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">battery_full</span>
                    <h3 class="text-lg font-bold text-text-main uppercase tracking-wide">Battery</h3>
                </div>
                <div class="p-0">
                    @foreach(['battery' => 'Type/Capacity', 'charging' => 'Charging'] as $key => $label)
                        @if($val = $getAttribute($key))
                            <div
                                class="grid grid-cols-1 md:grid-cols-[160px_1fr] border-b border-border-light last:border-0 hover:bg-gray-50/50 transition-colors">
                                <div class="px-6 py-3 text-sm text-text-muted font-medium md:border-r border-border-light">
                                    {{ $label }}</div>
                                <div class="px-6 py-3 text-sm text-text-main break-words">{{ $val }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>

        <div class="lg:col-span-4 flex flex-col gap-8">
            <!-- Review Section (Placeholder for now as dynamic reviews structure might differ) -->
            <div class="bg-white border border-border-light rounded-xl p-6 shadow-card" id="reviews">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-text-main">Description</h3>
                </div>
                <div class="prose prose-sm max-w-none text-text-muted">
                    {!! $product->body !!}
                </div>
            </div>

            <!-- User Ratings (Static placeholder layout) -->
            <div class="bg-white border border-border-light rounded-xl p-6 shadow-card">
                <h3 class="text-lg font-bold text-text-main mb-4">User Ratings</h3>
                <!-- Add real rating logic here if available -->
                <button
                    class="w-full py-3 bg-primary hover:bg-primary-hover text-white rounded-lg font-bold transition-colors shadow-md shadow-blue-500/20">
                    Write a Review
                </button>
            </div>

            <!-- Compare With Widget -->
            <div class="bg-white border border-border-light rounded-xl p-6 shadow-card">
                <h3 class="text-lg font-bold text-text-main mb-4">Related Devices</h3>
                <div class="space-y-4">
                    @foreach($products as $related)
                        <a href="{{ route('product.show', $related->slug) }}"
                            class="flex items-center gap-3 group cursor-pointer p-2 -mx-2 rounded-lg hover:bg-gray-50 transition-colors decoration-0">
                            <img src="{{ $related->thumbnail }}" alt="{{ $related->name }}"
                                class="w-12 h-16 object-contain bg-white border border-gray-100 rounded-md p-1 shadow-sm">
                            <div>
                                <h4 class="text-sm font-bold text-text-main group-hover:text-primary transition-colors">
                                    {{ $related->name }}</h4>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection