@extends('layouts.dashboard')
@section('title', 'Media Library')

@section('content')
    <div class="admin-page-header">
        <div>
            <h1>Media Library</h1>
            <div class="breadcrumb-nav">
                <a href="{{ route('dashboard.index') }}">Dashboard</a>
                <span class="separator">/</span>
                Media Library
            </div>
        </div>
    </div>

    @include('includes.info-bar')

    {{-- Upload Card --}}
    <div class="admin-card" style="margin-bottom:24px;">
        <div class="admin-card-header">
            <h2><i class="fas fa-cloud-upload-alt" style="margin-right:8px; opacity:0.5;"></i>Upload Files</h2>
        </div>
        <div class="admin-card-body">
            <form action="{{ route('dashboard.media.upload') }}" method="POST" enctype="multipart/form-data"
                style="display:flex; gap:12px; align-items:end;">
                <div class="admin-form-group" style="flex:1; margin-bottom:0;">
                    <input type="file" name="files[]" class="admin-form-control" multiple
                        accept="image/*,video/mp4,.pdf,.svg">
                </div>
                <button type="submit" class="btn-admin-primary">
                    <i class="fas fa-upload" style="margin-right:6px;"></i>Upload
                </button>
            </form>
        </div>
    </div>

    {{-- Media Grid --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2><i class="fas fa-images" style="margin-right:8px; opacity:0.5;"></i>All Media ({{ count($media) }} files)
            </h2>
        </div>
        <div class="admin-card-body">
            @if(count($media) > 0)
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:16px;">
                    @foreach($media as $file)
                        <div class="media-item"
                            style="border:1px solid var(--admin-surface-border); border-radius:10px; overflow:hidden; background:var(--admin-bg-primary);">
                            <div
                                style="height:140px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:var(--admin-bg-secondary);">
                                @if(in_array(pathinfo($file['name'], PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']))
                                    <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}"
                                        style="width:100%; height:100%; object-fit:cover;">
                                @else
                                    <i class="fas fa-file" style="font-size:36px; color:var(--admin-text-muted);"></i>
                                @endif
                            </div>
                            <div style="padding:10px;">
                                <div style="font-size:12px; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"
                                    title="{{ $file['name'] }}">
                                    {{ $file['name'] }}
                                </div>
                                <div style="font-size:11px; color:var(--admin-text-muted); margin-top:4px;">
                                    {{ number_format($file['size'] / 1024, 1) }} KB
                                </div>
                                <div style="display:flex; gap:6px; margin-top:8px;">
                                    <button class="btn-admin-sm" onclick="copyToClipboard('{{ $file['url'] }}')" title="Copy URL"
                                        style="flex:1;">
                                        <i class="fas fa-link"></i>
                                    </button>
                                    <form action="{{ route('dashboard.media.destroy') }}" method="POST"
                                        onsubmit="return confirm('Delete this file?');" style="flex:1;">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="path" value="{{ $file['path'] }}">
                                        <button type="submit" class="btn-admin-sm btn-admin-danger" title="Delete"
                                            style="width:100%;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align:center; padding:40px; color:var(--admin-text-muted);">
                    <i class="fas fa-images" style="font-size:32px; margin-bottom:12px; display:block; opacity:0.3;"></i>
                    No media files yet. Upload some files above.
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(window.location.origin + text).then(function () {
                alert('URL copied to clipboard!');
            });
        }
    </script>
@endsection