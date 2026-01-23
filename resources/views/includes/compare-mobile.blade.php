<div class="row px-2">
	<input type="hidden" name="input1" id="input-search{{$id}}" value="{{ isset($product) ? $product->slug : '' }}">
  	<input type="text" name="search{{$id}}" id="search{{$id}}" class="form-control search" placeholder="Search Mobile" autocomplete="off" value="{{ isset($product) ? $product->name : '' }}" {{ isset($isReadOnly) && $isReadOnly ? 'readonly' : '' }}>
</div>

@if(isset($product))
	<div class="row">
		<div class="col-12">
			<a href="{{url('product/'.$product->slug)}}">
    			<h2 class="text-dark fw-bold fs-6 my-2">{{Str::title($product->name)}}</h2>			
    		</a>
		</div>
	  	<div class="col-12">
	  		<a href="{{url('product/'.$product->slug)}}">
	    		<img src="{{ $product->thumbnail }}" width="120" height="160" class="img-fluid w-auto mx-auto" alt="{{ $product->slug }}">
	    	</a>
	  	</div>
  </div>
@else
  <h2 class="text-dark fw-bold fs-6 my-2">Compare With</h2>
@endif
