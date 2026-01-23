<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$product = App\Product::where('slug', 'samsung-galaxy-f55-5g-12gb-ram-256gb')->first();
if ($product) {
    echo "Product: " . $product->name . " (ID: " . $product->id . ")\n";
    foreach ($product->Variants as $variant) {
        echo "Variant: " . $variant->name . " | Price: " . $variant->pivot->price . " | Country: " . $variant->pivot->country_id . "\n";
    }
}
