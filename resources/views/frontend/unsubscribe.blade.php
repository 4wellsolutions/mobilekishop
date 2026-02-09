@extends('layouts.frontend')

@section('title', "Unsubscribe - MKS")
@section('description', "Unsubscribe - MKS")
@section("canonical", url('/unsubscribe'))

@section("content")
    <main class="max-w-[1400px] mx-auto px-4 md:px-6 lg:px-8 py-8">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="flex items-center gap-2 text-xs text-text-muted">
                <li><a href="{{ url('/') }}" class="hover:text-primary transition-colors no-underline">Home</a></li>
                <li class="before:content-['/'] before:mx-1">Unsubscribe</li>
            </ol>
        </nav>

        <div class="max-w-md mx-auto">
            <div class="bg-surface-card rounded-2xl shadow-sm p-6 md:p-8">
                <h1 class="text-xl font-bold text-text-main mb-4">Unsubscribe</h1>

                @if(session('message'))
                    <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm">
                        {{ session('message') }}
                    </div>
                @endif

                <form action="{{ url('/unsubscribe') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-text-main mb-1">Email Address</label>
                        <input type="email" name="email" id="email"
                            class="w-full px-3 py-2 border border-border-light rounded-lg text-sm
                                       focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-colors" placeholder="Enter your email"
                            required>
                    </div>
                    <div>
                        <label for="reason" class="block text-sm font-medium text-text-main mb-1">Reason (optional)</label>
                        <textarea name="reason" id="reason" rows="3"
                            class="w-full px-3 py-2 border border-border-light rounded-lg text-sm
                                       focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-colors resize-none"
                            placeholder="Tell us why you're unsubscribing"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-text-main text-white font-semibold py-2.5 rounded-lg text-sm
                                   hover:bg-gray-700 transition-colors">
                        Unsubscribe
                    </button>
                </form>
            </div>
        </div>
    </main>
@endsection