<div class="mt-6" id="related_mobiles">
    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Related Mobile</h3>
    @if(!$products->isEmpty())
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($products as $prod)
                <div>
                    <x-product-card :product="$prod" :country="$country" />
                </div>
            @endforeach
        </div>
    @endif
</div>