@extends('layouts.dashboard')
@section('title', 'Add PTA Tax')
@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Add PTA Tax</h1>
      <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span class="separator">/</span><a
          href="{{ route('dashboard.taxes.index') }}">Taxes</a><span class="separator">/</span>Create</div>
    </div>
  </div>
  @include('includes.info-bar')
  <div class="admin-card">
    <div class="admin-card-header">
      <h2>Tax Details</h2>
    </div>
    <div class="admin-card-body">
      <form method="post" action="{{ route('dashboard.taxes.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="admin-form-grid">
          <div class="admin-form-group"><label class="admin-form-label">Brand</label>
            <select class="admin-form-control" name="brand_id" id="brand_id" required>
              <option value="">Select Brand</option>
              @foreach(App\Models\Brand::get() as $brand)
                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Product</label>
            <select class="admin-form-control" name="product_id" id="product_id" required>
              <option value="">Select Product</option>
            </select>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Variant</label>
            <input type="text" class="admin-form-control" name="variant" required>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Tax On Passport</label>
            <input type="number" class="admin-form-control" name="tax_on_passport" required>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Tax On CNIC</label>
            <input type="number" class="admin-form-control" name="tax_on_cnic" required>
          </div>
        </div>
        <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i> Create
            Tax</button></div>
      </form>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    $(document).ready(function () {
      $('#brand_id').change(function () {
        var brandId = $(this).val();
        if (brandId) {
          $.ajax({
            url: "{{ URL::to('/dashboard/product/brand/') }}/" + brandId,
            type: "GET", dataType: "json",
            success: function (data) {
              $('select[name="product_id"]').empty();
              $.each(data, function (i, p) {
                $('select[name="product_id"]').append('<option value="' + p.id + '">' + p.name + '</option>');
              });
            }
          });
        } else { $('select[name="product_id"]').empty(); }
      });
    });
  </script>
@endsection