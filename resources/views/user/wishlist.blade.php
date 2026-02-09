@extends('layouts.frontend')

@section('title', 'Wishlist - MKS')
@section('description', 'View and manage your wishlist items.')

@section("content")
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-6">
        <a class="hover:text-primary hover:underline" href="{{ url('/') }}">Home</a>
        <span class="material-symbols-outlined text-[12px]">chevron_right</span>
        <a class="hover:text-primary hover:underline" href="{{ route('user.index') }}">My Account</a>
        <span class="material-symbols-outlined text-[12px]">chevron_right</span>
        <span class="font-medium text-slate-900 dark:text-white">Wishlist</span>
    </div>

    <div class="flex flex-col gap-8 lg:flex-row">
        <!-- Sidebar -->
        <aside class="w-full shrink-0 lg:w-64">
            @include("includes.user-sidebar")
        </aside>

        <!-- Main Content -->
        <div class="flex-1 min-w-0">
            @include("includes.info-bar")

            <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">My Wishlist</h1>

            @if(isset($wishlists) && $wishlists->isNotEmpty())
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($wishlists as $wishlist)
                        <div
                            class="group relative flex flex-col rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 transition-all hover:shadow-lg dark:bg-slate-900 dark:ring-slate-800">
                            <a href="{{ route('product.show', [$wishlist->product->brand->slug, $wishlist->product->slug]) }}"
                                class="relative mb-4 flex h-40 items-center justify-center overflow-hidden rounded-lg bg-gradient-to-tr from-slate-100 to-white dark:from-slate-800 dark:to-slate-900/50">
                                <img src="{{ $wishlist->product->thumbnail }}" alt="{{ $wishlist->product->slug }}"
                                    class="h-36 w-auto object-contain transition-transform duration-300 group-hover:scale-105"
                                    loading="lazy" />
                            </a>
                            <div class="flex items-center justify-between mb-1">
                                <a href="{{ route('brand.show', $wishlist->product->brand->slug) }}"
                                    class="text-xs text-primary font-medium hover:underline relative z-10">{{ $wishlist->product->brand->name }}</a>
                                <a href="{{ route('user.wishlist.delete', $wishlist->id) }}"
                                    class="text-slate-400 hover:text-red-500 transition-colors relative z-10"
                                    onclick="return confirm('Remove from wishlist?')">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </a>
                            </div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white leading-tight mb-2">
                                <a href="{{ route('product.show', [$wishlist->product->brand->slug, $wishlist->product->slug]) }}"
                                    class="after:absolute after:inset-0">{{ Str::title($wishlist->product->name) }}</a>
                            </h2>
                            <p class="mt-auto text-base font-bold text-primary">
                                Rs.{{ number_format($wishlist->product->price_in_pkr) }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div
                    class="text-center py-20 bg-white rounded-xl shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-800">
                    <span class="material-symbols-outlined text-6xl text-slate-300 mb-4">favorite</span>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Your Wishlist is Empty</h3>
                    <p class="text-slate-500 mt-2">Browse products and add items to your wishlist.</p>
                </div>
            @endif
        </div>
    </div>
@endsection