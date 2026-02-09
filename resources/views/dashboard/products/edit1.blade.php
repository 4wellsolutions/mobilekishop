@extends("layouts.dashboard")
@section('title', "Edit Product - Dashboard")
@section("content")

<div class="page-wrapper bg-white">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Edit Product</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Products
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container bg-white my-2">
        @include("includes.info-bar")
        <form id="form_validation" action="{{ route('dashboard.products.update', $product->id)}}" enctype="multipart/form-data" method="post">
            @csrf
            @method('PUT')
            <input type="hidden" name="category_id" value="{{ $product->category_id }}">
            <div class="alert alert-danger d-none" role="alert"></div>
            <div class="row">
                <div class="form-group col-12 col-md-6">
                    <label for="brand_id">Brand</label>
                    <select name="brand_id" id="brand_id" class="form-control">
                        <option>Select Brand</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="name">Name</label>
                    <input type="text" name="name" value="{{ $product->name }}" class="form-control">
                </div>
                @if($colors = App\Models\Color::all())
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="colors">Select Colors</label>
                        <select name="colors[]" id="colors" class="form-control" style="height: 100px;" multiple>
                            @foreach($colors as $color)
                            <option value="{{ $color->id }}" {{ in_array($color->id, $product->colors->pluck('id')->toArray()) ? 'selected' : '' }}>
                                {{ $color->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
                <div class="col-12 col-md-6">
                    <div class="col-12" id="colorImageUploadContainer">
                        @foreach($product->colors as $productColor)
                        <div class="form-group col-12 col-md-6 color-image-group" data-color-id="{{ $productColor->id }}">
                            <label>Existing image for {{ $productColor->name }}</label>
                            <img src="{{ URL::to('/products/') . '/' . $productColor->pivot->image }}" class="img-fluid" style="max-height: 100px;">
                            <label>Upload new image for {{ $productColor->name }}</label>
                            <input type="file" name="color_images[{{ $productColor->id }}]" accept="image/*" class="form-control">
                            <button type="button" class="btn btn-danger removeColorImage" data-color-id="{{ $productColor->id }}">Remove</button>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-12">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="specifications">Select Variants</label>
                                <select name="variants[]" id="variants" class="form-control" style="height: 100px;" multiple required>
                                    @foreach($variants as $variant)
                                    <option value="{{ $variant->id }}" {{ in_array($variant->id, $product->variants->pluck('id')->toArray()) ? 'selected' : '' }} data-index="{{ $loop->index }}">
                                        {{ $variant->variant_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <input type="hidden" id="countriesData" value="{{ $countries->toJson() }}">
                            <div id="variantsPricesContainer"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <hr class="mx-3 my-1">
                    <hr class="mx-4 my-1">
                    <hr class="mx-5 my-1">
                </div>
                <div class="form-group col-12 col-md-12 col-lg-6">
                    <label for="">Release Date</label>
                    <input type="date" name="release_date" value="{{ $product->release_date }}" class="form-control">
                </div>
                @php
                    $categoryAttributes = $product->category->attributes()->orderBy("sequence","ASC")->get();
                @endphp



                @foreach($categoryAttributes as $attribute)
                    @if($attribute->name !== 'release_date' && $attribute->name !== 'thumbnail')
                        @php
                            $attributeValue = $product->attributes()->where("attribute_id", $attribute->id)->first();
                            $attributeTextValue = $attributeValue ? $attributeValue->pivot->value : null;
                        @endphp
                        <div class="form-group col-12 col-md-12 col-lg-6">
                            <label for="">{{ $attribute->label }}</label>
                            @if($attribute->type == 'input')
                                <input type="text" name="attributes[{{ $attribute->id }}]" value="{{ $attributeTextValue }}" class="form-control">
                            @elseif($attribute->type == 'text')
                                <textarea name="attributes[{{ $attribute->id }}]" rows="5" class="form-control">{!! $attributeTextValue !!}</textarea>
                            @elseif($attribute->type == 'number')
                                <input type="number" name="attributes[{{ $attribute->id }}]" value="{{ $attributeTextValue }}" class="form-control">
                            @endif
                        </div>
                    @endif
                @endforeach



                <div class="col-12">
                    <div class="form-group">
                        <label for="body">Body</label>
                        <textarea class="form-control" id="body" name="body" id="editor" rows="5">{{ $product->body }}</textarea>
                    </div>
                </div>

                <div class="col-9 col-md-5 col-lg-4">
                    <div id="imageInputContainer" class="form-group">
                        <div class="mb-3 input-group">
                            <input type="file" class="form-control" name="images[]" accept="image/*" multiple>
                            <button type="button" class="btn btn-primary addImageInput">+</button>
                        </div>
                    </div>
                    <div id="imagePreviewContainer" class="d-flex flex-wrap"></div>
                </div>

                <div class="col-12">
                    <div class="col-12 bg-white py-4">
                        <div class="form-group">
                            <button class="btn btn-success text-white btn-block">Submit</button>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</div>
@stop

@section('scripts')
<script>
    $(document).ready(function() {
        const colorsSelect = $('#colors');
        const colorImageUploadContainer = $('#colorImageUploadContainer');

        colorsSelect.on('change', function() {
            colorImageUploadContainer.empty();

            $(this).find('option:selected').each(function() {
                const colorId = $(this).val();
                const colorName = $(this).text();

                // Check if the color already has an image
                let existingImage = '';
                @foreach($product->colors as $productColor)
                    if ({{ $productColor->id }} == colorId) {
                        existingImage = '{{ asset($productColor->pivot->image) }}'; // Ensure the full URL is used
                    }
                @endforeach

                const formGroup = $('<div></div>').addClass('form-group col-12 col-md-6 color-image-group').attr('data-color-id', colorId);
                if (existingImage) {
                    const existingImageLabel = $('<label></label>').text(`Existing image for ${colorName}`);
                    const existingImageView = $('<img>').attr('src', existingImage).addClass('img-fluid').css('max-height', '100px');
                    formGroup.append(existingImageLabel).append(existingImageView);
                }
                const label = $('<label></label>').text(`Upload image for ${colorName}`);
                const input = $('<input>').attr({
                    type: 'file',
                    name: `color_images[${colorId}]`,
                    accept: 'image/*',
                    required: !existingImage // Make the input required if no existing image
                }).addClass('form-control');
                const removeButton = $('<button></button>').attr({
                    type: 'button',
                    'data-color-id': colorId
                }).addClass('btn btn-danger removeColorImage').text('Remove');

                formGroup.append(label).append(input).append(removeButton);
                colorImageUploadContainer.append(formGroup);
            });
        });

        // Handle removing color image
        $(document).on('click', '.removeColorImage', function() {
            const colorId = $(this).data('color-id');
            const productId = '{{ $product->id }}'; // Assuming product ID is available in the template

            $.ajax({
                url: `/dashboard/product/${productId}/color/${colorId}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}' // Assuming you have CSRF token in your Blade template
                },
                success: function(response) {
                    if (response.success) {
                        $(`.color-image-group[data-color-id="${colorId}"]`).remove();
                        $(`#colors option[value="${colorId}"]`).prop('selected', false).trigger('change');
                    }
                }
            });
        });

        // Trigger change event to load images for already selected colors
        colorsSelect.trigger('change');
    });

    $(document).ready(function() {
        let productVariants = @json($product->variants->map(function($variant) {
            return [
                'id' => $variant->id,
                'pivot_country_id' => $variant->pivot->country_id,
                'pivot_price' => $variant->pivot->price
            ];
        }));

        console.log(productVariants);
        // Parse the countries data from a hidden input field
        let countries = JSON.parse($('#countriesData').val());

        function loadVariantPrices() {
            // Clear the previous variant prices container
            $('#variantsPricesContainer').empty();
            let selectedVariants = $('#variants').val();

            if (selectedVariants.length === 0) {
                return;
            }

            // Loop through selected variants
            selectedVariants.forEach(function(variantId) {
                let variantName = $('#variants option[value="' + variantId + '"]').text();
                let variantContainer = `
                    <div class="form-group country-price-group">
                        <label>Country Prices for ${variantName}</label>
                        <div class="row country-prices" data-variant-id="${variantId}"></div>
                    </div>
                `;
                $('#variantsPricesContainer').append(variantContainer);

                // Loop through each country and create a price input field
                countries.forEach(function(country) {
                    let countryPrice = getCountryPrice(variantId, country.id);
                    let countryPriceField = `
                        <div class="col-12 col-md-6 form-group">
                            <label for="price_${variantId}_${country.id}">${country.country_name} ${country.currency}</label>
                            <input type="text" name="prices[${variantId}][${country.id}]" id="price_${variantId}_${country.id}" class="form-control" value="${countryPrice}" required>
                        </div>
                    `;
                    $(`.country-prices[data-variant-id="${variantId}"]`).append(countryPriceField);
                });
            });
        }

        function getCountryPrice(variantId, countryId) {
            let price = '';

            productVariants.forEach(function(variant) {
                if (variant.id == variantId && variant.pivot_country_id == countryId) {
                    price = variant.pivot_price;
                }
            });

            return price;
        }

        // Load variant prices when the variants select field changes
        $('#variants').on('change', loadVariantPrices);
        // Initial load of variant prices
        loadVariantPrices();
    });

    $(document).ready(function() {
        $(document).on('click', '.addImageInput', function() {
            var html = '';
            html += '<div class="mb-3 input-group">';
            html += '<input type="file" class="form-control image-input" name="images[]" multiple>';
            html += '<div class="imagePreview"></div>';
            html += '<button type="button" class="btn btn-danger removeImageInput">-</button>';
            html += '</div>';
            $('#imageInputContainer').append(html);
        });

        $(document).on('click', '.removeImageInput', function() {
            $(this).closest('.input-group').remove();
        });

        $(document).on('change', '.image-input', function() {
            var file = this.files[0];
            var reader = new FileReader();

            reader.onload = (function(inputElement) {
                return function(e) {
                    $(inputElement).siblings('.imagePreview').empty();
                    $(inputElement).siblings('.imagePreview').append('<img src="'+ e.target.result +'" width="100" class="img-thumbnail" />');
                };
            })(this);

            reader.readAsDataURL(file);
        });
    });

    $(document).ready(function () {
        $('#form_validation').on('submit', function (e) {
            e.preventDefault();
            $('.alert').addClass('d-none').empty();
            // Get the textarea content
            $('textarea').each(function() {
                // Get the current textarea
                var $textarea = $(this);
                var content = $textarea.val();
                
                // Convert new lines and carriage returns to <br> tags
                var contentWithBr = content.replace(/(?:\r\n|\r|\n)/g, '<br>');

                // Replace the textarea value with the modified content
                $textarea.val(contentWithBr);
            });
            var formData = new FormData(this);

            $.ajax({
                url: "{{route('dashboard.products.update', $product->id)}}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert('Product updated successfully');
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var alertBox = $('.alert');
                        $.each(errors, function (key, value) {
                            alertBox.append('<p>' + value[0] + '</p>');
                        });
                        alertBox.removeClass('d-none').hide().slideDown(500);
                    }
                }
            });
        });
    });

    ClassicEditor
        .create(document.querySelector('#editor'))
        .then(editor => {
            console.log(editor);
        })
        .catch(error => {
            console.error(error);
        });

    $('.deleteButton').click(function() {
        if (confirm('Are you sure?')) {
            var url = $(this).data('url');
            $('.deleteButton').attr("href", url);
        }
    });

    $("#category_id").change(function(){
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
