@extends('layouts.dashboard')
@section('title', 'Edit Product')
@section('content')
    <div class="admin-page-header">
        <div>
            <h1>Edit Product</h1>
            <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span
                    class="separator">/</span><a href="{{ route('dashboard.products.index') }}">Products</a><span
                    class="separator">/</span>{{ $product->name }}</div>
        </div>
    </div>
    @include('includes.info-bar')
    <form id="form_validation" action="{{ route('dashboard.products.update', $product->id) }}" enctype="multipart/form-data"
        method="post">
        @csrf @method('PUT')
        <div class="alert alert-danger d-none" role="alert"></div>
        <div class="admin-card" style="margin-bottom:24px;">
            <div class="admin-card-header">
                <h2>Basic Information</h2>
            </div>
            <div class="admin-card-body">
                <div class="admin-form-grid">
                    <div class="admin-form-group"><label class="admin-form-label">Brand</label><select name="brand_id"
                            id="brand_id" class="admin-form-control" required>
                            <option value="">Select Brand</option>@foreach($brands as $brand)<option
                                value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}</option>@endforeach
                        </select></div>
                    <div class="admin-form-group"><label class="admin-form-label">Name</label><input type="text" name="name"
                            value="{{ old('name', $product->name) }}" class="admin-form-control" required></div>
                    @if($colors->count())
                        <div class="admin-form-group"><label class="admin-form-label">Colors</label><select name="colors[]"
                                id="colors" class="admin-form-control" style="height:100px;" multiple
                                required>@foreach($colors as $color)<option value="{{ $color->id }}" {{ in_array($color->id, $product->colors->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $color->name }}</option>
                    @endforeach</select></div>@endif
                    <div class="admin-form-group"><label class="admin-form-label">Variants</label><select name="variants[]"
                            id="variants" class="admin-form-control" style="height:100px;" multiple
                            required>@foreach($variants as $variant)<option value="{{ $variant->id }}"
                                data-index="{{ $loop->index }}" {{ in_array($variant->id, $product->variants->pluck('id')->toArray()) ? 'selected' : '' }}>
                            {{ $variant->variant_name }}</option>@endforeach</select></div>
                </div>
                <div id="colorImageUploadContainer" style="margin-top:16px;">@foreach($product->colors as $color)<div
                    class="admin-form-group" style="margin-bottom:12px;"><label class="admin-form-label">Upload image
                        for {{ $color->name }}</label><input type="file" name="color_images[{{ $color->id }}]"
                        accept="image/*" class="admin-form-control">@if($color->pivot->image)<img
                        src="{{ $color->pivot->image }}" style="width:80px;border-radius:6px;margin-top:8px;">@endif
                </div>@endforeach</div>
            </div>
        </div>
        <div class="admin-card" style="margin-bottom:24px;">
            <div class="admin-card-header">
                <h2>Variant Prices</h2>
            </div>
            <div class="admin-card-body">
                <input type="hidden" id="countriesData" value="{{ $countries->toJson() }}">
                <div id="variantsPricesContainer">
                    @foreach($variants as $variant)@if($product->variants->contains($variant->id))
                        <div style="margin-bottom:16px;"><label class="admin-form-label"
                                style="font-weight:600;">{{ $variant->variant_name }}</label>
                            <div class="admin-form-grid">@foreach($countries as $country)<div class="admin-form-group"><label
                                class="admin-form-label">{{ $country->country_name }}</label><input type="text"
                                name="prices[{{ $variant->id }}][{{ $country->id }}]"
                                value="{{ old('prices.' . $variant->id . '.' . $country->id, $variantPrices[$variant->id][$country->id] ?? 0) }}"
                            class="admin-form-control" required></div>@endforeach</div>
                    </div>@endif @endforeach
                </div>
            </div>
        </div>
        <div class="admin-card" style="margin-bottom:24px;">
            <div class="admin-card-header">
                <h2>Attributes</h2>
            </div>
            <div class="admin-card-body">
                <div class="admin-form-grid">
                    <div class="admin-form-group"><label class="admin-form-label">Release Date</label><input type="date"
                            name="release_date"
                            value="{{ old('release_date', $product->release_date ? $product->release_date->format('Y-m-d') : '') }}"
                            class="admin-form-control"></div>
                    @php $category = $product->category; @endphp
                    @if($attributes = $category->attributes()->orderBy('sequence', 'ASC')->whereNotIn('name', ['release_date', 'thumbnail'])->get())@foreach($attributes as $attribute)
                        <div class="admin-form-group"><label class="admin-form-label">{{ $attribute->label }}</label><input
                                type="text" name="{{ $attribute->id }}"
                                value="{{ old($attribute->id, $product->attributes->where('id', $attribute->id)->first()->pivot->value ?? '') }}"
                    class="admin-form-control"></div>@endforeach @endif
                </div>
            </div>
        </div>
        <div class="admin-card" style="margin-bottom:24px;">
            <div class="admin-card-header">
                <h2>Content & Images</h2>
            </div>
            <div class="admin-card-body">
                <div class="admin-form-group" style="margin-bottom:16px;"><label
                        class="admin-form-label">Body</label><textarea class="admin-form-control" name="body" id="editor"
                        rows="5">{{ old('body', $product->body) }}</textarea></div>
                <div id="imageInputContainer"><label class="admin-form-label">Product Images</label>
                    <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;"><input type="file"
                            class="admin-form-control" name="images[]" accept="image/*" multiple><button type="button"
                            class="btn-admin-sm addImageInput"><i class="fas fa-plus"></i></button></div>
                    @foreach($product->images as $image)<div
                        style="display:flex;gap:8px;align-items:center;margin-bottom:8px;"><img
                            src="{{ url('/products/' . $image->name) }}" style="width:80px;border-radius:6px;"><input
                            type="file" class="admin-form-control image-input" name="images[]" accept="image/*"
                            multiple><button type="button" class="btn-admin-sm removeImageInput"
                    style="background:#ef4444;"><i class="fas fa-minus"></i></button></div>@endforeach
                </div>
            </div>
        </div>
        @php $rc = ['design' => ['Design & Build', 'fa-palette', '#8b5cf6'], 'display' => ['Display', 'fa-tv', '#06b6d4'], 'performance' => ['Performance', 'fa-microchip', '#f59e0b'], 'camera' => ['Camera', 'fa-camera', '#ef4444'], 'battery' => ['Battery Life', 'fa-battery-full', '#22c55e'], 'value_for_money' => ['Value for Money', 'fa-tag', '#3b82f6']]; @endphp
        <div class="admin-card" style="margin-bottom:24px;">
            <div class="admin-card-header">
                <h2><i class="fas fa-star" style="color:#f59e0b;margin-right:8px;"></i>Expert Rating</h2>
            </div>
            <div class="admin-card-body">
                <div style="display:grid;grid-template-columns:1fr 280px;gap:24px;">
                    <div>
                        @foreach($rc as $key => $info)<div style="margin-bottom:20px;"><label class="admin-form-label"
                                style="display:flex;align-items:center;gap:8px;"><i class="fas {{ $info[1] }}"
                                    style="color:{{ $info[2] }};"></i>{{ $info[0] }}</label>
                            <div style="display:flex;align-items:center;gap:16px;"><input type="range" class="expert-slider"
                                    name="expert_{{ $key }}" min="0" max="10" step="0.5"
                                    value="{{ old('expert_' . $key, $expertRating->$key ?? 0) }}"
                                    style="flex:1;accent-color:{{ $info[2] }};"
                                    oninput="document.getElementById('expert-score-{{ $key }}').textContent=this.value;updateExpertOverall();"><span
                                    id="expert-score-{{ $key }}"
                                    style="min-width:44px;text-align:center;padding:6px 12px;border-radius:8px;background:var(--admin-bg-secondary);font-weight:700;color:{{ $info[2] }};">{{ old('expert_' . $key, $expertRating->$key ?? 0) }}</span>
                            </div>
                        </div>@endforeach
                    </div>
                    <div>
                        <div class="admin-card" style="margin-bottom:16px;text-align:center;">
                            <div class="admin-card-body" style="padding:24px 16px;">
                                <div
                                    style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--admin-text-secondary);">
                                    Overall</div>
                                <div id="expert-overall-preview"
                                    style="font-size:42px;font-weight:800;color:var(--admin-primary);">
                                    {{ $expertRating->overall ?? '0.0' }}</div>
                                <div id="expert-overall-label" style="color:var(--admin-text-secondary);">
                                    {{ $expertRating->id ? $expertRating->getLabel() : 'Not Rated' }}</div>
                            </div>
                        </div>
                        <div class="admin-form-group" style="margin-bottom:12px;"><label
                                class="admin-form-label">Verdict</label><textarea name="expert_verdict"
                                class="admin-form-control"
                                rows="3">{{ old('expert_verdict', $expertRating->verdict) }}</textarea></div>
                        <div class="admin-form-group"><label class="admin-form-label">Rated By</label><input type="text"
                                name="expert_rated_by" class="admin-form-control"
                                value="{{ old('expert_rated_by', $expertRating->rated_by) }}"></div>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-bottom:32px;"><button type="submit" class="btn-admin-primary" style="width:100%;"><i
                    class="fas fa-save"></i> Update Product</button></div>
    </form>
@endsection
@section('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () { var cs = document.getElementById('colors'), cic = document.getElementById('colorImageUploadContainer'); if (cs) { cs.addEventListener('change', function () { cic.innerHTML = ''; Array.from(this.selectedOptions).forEach(o => { var d = document.createElement('div'); d.className = 'admin-form-group'; d.style.marginBottom = '12px'; var l = document.createElement('label'); l.className = 'admin-form-label'; l.textContent = 'Upload image for ' + o.text; d.appendChild(l); var i = document.createElement('input'); i.type = 'file'; i.name = 'color_images[' + o.value + ']'; i.accept = 'image/*'; i.className = 'admin-form-control'; d.appendChild(i); cic.appendChild(d); }); }); } });
        $(document).ready(function () { var vp = @json($variantPrices); var cs = JSON.parse($('#countriesData').val()); function pop(sv, vp) { $('#variantsPricesContainer').empty(); sv.forEach(function (vi) { var vn = $('#variants option[value="' + vi + '"]').text(); var h = '<div style="margin-bottom:16px;"><label class="admin-form-label" style="font-weight:600;">' + vn + '</label><div class="admin-form-grid">'; cs.forEach(function (c) { var ep = (vp[vi] && vp[vi][c.id]) ? vp[vi][c.id] : 0; h += '<div class="admin-form-group"><label class="admin-form-label">' + c.country_name + '</label><input type="text" name="prices[' + vi + '][' + c.id + ']" value="' + ep + '" class="admin-form-control" required></div>'; }); h += '</div></div>'; $('#variantsPricesContainer').append(h); }); } $('#variants').on('change', function () { var sv = $(this).val(); if (sv) pop(sv, vp); else $('#variantsPricesContainer').empty(); }); });
        $(document).ready(function () { $(document).on('click', '.addImageInput', function () { $('#imageInputContainer').append('<div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;"><input type="file" class="admin-form-control image-input" name="images[]" accept="image/*" multiple><div class="imagePreview"></div><button type="button" class="btn-admin-sm removeImageInput" style="background:#ef4444;"><i class="fas fa-minus"></i></button></div>'); }); $(document).on('click', '.removeImageInput', function () { $(this).closest('div').remove(); }); $(document).on('change', '.image-input', function () { var f = this.files[0], r = new FileReader(); r.onload = (function (el) { return function (e) { $(el).siblings('.imagePreview').empty().append('<img src="' + e.target.result + '" width="80" style="border-radius:6px;"/>'); } })(this); r.readAsDataURL(f); }); });
        $(document).ready(function () { $('#form_validation').on('submit', function (e) { e.preventDefault(); $('.alert').addClass('d-none').empty(); $.ajax({ url: "{{ route('dashboard.products.update', $product->id) }}", method: 'POST', data: new FormData(this), processData: false, contentType: false, success: function (r) { alert('Product updated'); window.location.href = r.url; }, error: function (x) { if (x.status === 422) { var er = x.responseJSON.errors, ab = $('.alert'); $.each(er, function (k, v) { ab.append('<p>' + v[0] + '</p>'); }); ab.removeClass('d-none').hide().slideDown(500); } else alert('Error'); } }); }); });
        ClassicEditor.create(document.querySelector('#editor')).catch(e => console.error(e));
        function updateExpertOverall() { var sl = document.querySelectorAll('.expert-slider'), el = document.getElementById('expert-overall-preview'), lb = document.getElementById('expert-overall-label'); if (!el || !sl.length) return; var t = 0; sl.forEach(s => t += parseFloat(s.value)); var a = (t / sl.length).toFixed(1); el.textContent = a; lb.textContent = a >= 9 ? 'Exceptional' : a >= 8 ? 'Excellent' : a >= 7 ? 'Very Good' : a >= 6 ? 'Good' : a >= 5 ? 'Average' : 'Below Average'; el.style.color = a >= 8 ? '#22c55e' : a >= 6 ? '#3b82f6' : a >= 4 ? '#f59e0b' : '#ef4444'; }
        document.addEventListener('DOMContentLoaded', updateExpertOverall);
    </script>
@endsection
@section('styles')<style>
    .ck-editor__editable_inline {
        min-height: 200px;
    }
</style>@endsection