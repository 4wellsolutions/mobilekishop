@foreach($products as $product)
	<div class="col-6 col-sm-4 col-md-4 col-lg-3">
	    @include('includes.product-details', ['product' => $product])
	</div>
@endforeach