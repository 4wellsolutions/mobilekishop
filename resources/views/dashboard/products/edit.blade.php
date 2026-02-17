@extends("layouts.dashboard")
@section('title', "Edit Product - Dashboard")
@section("content")

<div class="page-wrapper bg-white">
    <!-- Breadcrumb Section -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Edit Product</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.products.index') }}">Products</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container bg-white my-2">
        @include("includes.info-bar")

        <!-- Edit Product Form -->
        <form id="form_validation" action="{{ route('dashboard.products.update', $product->id) }}"
            enctype="multipart/form-data" method="post">
            @csrf
            @method('PUT')
            <div class="alert alert-danger d-none" role="alert"></div>
            <div class="row">

                <!-- Brand Selection -->
                <div class="form-group col-12 col-md-6">
                    <label for="brand_id">Brand</label>
                    <select name="brand_id" id="brand_id" class="form-control" required>
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Product Name -->
                <div class="form-group col-12 col-md-6">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                        class="form-control" required>
                </div>

                <!-- Color Selection -->
                @if($colors->count())
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="colors">Select Colors</label>
                            <select name="colors[]" id="colors" class="form-control" style="height: 100px;" multiple
                                required>
                                @foreach($colors as $color)
                                    <option value="{{ $color->id }}" {{ in_array($color->id, $product->colors->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $color->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                <!-- Variant Selection -->
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="variants">Select Variants</label>
                        <select name="variants[]" id="variants" class="form-control" style="height: 100px;" multiple
                            required>
                            @foreach($variants as $variant)
                                <option value="{{ $variant->id }}" data-index="{{ $loop->index }}" {{ in_array($variant->id, $product->variants->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $variant->variant_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Color Image Uploads -->
                <div class="col-12 col-md-6">
                    <div id="colorImageUploadContainer">
                        @foreach($product->colors as $color)
                            <div class="form-group col-12 col-md-6">
                                <label for="color_images_{{ $color->id }}">Upload image for {{ $color->name }}</label>
                                <input type="file" name="color_images[{{ $color->id }}]" id="color_images_{{ $color->id }}"
                                    accept="image/*" class="form-control">
                                @if($color->pivot->image)
                                    <img src="{{ $color->pivot->image }}" alt="{{ $color->name }}" width="100"
                                        class="img-thumbnail mt-2">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Variant Prices -->
                <div class="col-12">
                    <input type="hidden" id="countriesData" value="{{ $countries->toJson() }}">
                    <div class="row" id="variantsPricesContainer">
                        @foreach($variants as $variant)
                            @if($product->variants->contains($variant->id))
                                <div class="form-group country-price-group">
                                    <label>Country Prices for {{ $variant->variant_name }}</label>
                                    <div class="country-prices row" data-variant-id="{{ $variant->id }}">
                                        @foreach($countries as $country)
                                            <div class="form-group col-6 col-md-3">
                                                <label
                                                    for="price_{{ $variant->id }}_{{ $country->id }}">{{ $country->country_name }}</label>
                                                <input type="text" name="prices[{{ $variant->id }}][{{ $country->id }}]"
                                                    id="price_{{ $variant->id }}_{{ $country->id }}"
                                                    value="{{ old('prices.' . $variant->id . '.' . $country->id, $variantPrices[$variant->id][$country->id] ?? 0) }}"
                                                    class="form-control" required>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Divider -->
                <div class="col-12">
                    <hr class="mx-3 my-1">
                    <hr class="mx-4 my-1">
                    <hr class="mx-5 my-1">
                </div>

                <!-- Release Date -->
                <div class="form-group col-12 col-md-6">
                    <label for="release_date">Release Date </label>
                    <input type="date" name="release_date" id="release_date"
                        value="{{ old('release_date', $product->release_date ? $product->release_date->format('Y-m-d') : '') }}"
                        class="form-control">

                </div>

                <!-- Custom Attributes -->
                @php
                    $category = $product->category;
                  @endphp
                @if(
                        $attributes = $category->attributes()
                            ->orderBy("sequence", "ASC")
                            ->whereNotIn('name', ['release_date', 'thumbnail'])
                            ->get()
                    )
                    @foreach($attributes as $attribute)
                        <div class="form-group col-12 col-md-6">
                            <label for="attribute_{{ $attribute->id }}">{{ $attribute->label }}</label>
                            <input type="text" name="{{ $attribute->id }}" id="attribute_{{ $attribute->id }}"
                                value="{{ old($attribute->id, $product->attributes->where('id', $attribute->id)->first()->pivot->value ?? '') }}"
                                class="form-control">
                        </div>
                    @endforeach
                @endif

                <!-- Body -->
                <div class="col-12">
                    <div class="form-group">
                        <label for="body">Body</label>
                        <textarea class="form-control" name="body" id="editor"
                            rows="5">{{ old('body', $product->body) }}</textarea>
                    </div>
                </div>

                <!-- Product Images -->
                <div class="col-12 col-md-6">
                    <div id="imageInputContainer" class="form-group">
                        <div class="mb-3 input-group">
                            <input type="file" class="form-control" name="images[]" accept="image/*" multiple>
                            <button type="button" class="btn btn-primary addImageInput">+</button>
                        </div>
                        @foreach($product->images as $image)
                            <div class="mb-3 input-group">
                                <img src="{{ url('/products/' . $image->name) }}" alt="{{ $image->alt }}" width="100"
                                    class="img-thumbnail">
                                <input type="file" class="form-control image-input" name="images[]" accept="image/*"
                                    multiple>
                                <button type="button" class="btn btn-danger removeImageInput">-</button>
                            </div>
                        @endforeach
                    </div>
                    <div id="imagePreviewContainer" class="d-flex flex-wrap">
                        @foreach($product->images as $image)
                            <img src="{{ $image->url }}" width="100" class="img-thumbnail m-1">
                        @endforeach
                    </div>
                </div>

                <!-- Expert Rating Section -->
                <div class="col-12 mt-4">
                    <hr>
                    <h4 class="mb-3"><i class="fas fa-star text-warning me-2"></i>Expert Rating</h4>
                    <p class="text-muted small mb-4">Rate this product on a scale of 0–10 for each criterion. The
                        overall score is auto-calculated.</p>
                </div>

                <div class="col-md-8">
                    @php
                        $ratingCriteria = [
                            'design' => ['label' => 'Design & Build', 'icon' => 'fas fa-palette'],
                            'display' => ['label' => 'Display', 'icon' => 'fas fa-tv'],
                            'performance' => ['label' => 'Performance', 'icon' => 'fas fa-microchip'],
                            'camera' => ['label' => 'Camera', 'icon' => 'fas fa-camera'],
                            'battery' => ['label' => 'Battery Life', 'icon' => 'fas fa-battery-full'],
                            'value_for_money' => ['label' => 'Value for Money', 'icon' => 'fas fa-tag'],
                        ];
                      @endphp

                    @foreach($ratingCriteria as $key => $info)
                        <div class="form-group col-12 mb-3">
                            <label class="fw-bold">
                                <i class="{{ $info['icon'] }} me-2 text-primary"></i>{{ $info['label'] }}
                            </label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="range" class="form-range flex-grow-1 expert-slider" name="expert_{{ $key }}"
                                    min="0" max="10" step="0.5"
                                    value="{{ old('expert_' . $key, $expertRating->$key ?? 0) }}"
                                    oninput="document.getElementById('expert-score-{{ $key }}').textContent = this.value; updateExpertOverall();">
                                <span id="expert-score-{{ $key }}" class="badge bg-primary px-3 py-2"
                                    style="min-width:50px; font-size:16px;">
                                    {{ old('expert_' . $key, $expertRating->$key ?? 0) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="col-md-4">
                    <!-- Live Overall Preview -->
                    <div class="card mb-3">
                        <div class="card-body text-center">
                            <h5 class="card-title">Overall Score</h5>
                            <div id="expert-overall-preview" class="display-3 fw-bold text-primary mb-2">
                                {{ $expertRating->overall ?? '0.0' }}
                            </div>
                            <p class="text-muted mb-0" id="expert-overall-label">
                                {{ $expertRating->id ? $expertRating->getLabel() : 'Not Rated' }}
                            </p>
                        </div>
                    </div>

                    <!-- Verdict -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <label class="form-label fw-bold">Expert Verdict</label>
                            <textarea name="expert_verdict" class="form-control" rows="4"
                                placeholder="Write your expert verdict...">{{ old('expert_verdict', $expertRating->verdict) }}</textarea>
                        </div>
                    </div>

                    <!-- Rated By -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <label class="form-label fw-bold">Rated By</label>
                            <input type="text" name="expert_rated_by" class="form-control" placeholder="Expert name"
                                value="{{ old('expert_rated_by', $expertRating->rated_by) }}">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="col-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success text-white btn-block">Update</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@stop

@section('scripts')
<!-- CKEditor -->
<script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const colorsSelect = document.getElementById('colors');
        const colorImageUploadContainer = document.getElementById('colorImageUploadContainer');

        colorsSelect.addEventListener('change', function () {
            colorImageUploadContainer.innerHTML = '';

            Array.from(this.selectedOptions).forEach(option => {
                const colorId = option.value;
                const colorName = option.text;

                const formGroup = document.createElement('div');
                formGroup.classList.add('form-group', 'col-12', 'col-md-6');

                const label = document.createElement('label');
                label.textContent = `Upload image for ${colorName}`;
                formGroup.appendChild(label);

                const input = document.createElement('input');
                input.type = 'file';
                input.name = `color_images[${colorId}]`;
                input.accept = 'image/*';
                input.classList.add('form-control');
                formGroup.appendChild(input);

                colorImageUploadContainer.appendChild(formGroup);
            });
        });
    });

    $(document).ready(function () {
        // Parse the variantPrices passed from the controller
        let variantPricesData = @json($variantPrices);
        let countries = JSON.parse($('#countriesData').val());

        /**
         * Function to populate variant prices
         * @param {Array} selectedVariants - Array of selected variant IDs
         * @param {Object} variantPrices - Object containing variant prices
         */
        function populateVariantPrices(selectedVariants, variantPrices) {
            $('#variantsPricesContainer').empty();

            selectedVariants.forEach(function (variantId) {
                // Get the variant name
                let variantName = $('#variants option[value="' + variantId + '"]').text();

                // Create a container for this variant's prices
                let variantContainer = `
                    <div class="form-group country-price-group">
                        <label>Country Prices for ${variantName}</label>
                        <div class="country-prices row" data-variant-id="${variantId}"></div>
                    </div>
                `;

                $('#variantsPricesContainer').append(variantContainer);

                // Loop through each country and create a price field
                countries.forEach(function (country) {
                    // Fetch the existing price from variantPricesData
                    let existingPrice = (variantPrices[variantId] && variantPrices[variantId][country.id]) ? variantPrices[variantId][country.id] : 0;

                    let countryPriceField = `
                        <div class="form-group col-6 col-md-3">
                            <label for="price_${variantId}_${country.id}">${country.country_name}</label>
                            <input type="text" name="prices[${variantId}][${country.id}]" id="price_${variantId}_${country.id}" value="${existingPrice}" class="form-control" required>
                        </div>
                    `;
                    $(`.country-prices[data-variant-id="${variantId}"]`).append(countryPriceField);
                });
            });
        }

        // Event listener for variants selection change
        $('#variants').on('change', function () {
            let selectedVariants = $(this).val();
            if (selectedVariants) {
                populateVariantPrices(selectedVariants, variantPricesData);
            } else {
                $('#variantsPricesContainer').empty();
            }
        });

        // Trigger change on page load to populate existing variant prices
        let initiallySelectedVariants = $('#variants').val();
        if (initiallySelectedVariants) {
            populateVariantPrices(initiallySelectedVariants, variantPricesData);
        }
    });

    // Image Input Handling
    $(document).ready(function () {
        $(document).on('click', '.addImageInput', function () {
            var html = '';
            html += '<div class="mb-3 input-group">';
            html += '<input type="file" class="form-control image-input" name="images[]" accept="image/*" multiple>';
            html += '<div class="imagePreview"></div>';
            html += '<button type="button" class="btn btn-danger removeImageInput">-</button>';
            html += '</div>';
            $('#imageInputContainer').append(html);
        });

        $(document).on('click', '.removeImageInput', function () {
            $(this).closest('.input-group').remove();
        });

        $(document).on('change', '.image-input', function () {
            var file = this.files[0];
            var reader = new FileReader();

            reader.onload = (function (inputElement) {
                return function (e) {
                    $(inputElement).siblings('.imagePreview').empty();
                    $(inputElement).siblings('.imagePreview').append('<img src="' + e.target.result + '" width="100" class="img-thumbnail" />');
                };
            })(this);

            reader.readAsDataURL(file);
        });
    });

    // Form Submission via AJAX
    $(document).ready(function () {
        $('#form_validation').on('submit', function (e) {
            e.preventDefault();

            // Clear previous errors
            $('.alert').addClass('d-none').empty();

            // Prepare the form data
            var formData = new FormData(this);

            $.ajax({
                url: "{{ route('dashboard.products.update', $product->id) }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    // Handle success response
                    alert('Product updated successfully');
                    window.location.href = response.url;
                },
                error: function (xhr) {
                    // Handle validation errors
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var alertBox = $('.alert');
                        $.each(errors, function (key, value) {
                            alertBox.append('<p>' + value[0] + '</p>');
                        });
                        alertBox.removeClass('d-none').hide().slideDown(500);
                    } else {
                        alert('An unexpected error occurred.');
                    }
                }
            });
        });
    });

    // Initialize CKEditor
    ClassicEditor
        .create(document.querySelector('#editor'))
        .then(editor => {
            console.log(editor);
        })
        .catch(error => {
            console.error(error);
        });
    // Expert Rating: Live overall score calculation
    function updateExpertOverall() {
        const sliders = document.querySelectorAll('.expert-slider');
        const overallEl = document.getElementById('expert-overall-preview');
        const labelEl = document.getElementById('expert-overall-label');

        if (!overallEl || !sliders.length) return;

        let total = 0;
        sliders.forEach(s => total += parseFloat(s.value));
        const avg = (total / sliders.length).toFixed(1);
        overallEl.textContent = avg;

        if (avg >= 9) labelEl.textContent = 'Exceptional';
        else if (avg >= 8) labelEl.textContent = 'Excellent';
        else if (avg >= 7) labelEl.textContent = 'Very Good';
        else if (avg >= 6) labelEl.textContent = 'Good';
        else if (avg >= 5) labelEl.textContent = 'Average';
        else labelEl.textContent = 'Below Average';

        overallEl.classList.remove('text-success', 'text-primary', 'text-warning', 'text-danger');
        if (avg >= 8) overallEl.classList.add('text-success');
        else if (avg >= 6) overallEl.classList.add('text-primary');
        else if (avg >= 4) overallEl.classList.add('text-warning');
        else overallEl.classList.add('text-danger');
    }

    // Run on page load
    document.addEventListener('DOMContentLoaded', function () {
        updateExpertOverall();
    });
</script>

<script>
    // Prevent accidental form submissions or handle deletions if necessary
    $('.deleteButton').click(function () {
        if (confirm('Are you sure?')) {
            var url = $(this).data('url');
            $('.deleteButton').attr("href", url);
        }
    });

    // Handle category change if applicable
    $("#category_id").change(function () {
        $("#categoryForm").submit();
    });
</script>
@stop

@section('styles')
<style type="text/css">
    .ck-editor__editable_inline {
        min-height: 200px;
    }
</style>
@stop