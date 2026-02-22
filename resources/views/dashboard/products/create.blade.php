@extends('layouts.dashboard')
@section('title', 'Create Product')
@section('content')
    <div class="admin-page-header">
        <div>
            <h1>Create Product</h1>
            <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span
                    class="separator">/</span><a href="{{ route('dashboard.products.index') }}">Products</a><span
                    class="separator">/</span>Create</div>
        </div>
    </div>
    @include('includes.info-bar')

    @if(!Request::has('category_id'))
        {{-- Category Selection --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h2>Select Category</h2>
            </div>
            <div class="admin-card-body">
                <form action="" method="get" id="categoryForm">
                    <div class="admin-form-group"><label class="admin-form-label">Category</label>
                        <select name="category_id" id="category_id" class="admin-form-control">
                            <option>Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
    @else
        <form id="form_validation" action="{{ route('dashboard.products.store') }}" enctype="multipart/form-data" method="post">
            <input type="hidden" name="category_id" value="{{ \Request::get('category_id') }}">
            @csrf
            <div class="alert alert-danger d-none" role="alert"></div>

            {{-- Basic Info --}}
            <div class="admin-card" style="margin-bottom:24px;">
                <div class="admin-card-header">
                    <h2>Basic Information</h2>
                </div>
                <div class="admin-card-body">
                    <div class="admin-form-grid">
                        <div class="admin-form-group"><label class="admin-form-label">Brand</label>
                            <select name="brand_id" id="brand_id" class="admin-form-control">
                                <option>Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="admin-form-group"><label class="admin-form-label">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="admin-form-control">
                        </div>
                        @if($colors = App\Models\Color::all())
                            <div class="admin-form-group"><label class="admin-form-label">Colors</label>
                                <select name="colors[]" id="colors" class="admin-form-control" style="height:100px;" multiple>
                                    @foreach($colors as $color)
                                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="admin-form-group"><label class="admin-form-label">Variants</label>
                            <select name="variants[]" id="variants" class="admin-form-control" style="height:100px;" multiple
                                required>
                                @foreach($variants as $variant)
                                    <option value="{{ $variant->id }}" data-index="{{ $loop->index }}">{{ $variant->variant_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="colorImageUploadContainer" style="margin-top:16px;"></div>
                </div>
            </div>

            {{-- Variant Prices --}}
            <div class="admin-card" style="margin-bottom:24px;">
                <div class="admin-card-header">
                    <h2>Variant Prices by Country</h2>
                </div>
                <div class="admin-card-body">
                    <input type="hidden" id="countriesData" value="{{ $countries->toJson() }}">
                    <div id="variantsPricesContainer"></div>
                    <p style="color:var(--admin-text-secondary);font-size:13px;"><i class="fas fa-info-circle"></i> Select
                        variants above to set country prices.</p>
                </div>
            </div>

            {{-- Attributes --}}
            <div class="admin-card" style="margin-bottom:24px;">
                <div class="admin-card-header">
                    <h2>Attributes</h2>
                </div>
                <div class="admin-card-body">
                    <div class="admin-form-grid">
                        <div class="admin-form-group"><label class="admin-form-label">Release Date</label>
                            <input type="date" name="release_date" value="{{ old('release_date') }}" class="admin-form-control">
                        </div>
                        @php $category = App\Models\Category::find(Request::get('category_id')); @endphp
                        @if($attributes = $category->attributes()->orderBy('sequence', 'ASC')->where('name', '!=', 'release_date')->where('name', '!=', 'thumbnail')->get())
                            @foreach($attributes as $attribute)
                                <div class="admin-form-group"><label class="admin-form-label">{{ $attribute->label }}</label>
                                    <input type="text" name="{{ $attribute->id }}" value="{{ old($attribute->id) }}"
                                        class="admin-form-control">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            {{-- Content & Images --}}
            <div class="admin-card" style="margin-bottom:24px;">
                <div class="admin-card-header">
                    <h2>Content & Images</h2>
                </div>
                <div class="admin-card-body">
                    <div class="admin-form-group" style="margin-bottom:16px;"><label class="admin-form-label">Body</label>
                        <textarea class="admin-form-control" name="body" id="editor" rows="5"></textarea>
                    </div>
                    <div id="imageInputContainer">
                        <label class="admin-form-label">Product Images</label>
                        <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;">
                            <input type="file" class="admin-form-control" name="images[]" accept="image/*" multiple>
                            <button type="button" class="btn-admin-sm addImageInput"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <div id="imagePreviewContainer" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px;"></div>
                    <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i>
                            Create Product</button></div>
                </div>
            </div>
        </form>
    @endif
@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const colorsSelect = document.getElementById('colors');
            const colorImageUploadContainer = document.getElementById('colorImageUploadContainer');
            if (colorsSelect) {
                colorsSelect.addEventListener('change', function () {
                    colorImageUploadContainer.innerHTML = '';
                    Array.from(this.selectedOptions).forEach(option => {
                        const colorId = option.value, colorName = option.text;
                        const fg = document.createElement('div'); fg.className = 'admin-form-group'; fg.style.marginBottom = '12px';
                        const lbl = document.createElement('label'); lbl.className = 'admin-form-label'; lbl.textContent = 'Upload image for ' + colorName; fg.appendChild(lbl);
                        const inp = document.createElement('input'); inp.type = 'file'; inp.name = 'color_images[' + colorId + ']'; inp.accept = 'image/*'; inp.className = 'admin-form-control'; fg.appendChild(inp);
                        colorImageUploadContainer.appendChild(fg);
                    });
                });
            }
        });

        $(document).ready(function () {
            let countries = JSON.parse($('#countriesData').val() || '[]');
            $('#variants').on('change', function () {
                $('#variantsPricesContainer').empty();
                let selectedVariants = $(this).val();
                if (!selectedVariants || selectedVariants.length === 0) return;
                selectedVariants.forEach(function (variantId) {
                    let variantName = $('#variants option[value="' + variantId + '"]').text();
                    let html = '<div class="admin-form-group" style="margin-bottom:16px;"><label class="admin-form-label" style="font-weight:600;">' + variantName + '</label><div class="admin-form-grid">';
                    countries.forEach(function (country) {
                        html += '<div class="admin-form-group"><label class="admin-form-label">' + country.country_name + '</label><input type="text" name="prices[' + variantId + '][' + country.id + ']" value="0" class="admin-form-control" required></div>';
                    });
                    html += '</div></div>';
                    $('#variantsPricesContainer').append(html);
                });
            });
        });

        $(document).ready(function () {
            $(document).on('click', '.addImageInput', function () {
                var html = '<div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;">';
                html += '<input type="file" class="admin-form-control image-input" name="images[]" multiple>';
                html += '<div class="imagePreview"></div>';
                html += '<button type="button" class="btn-admin-sm removeImageInput" style="background:#ef4444;"><i class="fas fa-minus"></i></button></div>';
                $('#imageInputContainer').append(html);
            });
            $(document).on('click', '.removeImageInput', function () { $(this).closest('div').remove(); });
            $(document).on('change', '.image-input', function () {
                var file = this.files[0]; var reader = new FileReader();
                reader.onload = (function (el) { return function (e) { $(el).siblings('.imagePreview').empty().append('<img src="' + e.target.result + '" width="80" style="border-radius:6px;" />'); }; })(this);
                reader.readAsDataURL(file);
            });
        });

        $(document).ready(function () {
            $('#form_validation').on('submit', function (e) {
                e.preventDefault();
                $('.alert').addClass('d-none').empty();
                var formData = new FormData(this);
                $.ajax({
                    url: "{{ route('dashboard.products.store') }}", method: 'POST', data: formData, processData: false, contentType: false,
                    success: function (r) { alert('Product created successfully'); },
                    error: function (xhr) { if (xhr.status === 422) { var errors = xhr.responseJSON.errors; var ab = $('.alert'); $.each(errors, function (k, v) { ab.append('<p>' + v[0] + '</p>'); }); ab.removeClass('d-none').hide().slideDown(500); } }
                });
            });
        });

        if (document.querySelector('#editor')) {
            ClassicEditor.create(document.querySelector('#editor')).then(e => console.log(e)).catch(e => console.error(e));
        }
        $("#category_id").change(function () { $("#categoryForm").submit(); });
    </script>
@endsection
@section('styles')
    <style>
        .ck-editor__editable_inline {
            min-height: 200px;
        }
    </style>
@endsection