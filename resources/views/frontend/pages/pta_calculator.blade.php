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
                                @if($brands = App\Models\Brand::all())
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

<!-- Select2 is required for this page -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>