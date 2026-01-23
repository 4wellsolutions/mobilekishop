@extends("layouts.dashboard")
@section('title', "Edit Product Prices - Dashboard")
@section("content")

<div class="page-wrapper bg-white">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Edit Product Prices</h4>
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

    <div class="container my-2 bg-white">
        @include("includes.info-bar")
        <form id="form_validation" action="{{ route('dashboard.products.price.store', $product->id) }}" enctype="multipart/form-data" method="post">
            @csrf
            <div class="row">
                <h1>{{ $product->name }}</h1>
                <div class="form-group col-12 col-md-6 col-lg-6">
                    <label for="">Slug</label>
                    <input type="text" id="slug" placeholder="Mobile into product slug here" class="form-control">
                </div>
                
                <div class="col-12 bg-white py-2">
                    <div class="form-group">
                        <button type="button" id="ajaxButton" class="btn btn-primary">
                            <span id="buttonText">Load Prices</span>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                        </button>
                        <div class="progress my-2" style="height: 20px; display: none;">
                            <div id="progressBar" class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div id="countryNotification" class="mt-2"></div>
                    </div>
                </div>
                <div class="row">
                @foreach ($countries as $country)
                    <input type="hidden" name="country_id[]" value="{{ $country->id }}">
                    <input type="hidden" name="product_id[]" value="{{ $product->id }}">

                    <div class="form-group col-12 col-md-3 col-lg-3">
                        <label for="">Country</label>
                        <input type="text" value="{{ $country->country_name }}" class="form-control" readonly>
                         <p class="fs-6">
                            <a href="{{ isset($countryUrls[$country->country_code]) ? $countryUrls[$country->country_code] : '#' }}" id="country_id_url_{{ $country->id }}" target="_blank">
                                Click here
                            </a>
                        </p>
                    </div>
                    
                    <div class="form-group col-12 col-md-3 col-lg-3">
                        <label for="">Price ({{ $country->currency }})</label>
                        <input type="number" name="price[]" class="form-control" id="price_{{ $country->id }}" data-country-url="{{ isset($countryUrls[$country->country_code]) ? $countryUrls[$country->country_code] : '' }}">
                    </div>
                @endforeach
                </div>

                <div class="col-12 bg-white py-4">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success text-white btn-block">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@stop

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        const countryUrls = [];
        const countryNames = [];

        @foreach ($countryUrls as $countryCode => $countryUrl)
            var countryUrl = "{{ $countryUrl }}";
            var countryName = "{{ $countries[$loop->index]->country_name }}";
            if (countryUrl) {
                countryUrls.push(countryUrl);
                countryNames.push(countryName);
            }
        @endforeach

        $('#ajaxButton').click(function() {
            // Disable the button and show the spinner
            $('#ajaxButton').prop('disabled', true);
            $('#buttonText').hide(); // Hide the button text
            $('.spinner-border').show(); // Show the spinner
            $(".progress").show(); // Show progress bar
            $('#progressBar').css('width', '0%'); // Reset progress bar
            $('#countryNotification').text(''); // Clear previous notification
            loadPricesSequentially(countryUrls, countryNames); // Start loading prices
        });
        
        function loadPricesSequentially(urls, countries) {
            let index = 0;
            const total = urls.length;

            function loadNext() {
                if (index < total) {
                    const countryUrl = urls[index];
                    const countryName = countries[index];
                    $.ajax({
                        url: "{{ route('dashboard.products.price.getPrices') }}",
                        type: "GET",
                        data: { url: countryUrl, slug: $("#slug").val() },
                        success: function(response) {
                            if (response.success) {
                                $('#price_' + response.countryId).val(response.price);
                                $('#countryNotification').text('Prices for ' + response.country + ' have been added.');
                                $('#country_id_url_' + response.countryId).attr('href', response.url);
                            } else {
                                $('#price_' + response.countryId).val(0);
                                $('#countryNotification').text('No prices found for ' + response.country);
                            }
                            index++;
                            updateProgress();
                            loadNext();
                        },
                        error: function(xhr, status, error) {
                            $('#price_' + urls[index].countryId).val(0);
                            $('#countryNotification').text('Failed to load prices for ' + response.country);
                            index++;
                            updateProgress();
                            loadNext();
                        }
                    });
                } else {
                    // Re-enable the button and hide the spinner after all requests
                    $('#ajaxButton').prop('disabled', false);
                    $('#buttonText').show(); // Show the button text
                    $('.spinner-border').hide(); // Hide the spinner
                }
            }

            function updateProgress() {
                const percentage = Math.floor((index / total) * 100);
                $('#progressBar').css('width', percentage + '%').attr('aria-valuenow', percentage);
                if (percentage >= 100) {
                    $(".progress").hide(); // Hide progress bar when done
                }
            }

            loadNext();
        }
    });
</script>

@stop