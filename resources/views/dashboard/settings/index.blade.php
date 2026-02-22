@extends('layouts.dashboard')
@section('title', 'Settings')

@section('content')
    <div class="admin-page-header">
        <div>
            <h1>Settings</h1>
            <div class="breadcrumb-nav">
                <a href="{{ route('dashboard.index') }}">Dashboard</a>
                <span class="separator">/</span>
                Settings
            </div>
        </div>
    </div>

    @include('includes.info-bar')

    <form action="{{ route('dashboard.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="admin-card" style="margin-bottom:24px;">
            <div class="admin-card-header">
                <h2><i class="fas fa-code" style="margin-right:8px; opacity:0.5;"></i>Head Code</h2>
            </div>
            <div class="admin-card-body">
                <div class="admin-form-group">
                    <label class="admin-form-label">Code Snippets (inserted in &lt;head&gt;)</label>
                    <textarea name="head_code" class="admin-form-control" rows="6"
                        style="font-family:monospace; font-size:13px;">{{ $settings->head_code ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <div class="admin-card" style="margin-bottom:24px;">
            <div class="admin-card-header">
                <h2><i class="fas fa-play" style="margin-right:8px; opacity:0.5;"></i>Body Start Code</h2>
            </div>
            <div class="admin-card-body">
                <div class="admin-form-group">
                    <label class="admin-form-label">Code Snippets (after &lt;body&gt; open tag)</label>
                    <textarea name="body_start_code" class="admin-form-control" rows="6"
                        style="font-family:monospace; font-size:13px;">{{ $settings->body_start_code ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <div class="admin-card" style="margin-bottom:24px;">
            <div class="admin-card-header">
                <h2><i class="fas fa-stop" style="margin-right:8px; opacity:0.5;"></i>Body End Code</h2>
            </div>
            <div class="admin-card-body">
                <div class="admin-form-group">
                    <label class="admin-form-label">Code Snippets (before &lt;/body&gt; close tag)</label>
                    <textarea name="body_end_code" class="admin-form-control" rows="6"
                        style="font-family:monospace; font-size:13px;">{{ $settings->body_end_code ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-admin-primary">
            <i class="fas fa-save"></i> Save Settings
        </button>
    </form>
@endsection