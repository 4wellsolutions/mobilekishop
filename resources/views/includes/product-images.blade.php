@if(!$product->images->isEmpty())
    <!-- images div -->
    <div class="my-6" id="images">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Images</h2>
        <div class="space-y-4">
            @foreach($product->images as $image)
                <div class="text-center">
                    <img src="{{ URL::to('/images/thumbnail.png') }}" data-echo="{{ URL::to('/products/' . $image->name) }}"
                        class="max-w-full h-auto mx-auto rounded-lg" alt="{{ $product->slug }}">
                </div>
            @endforeach
        </div>
    </div>
    <!-- images div -->
@endif