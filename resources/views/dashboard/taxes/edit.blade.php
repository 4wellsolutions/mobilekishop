@extends("layouts.dashboard")
@section("title", "Edit PTA Tax - MKS")
@section("content")
  <div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">PTA Tax</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pt-sm-1">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Edit PTA Tax
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <div class="row justify-content-center bg-white pt-4">
        <div class="col-12 col-md-10">
          <h1>Edit PTA Tax</h1>
              @include("includes.info-bar")
          <form class="pageForm" method="post" action="{{ route('dashboard.taxes.update', $tax->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Brand Dropdown --}}
            <div class="form-group">
              <label>Brand</label>
              <select class="form-control" name="brand_id" id="brand_id" required>
                <option>Select Brand</option>
                @if($brands = App\Brand::get())
                @foreach($brands as $brand)
                  <option value="{{ $brand->id }}" {{ $brand->id == $tax->brand_id ? 'selected' : '' }}>{{ $brand->name }}</option>
                @endforeach
                @endif
              </select>
            </div>

            {{-- Product Dropdown --}}
            <div class="form-group">
              <label>Product</label>
              <select class="form-control" name="product_id" id="product_id" required>
                <option value="{{ $tax->product_id }}">{{ $tax->product->name ?? 'Select Product' }}</option>
              </select>
            </div>

            {{-- Other fields pre-filled with existing data --}}
            <div class="form-group">
              <label for="variant">Variant</label>
              <input type="text" class="form-control" name="variant" id="variant" value="{{ $tax->variant }}">
            </div>
            <div class="form-group">
              <label for="tax_on_passport">Tax On Passport</label>
              <input type="number" class="form-control" name="tax_on_passport" id="tax_on_passport" value="{{ $tax->tax_on_passport }}">
            </div>
            <div class="form-group">
              <label for="tax_on_cnic">Tax On CNIC</label>
              <input type="number" class="form-control" name="tax_on_cnic" id="tax_on_cnic" value="{{ $tax->tax_on_cnic }}">
            </div>
            <div class="form-group my-3">
              <button class="btn btn-dark formButton">Update</button>
            </div>
          </form>
        </div>
      </div>

  </div>
@stop

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
  .twitter-typeahead{
    width: 100% !important;
  }
  .tt-menu{
    width: inherit !important;
    position: inherit !important;
  }.table td, .table th {
    padding: 0.5rem !important;
  }
</style>
@stop

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 on product_id
    $('#product_id').select2({
        placeholder: "Select a product",
        allowClear: true
    });

    // Function to load products based on the selected brand
    function loadProducts(brandId, selectedProductId) {
        if(brandId) {
            $.ajax({
                url: "{{ URL::to('/dashboard/product/brand/') }}/" + brandId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    var productSelect = $('select[name="product_id"]');
                    productSelect.empty();
                    $.each(data, function(key, product) {
                        productSelect.append(new Option(product.name, product.id, false, false));
                    });
                    // Set the selected product
                    if (selectedProductId) {
                        productSelect.val(selectedProductId).trigger('change');
                    }
                }
            });
        } else {
            $('select[name="product_id"]').empty().trigger('change');
        }
    }

    // Trigger change on brand_id to load products
    $('#brand_id').change(function() {
      console.log("{{ $tax->product_id }}");
      loadProducts($(this).val());
    });

    // Trigger change event on document ready to pre-load products
    loadProducts($('#brand_id').val(), "{{ $tax->product_id }}");
});
</script>




@stop
