@if($country && !empty($country->amazon_url) && !empty($country->amazon_tag))
    <div class="mt-4">
        <a href="{{ $country->amazon_url }}s?k={{ urlencode($product->name) }}&tag={{ $country->amazon_tag }}"
            class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-amber-400 hover:bg-amber-500 text-slate-900 font-bold text-sm rounded-lg shadow-sm transition amazonButton"
            data-product-slug="{{ $product->slug }}" target="_blank">
            <span class="material-symbols-outlined text-[18px]">shopping_cart</span>
            Buy on Amazon
        </a>
        <p class="text-xs text-slate-400 mt-2">
            Disclosure: As an Amazon Associate, I earn from qualifying purchases. This means that if you click on a link
            to Amazon and make a purchase, I may receive a small commission at no extra cost to you.
        </p>
    </div>
@else
    <div class="mt-4">
        <a href="{{ url('/contact') }}"
            class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-primary hover:bg-blue-600 text-white font-bold text-sm rounded-lg shadow-sm transition">
            <span class="material-symbols-outlined text-[18px]">ads_click</span>
            Place Your Ad Here
        </a>
    </div>
@endif