@extends('layouts.frontend')

@section('title', $metas->title)
@section('description', $metas->description)
@section("canonical", $metas->canonical)

@section("content")
    <main class="max-w-[1400px] mx-auto px-4 md:px-6 lg:px-8 py-4">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="flex items-center gap-1 text-xs text-text-muted">
                <li><a href="{{ URL::to('/') }}" class="hover:text-primary no-underline transition-colors">Home</a></li>
                <li class="before:content-['/'] before:mx-1">Products</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-12 gap-4">
            {{-- Sidebar --}}
            <div class="md:col-span-1 lg:col-span-3">
                @include("frontend.sidebar_widget")
            </div>

            {{-- Main Content --}}
            <div class="md:col-span-3 lg:col-span-9">
                <h1 class="text-lg font-bold text-text-main text-center mb-3">{{ $metas->h1 }}</h1>

                {{-- Sort & Filter --}}
                <form action="" method="get" class="formFilter mb-3">
                    <input type="hidden" name="filter" value="true">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-text-main whitespace-nowrap">Sort By:</label>
                            <select name="orderby" id="sort_filter" class="select-filter px-3 py-1.5 border border-border-light rounded-lg text-sm
                                           focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                                <option value="" {{ (Request::get('orderby') == 0) ? "selected" : '' }}>Default sorting
                                </option>
                                <option value="new" {{ (Request::get('orderby') == "new") ? "selected" : '' }}>Sort by Latest
                                </option>
                                <option value="price_asc" {{ (Request::get('orderby') == "price_asc") ? "selected" : '' }}>
                                    Sort by price: low to high</option>
                                <option value="price_desc" {{ (Request::get('orderby') == "price_desc") ? "selected" : '' }}>
                                    Sort by price: high to low</option>
                            </select>
                        </div>
                        <button type="button" onclick="document.getElementById('filterPanel').classList.toggle('hidden')"
                            class="p-2 border border-border-light rounded-full hover:bg-surface-alt transition-colors">
                            <span class="material-symbols-outlined text-xl">tune</span>
                        </button>
                    </div>

                    {{-- Collapsible Filters --}}
                    <div id="filterPanel"
                        class="{{ (new \Jenssegers\Agent\Agent())->isDesktop() || Request::get('filter') ? '' : 'hidden' }}">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-3">
                            <div>
                                <label class="block text-sm font-medium text-text-main mb-1">Year</label>
                                <select class="select-filter w-full px-3 py-1.5 border border-border-light rounded-lg text-sm
                                                   focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                    name="year">
                                    <option value="">Select Year</option>
                                    @for($y = 2025; $y >= 2019; $y--)
                                        <option value="{{ $y }}" {{ (Request::get('year') == $y) ? "selected" : '' }}>{{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            @if(!isset($brand))
                                <div>
                                    <label class="block text-sm font-medium text-text-main mb-1">Brand</label>
                                    <select
                                        class="select-filter w-full px-3 py-1.5 border border-border-light rounded-lg text-sm
                                                               focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"
                                        name="brand_id">
                                        <option value="">Select Brand</option>
                                        @if($brands = App\Models\Brand::limit(20)->get())
                                            @foreach($brands as $brnd)
                                                <option value="{{ $brnd->id }}" {{ (Request::get('brand_id') == $brnd->id) ? "selected" : '' }}>{{ $brnd->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>
                </form>

                {{-- Product Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 my-2">
                    @if(!$products->isEmpty())
                        @foreach($products as $product)
                            <x-product-card :product="$product" :country="$country" />
                        @endforeach
                    @else
                        <div class="col-span-full">
                            <h3 class="text-text-muted text-center py-8">No Product Found.</h3>
                        </div>
                    @endif
                </div>

                {{-- Pagination --}}
                <div class="flex justify-center my-4">
                    {{ $products->withQueryString()->links() }}
                </div>

                {{-- Body Content --}}
                <div class="prose prose-sm max-w-none text-text-muted mt-4">
                    {!! isset($metas->body) ? $metas->body : '' !!}
                    {!! isset($brand->body) ? $brand->body : '' !!}
                </div>
            </div>
        </div>
    </main>
@endsection

@section("script")
    <script type="text/javascript">
        $(".select-filter").change(function () {
            $(".formFilter").submit();
        });
    </script>
    <script type="application/ld+json">
        {
          "@@context": "https://schema.org/", 
          "@@type": "BreadcrumbList", 
          "itemListElement": [{
            "@@type": "ListItem", 
            "position": 1, 
            "name": "Home",
            "item": "{{url('/')}}/"  
          },{
            "@@type": "ListItem", 
            "position": 2, 
            "name": "{{$metas->name}}"
          }]
        }
        </script>
@endsection