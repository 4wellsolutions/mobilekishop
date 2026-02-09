@php
    // Extract country code from URL path (first segment)
    $pathSegments = explode('/', trim(request()->path(), '/'));
    $firstSegment = $pathSegments[0] ?? null;

    // Get allowed countries (cached)
    $allowedCountries = \Illuminate\Support\Facades\Cache::remember('allowed_country_codes', 3600, fn() => DB::table('countries')->pluck('country_code')->map(fn($c) => strtolower($c))->toArray());

    // Determine country code from path or default to 'pk'
    $countryCode = in_array($firstSegment, $allowedCountries) ? $firstSegment : 'pk';

    // Use the session helper function to set the country_code
    session(['country_code' => $countryCode]);

    $country = DB::table("countries")->where("country_code", $countryCode)->first();
@endphp

@extends('layouts.frontend')

@section('title', '404 - Page not Found | MobileKiShop')
@section('description', '404 - The page you are looking for could not be found.')

@section("content")
    <main class="max-w-[1400px] mx-auto px-4 md:px-6 lg:px-8">
        <div class="flex flex-col items-center justify-center min-h-[60vh] text-center py-16">
            <div class="text-8xl md:text-9xl font-black text-primary/20 leading-none select-none">404</div>
            <h1 class="text-2xl md:text-3xl font-bold text-text-main mt-4 mb-3">Page Not Found</h1>
            <p class="text-text-muted text-lg mb-8 max-w-md">Sorry, the page you are looking for could not be found or has
                been moved.</p>
            @php
                $pathSegments = explode('/', trim(request()->path(), '/'));
                $firstSegment = $pathSegments[0] ?? null;
                $allowedCountries = ['us', 'uk', 'bd', 'ae', 'in'];
                $countryCode = in_array($firstSegment, $allowedCountries) ? $firstSegment : 'pk';
                $homeUrl = $countryCode === 'pk' ? url('/') : url('/' . $countryCode);
            @endphp
            <a href="{{ $homeUrl }}" class="inline-flex items-center gap-2 bg-primary text-white font-semibold py-3 px-8 rounded-xl text-sm
                       hover:bg-blue-700 transition-colors shadow-lg shadow-primary/20 no-underline">
                <span class="material-symbols-outlined text-xl">home</span>
                Go to Homepage
            </a>
        </div>
    </main>
@endsection