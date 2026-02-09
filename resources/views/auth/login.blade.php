@extends('layouts.techspec')

@section('title', 'Login - MobileKiShop')
@section('description', 'Login or register for MobileKiShop')
@section('canonical', URL::to('/login'))

@section('content')
    <main class="max-w-[1400px] mx-auto px-4 md:px-6 lg:px-8 py-8">
        <div class="max-w-4xl mx-auto">
            @include("includes.info-bar")

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                {{-- Login --}}
                <div class="bg-surface-card rounded-2xl shadow-sm p-6">
                    <h1
                        class="text-xl font-bold text-center text-primary bg-surface-alt py-3 rounded-xl mb-6 uppercase tracking-wide">
                        Login</h1>
                    <form action="{{ route('login') }}" method="post" class="space-y-4">
                        @csrf
                        <div>
                            <label for="login-email" class="block text-sm font-medium text-text-main mb-1">Email address
                                <span class="text-red-500">*</span></label>
                            <input type="email" value="{{ old('email') }}" name="email" placeholder="name@example.com"
                                id="login-email" required
                                class="w-full px-3 py-2.5 border border-border-light rounded-lg text-sm
                                       focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-colors">
                        </div>
                        <div>
                            <label for="login-password" class="block text-sm font-medium text-text-main mb-1">Password <span
                                    class="text-red-500">*</span></label>
                            <input type="password" name="password" placeholder="Password" id="login-password" required
                                class="w-full px-3 py-2.5 border border-border-light rounded-lg text-sm
                                       focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-colors">
                        </div>
                        <button type="submit" class="w-full bg-text-main text-white font-semibold py-2.5 rounded-lg text-sm
                                   hover:bg-gray-700 transition-colors">
                            Login
                        </button>
                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center gap-2 text-text-muted cursor-pointer">
                                <input type="checkbox" name="remember"
                                    class="rounded border-border-light text-primary focus:ring-primary">
                                Remember Me
                            </label>
                            <a href="#" class="text-text-muted hover:text-primary no-underline transition-colors">Forgot
                                password?</a>
                        </div>
                        <div>
                            <a href="{{ URL::to('/google/redirect') }}" id="sign-with-google">
                                <img src="{{ URL::to('/images/login-with-google.png') }}" alt="Login with Google"
                                    class="max-w-[200px]">
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Register --}}
                <div class="bg-surface-card rounded-2xl shadow-sm p-6">
                    <h4
                        class="text-xl font-bold text-center text-primary bg-surface-alt py-3 rounded-xl mb-6 uppercase tracking-wide">
                        Register</h4>
                    <form action="{{ route('register') }}" method="post" class="space-y-4">
                        @csrf
                        <div>
                            <label for="register-name" class="block text-sm font-medium text-text-main mb-1">Full Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" placeholder="Full name" value="{{ old('name') }}"
                                id="register-name" required
                                class="w-full px-3 py-2.5 border border-border-light rounded-lg text-sm
                                       focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-colors">
                        </div>
                        <div>
                            <label for="register-phone" class="block text-sm font-medium text-text-main mb-1">Phone Number
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="phone_number" placeholder="Phone number"
                                value="{{ old('phone_number') }}" id="register-phone" required
                                class="w-full px-3 py-2.5 border border-border-light rounded-lg text-sm
                                       focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-colors">
                        </div>
                        <div>
                            <label for="register-email" class="block text-sm font-medium text-text-main mb-1">Email address
                                <span class="text-red-500">*</span></label>
                            <input type="email" placeholder="name@example.com" name="email" value="{{ old('email') }}"
                                id="register-email" required
                                class="w-full px-3 py-2.5 border border-border-light rounded-lg text-sm
                                       focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-colors">
                        </div>
                        <div>
                            <label for="register-password" class="block text-sm font-medium text-text-main mb-1">Password
                                <span class="text-red-500">*</span></label>
                            <input type="password" name="password" placeholder="Password" id="register-password" required
                                class="w-full px-3 py-2.5 border border-border-light rounded-lg text-sm
                                       focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-colors">
                        </div>
                        <label class="flex items-center gap-2 text-sm text-text-muted cursor-pointer">
                            <input type="checkbox" name="newsletter" checked
                                class="rounded border-border-light text-primary focus:ring-primary">
                            Sign up for our Newsletter
                        </label>
                        <button type="submit" class="w-full bg-text-main text-white font-semibold py-2.5 rounded-lg text-sm
                                   hover:bg-gray-700 transition-colors">
                            Register
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection