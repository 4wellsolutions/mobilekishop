@php
    $layout = ($country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

@section('title', Str::title($product->name) . ": Price, Specs & Deals in {$country->country_name} | MobileKiShop")

@section('description', "Discover the powerful {$product->name}. Compare specs, explore features, read user reviews, and find the best price in {$country->country_name}. Shop today!")

@section("keywords", "Mobiles prices, mobile specification, mobile phone features")

@section("canonical", ($country->country_code == 'pk') ? route('product.show', [$product->slug]) : route('country.product.show', [$country->country_code, $product->slug]))

@section("content")

@php
    $nowDate = Carbon\Carbon::now();
    $price_in_pkr = $product->getFirstVariantPriceForCountry($product->id, $country->id);

    $attributes = $product->Attributes()->get()->keyBy(function ($item) {
        return strtolower(str_replace([' ', '(', ')'], ['_', '', ''], $item->label));
    });

    // Specification variables
    $ear_placement = $attributes->get('ear_placement')->pivot->value ?? null;
    $noise_control = $attributes->get('noise_control')->pivot->value ?? null;
    $jack = $attributes->get('jack')->pivot->value ?? null;
    $connectivity = $attributes->get('connectivity')->pivot->value ?? null;
    $wireless_communication_technology = $attributes->get('wireless_communication_technology')->pivot->value ?? null;
    $bluetooth_range = $attributes->get('bluetooth_range')->pivot->value ?? null;
    $bluetooth_version = $attributes->get('bluetooth_version')->pivot->value ?? null;
    $special_feature = $attributes->get('special_feature')->pivot->value ?? null;
    $compatible_devices = $attributes->get('compatible_devices')->pivot->value ?? null;
    $control_type = $attributes->get('control_type')->pivot->value ?? null;
    $cable_feature = $attributes->get('cable_feature')->pivot->value ?? null;
    $weight = $attributes->get('weight')->pivot->value ?? null;
    $water_resistance_level = $attributes->get('water_resistance_level')->pivot->value ?? null;
    $style = $attributes->get('style')->pivot->value ?? null;
    $audio_driver_type = $attributes->get('audio_driver_type')->pivot->value ?? null;
    $dimensions = $attributes->get('dimensions')->pivot->value ?? null;
    $material = $attributes->get('material')->pivot->value ?? null;
    $charging_time = $attributes->get('charging_time')->pivot->value ?? null;
    $batteries = $attributes->get('batteries')->pivot->value ?? null;
    $battery_life = $attributes->get('battery_life')->pivot->value ?? null;
    $included_components = $attributes->get('included_components')->pivot->value ?? null;
    $age_range = $attributes->get('age_range')->pivot->value ?? null;
    $product_use = $attributes->get('product_use')->pivot->value ?? null;
    $water_resistance_intro = $attributes->get('water_resistance_intro')->pivot->value ?? null;
    $noice_control_intro = $attributes->get('noice_control_intro')->pivot->value ?? null;
    $battery_time_intro = $attributes->get('battery_time_intro')->pivot->value ?? null;

    $release_date = \Carbon\Carbon::parse($product->release_date)->format("M-Y");

@endphp

@section("style") @stop

@section("script")

<script type="application/ld+json">
    {
        "@@context": "https://schema.org/",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "{{ url('/' . ($country->country_code === 'pk' ? '' : $country->country_code)) }}"
            },
            {
                "@type": "ListItem",
                "position": 2,
                "name": "{{ $product->category->category_name }}",
                "item": "{{ url(($country->country_code === 'pk' ? '' : $country->country_code) . '/category/' . $product->category->slug) }}"
            },
            {
                "@type": "ListItem",
                "position": 3,
                "name": "{{ $product->brand->name }}",
                "item": "{{ url(($country->country_code === 'pk' ? '' : $country->country_code) . '/brand/' . $product->brand->slug . '/' . $product->category->slug) }}"
            },
            {
                "@type": "ListItem",
                "position": 4,
                "name": "{{ Str::title($product->name) }}",
                "item": "{{ url(($country->country_code === 'pk' ? '' : $country->country_code) . '/product/' . $product->slug) }}"
            }
        ]
    }
</script>

@stop