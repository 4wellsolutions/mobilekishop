@extends("layouts.dashboard")

@section("content")
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Expert Rating — {{ $product->name }}</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.expert-ratings.index') }}">Expert
                                    Ratings</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="">
        @include("includes.info-bar")

        <form method="POST" action="{{ route('dashboard.expert-ratings.update', $product->id) }}">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Rating Inputs --}}
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Rating Criteria (0–10)</h5>

                            @php
                                $criteria = [
                                    'design' => ['label' => 'Design & Build', 'icon' => 'fas fa-palette'],
                                    'display' => ['label' => 'Display', 'icon' => 'fas fa-tv'],
                                    'performance' => ['label' => 'Performance', 'icon' => 'fas fa-microchip'],
                                    'camera' => ['label' => 'Camera', 'icon' => 'fas fa-camera'],
                                    'battery' => ['label' => 'Battery Life', 'icon' => 'fas fa-battery-full'],
                                    'value_for_money' => ['label' => 'Value for Money', 'icon' => 'fas fa-tag'],
                                ];
                            @endphp

                            @foreach($criteria as $key => $info)
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="{{ $info['icon'] }} me-2 text-primary"></i>{{ $info['label'] }}
                                    </label>
                                    <div class="d-flex align-items-center gap-3">
                                        <input type="range" class="form-range flex-grow-1 rating-slider" name="{{ $key }}"
                                            min="0" max="10" step="0.5" value="{{ old($key, $rating->$key ?? 0) }}"
                                            data-target="score-{{ $key }}"
                                            oninput="document.getElementById('score-{{ $key }}').textContent = this.value">
                                        <span id="score-{{ $key }}" class="badge bg-primary px-3 py-2"
                                            style="min-width:50px; font-size:16px;">
                                            {{ old($key, $rating->$key ?? 0) }}
                                        </span>
                                    </div>
                                    @error($key)
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Sidebar: Verdict & Meta --}}
                <div class="col-md-4">
                    {{-- Live Preview --}}
                    <div class="card mb-3">
                        <div class="card-body text-center">
                            <h5 class="card-title">Overall Score</h5>
                            <div id="overall-preview" class="display-3 fw-bold text-primary mb-2">
                                {{ $rating->overall ?? '0.0' }}
                            </div>
                            <p class="text-muted mb-0" id="overall-label">
                                {{ $rating->id ? $rating->getLabel() : 'Not Rated' }}
                            </p>
                        </div>
                    </div>

                    {{-- Product Info --}}
                    <div class="card mb-3">
                        <div class="card-body text-center">
                            @if($product->thumbnail)
                                <img src="{{ $product->thumbnail }}" alt="{{ $product->name }}" style="max-height:150px;"
                                    class="mb-3">
                            @endif
                            <h6>{{ $product->name }}</h6>
                            <small class="text-muted">{{ optional($product->brand)->name }}</small>
                        </div>
                    </div>

                    {{-- Verdict --}}
                    <div class="card mb-3">
                        <div class="card-body">
                            <label class="form-label fw-bold">Expert Verdict</label>
                            <textarea name="verdict" class="form-control" rows="5"
                                placeholder="Write your expert verdict...">{{ old('verdict', $rating->verdict) }}</textarea>
                        </div>
                    </div>

                    {{-- Rated By --}}
                    <div class="card mb-3">
                        <div class="card-body">
                            <label class="form-label fw-bold">Rated By</label>
                            <input type="text" name="rated_by" class="form-control" placeholder="Expert name"
                                value="{{ old('rated_by', $rating->rated_by) }}">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-save me-2"></i>Save Expert Rating
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Live overall score calculation
    document.addEventListener('DOMContentLoaded', function () {
        const sliders = document.querySelectorAll('.rating-slider');
        const overallEl = document.getElementById('overall-preview');
        const labelEl = document.getElementById('overall-label');

        function updateOverall() {
            let total = 0;
            sliders.forEach(s => total += parseFloat(s.value));
            const avg = (total / sliders.length).toFixed(1);
            overallEl.textContent = avg;

            // Update label
            if (avg >= 9) labelEl.textContent = 'Exceptional';
            else if (avg >= 8) labelEl.textContent = 'Excellent';
            else if (avg >= 7) labelEl.textContent = 'Very Good';
            else if (avg >= 6) labelEl.textContent = 'Good';
            else if (avg >= 5) labelEl.textContent = 'Average';
            else labelEl.textContent = 'Below Average';

            // Update color
            overallEl.classList.remove('text-success', 'text-primary', 'text-warning', 'text-danger');
            if (avg >= 8) overallEl.classList.add('text-success');
            else if (avg >= 6) overallEl.classList.add('text-primary');
            else if (avg >= 4) overallEl.classList.add('text-warning');
            else overallEl.classList.add('text-danger');
        }

        sliders.forEach(s => s.addEventListener('input', updateOverall));
        updateOverall();
    });
</script>
@stop