@extends('layouts.frontend')

@section('title', $metas->title)
@section('description', $metas->description)
@section("keywords","Mobiles prices, mobile specification, mobile phone features")
@section("canonical",$metas->canonical)

@section("og_graph") @stop

@section("noindex")
@if(str_contains(URL::full(), '?page='))
<meta name="robots" content="noindex">
@endif
@stop

@section("content")
    <!-- Breadcrumbs & Page Title -->
    <div class="mb-8 flex flex-col gap-4 border-b border-slate-200 pb-6 dark:border-slate-800 md:flex-row md:items-end md:justify-between">
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                <a class="hover:text-primary hover:underline" href="{{ route(($country->country_code == 'pk' ? '' : 'country.') . 'index', ($country->country_code == 'pk' ? [] : ['country_code' => $country->country_code])) }}">Home</a>
                <span class="material-symbols-outlined text-[12px]">chevron_right</span>
                @if (isset($category))
                    <a class="hover:text-primary hover:underline" href="{{ route(($country->country_code == 'pk' ? '' : 'country.') . 'category.show', array_merge(($country->country_code == 'pk' ? [] : ['country_code' => $country->country_code]), ['category' => $category->slug])) }}">
                        {{ Str::title($category->category_name) }}
                    </a>
                    <span class="material-symbols-outlined text-[12px]">chevron_right</span>
                    <span class="font-medium text-slate-900 dark:text-white">Brands</span>
                @else
                     <span class="font-medium text-slate-900 dark:text-white">All Brands</span>
                @endif
            </div>
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white md:text-4xl">{{ $metas->h1 }}</h1>
            <p class="text-slate-500 dark:text-slate-400 max-w-2xl">Browse all available brands for {{ isset($category) ? $category->category_name : 'mobile devices' }}.</p>
        </div>
    </div>

    <div class="flex flex-col gap-8 lg:flex-row">
         <!-- LEFT SIDEBAR: FILTERS -->
         <aside class="hidden lg:block w-full shrink-0 lg:w-72">
             <div class="sticky top-24 max-h-[calc(100vh-120px)] overflow-y-auto custom-scrollbar pr-2 pb-10">
                <x-filters-sidebar :category="$category ?? null" :country="$country" />
             </div>
         </aside>

        <!-- RIGHT MAIN: BRAND GRID -->
        <div class="flex-1 min-w-0">
             <div class="grid grid-cols-2 gap-4 sm:gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-5">
                @if(isset($category))
                    @if(!$brands->isEmpty())
                        @foreach($brands as $brand)
                            <!-- Corrected Route Parameters for Brand Show -->
                             <a href="{{ route(($country->country_code == 'pk' ? '' : 'country.') . 'brand.show', array_merge(($country->country_code == 'pk' ? [] : ['country_code' => $country->country_code]), ['brand' => $brand->slug, 'categorySlug' => $category->slug])) }}" 
                               class="group flex flex-col items-center justify-center rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition-all hover:shadow-lg hover:ring-primary/20 dark:bg-slate-900 dark:ring-slate-800 dark:hover:ring-primary/40">
                                <div class="mb-4 flex h-16 w-full items-center justify-center">
                                    <img src="{{$brand->thumbnail}}" alt="{{$brand->name}}" title="{{$brand->name}}" class="max-h-full max-w-full object-contain grayscale transition-all group-hover:grayscale-0 group-hover:scale-110">
                                </div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-primary">{{ $brand->name }}</h3>
                            </a>
                        @endforeach
                    @else
                         <div class="col-span-full text-center py-20">
                            <span class="material-symbols-outlined text-6xl text-slate-300 opacity-20 mb-4">domain_disabled</span>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">No Brands Found</h3>
                        </div>
                    @endif
                @else
                    @if($categories->isNotEmpty())
                        @foreach($categories as $cat)
                            <div class="col-span-full border-b border-slate-200 pb-2 mb-4 dark:border-slate-800 mt-8 first:mt-0">
                                <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $cat->category_name }}</h2>
                            </div>
                            @if($cat->brands->isNotEmpty())
                                <div class="col-span-full grid grid-cols-2 gap-4 sm:gap-6 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-5">
                                @foreach($cat->brands as $brd)
                                     <a href="{{ route(($country->country_code == 'pk' ? '' : 'country.') . 'brand.show', array_merge(($country->country_code == 'pk' ? [] : ['country_code' => $country->country_code]), ['brand' => $brd->slug, 'categorySlug' => $cat->slug])) }}" 
                                       class="group flex flex-col items-center justify-center rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200 transition-all hover:shadow-lg hover:ring-primary/20 dark:bg-slate-900 dark:ring-slate-800 dark:hover:ring-primary/40">
                                        <div class="mb-4 flex h-16 w-full items-center justify-center">
                                            <img src="{{$brd->thumbnail}}" alt="{{$brd->name}}" title="{{$brd->name}}" class="max-h-full max-w-full object-contain grayscale transition-all group-hover:grayscale-0 group-hover:scale-110">
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-primary">{{ $brd->name }}</h3>
                                    </a>
                                @endforeach
                                </div>
                            @else
                                <div class="col-span-full py-4 text-center">
                                    <p class="text-sm text-slate-500 dark:text-slate-400">No brands found for this category.</p>
                                </div>
                            @endif
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
@stop

@section("script")

@stop

@section("style")
 <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }
    </style>
@stop
