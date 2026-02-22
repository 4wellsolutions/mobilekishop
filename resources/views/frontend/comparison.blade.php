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

    {{-- Compare Search Builder --}}
    <div class="bg-white rounded-2xl border border-border-light shadow-card p-6 md:p-8 mb-10">
        <div class="flex items-center gap-2 mb-6">
            <span class="material-symbols-outlined text-primary text-2xl">compare_arrows</span>
            <h2 class="text-xl font-bold text-text-main">Compare Devices</h2>
        </div>
        <p class="text-sm text-text-muted mb-6">Search and select 2 or 3 devices to compare side by side.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            {{-- Slot 1 --}}
            <div class="compare-slot" data-slot="0">
                <label class="block text-xs font-bold text-text-muted uppercase tracking-wider mb-2">Device 1 <span
                        class="text-red-400">*</span></label>
                <div class="relative">
                    <input type="text"
                        class="compare-search-input w-full px-4 py-3 pl-10 text-sm border-2 border-border-light rounded-xl bg-slate-50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all placeholder-text-muted"
                        placeholder="Search first device..." autocomplete="off">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-text-muted pointer-events-none">search</span>
                    <div
                        class="compare-results absolute z-50 top-full left-0 right-0 mt-1 bg-white border border-border-light rounded-xl shadow-lg max-h-64 overflow-y-auto hidden">
                    </div>
                </div>
                <div
                    class="compare-selected hidden mt-3 flex items-center gap-3 p-3 bg-primary/5 border border-primary/20 rounded-xl">
                    <img class="selected-thumb w-12 h-12 object-contain rounded-lg bg-white border border-border-light"
                        src="" alt="">
                    <span class="selected-name text-sm font-semibold text-text-main flex-1 truncate"></span>
                    <button type="button"
                        class="compare-remove p-1 rounded-lg hover:bg-red-50 text-text-muted hover:text-red-500 transition-colors">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            </div>

            {{-- Slot 2 --}}
            <div class="compare-slot" data-slot="1">
                <label class="block text-xs font-bold text-text-muted uppercase tracking-wider mb-2">Device 2 <span
                        class="text-red-400">*</span></label>
                <div class="relative">
                    <input type="text"
                        class="compare-search-input w-full px-4 py-3 pl-10 text-sm border-2 border-border-light rounded-xl bg-slate-50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all placeholder-text-muted"
                        placeholder="Search second device..." autocomplete="off">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-text-muted pointer-events-none">search</span>
                    <div
                        class="compare-results absolute z-50 top-full left-0 right-0 mt-1 bg-white border border-border-light rounded-xl shadow-lg max-h-64 overflow-y-auto hidden">
                    </div>
                </div>
                <div
                    class="compare-selected hidden mt-3 flex items-center gap-3 p-3 bg-primary/5 border border-primary/20 rounded-xl">
                    <img class="selected-thumb w-12 h-12 object-contain rounded-lg bg-white border border-border-light"
                        src="" alt="">
                    <span class="selected-name text-sm font-semibold text-text-main flex-1 truncate"></span>
                    <button type="button"
                        class="compare-remove p-1 rounded-lg hover:bg-red-50 text-text-muted hover:text-red-500 transition-colors">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            </div>

            {{-- Slot 3 (Optional) --}}
            <div class="compare-slot" data-slot="2">
                <label class="block text-xs font-bold text-text-muted uppercase tracking-wider mb-2">Device 3 <span
                        class="text-text-muted font-normal normal-case">(optional)</span></label>
                <div class="relative">
                    <input type="text"
                        class="compare-search-input w-full px-4 py-3 pl-10 text-sm border-2 border-border-light rounded-xl bg-slate-50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/10 outline-none transition-all placeholder-text-muted"
                        placeholder="Search third device..." autocomplete="off">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-text-muted pointer-events-none">search</span>
                    <div
                        class="compare-results absolute z-50 top-full left-0 right-0 mt-1 bg-white border border-border-light rounded-xl shadow-lg max-h-64 overflow-y-auto hidden">
                    </div>
                </div>
                <div
                    class="compare-selected hidden mt-3 flex items-center gap-3 p-3 bg-primary/5 border border-primary/20 rounded-xl">
                    <img class="selected-thumb w-12 h-12 object-contain rounded-lg bg-white border border-border-light"
                        src="" alt="">
                    <span class="selected-name text-sm font-semibold text-text-main flex-1 truncate"></span>
                    <button type="button"
                        class="compare-remove p-1 rounded-lg hover:bg-red-50 text-text-muted hover:text-red-500 transition-colors">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <p class="text-xs text-text-muted" id="compareHint">Select at least 2 devices to compare</p>
            <button type="button" id="compareBtn" disabled
                class="px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-xl shadow-md shadow-primary/20 hover:shadow-lg hover:shadow-primary/30 disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none transition-all duration-300 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">compare_arrows</span>
                Compare Now
            </button>
        </div>
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
                    "@@type": "BreadcrumbList", 
                    "itemListElement": [{
                        "@@type": "ListItem", 
                        "position": 1, 
                        "name": "Home",
                        "item": "{{url('/')}}"  
                    },{
                        "@@type": "ListItem", 
                        "position": 2, 
                        "name": "{{$metas->name}}"
                    }]
                }
                </script>

    <script>
    (function() {
        const API_URL = "{{ url('product/autocomplete') }}";
        const COMPARE_BASE = "{{ url('compare') }}";
        const slots = document.querySelectorAll('.compare-slot');
        const compareBtn = document.getElementById('compareBtn');
        const compareHint = document.getElementById('compareHint');
        const selected = [null, null, null]; // { slug, name, thumbnail }
        let debounceTimers = {};

        function updateCompareBtn() {
            const count = selected.filter(Boolean).length;
            compareBtn.disabled = count < 2;
            if (count >= 2) {
                compareHint.textContent = count + ' devices selected — ready to compare!';
                compareHint.classList.add('text-green-600');
                compareHint.classList.remove('text-text-muted');
            } else {
                compareHint.textContent = 'Select at least 2 devices to compare';
                compareHint.classList.remove('text-green-600');
                compareHint.classList.add('text-text-muted');
            }
        }

        slots.forEach(slot => {
            const index = parseInt(slot.dataset.slot);
            const input = slot.querySelector('.compare-search-input');
            const results = slot.querySelector('.compare-results');
            const selectedEl = slot.querySelector('.compare-selected');
            const removeBtn = slot.querySelector('.compare-remove');
            const thumbImg = slot.querySelector('.selected-thumb');
            const nameSpan = slot.querySelector('.selected-name');

            // Debounced search
            input.addEventListener('input', function() {
                const term = this.value.trim();
                clearTimeout(debounceTimers[index]);

                if (term.length < 2) {
                    results.classList.add('hidden');
                    results.innerHTML = '';
                    return;
                }

                debounceTimers[index] = setTimeout(() => {
                    fetch(API_URL + '?term=' + encodeURIComponent(term))
                        .then(r => r.json())
                        .then(products => {
                            if (!products.length) {
                                results.innerHTML = '<div class="px-4 py-3 text-sm text-text-muted text-center">No devices found</div>';
                                results.classList.remove('hidden');
                                return;
                            }
                            results.innerHTML = products.map(p => `
                                <button type="button" class="compare-result-item flex items-center gap-3 w-full px-4 py-2.5 text-left hover:bg-primary/5 transition-colors"
                                    data-slug="${p.slug}" data-name="${p.name}" data-thumb="${p.thumbnail || ''}">
                                    <img src="${p.thumbnail || '/images/placeholder.png'}" alt=""
                                        class="w-10 h-10 object-contain rounded-lg bg-slate-50 border border-border-light shrink-0"
                                        onerror="this.src='/images/placeholder.png'">
                                    <span class="text-sm font-medium text-text-main truncate">${p.name}</span>
                                </button>
                            `).join('');
                            results.classList.remove('hidden');

                            // Bind click on results
                            results.querySelectorAll('.compare-result-item').forEach(item => {
                                item.addEventListener('click', function() {
                                    const slug = this.dataset.slug;
                                    const name = this.dataset.name;
                                    const thumb = this.dataset.thumb;

                                    // Prevent selecting the same device twice
                                    if (selected.some(s => s && s.slug === slug)) {
                                        results.innerHTML = '<div class="px-4 py-3 text-sm text-amber-600 text-center font-medium">This device is already selected</div>';
                                        setTimeout(() => results.classList.add('hidden'), 1500);
                                        return;
                                    }

                                    selected[index] = { slug, name, thumbnail: thumb };
                                    thumbImg.src = thumb || '/images/placeholder.png';
                                    nameSpan.textContent = name;
                                    selectedEl.classList.remove('hidden');
                                    input.parentElement.classList.add('hidden');
                                    results.classList.add('hidden');
                                    input.value = '';
                                    updateCompareBtn();
                                });
                            });
                        })
                        .catch(() => {
                            results.innerHTML = '<div class="px-4 py-3 text-sm text-red-500 text-center">Search failed. Please try again.</div>';
                            results.classList.remove('hidden');
                        });
                }, 300);
            });

            // Focus shows existing results
            input.addEventListener('focus', function() {
                if (results.innerHTML.trim()) results.classList.remove('hidden');
            });

            // Remove selection
            removeBtn.addEventListener('click', function() {
                selected[index] = null;
                selectedEl.classList.add('hidden');
                input.parentElement.classList.remove('hidden');
                input.value = '';
                input.focus();
                updateCompareBtn();
            });
        });

        // Close dropdowns on outside click
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.compare-slot')) {
                document.querySelectorAll('.compare-results').forEach(r => r.classList.add('hidden'));
            }
        });

        // Compare button
        compareBtn.addEventListener('click', function() {
            const slugs = selected.filter(Boolean).map(s => s.slug);
            if (slugs.length >= 2) {
                window.location.href = COMPARE_BASE + '/' + slugs.join('-vs-');
            }
        });
    })();
    </script>
@endsection