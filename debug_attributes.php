<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$product = App\Product::where('slug', 'samsung-galaxy-f55-5g-12gb-ram-256gb')->first();
if ($product) {
    echo "Product: " . $product->name . "\n";
    foreach ($product->Attributes as $attr) {
        echo $attr->id . ": " . $attr->name . " => " . $attr->pivot->value . "\n";
    }
} else {
    echo "Product not found.\n";
}
