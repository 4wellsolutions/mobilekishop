@extends('layouts.frontend')

@section('title', $metas->title)
@section('description', $metas->description)
@section("keywords", "Mobiles prices, mobile specification, mobile phone features")
@section("canonical", $metas->canonical)

@section("content")

    <div class="mb-8 text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-text-main mb-3 tracking-tight">{{$metas->h1}}</h1>
        <p class="text-text-muted max-w-2xl mx-auto">Browse our latest device comparisons to see how top smartphones stack
            up against each other.</p>
    </div>

    @if(!$compares->isEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="compareList" data-next-page="2">
            @foreach($compares as $compare)
                @php
                    // Check if the URL contains a country code
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
                        <div class="absolute bottom-4 left-4 right-4 animate-in fade-in slide-in-from-bottom-2 duration-500">
                            <span
                                class="inline-block px-2 py-1 bg-primary/90 rounded text-[10px] font-bold text-white uppercase tracking-wider mb-2">Comparison</span>
                        </div>
                    </div>
                    <div class="p-5">
                        <h2 class="text-lg font-bold text-text-main group-hover:text-primary transition-colors line-clamp-2">
                            {{ Str::title(str_replace('-', ' ', $compare->product1 . " vs " . $compare->product2 . ($compare->product3 ? " vs " . $compare->product3 : ""))) }}
                        </h2>
                        <div class="flex items-center gap-2 mt-4 text-sm font-medium text-primary group-hover:gap-3 transition-all">
                            View Comparison <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Loading Spinner Placeholder (if infinite scroll is used) -->
        <div id="loadingSpinner" class="flex justify-center py-8 hidden">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-xl border border-border-light border-dashed">
            <span class="material-symbols-outlined text-6xl text-text-muted opacity-20 mb-4">compare_arrows</span>
            <h3 class="text-xl font-bold text-text-main">No Comparisons Found</h3>
            <p class="text-text-muted mt-2">Try checking back later for new comparisons.</p>
        </div>
    @endif

@endsection

@section("script")
    <script type="application/ld+json">
            {
                "@@context": "https://schema.org/", 
                "@type": "BreadcrumbList", 
                "itemListElement": [{
                    "@type": "ListItem", 
                    "position": 1, 
                    "name": "Home",
                    "item": "{{url('/')}}/"  
                },{
                    "@type": "ListItem", 
                    "position": 2, 
                    "name": "{{$metas->name}}",
                    "item": "{{$metas->canonical}}"  
                }]
            }
            </script>
@endsection