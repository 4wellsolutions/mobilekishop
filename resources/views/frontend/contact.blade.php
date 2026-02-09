@extends('layouts.frontend')

@section('title', $metas->title)
@section('description', $metas->description)
@section("canonical", $metas->canonical)

@section("content")
    <main class="max-w-[1400px] mx-auto px-4 md:px-6 lg:px-8 py-8">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="flex items-center gap-2 text-xs text-text-muted">
                <li><a href="{{ URL::to('/') }}" class="hover:text-primary transition-colors no-underline">Home</a></li>
                <li class="before:content-['/'] before:mx-1">
                    {{ isset($brand->name) ? Str::title($brand->name) : $metas->name }}
                </li>
            </ol>
        </nav>

        <div class="max-w-2xl mx-auto">
            <div class="bg-surface-card rounded-2xl shadow-sm p-6 md:p-8">
                @include("includes.info-bar")
                <h1 class="text-2xl font-bold text-text-main mb-6">Contact</h1>

                <form action="{{ route('contact.post') }}" method="post" class="space-y-4" novalidate>
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-text-main mb-1">Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" placeholder="Your full name" value="{{ old('name') }}"
                            required class="w-full px-3 py-2.5 border border-border-light rounded-lg text-sm
                                   focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-colors">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-text-main mb-1">Email <span
                                class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" placeholder="name@example.com"
                            value="{{ old('email') }}" required class="w-full px-3 py-2.5 border border-border-light rounded-lg text-sm
                                   focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-colors">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-text-main mb-1">Phone Number</label>
                        <input type="text" name="phone" id="phone" placeholder="Phone number" value="{{ old('phone') }}"
                            class="w-full px-3 py-2.5 border border-border-light rounded-lg text-sm
                                   focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-colors">
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-text-main mb-1">Message <span
                                class="text-red-500">*</span></label>
                        <textarea name="message" id="message" rows="5" placeholder="Your message" required
                            class="w-full px-3 py-2.5 border border-border-light rounded-lg text-sm resize-none
                                   focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-colors">{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="bg-primary text-white font-semibold py-2.5 px-6 rounded-lg text-sm
                               hover:bg-blue-700 transition-colors">
                        Submit
                    </button>
                </form>

                <div class="mt-6 text-text-muted text-sm">
                    <p>For more details or any queries, please don't hesitate to contact us on WhatsApp.
                        <a href="https://wa.me/message/ZEKV4JT3A4LDK1" class="inline-flex items-center">
                            <img src="{{ URL::to('/images/icons/whatsapp.png') }}" alt="WhatsApp" class="h-6 ml-1">
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </main>
@endsection