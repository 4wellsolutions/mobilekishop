@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Site Settings</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('dashboard.settings.update') }}" method="POST">
            @csrf

            {{-- Head Code --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-code-slash me-2"></i>Head Code
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-2">
                        Code placed just before <code>&lt;/head&gt;</code>. Use for Google Analytics, AdSense verification,
                        meta tags, custom CSS, etc.
                    </p>
                    <textarea name="head_code" class="form-control font-monospace" rows="8"
                        placeholder="<!-- Google Analytics -->&#10;<script async src='https://www.googletagmanager.com/gtag/js?id=G-XXXXX'></script>">{{ $settings['head_code'] }}</textarea>
                </div>
            </div>

            {{-- Body Start Code --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-body-text me-2"></i>Body Start Code
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-2">
                        Code placed right after <code>&lt;body&gt;</code>. Use for Google Tag Manager noscript, cookie
                        banners, etc.
                    </p>
                    <textarea name="body_start_code" class="form-control font-monospace" rows="6"
                        placeholder="<!-- Google Tag Manager (noscript) -->">{{ $settings['body_start_code'] }}</textarea>
                </div>
            </div>

            {{-- Body End Code --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-braces me-2"></i>Body End Code
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-2">
                        Code placed just before <code>&lt;/body&gt;</code>. Use for chat widgets, AdSense auto ads, custom
                        scripts, etc.
                    </p>
                    <textarea name="body_end_code" class="form-control font-monospace" rows="6"
                        placeholder="<!-- AdSense Auto Ads -->">{{ $settings['body_end_code'] }}</textarea>
                </div>
            </div>

            <div class="text-end mb-4">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="bi bi-check-lg me-1"></i> Save Settings
                </button>
            </div>
        </form>
    </div>
@endsection