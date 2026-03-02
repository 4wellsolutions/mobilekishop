@extends('layouts.frontend')

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
                        <a href="#expert-rating"
                            class="flex items-center gap-3 bg-white px-4 py-2 rounded-xl border border-blue-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all group cursor-pointer">
                            <span class="text-3xl font-black text-blue-600 group-hover:scale-105 transition-transform">8.5</span>
                            <div class="flex flex-col leading-none">
                                <span
                                    class="text-[10px] text-text-muted uppercase font-bold tracking-wider">TechSpecs</span>
                                <span class="text-xs font-bold text-text-main">Rating</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Key Specs Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <!-- Display -->
                    <div
                        class="bg-white border border-border-light rounded-2xl p-5 flex flex-col gap-4 hover:border-primary/30 hover:shadow-lg transition-all group shadow-sm duration-300">
                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                            <span class="material-symbols-outlined text-primary text-[28px] group-hover:text-white transition-colors">smartphone</span>
                        </div>
                        <div>
                            <p class="text-[11px] text-text-muted font-bold uppercase tracking-widest mb-1">Display</p>
                            <p class="text-text-main font-extrabold text-xl leading-tight mb-1">{{ $screen_size ?? 'N/A' }}</p>
                            <p class="text-xs text-text-muted font-medium line-clamp-1" title="{{ $display_type }}">
                                {{ $display_type ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Chipset -->
                    <div
                        class="bg-white border border-border-light rounded-2xl p-5 flex flex-col gap-4 hover:border-primary/30 hover:shadow-lg transition-all group shadow-sm duration-300">
                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                            <span class="material-symbols-outlined text-primary text-[28px] group-hover:text-white transition-colors">memory</span>
                        </div>
                        <div>
                            <p class="text-[11px] text-text-muted font-bold uppercase tracking-widest mb-1">Chipset</p>
                            <p class="text-text-main font-extrabold text-xl leading-tight line-clamp-2" title="{{ $chipset }}">
                                {{ $chipset ?? 'N/A' }}</p>
                            <p class="text-xs text-text-muted font-medium mt-1" title="{{ $ram }}">
                                {{ $ram ? $ram . ' RAM' : '-' }}</p>
                        </div>
                    </div>

                    <!-- Camera -->
                    <div
                        class="bg-white border border-border-light rounded-2xl p-5 flex flex-col gap-4 hover:border-primary/30 hover:shadow-lg transition-all group shadow-sm duration-300">
                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                            <span class="material-symbols-outlined text-primary text-[28px] group-hover:text-white transition-colors">photo_camera</span>
                        </div>
                        <div>
                            <p class="text-[11px] text-text-muted font-bold uppercase tracking-widest mb-1">Camera</p>
                            <p class="text-text-main font-extrabold text-xl leading-tight line-clamp-1"
                                title="{{ $main_camera }}">{{ $main_camera ?? 'N/A' }}</p>
                            <p class="text-xs text-text-muted font-medium mt-1">Video Support</p>
                        </div>
                    </div>

                    <!-- Battery -->
                    <div
                        class="bg-white border border-border-light rounded-2xl p-5 flex flex-col gap-4 hover:border-primary/30 hover:shadow-lg transition-all group shadow-sm duration-300">
                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                            <span class="material-symbols-outlined text-primary text-[28px] group-hover:text-white transition-colors">battery_charging_full</span>
                        </div>
                        <div>
                            <p class="text-[11px] text-text-muted font-bold uppercase tracking-widest mb-1">Battery</p>
                            <p class="text-text-main font-extrabold text-xl leading-tight">{{ $battery ?? 'N/A' }}</p>
                            <p class="text-xs text-text-muted font-medium mt-1">Li-Ion</p>
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

                    <a href="{{ route(($country->country_code == 'pk' ? '' : 'country.') . 'compare.show', ($country->country_code == 'pk' ? $product->slug : ['country_code' => $country->country_code, 'slug' => $product->slug])) }}" class="flex-1 md::flex-none h-12 px-6 bg-white border border-gray-300 hover:border-primary hover:text-primary text-text-main rounded-lg font-bold flex items-center justify-center gap-2 transition-colors shadow-sm decoration-0">
                    <span class="material-symbols-outlined text-[20px]">compare_arrows</span>
                    Compare
                 </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="sticky top-[65px] z-40 bg-white/95 backdrop-blur-sm border-b border-border-light mb-8 -mx-4 px-4 lg:-mx-8 lg:px-8 shadow-sm">
        <div class="flex gap-8 overflow-x-auto no-scrollbar justify-center">
            <button onclick="switchTab('specs')" id="tab-specs"
                class="py-4 text-primary border-b-[3px] border-primary font-bold text-sm tracking-wide whitespace-nowrap transition-colors">FULL
                SPECS</button>
            <button onclick="switchTab('reviews')" id="tab-reviews"
                class="py-4 text-text-muted hover:text-primary border-b-[3px] border-transparent font-medium text-sm tracking-wide transition-colors whitespace-nowrap">USER
                OPINIONS</button>
        </div>
    </div>

    <!-- TAB: Full Specs -->
    <div id="panel-specs">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8 flex flex-col gap-6">
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
            <!-- Expert Rating -->
            <!-- Expert Rating -->
            <div class="relative overflow-hidden bg-white border border-blue-100 rounded-[28px] p-6 shadow-[0_10px_40px_-10px_rgba(37,99,235,0.08)]" id="expert-rating">
                <!-- Decorative Background -->
                <div class="absolute -top-20 -right-20 w-56 h-56 bg-blue-50/50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>

                <div class="relative z-10">
                    <!-- Title -->
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-600/20">
                            <span class="material-symbols-outlined text-[20px]">verified</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 tracking-tight">Expert Rating</h3>
                    </div>
                    
                    <!-- Score Circle ΓÇö Centered -->
                    <div class="flex justify-center mb-8">
                        <div class="relative w-40 h-40">
                            <svg class="w-full h-full transform -rotate-90">
                                <circle cx="80" cy="80" r="68" stroke="#F3F4F6" stroke-width="10" fill="transparent" />
                                <circle cx="80" cy="80" r="68" stroke="url(#gradient-score)" stroke-width="10" fill="transparent" 
                                    stroke-dasharray="427" stroke-dashoffset="{{ 427 - (427 * 8.5 / 10) }}" stroke-linecap="round" />
                                <defs>
                                    <linearGradient id="gradient-score" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="#3B82F6" />
                                        <stop offset="100%" stop-color="#6366F1" />
                                    </linearGradient>
                                </defs>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-5xl font-black text-gray-800 tracking-tighter leading-none">8.5</span>
                                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-3 py-0.5 rounded-full mt-1.5 uppercase tracking-widest">Excellent</span>
                            </div>
                        </div>
                    </div>

                    <!-- Specs Breakdown ΓÇö Full Width -->
                    <div class="flex flex-col gap-5">
                        @php
                            $specsRatings = [
                                ['label' => 'Display', 'score' => 8.7, 'icon' => 'smartphone', 'from' => 'from-blue-500', 'to' => 'to-blue-600', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
                                ['label' => 'Camera', 'score' => 8.2, 'icon' => 'photo_camera', 'from' => 'from-purple-500', 'to' => 'to-purple-600', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600'],
                                ['label' => 'Performance', 'score' => 8.9, 'icon' => 'memory', 'from' => 'from-orange-400', 'to' => 'to-orange-500', 'bg' => 'bg-orange-50', 'text' => 'text-orange-600'],
                                ['label' => 'Battery', 'score' => 8.1, 'icon' => 'battery_charging_full', 'from' => 'from-emerald-400', 'to' => 'to-emerald-500', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
                            ];
                        @endphp
                        
                        @foreach($specsRatings as $rating)
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg {{ $rating['bg'] }} flex items-center justify-center {{ $rating['text'] }}">
                                            <span class="material-symbols-outlined text-[18px]">{{ $rating['icon'] }}</span>
                                        </div>
                                        <span class="text-sm font-bold text-gray-700">{{ $rating['label'] }}</span>
                                    </div>
                                    <div class="flex items-baseline gap-0.5">
                                        <span class="text-lg font-black {{ $rating['text'] }}">{{ $rating['score'] }}</span>
                                        <span class="text-[10px] font-bold text-gray-400">/10</span>
                                    </div>
                                </div>
                                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r {{ $rating['from'] }} {{ $rating['to'] }} rounded-full" style="width: {{ $rating['score'] * 10 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>



            <!-- Store Prices Widget -->
            <div class="bg-white border border-border-light rounded-xl p-6 shadow-card" id="stores">
                <h3 class="text-lg font-bold text-text-main mb-4">Store Prices</h3>
                <div class="space-y-3">
                    @php
                        $basePrice = $price_in_pkr ?? 0;
                        $stores = [
                            ['name' => 'Amazon', 'price' => $basePrice * 1.02, 'stock' => 'In Stock', 'icon' => 'shopping_cart',
                             'link' => ($country->amazon_url && $country->amazon_tag) ? $country->amazon_url . 's?k=' . urlencode($product->name) . '&tag=' . $country->amazon_tag : '#',
                             'target' => ($country->amazon_url && $country->amazon_tag) ? '_blank' : '_self'],
                            ['name' => 'eBay', 'price' => $basePrice * 0.98, 'stock' => 'Limited Stock', 'icon' => 'sell', 'link' => '#', 'target' => '_self'],
                            ['name' => 'Walmart', 'price' => $basePrice, 'stock' => 'In Stock', 'icon' => 'storefront', 'link' => '#', 'target' => '_self'],
                        ];
                    @endphp
                    @foreach($stores as $store)
                         <div class="flex items-center justify-between p-3 border border-border-light rounded-lg hover:border-primary/30 transition-colors group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined">{{ $store['icon'] }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-text-main">{{ $store['name'] }}</p>
                                    <p class="text-[10px] text-text-muted uppercase font-bold tracking-wide {{ $store['stock'] == 'In Stock' ? 'text-green-600' : 'text-amber-600' }}">{{ $store['stock'] }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-primary">{{ $country->currency }} {{ number_format($store['price']) }}</p>
                                <a href="{{ $store['link'] ?? '#' }}" target="{{ $store['target'] ?? '_self' }}" class="text-xs text-text-muted hover:text-primary underline decoration-border-light hover:decoration-primary transition-all">View Deal</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($country && !empty($country->amazon_url) && !empty($country->amazon_tag))
                    <p class="text-xs text-slate-400 mt-3 px-1">
                        Disclosure: As an Amazon Associate, I earn from qualifying purchases.
                    </p>
                @endif
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
    </div> {{-- end panel-specs --}}

    <!-- TAB: User Opinions -->
    <div id="panel-reviews" class="hidden">
        @php
            $reviews = $product->activeReviews;
            $reviewCount = $reviews->count();
            $avgRating = $reviewCount > 0 ? round($reviews->avg('stars'), 1) : 0;
            $ratingDist = [];
            for ($s = 5; $s >= 1; $s--) {
                $count = $reviews->where('stars', $s)->count();
                $ratingDist[$s] = $reviewCount > 0 ? round(($count / $reviewCount) * 100) : 0;
            }
        @endphp

        <div class="max-w-[920px] mx-auto">
            <!-- Rating Summary -->
            <h2 class="text-[22px] font-bold text-text-main pb-3 pt-2">User Reviews</h2>
            <div class="flex flex-wrap gap-x-8 gap-y-6 pb-6">
                <div class="flex flex-col gap-2">
                    <p class="text-4xl font-black text-text-main tracking-tight">{{ $avgRating }}</p>
                    <div class="flex gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="material-symbols-outlined text-[18px] {{ $i <= round($avgRating) ? 'text-primary' : 'text-gray-300' }}" style="font-variation-settings: 'FILL' {{ $i <= round($avgRating) ? 1 : 0 }}">star</span>
                        @endfor
                    </div>
                    <p class="text-text-main text-base">{{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</p>
                </div>
                <div class="grid min-w-[200px] max-w-[400px] flex-1 grid-cols-[20px_1fr_40px] items-center gap-y-3">
                    @for($s = 5; $s >= 1; $s--)
                        <p class="text-text-main text-sm">{{ $s }}</p>
                        <div class="flex h-2 flex-1 overflow-hidden rounded-full bg-gray-200">
                            <div class="rounded-full bg-primary" style="width: {{ $ratingDist[$s] }}%"></div>
                        </div>
                        <p class="text-text-muted text-sm text-right">{{ $ratingDist[$s] }}%</p>
                    @endfor
                </div>
            </div>

            <!-- Individual Reviews -->
            <div class="flex flex-col gap-8">
                @forelse($reviews as $review)
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-base">
                                {{ strtoupper(substr($review->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <p class="text-text-main text-base font-medium">{{ $review->name ?? 'Anonymous' }}</p>
                                <p class="text-text-muted text-sm">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="material-symbols-outlined text-[20px] {{ $i <= $review->stars ? 'text-primary' : 'text-gray-300' }}" style="font-variation-settings: 'FILL' {{ $i <= $review->stars ? 1 : 0 }}">star</span>
                            @endfor
                        </div>
                        @if($review->review)
                            <p class="text-text-main text-base leading-normal">{{ $review->review }}</p>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12">
                        <span class="material-symbols-outlined text-5xl text-gray-200 mb-3 block">rate_review</span>
                        <p class="text-text-muted text-base">No reviews yet. Be the first to share your opinion!</p>
                    </div>
                @endforelse
            </div>

            <!-- Write Review Button -->
            <div class="pt-8 pb-4">
                <button onclick="openReviewModal()" class="w-full max-w-md mx-auto py-3 bg-primary hover:bg-primary-hover text-white rounded-xl font-bold transition-colors shadow-md shadow-blue-500/20 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                    Write a Review
                </button>
            </div>
        </div>
    </div>

    <!-- Comparison Section -->
    @if(isset($compares) && $compares->count() > 0)
        <div class="mt-12 mb-8">
            <h2 class="text-2xl font-bold text-text-main mb-6 tracking-tight">{{ $product->name }} Comparison</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($compares->take(6) as $compare)
                    @php
                        $url = $compare->link;
                        $countryCode = request()->segment(1);
                        $countries = App\Models\Country::pluck('country_code')->toArray();
                        if (in_array($countryCode, $countries)) {
                            $url = url("/$countryCode" . parse_url($compare->link, PHP_URL_PATH));
                        } else {
                            $url = url(parse_url($compare->link, PHP_URL_PATH));
                        }
                    @endphp
                    <a href="{{ $url }}"
                        class="group bg-white rounded-xl overflow-hidden border border-border-light hover:border-primary/50 shadow-card hover:shadow-lg transition-all duration-300 block">
                        <div class="aspect-video w-full bg-cover bg-center relative overflow-hidden">
                            <img src="{{$compare->thumbnail}}" alt="{{$compare->alt}}"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <span
                                    class="inline-block px-2 py-1 bg-primary/90 rounded text-[10px] font-bold text-white uppercase tracking-wider mb-2">Comparison</span>
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="text-base font-bold text-text-main group-hover:text-primary transition-colors line-clamp-2">
                                {{ Str::title(str_replace('-', ' ', $compare->product1 . " vs " . $compare->product2 . ($compare->product3 ? " vs " . $compare->product3 : ""))) }}
                            </h3>
                            <div class="flex items-center gap-2 mt-3 text-sm font-medium text-primary group-hover:gap-3 transition-all">
                                View Comparison <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <script>
        function switchTab(tab) {
            const specsPanel = document.getElementById('panel-specs');
            const reviewsPanel = document.getElementById('panel-reviews');
            const specsTab = document.getElementById('tab-specs');
            const reviewsTab = document.getElementById('tab-reviews');

            if (tab === 'specs') {
                specsPanel.classList.remove('hidden');
                reviewsPanel.classList.add('hidden');
                specsTab.className = 'py-4 text-primary border-b-[3px] border-primary font-bold text-sm tracking-wide whitespace-nowrap transition-colors';
                reviewsTab.className = 'py-4 text-text-muted hover:text-primary border-b-[3px] border-transparent font-medium text-sm tracking-wide transition-colors whitespace-nowrap';
            } else {
                specsPanel.classList.add('hidden');
                reviewsPanel.classList.remove('hidden');
                reviewsTab.className = 'py-4 text-primary border-b-[3px] border-primary font-bold text-sm tracking-wide whitespace-nowrap transition-colors';
                specsTab.className = 'py-4 text-text-muted hover:text-primary border-b-[3px] border-transparent font-medium text-sm tracking-wide transition-colors whitespace-nowrap';
            }
        }
    </script>

    @include('includes.review-modal')

    {{-- Sticky Mobile Bottom Bar --}}
    <div class="fixed bottom-0 left-0 right-0 z-50 lg:hidden bg-white border-t border-border-light shadow-[0_-4px_20px_rgba(0,0,0,0.1)] px-4 py-3" id="mobileBottomBarMp">
        <div class="flex items-center justify-between gap-3 max-w-[600px] mx-auto">
            <div class="flex flex-col min-w-0">
                <span class="text-xs text-text-muted font-medium">Best Price</span>
                <span class="text-xl font-bold text-text-main truncate">{{ $country->currency }} {{ $price_in_pkr ? number_format($price_in_pkr) : 'N/A' }}</span>
            </div>
            <a href="#stores"
                class="flex-shrink-0 flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-primary to-blue-600 hover:from-primary-hover hover:to-blue-700 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-500/25 transition-all active:scale-95 decoration-0">
                <span class="material-symbols-outlined text-[18px]">shopping_cart</span>
                Check Prices
            </a>
        </div>
    </div>
    <div class="h-20 lg:hidden"></div>
@endsection
