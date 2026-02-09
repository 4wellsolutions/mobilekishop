<!-- compare div -->
@if(!$compares->isEmpty())
  <section class="my-6">
    <h2 class="text-xl font-bold text-slate-900 dark:text-white text-center mb-4">{{ Str::title($product->name) }}
      Comparison</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
      @foreach($compares as $compare)
        <div class="group">
          <a href="{{ $compare->link }}"
            class="block bg-white rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden hover:shadow-lg hover:ring-primary/20 transition-all dark:bg-slate-900 dark:ring-slate-800 h-full">
            <div class="relative overflow-hidden">
              <img src="{{ URL::to('/images/comparison.jpg') }}" data-echo="{{ $compare->thumbnail }}"
                alt="{{ $compare->alt }}" width="366" height="206"
                class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
            </div>
            <div class="p-3 text-center">
              <h4 class="text-sm font-medium text-slate-900 dark:text-white leading-snug">
                {!! Str::title(Str::of($compare->product1 . " <strong>VS</strong> " . $compare->product2 . ($compare->product3 ? " <strong>Vs</strong> " . $compare->product3 : ""))->replace('-', ' ')) !!}
              </h4>
            </div>
          </a>
        </div>
      @endforeach
    </div>
  </section>
@endif
<!-- compare div -->