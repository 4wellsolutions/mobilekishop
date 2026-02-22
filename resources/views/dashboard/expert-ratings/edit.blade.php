@extends('layouts.dashboard')
@section('title', 'Expert Rating — ' . $product->name)
@section('content')
    <div class="admin-page-header">
        <div>
            <h1>Expert Rating</h1>
            <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span
                    class="separator">/</span><a href="{{ route('dashboard.expert-ratings.index') }}">Expert
                    Ratings</a><span class="separator">/</span>{{ $product->name }}</div>
        </div>
    </div>
    @include('includes.info-bar')

    <form method="POST" action="{{ route('dashboard.expert-ratings.update', $product->id) }}">
        @csrf @method('PUT')
        <div style="display:grid;grid-template-columns:1fr 350px;gap:24px;">
            {{-- Rating Sliders --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2>Rating Criteria (0–10)</h2>
                </div>
                <div class="admin-card-body">
                    @php
                        $criteria = [
                            'design' => ['label' => 'Design & Build', 'icon' => 'fas fa-palette', 'color' => '#8b5cf6'],
                            'display' => ['label' => 'Display', 'icon' => 'fas fa-tv', 'color' => '#06b6d4'],
                            'performance' => ['label' => 'Performance', 'icon' => 'fas fa-microchip', 'color' => '#f59e0b'],
                            'camera' => ['label' => 'Camera', 'icon' => 'fas fa-camera', 'color' => '#ef4444'],
                            'battery' => ['label' => 'Battery Life', 'icon' => 'fas fa-battery-full', 'color' => '#22c55e'],
                            'value_for_money' => ['label' => 'Value for Money', 'icon' => 'fas fa-tag', 'color' => '#3b82f6'],
                        ];
                      @endphp
                    @foreach($criteria as $key => $info)
                        <div style="margin-bottom:24px;">
                            <label class="admin-form-label" style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                                <i class="{{ $info['icon'] }}" style="color:{{ $info['color'] }};"></i>{{ $info['label'] }}
                            </label>
                            <div style="display:flex;align-items:center;gap:16px;">
                                <input type="range" class="rating-slider" name="{{ $key }}" min="0" max="10" step="0.5"
                                    value="{{ old($key, $rating->$key ?? 0) }}" data-target="score-{{ $key }}"
                                    oninput="document.getElementById('score-{{ $key }}').textContent=this.value;updateOverall();"
                                    style="flex:1;accent-color:{{ $info['color'] }};">
                                <span id="score-{{ $key }}"
                                    style="min-width:44px;text-align:center;padding:6px 12px;border-radius:8px;background:var(--admin-bg-secondary);font-weight:700;color:{{ $info['color'] }};font-size:16px;">{{ old($key, $rating->$key ?? 0) }}</span>
                            </div>
                            @error($key) <div style="color:#ef4444;font-size:13px;margin-top:4px;">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Sidebar --}}
            <div>
                {{-- Live Score --}}
                <div class="admin-card" style="margin-bottom:16px;text-align:center;">
                    <div class="admin-card-body" style="padding:32px 16px;">
                        <div
                            style="font-size:12px;text-transform:uppercase;letter-spacing:1px;color:var(--admin-text-secondary);margin-bottom:8px;">
                            Overall Score</div>
                        <div id="overall-preview"
                            style="font-size:48px;font-weight:800;color:var(--admin-primary);line-height:1;">
                            {{ $rating->overall ?? '0.0' }}</div>
                        <div id="overall-label" style="color:var(--admin-text-secondary);margin-top:4px;">
                            {{ $rating->id ? $rating->getLabel() : 'Not Rated' }}</div>
                    </div>
                </div>

                {{-- Product Info --}}
                <div class="admin-card" style="margin-bottom:16px;text-align:center;">
                    <div class="admin-card-body">
                        @if($product->thumbnail)
                            <img src="{{ $product->thumbnail }}" alt="{{ $product->name }}"
                                style="max-height:120px;margin-bottom:12px;">
                        @endif
                        <div style="font-weight:600;">{{ $product->name }}</div>
                        <div style="color:var(--admin-text-secondary);font-size:13px;">{{ optional($product->brand)->name }}
                        </div>
                    </div>
                </div>

                {{-- Verdict --}}
                <div class="admin-card" style="margin-bottom:16px;">
                    <div class="admin-card-body">
                        <label class="admin-form-label">Expert Verdict</label>
                        <textarea name="verdict" class="admin-form-control" rows="5"
                            placeholder="Write your expert verdict...">{{ old('verdict', $rating->verdict) }}</textarea>
                    </div>
                </div>

                {{-- Rated By --}}
                <div class="admin-card" style="margin-bottom:16px;">
                    <div class="admin-card-body">
                        <label class="admin-form-label">Rated By</label>
                        <input type="text" name="rated_by" class="admin-form-control"
                            value="{{ old('rated_by', $rating->rated_by) }}" placeholder="Expert name">
                    </div>
                </div>

                <button type="submit" class="btn-admin-primary" style="width:100%;"><i class="fas fa-save"></i> Save Expert
                    Rating</button>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        function updateOverall() {
            const sliders = document.querySelectorAll('.rating-slider');
            const el = document.getElementById('overall-preview');
            const lbl = document.getElementById('overall-label');
            let total = 0;
            sliders.forEach(s => total += parseFloat(s.value));
            const avg = (total / sliders.length).toFixed(1);
            el.textContent = avg;
            if (avg >= 9) lbl.textContent = 'Exceptional';
            else if (avg >= 8) lbl.textContent = 'Excellent';
            else if (avg >= 7) lbl.textContent = 'Very Good';
            else if (avg >= 6) lbl.textContent = 'Good';
            else if (avg >= 5) lbl.textContent = 'Average';
            else lbl.textContent = 'Below Average';
            el.style.color = avg >= 8 ? '#22c55e' : avg >= 6 ? '#3b82f6' : avg >= 4 ? '#f59e0b' : '#ef4444';
        }
        document.addEventListener('DOMContentLoaded', updateOverall);
    </script>
@endsection