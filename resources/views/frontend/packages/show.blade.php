@extends('layouts.frontend')

@section('title', $package->meta_title)
@section('description', $package->meta_description)
@section('canonical', URL::to('/packages/' . $package->filter_network . '/' . $package->slug))

@section("content")
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-6">
        <a class="hover:text-primary hover:underline" href="{{ url('/') }}">Home</a>
        <span class="material-symbols-outlined text-[12px]">chevron_right</span>
        <a class="hover:text-primary hover:underline" href="{{ url('/packages') }}">Packages</a>
        <span class="material-symbols-outlined text-[12px]">chevron_right</span>
        <a class="hover:text-primary hover:underline"
            href="{{ route('package.network.index', $package->filter_network) }}">{{ Str::title($package->filter_network) }}</a>
        <span class="material-symbols-outlined text-[12px]">chevron_right</span>
        <span class="font-medium text-slate-900 dark:text-white">{{ $package->name }}</span>
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
            <div
                class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden dark:bg-slate-900 dark:ring-slate-800">
                <!-- Header -->
                <div
                    class="flex flex-col md:flex-row items-center gap-6 p-6 border-b border-slate-200 dark:border-slate-800">
                    <div class="shrink-0">
                        <img src="{{ URL::to('/images/packages/' . $package->filter_network . '.png') }}" width="200"
                            height="85" class="h-20 w-auto object-contain" alt="{{ $package->filter_network }}">
                    </div>
                    <div class="text-center md:text-left flex-1">
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white uppercase mb-2">{{ $package->name }}
                        </h1>
                        <span
                            class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1 text-sm font-bold text-primary">
                            Rs.{{ $package->price }} <span class="ml-1 text-xs font-normal text-slate-500">(Incl.
                                Tax)</span>
                        </span>
                    </div>
                </div>

                <!-- Specs Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <tr>
                                <td class="px-6 py-4 font-medium text-slate-500 w-1/2">
                                    <span
                                        class="material-symbols-outlined text-[18px] align-middle mr-2 text-blue-500">language</span>
                                    Internet
                                </td>
                                <td class="px-6 py-4 text-slate-900 dark:text-white font-medium">{{ $package->data }}</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 font-medium text-slate-500">
                                    <span
                                        class="material-symbols-outlined text-[18px] align-middle mr-2 text-green-500">call</span>
                                    Onnet Min
                                </td>
                                <td class="px-6 py-4 text-slate-900 dark:text-white font-medium">{{ $package->onnet }}</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 font-medium text-slate-500">
                                    <span
                                        class="material-symbols-outlined text-[18px] align-middle mr-2 text-orange-500">call</span>
                                    OffNet Min
                                </td>
                                <td class="px-6 py-4 text-slate-900 dark:text-white font-medium">{{ $package->offnet }}</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 font-medium text-slate-500">
                                    <span
                                        class="material-symbols-outlined text-[18px] align-middle mr-2 text-purple-500">sms</span>
                                    SMS
                                </td>
                                <td class="px-6 py-4 text-slate-900 dark:text-white font-medium">{{ $package->sms }}</td>
                            </tr>
                            <tr class="bg-red-50/30 dark:bg-red-900/10">
                                <td class="px-6 py-4 font-bold text-red-600">
                                    <span class="material-symbols-outlined text-[18px] align-middle mr-2">payments</span>
                                    Price
                                </td>
                                <td class="px-6 py-4 font-bold text-red-600">Rs.{{ $package->price }}
                                    <span class="text-xs font-normal text-slate-500 ml-1">(Incl. Tax)</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Description -->
            @if($package->description || $package->method)
                <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6 mt-6 dark:bg-slate-900 dark:ring-slate-800">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Description</h3>
                    <div class="prose prose-sm max-w-none text-slate-600 dark:text-slate-400">
                        {!! $package->description !!}
                    </div>
                    @if($package->method)
                        <div class="mt-4 prose prose-sm max-w-none text-slate-600 dark:text-slate-400">
                            {!! $package->method !!}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection