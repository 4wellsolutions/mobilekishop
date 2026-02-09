@extends("layouts.dashboard")
@section('title',"Create Product - Dashboard")
@section("content")

  <div class="page-wrapper bg-white">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Create Product</h4>
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
        @if(!Request::has("category_id"))
        <form action="" method="get" id="categoryForm">
            <div class="form-group col-12 col-md-12">
                <label for="">Category</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option>Select Category</option>
                     @foreach($categories as $category)
                        <option value="{{$category->id}}">{{ $category->category_name }}</option>
                     @endforeach
                </select>
            </div>
        </form>
        @else
        <form id="form_validation" action="{{ route('dashboard.products.store')}}" enctype="multipart/form-data" method="post">
            <input type="hidden" name="category_id" value="{{\Request::get('category_id')}}">
            @csrf
            <div class="alert alert-danger d-none" role="alert"></div>
            <div class="row">
                <div class="form-group col-12 col-md-6">
                    <label for="">Brand</label>
                    <select name="brand_id" id="brand_id" class="form-control">
                        <option>Select Brand</option>
                         @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                         @endforeach
                    </select>
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="">Name</label>
                    <input type="text" name="name" value="{{old('name')}}" class="form-control">
                </div>
                @if($colors = App\Models\Color::all())
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="colors">Select Colors</label>
                        <select name="colors[]" id="colors" class="form-control" style="height: 100px;" multiple>
                            @foreach($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
                
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="specifications">Select Variants</label>
                        <select name="variants[]" id="variants" class="form-control" style="height: 100px;" multiple required>
                            @foreach($variants as $variant)
                            <option value="{{ $variant->id }}" data-index="{{ $loop->index }}">
                                {{ $variant->variant_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="col-12" id="colorImageUploadContainer"></div>
                </div>     
                <div class="col-12 col-md-12">
                    <input type="hidden" id="countriesData" value="{{ $countries->toJson() }}">
                    <div class="row" id="variantsPricesContainer"></div>
                </div>
                
                <div class="col-12">
                    <hr class="mx-3 my-1">
                    <hr class="mx-4 my-1">
                    <hr class="mx-5 my-1">
                </div>
                <div class="form-group col-12 col-md-12 col-lg-6">
                    <label for="">Release Date</label>
                    <input type="date" name="release_date" value="{{old('release_date')}}" class="form-control">
                </div>
                @php
                    $category = App\Models\Category::find(Request::get('category_id'));

                @endphp
                @if($attributes = $category->attributes()
                ->orderBy("sequence","ASC")
                ->where('name', '!=', 'release_date')
                ->where('name', '!=', 'thumbnail')
                ->get())

                @foreach($attributes as $attribute)
                    <div class="form-group col-12 col-md-12 col-lg-6">
                        <label for="">{{ $attribute->label }}</label>
                        <input type="text" name="{{ $attribute->id }}" value="{{old($attribute->id)}}" class="form-control">
                    </div>
                @endforeach
                @endif
                
                <div class="col-12">
                    <div class="form-group ">
                        <label for="">Body</label>
                        <textarea class="form-control" name="body" id="editor" rows="5">
                        </textarea>
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
    @endif
  </div>
@stop

@section('scripts')
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

$(document).ready(function() {
    // Get the countries data from the hidden input
    let countries = JSON.parse($('#countriesData').val());

    console.log('Countries data:', countries); // Debugging line

    $('#variants').on('change', function() {
        console.log('Variant selection changed.'); // Debugging line

        // Clear the existing country price fields
        $('#variantsPricesContainer').empty();
        
        // Get the selected variants
        let selectedVariants = $(this).val();
        
        console.log('Selected variants:', selectedVariants); // Debugging line

        if (selectedVariants.length === 0) {
            return;
        }

        // Loop through each selected variant
        selectedVariants.forEach(function(variantId) {
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
            countries.forEach(function(country) {
                let countryPriceField = `
                    <div class="form-group col-6 col-md-3">
                        <label for="price_${variantId}_${country.id}">${country.country_name}</label>
                        <input type="text" name="prices[${variantId}][${country.id}]" id="price_${variantId}_${country.id}" value="0" class="form-control" required>
                    </div>
                `;
                $(`.country-prices[data-variant-id="${variantId}"]`).append(countryPriceField);
            });
        });
    });
});


</script>

<script>
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
</script>
<script>
    $(document).ready(function () {
        $('#form_validation').on('submit', function (e) {
            e.preventDefault();

            // Clear previous errors
            $('.alert').addClass('d-none').empty();

            // Prepare the form data
            var formData = new FormData(this);

            $.ajax({
                url: "{{route('dashboard.products.store')}}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    // Handle success response
                    alert('Product created successfully');
                    // Redirect or update the UI as needed
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
                    }
                }
            });
        });
    });
</script>



    <script type="text/javascript">
      $(".plusSign").click(function(){
        console.log("Asdf");
        $(".images").append($(".bigImages:first").clone().val(""));
      });

    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .then( editor => {
                console.log( editor );
        } )
        .catch( error => {
                console.error( error );
        } );

    $('.deleteButton').click(function() {
        if (confirm('Are you sure?')) {
          var url = $(this).data('url');
          $('.deleteButton').attr("href",url);
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