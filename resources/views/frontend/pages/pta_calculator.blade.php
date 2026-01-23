<main class="main">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-12 py-2">
                <form action="" method="get" id="ptaForm">
                <div class="row">
                    <div class="col-12">
                        <h2 class="text-center">PTA Tax Calculator</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-5 my-2">
                        <select class="form-control" name="brand_id" id="brand_id">
                            <option value="">Select Brand</option>
                            @if($brands = App\Brand::all())
                            @foreach($brands as $brand)
                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-12 col-md-5 my-2">
                        <select class="form-control" name="product_id" id="product_id">
                            <option value="">Select Model</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 my-2">
                        <button class="btn btn-primary w-100" type="submit">Submit</button>
                    </div>
                </div>
                </form>
                <div class="col-12 taxTable" style="display: none;">
                    <table class="table table-striped table-lg fs-2 text-center my-3">
                        <tr>
                            <th>PTA Tax on Passport</th>
                            <th>PTA Tax on CNIC</th>
                        </tr>
                        <tr>
                            <th id="taxOnPassport"></th>
                            <th id="taxOnCNIC"></th>
                        </tr>
                    </table>
                </div>
            </div>            
        </div><!-- End .container -->
    </div>
</main><!-- End .main -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#brand_id').select2();
        $('#product_id').select2();
        
        $('#brand_id').change(function() {
            var brandId = $(this).val();
            $(".taxTable").fadeOut("fast");
            $("#taxOnPassport").text("");
            $("#taxOnCNIC").text("");
            $.ajax({
                url: "{{route('get.products.by.brand.pta')}}",
                type: 'GET',
                data: { brand_id: brandId },
                dataType: 'json',
                success: function(response) {
                    var productSelect = $('#product_id');
                    productSelect.empty();
                    console.log(response.products);
                    productSelect.append($('<option>', {
                        value: '',
                        text: 'Select Model'
                    }));
                    if(response.products.length > 0) {
                        $.each(response.products, function(index, product) {
                            productSelect.append($('<option>', {
                                value: product.id,
                                text: product.name
                            }));
                        });
                    } else {
                        productSelect.append($('<option>', {
                            value: '',
                            text: 'No products available'
                        }));
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: " + status + "\n" + error);
                }
            });
        });
        $('#ptaForm').submit(function(e) {
            e.preventDefault();
            var params = $(this).serialize();
            console.log(params);
            $.ajax({
                url: "{{route('get.pta')}}",
                type: 'GET',
                data: { brand_id: $("#brand_id").val(), product_id: $("#product_id").val() },
                dataType: 'json',
                success: function(response) {
                    if(response.success){
                        $(".taxTable").fadeIn("fast");
                        $("#taxOnPassport").text(response.tax.tax_on_passport);
                        $("#taxOnCNIC").text(response.tax.tax_on_cnic);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: " + status + "\n" + error);
                }
            });
        });
    });
</script>
  

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
.select2-container .select2-selection--single {
    height: 39px; /* Set your desired height */
}
</style>