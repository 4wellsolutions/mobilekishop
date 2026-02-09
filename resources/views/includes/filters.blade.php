<form action="" method="get" class="formFilter mb-3">
    <input type="hidden" name="filter" value="true">
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <label class="text-sm font-medium text-text-main whitespace-nowrap">Sort By:</label>
            <select name="orderby" id="sort_filter" class="select-filter px-3 py-1.5 border border-border-light rounded-lg text-sm
                       focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                <option value="" selected="selected" {{ (Request::get('orderby') == 0) ? "selected" : '' }}>Default
                    sorting</option>
                <option value="new" {{ (Request::get('orderby') == "new") ? "selected" : '' }}>Sort by Latest</option>
                <option value="price_asc" {{ (Request::get('orderby') == "price_asc") ? "selected" : '' }}>Sort by price:
                    low to high</option>
                <option value="price_desc" {{ (Request::get('orderby') == "price_desc") ? "selected" : '' }}>Sort by
                    price: high to low</option>
            </select>
        </div>
        <button type="button" onclick="document.getElementById('filterPanel').classList.toggle('hidden')"
            class="p-2 border border-border-light rounded-full hover:bg-surface-alt transition-colors">
            <span class="material-symbols-outlined text-xl">tune</span>
        </button>
    </div>
    <div id="filterPanel" class="hidden">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-3">
            <div>
                <label class="block text-sm font-medium text-text-main mb-1">RAM</label>
                <select class="select-filter w-full px-3 py-1.5 border border-border-light rounded-lg text-sm
                               focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" name="ram_in_gb">
                    <option value="">Select RAM</option>
                    <option value="2" {{ (Request::get('ram_in_gb') == 2) ? "selected" : '' }}>2GB</option>
                    <option value="4" {{ (Request::get('ram_in_gb') == 4) ? "selected" : '' }}>4GB</option>
                    <option value="8" {{ (Request::get('ram_in_gb') == 8) ? "selected" : '' }}>8GB</option>
                    <option value="12" {{ (Request::get('ram_in_gb') == 12) ? "selected" : '' }}>12GB</option>
                    <option value="16" {{ (Request::get('ram_in_gb') == 16) ? "selected" : '' }}>16GB</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-text-main mb-1">Storage</label>
                <select class="select-filter w-full px-3 py-1.5 border border-border-light rounded-lg text-sm
                               focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" name="rom_in_gb">
                    <option value="">Select ROM</option>
                    <option value="32" {{ (Request::get('rom_in_gb') == 32) ? "selected" : '' }}>32GB</option>
                    <option value="64" {{ (Request::get('rom_in_gb') == 64) ? "selected" : '' }}>64GB</option>
                    <option value="128" {{ (Request::get('rom_in_gb') == 128) ? "selected" : '' }}>128GB</option>
                    <option value="256" {{ (Request::get('rom_in_gb') == 256) ? "selected" : '' }}>256GB</option>
                    <option value="512" {{ (Request::get('rom_in_gb') == 512) ? "selected" : '' }}>512GB</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-text-main mb-1">Camera</label>
                <select class="select-filter w-full px-3 py-1.5 border border-border-light rounded-lg text-sm
                               focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" name="pixels">
                    <option value="">Select Camera</option>
                    <option value="12" {{ (Request::get('pixels') == 12) ? "selected" : '' }}>12MP</option>
                    <option value="13" {{ (Request::get('pixels') == 13) ? "selected" : '' }}>13MP</option>
                    <option value="16" {{ (Request::get('pixels') == 16) ? "selected" : '' }}>16MP</option>
                    <option value="24" {{ (Request::get('pixels') == 24) ? "selected" : '' }}>24MP</option>
                    <option value="48" {{ (Request::get('pixels') == 48) ? "selected" : '' }}>48MP</option>
                    <option value="64" {{ (Request::get('pixels') == 64) ? "selected" : '' }}>64MP</option>
                    <option value="108" {{ (Request::get('pixels') == 108) ? "selected" : '' }}>108MP</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-text-main mb-1">Year</label>
                <select class="select-filter w-full px-3 py-1.5 border border-border-light rounded-lg text-sm
                               focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" name="year">
                    <option value="">Select Year</option>
                    @for($y = 2025; $y >= 2019; $y--)
                        <option value="{{ $y }}" {{ (Request::get('year') == $y) ? "selected" : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            @if(!isset($brand))
                <div>
                    <label class="block text-sm font-medium text-text-main mb-1">Brand</label>
                    <select class="select-filter w-full px-3 py-1.5 border border-border-light rounded-lg text-sm
                                   focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" name="brand_id">
                        <option value="">Select Brand</option>
                        @if($brands = App\Models\Brand::limit(20)->get())
                            @foreach($brands as $brnd)
                                <option value="{{ $brnd->id }}" {{ (Request::get('brand_id') == $brnd->id) ? "selected" : '' }}>
                                    {{ $brnd->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            @endif
            <div>
                <label class="block text-sm font-medium text-text-main mb-1">Network Band</label>
                <select class="select-filter w-full px-3 py-1.5 border border-border-light rounded-lg text-sm
                               focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                    name="network_band">
                    <option value="">Select Network</option>
                    <option value="4g_band" {{ (Request::get('network_band') == "4g_band") ? "selected" : '' }}>4G Band
                    </option>
                    <option value="5g_band" {{ (Request::get('network_band') == "5g_band") ? "selected" : '' }}>5G Band
                    </option>
                </select>
            </div>
        </div>
    </div>
</form>