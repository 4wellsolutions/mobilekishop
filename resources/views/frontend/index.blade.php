@extends('layouts.frontend')

@section('title', $metas->title)
@section('description', $metas->description)
@section('canonical', \URL::full())

@php
    $comparisonRoute = ($country->country_code == 'pk') ? route('comparison') : route('country.comparison', ['country_code' => $country->country_code]);
@endphp

@section('content')
    <section class="@container rounded-2xl overflow-hidden relative group shadow-lg">
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent z-10 pointer-events-none">
        </div>
        <div class="w-full h-[480px] bg-center bg-cover bg-no-repeat transition-transform duration-700 group-hover:scale-105"
            data-alt="Close up of a premium titanium smartphone in dark lighting"
            style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCcMzxbmQbh563cotBN1pK9fZLd2903kYM532P0HLO1yyLJ32jIQRBLeCSr01W_EMQ5kvfEDbtCbN9u9Xt2pc404coMRoDcrbmL1BEC_JypI-G-HL3YClYBGomzAQndKjg9jtMlSkqZGJ2_Vxn1KEQ3JtM8hNi1SSLqfpYjA72PqZQBTYBrafRRx4D-Cm25M2tTXebqeguIX-jRaCp5xtvBdu2ceUPhCqnjV1oG2JOaGDZMTEVErNh6m4FByQ40gNt5OFbgfnKFsczM');">
        </div>
        <div class="absolute inset-0 z-20 flex flex-col justify-center px-8 md:px-12 lg:px-16 max-w-3xl">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/20 border border-primary/30 w-fit mb-4 backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                <span class="text-xs font-bold text-primary uppercase tracking-wider">Featured Review</span>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-[1.1] mb-4 tracking-tight">
                iPhone 16 Pro Max
            </h1>
            <p class="text-lg md:text-xl text-gray-200 mb-8 max-w-xl font-normal leading-relaxed">
                The Titanium Titan. Experience the A18 Pro chip and new camera control features in our in-depth technical
                analysis.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="#"
                    class="bg-primary hover:bg-primary-hover text-white px-8 py-3.5 rounded-lg font-bold text-sm tracking-wide transition-colors flex items-center gap-2 shadow-lg shadow-primary/25 decoration-0">
                    Read Full Review
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
                <a href="{{ $comparisonRoute }}"
                    class="bg-white/10 hover:bg-white/20 backdrop-blur-md text-white px-8 py-3.5 rounded-lg font-bold text-sm tracking-wide transition-colors border border-white/20 flex items-center gap-2 decoration-0">
                    <span class="material-symbols-outlined text-sm">compare_arrows</span>
                    Compare Specs
                </a>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <div class="lg:col-span-8 space-y-8">
            @foreach ($categories as $category)
                @if ($category->latest_products->isNotEmpty())
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-text-main tracking-tight flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary">smartphone</span>
                                Latest {{ $category->category_name }}
                            </h2>
                            <div class="flex gap-2">
                                <a href="{{ route(($country->country_code == 'pk' ? '' : 'country.') . 'category.show', ($country->country_code == 'pk' ? $category->slug : ['country_code' => $country->country_code, 'category' => $category->slug])) }}"
                                    class="text-sm font-medium text-primary hover:text-primary-hover transition-colors">View All</a>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @foreach ($category->latest_products as $product)
                                <x-product-card :product="$product" :country="$country" variant="featured" />
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            <div class="row">
                @if($page = App\Models\Page::whereSlug(\Request::fullUrl())->first())
                    <div class="prose max-w-none">
                        {!! $page->body !!}
                    </div>
                @endif
            </div>
        </div>

        <aside class="lg:col-span-4 space-y-8">
            <div class="bg-surface-card rounded-xl p-6 border border-border-light shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-text-main flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">trending_up</span>
                        Trending Now
                    </h3>
                    <span class="text-xs text-text-muted uppercase tracking-wider font-semibold">Weekly</span>
                </div>
                <!-- Trending items placeholder - would need logic to fetch popular items -->
                <div class="space-y-1">
                    @php $count = 1; @endphp
                    @foreach($categories->first()->latest_products->take(5) as $product)
                        <a href="{{ route(($country->country_code == 'pk' ? '' : 'country.') . 'product.show', ($country->country_code == 'pk' ? $product->slug : ['country_code' => $country->country_code, 'product' => $product->slug])) }}"
                            class="flex items-center gap-4 p-3 rounded-lg hover:bg-surface-hover group transition-colors">
                            <span
                                class="text-2xl font-black text-slate-200 group-hover:text-primary transition-colors">0{{$count++}}</span>
                            <div class="flex-1">
                                <h4 class="text-text-main font-bold text-sm">{{ $product->name }}</h4>
                                <p class="text-xs text-text-muted">High interest</p>
                            </div>
                            <span class="material-symbols-outlined text-green-500 text-sm">arrow_upward</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="bg-surface-card rounded-xl p-6 border border-border-light shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-text-main flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">compare</span>
                        Popular Battles
                    </h3>
                </div>
                <div class="flex flex-col gap-4">
                    <!-- Comparisons Placeholder -->
                    <div class="bg-surface-hover rounded-lg p-4 border border-border-light">
                        <div class="text-center mb-3">
                            <p class="text-text-main text-sm font-bold">Compare Devices</p>
                            <p class="text-xs text-text-muted">Find the best device for you</p>
                        </div>
                        <a href="{{ $comparisonRoute }}"
                            class="w-full block text-center bg-white hover:bg-white shadow-sm hover:shadow text-primary text-xs font-bold py-2 rounded uppercase tracking-wider transition-all decoration-0">
                            Go to Comparison Tool
                        </a>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-primary to-blue-600 rounded-xl p-6 text-center text-white shadow-lg shadow-primary/20">
                <div
                    class="size-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                    <span class="material-symbols-outlined">mail</span>
                </div>
                <h4 class="font-bold text-lg mb-2">MobileKiShop Weekly</h4>
                <p class="text-sm text-blue-100 mb-4">Get the latest reviews and spec leaks delivered to your inbox.</p>
                <button
                    class="w-full bg-white text-primary font-bold py-2 rounded-lg text-sm hover:bg-blue-50 transition-colors shadow-sm">
                    Subscribe
                </button>
            </div>
        </aside>
    </div>
@endsection