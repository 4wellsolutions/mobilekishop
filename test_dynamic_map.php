<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$product = App\Product::where('slug', 'samsung-galaxy-f55-5g-12gb-ram-256gb')->first();
if ($product) {
    $attributes = $product->Attributes()->get()->keyBy(function ($item) {
        return strtolower(str_replace([' ', '(', ')'], ['_', '', ''], $item->label));
    });

    echo "OS: " . (optional($attributes->get('os'))->pivot->value ?? 'N/A') . "\n";
    echo "Dimensions: " . (optional($attributes->get('dimensions'))->pivot->value ?? 'N/A') . "\n";
    echo "Ram: " . (optional($attributes->get('ram_in_gb'))->pivot->value ?? 'N/A') . "\n";
}
