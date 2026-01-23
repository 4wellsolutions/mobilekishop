@if($country && !empty($country->amazon_url) && !empty($country->amazon_tag))
    <div class="row mt-4">
        <div class="col-12">
            <a href="{{ $country->amazon_url }}s?k={{ urlencode($product->name) }}&tag={{ $country->amazon_tag }}" 
               class="btn btn-warning border-dark w-100 amazonButton" 
               target="_blank">
                Buy on Amazon
            </a>
            <p style="font-size: 14px;" class="text-secondary mt-1">
                Disclosure: As an Amazon Associate, I earn from qualifying purchases. This means that if you click on a link to Amazon and make a purchase, I may receive a small commission at no extra cost to you.
            </p>
        </div>
    </div>
@else
    <div class="row mt-4">
        <div class="col-12">
            <a href="{{ url('/contact') }}" class="btn btn-primary w-100">
                Place Your Ad Here
            </a>
        </div>
    </div>
@endif