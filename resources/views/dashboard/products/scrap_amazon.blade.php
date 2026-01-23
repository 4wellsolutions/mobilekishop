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
      
      <!-- Amazon Scraping Form -->
      <div class="card mb-4">
        <div class="card-header">
          <h5>Fetch Amazon Product Data</h5>
        </div>
        <div class="card-body p-0">
          <form id="amazonScrapForm" action="" method="get">
            @csrf
            <div class="input-group mb-3">
              <input type="text" name="amazon_url" id="amazon_url" class="form-control" placeholder="Enter Amazon Product URL" required>
              <button class="btn btn-primary" type="submit" id="fetchButton">Fetch</button>
            </div>
          </form>
          <div id="scrapErrors" class="alert alert-danger d-none"></div>
        </div>
      </div>
      
      <!-- API Response Table -->
      <div class="card">
        <div class="card-header">
          <h5>Fetched Product Details</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-sm" id="apiResponseTable">
              <thead class="thead-light">
                <tr>
                  <th>Key</th>
                  <th>Value</th>
                  <th>Copy</th>
                </tr>
              </thead>
              <tbody>
                <!-- Dynamic Content -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
      
    </div>
  </div>
@stop

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.4.0/clipboard.min.js" integrity="sha512-iJh0F10blr9SC3d0Ow1ZKHi9kt12NYa+ISlmCdlCdNZzFwjH1JppRTeAnypvUez01HroZhAmP4ro4AvZ/rG0UQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
  $(document).ready(function() {
    // Initialize Clipboard.js
    var clipboard = new Clipboard('.copy-btn');

    clipboard.on('success', function(e) {
        // Show a temporary tooltip or alert
        $(e.trigger).tooltip({
            title: "Copied!",
            placement: "top",
            trigger: "manual"
        }).tooltip('show');

        setTimeout(function() {
            $(e.trigger).tooltip('hide');
        }, 1000);

        e.clearSelection();
    });

    clipboard.on('error', function(e) {
        alert('Failed to copy!');
    });

    // Handle Amazon Scraping Form Submission
    $('#amazonScrapForm').on('submit', function(e) {
    e.preventDefault();

    // Clear previous errors
    $('#scrapErrors').addClass('d-none').empty();

    // Get the input value
    var amazonUrl = $('#amazon_url').val();

    // Validate input (optional client-side validation)
    if (amazonUrl.trim() === '') {
        $('#scrapErrors').removeClass('d-none').text('Please enter a valid Amazon URL.');
        return;
    }

    // Disable the button and change its text to "Loading..."
    var fetchButton = $('#fetchButton');
    fetchButton.prop('disabled', true).text('Loading...');

    // Send AJAX request
    $.ajax({
        url: "{{ route('dashboard.products.scrap.amazon') }}",
        method: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            amazon_url: amazonUrl
        },
        beforeSend: function() {
            $('#loadingSpinner').removeClass('d-none');
        },
        success: function(response) {
            if (response.error) {
                $('#scrapErrors').removeClass('d-none').text(response.error); // Show validation error
            } else {
                var tbody = $('#apiResponseTable tbody');
                tbody.empty(); // Clear existing data

                // Loop through the response data and create rows for each key-value pair
                $.each(response.data, function(key, value) {
                    var displayValue = value;

                    // Handle nested arrays (details and overview)
                    if (key === "overview" || key === "details") {
                        // Remove the first element and join the rest
                        value.shift(); // Removes the first element from the array
                        displayValue = value.join(', '); // Join array elements into a string
                    }

                    // Handle other nested arrays or objects
                    if ($.isArray(value) && key !== "overview" && key !== "details") {
                        displayValue = value.join(', '); // Join array values into a string
                    } else if (typeof value === 'object') {
                        displayValue = JSON.stringify(value, null, 2); // Format object as string
                    }

                    var row = `
                        <tr>
                            <td>${key}</td>
                            <td>
                                <span>${displayValue}</span>
                            </td>
                            <td>
                              <button class="btn btn-secondary btn-sm copy-btn" data-clipboard-text='${displayValue}'>
                                    <i class="fa fa-copy"></i>
                              </button>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });

                // Show success message
                $('#scrapErrors').removeClass('d-none').removeClass('alert-danger').addClass('alert-success').text(response.success);
            }
        },
        error: function(xhr) {
            $('#scrapErrors').removeClass('d-none').removeClass('alert-success').addClass('alert-danger').text('An error occurred. Please try again.');
        },
        complete: function() {
            fetchButton.prop('disabled', false).text('Fetch');
            $('#loadingSpinner').addClass('d-none');
        }
    });
});



    // Initialize tooltips for copy buttons
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });
  });
</script>


@stop

@section('styles')
  <style type="text/css">
    .ck-editor__editable_inline {
      min-height: 200px;
    }
    pre {
      background-color: #f8f9fa;
      padding: 10px;
      border-radius: 4px;
      white-space: pre-wrap;
      word-wrap: break-word;
      margin: 0;
    }
  </style>
@stop
