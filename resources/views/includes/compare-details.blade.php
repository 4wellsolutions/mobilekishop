@php
    $url = $compare->link;
    $countryCode = request()->segment(1);
    $countries = App\Models\Country::pluck('country_code')->toArray();
    if (in_array($countryCode, $countries)) {
        $url = url("/$countryCode" . parse_url($compare->link, PHP_URL_PATH));
    } else {
        $url = url(parse_url($compare->link, PHP_URL_PATH));
    }
@endphp
<div class="group">
    <a href="{{ $url }}"
        class="block bg-white rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden hover:shadow-lg hover:ring-primary/20 transition-all dark:bg-slate-900 dark:ring-slate-800 h-full">
        <div class="relative overflow-hidden">
            <img class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105 compareImage"
                width="398" height="224" src="{{ $compare->thumbnail }}" alt="{{ $compare->alt }}" loading="lazy">
        </div>
        <div class="p-3 text-center">
            <h2 class="text-sm font-medium text-slate-900 dark:text-white leading-snug">
                {!! Str::title(Str::of($compare->product1 . " VS " . $compare->product2 . ($compare->product3 ? " <strong>Vs</strong> " . $compare->product3 : ""))->replace('-', ' ')) !!}
            </h2>
        </div>
    </a>
</div>