@extends('layouts.techspec')

@section('title', $metas->title)
@section('description', $metas->description)
@section("canonical", $metas->canonical)

@section("content")
    <main class="max-w-[1400px] mx-auto px-4 md:px-6 lg:px-8 py-8">
        <h1 class="text-2xl md:text-3xl font-bold text-center text-text-main mb-6">Sitemap Mobilekishop</h1>

        @if($categories = App\Models\Category::has('products')->get())
            @foreach($categories as $category)
                <section class="mb-8">
                    <h2 class="text-xl font-bold text-center mb-4">
                        <a class="no-underline text-text-main hover:text-primary transition-colors"
                            href="{{ URL::to('/category/') . '/' . $category->slug }}">{{ $category->category_name }}</a>
                    </h2>

                    @php
                        $categoryId = $category->id;
                        $countryId = $country->id;
                        $brands = \App\Models\Brand::whereHas('products', function ($query) use ($categoryId, $countryId) {
                            $query->where('category_id', $categoryId)
                                ->whereHas('variants', function ($query) use ($countryId) {
                                    $query->where('country_id', $countryId)
                                        ->where('price', '>', 0);
                                });
                        })->get();
                    @endphp

                    @foreach($brands as $brand)
                        <div class="mb-4">
                            <p class="text-center bg-surface-alt py-2 rounded-lg mb-3">
                                <a class="no-underline text-text-main text-lg font-medium hover:text-primary transition-colors"
                                    href="{{ url("/brand/{$brand->slug}/" . ($category->slug ?? 'mobile-phones')) }}">{{ $brand->name }}</a>
                            </p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                                @php
                                    $products = \App\Models\Product::whereHas('variants', function ($query) use ($countryId) {
                                        $query->where('country_id', $countryId)->where('price', '>', 0);
                                    })->where("brand_id", $brand->id)->where("category_id", $category->id)->get();
                                @endphp
                                @foreach($products as $product)
                                    <p class="mb-1 text-sm">
                                        <a class="no-underline text-text-muted hover:text-primary transition-colors"
                                            href="{{ url('/product/' . $product->slug) }}">{{ Str::title($product->name) }}</a>
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </section>
            @endforeach
        @endif

        {{-- Compares --}}
        @if($compares = App\Models\Compare::all())
            <section class="mb-8">
                <h2 class="text-xl font-bold text-center mb-4">
                    <a class="no-underline text-text-main hover:text-primary transition-colors"
                        href="{{ url('comparison') }}">Compares</a>
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                    @foreach($compares as $compare)
                        <p class="mb-1 text-sm">
                            @php
                                $parsedUrl = parse_url($compare->link);
                                $relativeUrl = $parsedUrl['path'] ?? '';
                                $fullUrl = url($relativeUrl);
                            @endphp
                            <a class="no-underline text-text-muted hover:text-primary transition-colors"
                                href="{{ $fullUrl }}">{!! Str::title(Str::of($compare->product1 . " VS " . $compare->product2 . ($compare->product3 ? " Vs " . $compare->product3 : ""))->replace('-', ' ')) !!}</a>
                        </p>
                    @endforeach
                </div>
            </section>
        @endif
    </main>
@endsection