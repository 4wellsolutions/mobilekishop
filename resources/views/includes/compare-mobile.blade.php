<div class="px-2">
	<input type="hidden" name="input1" id="input-search{{ $id }}" value="{{ isset($product) ? $product->slug : '' }}">
	<input type="text" name="search{{ $id }}" id="search{{ $id }}" class="w-full px-3 py-2 border border-border-light rounded-lg text-sm search
               focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="Search Mobile"
		autocomplete="off" value="{{ isset($product) ? $product->name : '' }}" {{ isset($isReadOnly) && $isReadOnly ? 'readonly' : '' }}>
</div>

@if(isset($product))
	<div class="text-center mt-2">
		<a href="{{ url('product/' . $product->slug) }}" class="no-underline">
			<h2 class="text-sm font-bold text-text-main mb-2">{{ Str::title($product->name) }}</h2>
		</a>
		<a href="{{ url('product/' . $product->slug) }}">
			<img src="{{ $product->thumbnail }}" width="120" height="160" class="max-w-full h-auto mx-auto"
				alt="{{ $product->slug }}">
		</a>
	</div>
@else
	<h2 class="text-sm font-bold text-text-main text-center my-2">Compare With</h2>
@endif