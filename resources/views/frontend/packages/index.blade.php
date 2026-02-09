@extends('layouts.techspec')

@section('title', $metas->title)
@section('description', $metas->description)
@section('canonical', $metas->canonical)

@section("content")
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-6">
        <a class="hover:text-primary hover:underline" href="{{ url('/') }}">Home</a>
        <span class="material-symbols-outlined text-[12px]">chevron_right</span>
        <span class="font-medium text-slate-900 dark:text-white">Packages</span>
    </div>

    <div class="flex flex-col gap-8 lg:flex-row">
        <!-- Sidebar -->
        <aside class="hidden lg:block w-full shrink-0 lg:w-72">
            <div class="sticky top-24 max-h-[calc(100vh-120px)] overflow-y-auto pr-2 pb-10">
                @include("includes.sidebar.packages")
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-6 text-center uppercase">{{ $metas->h1 }}</h1>

            @if($networks = App\Models\Package::distinct('filter_network')->pluck('filter_network'))
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach($networks as $network)
                        <a href="{{ route('package.network.index', $network) }}"
                            class="group flex flex-col items-center justify-center p-6 bg-white rounded-xl shadow-sm ring-1 ring-slate-200 hover:shadow-lg hover:ring-primary/20 transition-all dark:bg-slate-900 dark:ring-slate-800">
                            <img src="{{ URL::to('/images/packages/' . $network . '.png') }}"
                                class="h-16 w-auto object-contain transition-transform group-hover:scale-110" alt="{{ $network }}">
                            <span
                                class="mt-3 text-sm font-bold text-slate-700 dark:text-slate-300">{{ Str::title($network) }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection