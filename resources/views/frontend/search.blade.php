@extends('layouts.frontend')

@section('title', $metas->title)
@section('description', $metas->description)
@section("canonical", $metas->canonical)

@section("content")
  <!-- Page Header -->
  <div
    class="hidden lg:flex mb-8 flex-col gap-4 border-b border-slate-200 pb-6 dark:border-slate-800 md:flex-row md:items-end md:justify-between">
    <div class="flex flex-col gap-2">
      <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
        <a class="hover:text-primary hover:underline" href="{{ url('/') }}">Home</a>
        <span class="material-symbols-outlined text-[12px]">chevron_right</span>
        <span class="font-medium text-slate-900 dark:text-white">{{ $metas->name ?? 'Search Results' }}</span>
      </div>
      <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white md:text-4xl">{{ $metas->h1 }}</h1>
    </div>
  </div>

  <!-- Mobile Header -->
  <div class="lg:hidden mb-4">
    <h1 class="text-xl font-black tracking-tight text-slate-900 dark:text-white">{{ $metas->h1 }}</h1>
  </div>

  <!-- Sorting Bar -->
  <div
    class="hidden lg:flex mb-6 flex-wrap items-center justify-between gap-4 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-800">
    <div class="flex items-center gap-2">
      <span class="text-sm font-medium text-slate-500">Showing</span>
      <span class="text-sm font-bold text-slate-900">{{ $products->count() }}</span>
      <span class="text-sm font-medium text-slate-500">results</span>
    </div>
    <div class="flex items-center gap-4">
      <div class="flex items-center gap-2">
        <span class="text-sm font-medium text-slate-500">Sort by:</span>
        <div class="relative">
          <select
            class="appearance-none rounded-lg border-none bg-slate-50 py-1.5 pl-3 pr-8 text-sm font-bold text-slate-900 shadow-sm ring-1 ring-slate-200 focus:ring-2 focus:ring-primary"
            onchange="window.location.href=this.value">
            <option value="{{ request()->fullUrlWithQuery(['orderby' => '']) }}">Relevance</option>
            <option value="{{ request()->fullUrlWithQuery(['orderby' => 'new']) }}" {{ request('orderby') == 'new' ? 'selected' : '' }}>Newest</option>
            <option value="{{ request()->fullUrlWithQuery(['orderby' => 'price_asc']) }}" {{ request('orderby') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
            <option value="{{ request()->fullUrlWithQuery(['orderby' => 'price_desc']) }}" {{ request('orderby') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
          </select>
          <span
            class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-[18px] text-slate-500">expand_more</span>
        </div>
      </div>
      <div class="h-6 w-px bg-slate-200"></div>
      <div class="flex items-center rounded-lg bg-slate-50 p-1 shadow-sm ring-1 ring-slate-200">
        <button id="btnGrid" onclick="switchView('grid')" class="rounded p-1 text-slate-900 shadow-sm bg-white">
          <span class="material-symbols-outlined text-[20px]">grid_view</span>
        </button>
        <button id="btnList" onclick="switchView('list')" class="rounded p-1 text-slate-400 hover:text-primary">
          <span class="material-symbols-outlined text-[20px]">view_list</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Product Grid -->
  <div class="grid grid-cols-2 gap-3 sm:gap-6 lg:grid-cols-3 xl:grid-cols-4" id="productList" data-next-page="2">
    @if(!$products->isEmpty())
      @foreach($products as $product)
        <x-product-card :product="$product" :country="$country" />
      @endforeach
    @else
      <div class="col-span-full text-center py-20">
        <span class="material-symbols-outlined text-6xl text-slate-300 opacity-20 mb-4">search_off</span>
        <h3 class="text-xl font-bold text-slate-900">No Results Found</h3>
        <p class="text-slate-500 mt-2">Try a different search term or browse categories.</p>
        @include('includes.search-form')
      </div>
    @endif
  </div>

  <div id="dynamicContentEnd"></div>
  <div id="loadingSpinner" class="text-center py-8" style="display: none;">
    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto"></div>
  </div>
@endsection

@section("style")
  <style>
    #productList.list-view {
      grid-template-columns: 1fr !important;
      gap: 0.75rem;
    }

    #productList.list-view>div {
      display: grid !important;
      grid-template-columns: 140px 1fr !important;
      grid-template-rows: auto auto auto !important;
      padding: 1rem 1.25rem !important;
      gap: 0 1.25rem;
      align-items: start;
    }

    #productList.list-view>div>a:first-of-type {
      grid-column: 1;
      grid-row: 1 / -1;
      height: 130px !important;
      width: 140px;
      margin-bottom: 0 !important;
      border-radius: 0.75rem;
      align-self: center;
    }

    #productList.list-view>div>a:first-of-type img {
      height: 110px !important;
    }

    #productList.list-view>div>.absolute {
      position: absolute;
    }

    #productList.list-view>div>div:nth-child(3) {
      grid-column: 2;
      grid-row: 1;
      margin-bottom: 0.25rem !important;
    }

    #productList.list-view>div>div:nth-child(4) {
      grid-column: 2;
      grid-row: 2;
      display: flex !important;
      gap: 1.25rem;
      border-top: none !important;
      border-bottom: none !important;
      padding: 0.5rem 0 !important;
      margin-bottom: 0.25rem !important;
    }

    #productList.list-view>div>div:nth-child(4)>div {
      display: flex !important;
    }

    #productList.list-view>div>div:nth-child(5) {
      grid-column: 2;
      grid-row: 3;
      flex-direction: row !important;
      align-items: center !important;
      justify-content: space-between;
      margin-top: 0 !important;
    }
  </style>
@endsection

@section("script")
  <script>
    function switchView(mode) {
      const grid = document.getElementById('productList');
      const btnGrid = document.getElementById('btnGrid');
      const btnList = document.getElementById('btnList');
      const activeClass = 'rounded p-1 text-slate-900 shadow-sm bg-white';
      const inactiveClass = 'rounded p-1 text-slate-400 hover:text-primary';
      if (mode === 'list') { grid.classList.add('list-view'); btnList.className = activeClass; btnGrid.className = inactiveClass; }
      else { grid.classList.remove('list-view'); btnGrid.className = activeClass; btnList.className = inactiveClass; }
      localStorage.setItem('viewMode', mode);
    }
    document.addEventListener('DOMContentLoaded', function () {
      const saved = localStorage.getItem('viewMode');
      if (saved === 'list') switchView('list');
    });
  </script>

  <script type="application/ld+json">
              {
                "@@context": "https://schema.org/",
                "@type": "BreadcrumbList",
                "itemListElement": [{
                  "@type": "ListItem",
                  "position": 1,
                  "name": "Home",
                  "item": "{{ url('/') }}/"
                },{
                  "@type": "ListItem",
                  "position": 2,
                  "name": "{{ $metas->name }}",
                  "item": "{{ $metas->canonical }}"
                }]
              }
              </script>
@endsection