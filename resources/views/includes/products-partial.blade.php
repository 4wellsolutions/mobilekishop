@foreach($products as $product)
	<div>
		<x-product-card :product="$product" :country="$country" />
	</div>
@endforeach