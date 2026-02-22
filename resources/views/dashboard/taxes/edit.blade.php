@extends('layouts.dashboard')
@section('title', 'Edit PTA Tax')
@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Edit PTA Tax</h1>
      <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span class="separator">/</span><a
          href="{{ route('dashboard.taxes.index') }}">Taxes</a><span class="separator">/</span>Edit</div>
    </div>
  </div>
  @include('includes.info-bar')
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Tax Details</h2>
    </div>
    <div class="admin-card-body">
      <form method="post" action="{{ route('dashboard.taxes.update', $tax->id) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="admin-form-grid">
          <div class="admin-form-group"><label class="admin-form-label">Brand</label>
            <select class="admin-form-control" name="brand_id" id="brand_id" required>
              <option value="">Select Brand</option>
              @foreach(App\Models\Brand::get() as $brand)
                <option value="{{ $brand->id }}" {{ $brand->id == $tax->brand_id ? 'selected' : '' }}>{{ $brand->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Product</label>
            <select class="admin-form-control" name="product_id" id="product_id" required>
              <option value="{{ $tax->product_id }}">{{ $tax->product->name ?? 'Select Product' }}</option>
            </select>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Variant</label>
            <input type="text" class="admin-form-control" name="variant" value="{{ $tax->variant }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Tax On Passport</label>
            <input type="number" class="admin-form-control" name="tax_on_passport" value="{{ $tax->tax_on_passport }}">
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Tax On CNIC</label>
            <input type="number" class="admin-form-control" name="tax_on_cnic" value="{{ $tax->tax_on_cnic }}">
          </div>
        </div>
        <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i> Update
            Tax</button></div>
      </form>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    $(document).ready(function () {
      function loadProducts(brandId, selectedId) {
        if (brandId) {
          $.ajax({
            url: "{{ URL::to('/dashboard/product/brand/') }}/" + brandId,
            type: "GET", dataType: "json",
            success: function (data) {
              var sel = $('select[name="product_id"]');
              sel.empty();
              $.each(data, function (k, p) { sel.append(new Option(p.name, p.id, false, false)); });
              if (selectedId) sel.val(selectedId);
            }
          });
        } else { $('select[name="product_id"]').empty(); }
      }
      $('#brand_id').change(function () { loadProducts($(this).val()); });
      loadProducts($('#brand_id').val(), "{{ $tax->product_id }}");
    });
  </script>
@endsection