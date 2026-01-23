@extends("layouts.dashboard")

@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Edit Robots.txt</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Edit Robots.txt
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    
    <div class="card">
        <div class="card-body">
          <h5 class="card-title">Edit Robots.txt</h5>

          <!-- Dropdown for selecting country -->
          <div class="form-group">
            <label for="country-select">Select Country</label>
            <select class="form-control" id="country-select">
              <option value="">Select Country</option>
              @foreach($countries as $country)
                <option value="{{ $country->country_code }}">{{ $country->country_name }}</option>
              @endforeach
            </select>
          </div>

          <!-- Textarea to display and edit robots.txt content -->
          <div class="form-group mt-4" id="robots-content-container" style="display:none;">
            <h6>Robots.txt Content</h6>
            <textarea class="form-control" id="robots-content" rows="10"></textarea>
            <button class="btn btn-primary mt-3" id="update-robots-btn">Update</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal for success/error messages -->
  <div class="modal" tabindex="-1" id="messageModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Status</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p id="modal-message"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

@stop

@section('scripts')
  <script>
    $(document).ready(function() {
      // Listen for country selection
      $('#country-select').change(function() {
        const countryCode = $(this).val();
        
        if (countryCode) {
          // Fetch robots.txt content via AJAX
          $.ajax({
            url: '/dashboard/robots/' + countryCode + '/edit',
            type: 'GET',
            success: function(response) {
              if (response.success) {
                $('#robots-content').val(response.content);
                $('#robots-content-container').show();
              } else {
                $('#robots-content-container').hide();
                showMessage(response.message, 'error');
              }
            },
            error: function() {
              showMessage('Something went wrong while fetching robots.txt file.', 'error');
            }
          });
        } else {
          $('#robots-content-container').hide();
        }
      });

      // Handle update robots.txt
      $('#update-robots-btn').click(function() {
        const countryCode = $('#country-select').val();
        const content = $('#robots-content').val();

        if (countryCode && content) {
          // Update robots.txt via AJAX
          $.ajax({
            url: '/dashboard/robots/' + countryCode + '/update',
            type: 'POST',
            data: {
              _token: "{{ csrf_token() }}",
              content: content
            },
            success: function(response) {
              if (response.success) {
                showMessage('Robots.txt updated successfully!', 'success');
              } else {
                showMessage(response.message, 'error');
              }
            },
            error: function() {
              showMessage('Error while updating robots.txt.', 'error');
            }
          });
        } else {
          showMessage('Please select a country and provide content to update.', 'error');
        }
      });

      // Function to display success/error messages
      function showMessage(message, type) {
        $('#modal-message').text(message);
        $('#messageModal').modal('show');
        if (type === 'success') {
          $('#modal-message').css('color', 'green');
        } else {
          $('#modal-message').css('color', 'red');
        }
      }
    });
  </script>
@stop
