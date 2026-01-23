<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$product = App\Product::where('slug', 'samsung-galaxy-f55-5g-12gb-ram-256gb')->first();
if ($product) {
    foreach ($product->Attributes as $attr) {
        if (str_contains(strtolower($attr->name), 'memory') || str_contains(strtolower($attr->name), 'built') || str_contains(strtolower($attr->label), 'memory') || str_contains(strtolower($attr->label), 'built')) {
            echo $attr->id . ": " . $attr->name . " [" . $attr->label . "] => " . $attr->pivot->value . "\n";
        }
    }
}
