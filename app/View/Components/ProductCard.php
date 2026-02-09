<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Carbon\Carbon;

class ProductCard extends Component
{
    public string $productUrl;
    public string $productName;
    public int|float $price;
    public ?string $screen;
    public ?string $chipset;
    public ?string $camera;
    public ?string $battery;
    public bool $isNew;
    public string $variant;

    public function __construct(
        public $product,
        public $country,
        string $variant = 'grid'
    ) {
        $this->variant = $variant;

        // Build product URL
        $isPk = $country->country_code === 'pk';
        $this->productUrl = $isPk
            ? route('product.show', [$product->slug])
            : route('country.product.show', [$country->country_code, $product->slug]);

        // Product name — ensure first character is uppercase
        $this->productName = ucfirst($product->name);

        // Price
        $this->price = $product->getFirstVariantPriceForCountry($product->id, $country->id);

        // Specs — uses eager-loaded attributes (no extra queries)
        $cardStats = $product->attributes
            ->whereIn('name', ['size', 'technology', 'chipset', 'main', 'capacity', 'battery'])
            ->keyBy('name');

        $this->screen = $cardStats->get('size')?->pivot?->value;
        $this->chipset = $cardStats->get('chipset')?->pivot?->value;
        $this->camera = $cardStats->get('main')?->pivot?->value;
        $this->battery = $cardStats->get('capacity')?->pivot?->value ?? $cardStats->get('battery')?->pivot?->value;

        // New badge (released within 90 days)
        $this->isNew = Carbon::parse($product->release_date)->diffInDays(now()) < 90;
    }

    public function render()
    {
        return view('components.product-card');
    }
}
