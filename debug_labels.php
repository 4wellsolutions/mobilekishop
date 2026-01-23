<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$product = App\Product::where('slug', 'samsung-galaxy-f55-5g-12gb-ram-256gb')->first();
if ($product) {
    $results = DB::table('product_variant_attributes_view')->where('product_id', $product->id)->get();
    foreach ($results as $res) {
        echo $res->attribute_label . " => " . $res->attribute_value . "\n";
    }
}
