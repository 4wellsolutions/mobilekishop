{{-- All data prepared by App\View\Components\ProductCard --}}

@if($variant === 'featured')
    {{-- ═══════════════════════════════════════════════════
    FEATURED CARD — Horizontal layout for homepage
    ═══════════════════════════════════════════════════ --}}
    <div
        class="bg-surface-card rounded-xl overflow-hidden border border-border-light group hover:border-primary/50 transition-all duration-300 hover:shadow-lg hover:shadow-primary/5 flex flex-col">
        <a href="{{ $productUrl }}"
            class="relative block w-full aspect-video bg-gradient-to-tr from-slate-50 to-white overflow-hidden">
            <img src="{{ $product->thumbnail }}" alt="{{ $productName }}"
                class="absolute inset-0 w-full h-full object-contain p-4" loading="lazy" />
            @if($isNew)
                <div
                    class="absolute top-3 left-3 bg-primary/90 text-white px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide shadow-sm z-10">
                    New</div>
            @endif
            <div
                class="absolute top-3 right-3 bg-white/90 backdrop-blur-md px-2 py-1 rounded text-xs font-bold text-slate-900 shadow-sm z-10">
                {{ optional($product->category)->category_name }}
            </div>
        </a>
        <div class="p-5 flex flex-col flex-1">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <h3 class="text-xl font-bold text-text-main group-hover:text-primary transition-colors">
                        <a href="{{ $productUrl }}" class="no-underline text-inherit">{{ $productName }}</a>
                    </h3>
                    <p class="text-sm text-text-muted mt-1">Released
                        {{ \Carbon\Carbon::parse($product->release_date)->format('M Y') }}
                    </p>
                </div>
                @if($price > 0)
                    <span
                        class="bg-primary/10 text-primary text-sm font-bold px-3 py-1 rounded-full whitespace-nowrap">{{ $country->currency }}
                        {{ number_format($price) }}</span>
                @endif
            </div>

            {{-- Specs --}}
            @if($screen || $chipset || $camera || $battery)
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 py-3 border-y border-slate-100 my-3">
                    @if($screen)
                        <div class="flex flex-col items-center gap-1 text-center">
                            <span class="material-symbols-outlined text-text-muted text-[20px]">smartphone</span>
                            <span
                                class="text-xs text-text-main font-medium truncate max-w-full">{{ Str::limit($screen, 15) }}</span>
                        </div>
                    @endif
                    @if($chipset)
                        <div class="flex flex-col items-center gap-1 text-center border-l border-slate-100">
                            <span class="material-symbols-outlined text-text-muted text-[20px]">memory</span>
                            <span
                                class="text-xs text-text-main font-medium truncate max-w-full">{{ Str::limit($chipset, 15) }}</span>
                        </div>
                    @endif
                    @if($camera)
                        <div class="flex flex-col items-center gap-1 text-center border-l border-slate-100">
                            <span class="material-symbols-outlined text-text-muted text-[20px]">photo_camera</span>
                            <span
                                class="text-xs text-text-main font-medium truncate max-w-full">{{ Str::limit($camera, 12) }}</span>
                        </div>
                    @endif
                    @if($battery)
                        <div class="flex flex-col items-center gap-1 text-center border-l border-slate-100">
                            <span class="material-symbols-outlined text-text-muted text-[20px]">battery_full</span>
                            <span
                                class="text-xs text-text-main font-medium truncate max-w-full">{{ Str::limit($battery, 10) }}</span>
                        </div>
                    @endif
                </div>
            @endif

            <a href="{{ $productUrl }}"
                class="w-full mt-auto bg-slate-50 hover:bg-slate-100 text-text-main hover:text-primary text-sm font-semibold py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2 border border-slate-100 no-underline">
                View Full Specs
            </a>
        </div>
    </div>

@else
    {{-- ═══════════════════════════════════════════════════
    GRID CARD — Vertical layout for listing pages
    ═══════════════════════════════════════════════════ --}}
    <div
        class="group relative flex flex-col rounded-xl sm:rounded-2xl bg-white p-3 sm:p-5 shadow-sm ring-1 ring-slate-200 transition-all hover:shadow-xl hover:ring-primary/20 dark:bg-slate-900 dark:ring-slate-800 dark:hover:ring-primary/40">
        {{-- Badge --}}
        @if($isNew)
            <div class="absolute left-2 sm:left-5 top-2 sm:top-5 z-10 flex flex-col gap-2">
                <span
                    class="inline-flex items-center rounded bg-primary/10 px-1.5 sm:px-2 py-0.5 sm:py-1 text-[8px] sm:text-[10px] font-bold uppercase tracking-wide text-primary">New</span>
            </div>
        @endif

        {{-- Image --}}
        <a href="{{ $productUrl }}"
            class="relative mb-3 sm:mb-6 flex h-36 sm:h-64 items-center justify-center overflow-hidden rounded-lg sm:rounded-xl bg-gradient-to-tr from-slate-100 to-white dark:from-slate-800 dark:to-slate-900/50">
            <img src="{{ $product->thumbnail }}" alt="{{ $productName }}"
                class="h-32 sm:h-56 w-auto object-contain transition-transform duration-300 group-hover:scale-105"
                loading="lazy" />
        </a>

        {{-- Content --}}
        <div class="mb-2 sm:mb-4">
            <h3 class="mb-1 text-sm sm:text-lg font-bold text-slate-900 dark:text-white leading-tight">
                <a class="after:absolute after:inset-0" href="{{ $productUrl }}">{{ $productName }}</a>
            </h3>
            <div class="flex items-center gap-1">
                <div class="flex text-yellow-400 text-[12px] sm:text-[16px]">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $product->avg_rating) <span
                            class="material-symbols-outlined text-[10px] sm:text-[16px] fill-current">star</span>
                        @elseif($i > $product->avg_rating && $i < $product->avg_rating + 1) <span
                            class="material-symbols-outlined text-[10px] sm:text-[16px] fill-current">star_half</span>
                        @else <span
                            class="material-symbols-outlined text-[10px] sm:text-[16px] fill-current text-slate-300">star</span>
                        @endif
                    @endfor
                </div>
                <span
                    class="text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium">({{ number_format($product->avg_rating, 1) }})</span>
            </div>
        </div>

        {{-- Key Specs --}}
        @if($screen || $chipset || $camera || $battery)
            <div
                class="mb-3 sm:mb-6 grid grid-cols-1 sm:grid-cols-2 gap-y-1 sm:gap-y-3 gap-x-2 border-y border-slate-100 py-2 sm:py-4 dark:border-slate-800">
                @if($screen)
                    <div class="flex items-center gap-2 text-[10px] sm:text-xs font-medium text-slate-600 dark:text-slate-400">
                        <span class="material-symbols-outlined text-[14px] sm:text-[16px] text-slate-400">smartphone</span>
                        <span class="truncate">{{ $screen }}</span>
                    </div>
                @endif
                @if($chipset)
                    <div class="hidden sm:flex items-center gap-2 text-xs font-medium text-slate-600 dark:text-slate-400">
                        <span class="material-symbols-outlined text-[16px] text-slate-400">memory</span>
                        <span class="truncate">{{ Str::limit($chipset, 15) }}</span>
                    </div>
                @endif
                @if($camera)
                    <div class="flex items-center gap-2 text-[10px] sm:text-xs font-medium text-slate-600 dark:text-slate-400">
                        <span class="material-symbols-outlined text-[14px] sm:text-[16px] text-slate-400">photo_camera</span>
                        <span class="truncate">{{ Str::limit($camera, 12) }}</span>
                    </div>
                @endif
                @if($battery)
                    <div class="hidden sm:flex items-center gap-2 text-xs font-medium text-slate-600 dark:text-slate-400">
                        <span class="material-symbols-outlined text-[16px] text-slate-400">battery_full</span>
                        <span class="truncate">{{ Str::limit($battery, 10) }}</span>
                    </div>
                @endif
            </div>
        @endif

        {{-- Footer --}}
        <div class="mt-auto flex flex-col sm:flex-row sm:items-end justify-between gap-2 sm:gap-0 relative z-20">
            <div>
                <p class="text-[9px] sm:text-[10px] font-bold uppercase text-slate-400">Price</p>
                @if($price > 0)
                    <p class="text-base sm:text-xl font-bold text-primary">{{ $country->currency }} {{ number_format($price) }}
                    </p>
                @else
                    <p class="text-xs sm:text-sm font-bold text-slate-500">Coming Soon</p>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <label
                    class="flex cursor-pointer items-center gap-1 sm:gap-2 rounded-lg border border-slate-200 bg-slate-50 px-1.5 py-1 sm:px-2 sm:py-1.5 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700">
                    <input type="checkbox" class="size-3 sm:size-4 rounded border-slate-300 text-primary focus:ring-primary"
                        onclick="toggleCompare('{{ $product->slug }}')" id="compare-{{ $product->slug }}">
                    <span class="text-[10px] sm:text-xs font-bold text-slate-600 dark:text-slate-300">Compare</span>
                </label>
            </div>
        </div>
    </div>
@endif