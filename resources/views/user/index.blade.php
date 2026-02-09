@extends('layouts.techspec')

@section('title', 'My Account - MKS')
@section('description', 'Manage your account settings and profile information.')

@section("content")
    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-6">
        <a class="hover:text-primary hover:underline" href="{{ url('/') }}">Home</a>
        <span class="material-symbols-outlined text-[12px]">chevron_right</span>
        <span class="font-medium text-slate-900 dark:text-white">My Account</span>
    </div>

    <div class="flex flex-col gap-8 lg:flex-row">
        <!-- Sidebar -->
        <aside class="w-full shrink-0 lg:w-64">
            @include("includes.user-sidebar")
        </aside>

        <!-- Main Content -->
        <div class="flex-1 min-w-0">
            <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6 dark:bg-slate-900 dark:ring-slate-800">
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">Edit Account Information</h1>

                @include("includes.info-bar")

                <form action="{{ route('user.update', Auth::User()->id) }}" method="post">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label for="acc-name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">First Name</label>
                            <input type="text" id="acc-name" name="name" value="{{ Auth::User()->name }}" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                            <input type="email" id="email" value="{{ Auth::User()->email }}" readonly
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-slate-50 text-slate-500 dark:bg-slate-800 dark:border-slate-700">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Phone Number</label>
                            <input type="text" id="phone" name="phone" value="{{ Auth::user()->phone_number }}"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                        </div>
                    </div>

                    <div class="space-y-3 mb-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="newsletter" id="newsletter" {{ Auth::User()->newsletter ? 'checked' : '' }}
                                class="size-4 rounded border-slate-300 text-primary focus:ring-primary">
                            <span class="text-sm text-slate-700 dark:text-slate-300">Newsletter</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="change-pass-checkbox" value="1"
                                class="size-4 rounded border-slate-300 text-primary focus:ring-primary"
                                onclick="document.getElementById('password-section').classList.toggle('hidden')">
                            <span class="text-sm text-slate-700 dark:text-slate-300">Change Password</span>
                        </label>
                    </div>

                    <div id="password-section" class="hidden mb-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Change Password</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password</label>
                                <input type="password" id="password" name="password"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Confirm Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none dark:bg-slate-800 dark:border-slate-700 dark:text-white">
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-2">* Required Field</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-lg shadow-sm hover:bg-blue-600 transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection