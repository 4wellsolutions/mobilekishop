<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$product = App\Product::where('slug', 'samsung-galaxy-f55-5g-12gb-ram-256gb')->first();
if ($product) {
    $results = DB::table('view_product_attributes')->where('product_id', $product->id)->get();
    foreach ($results as $res) {
        print_r($res);
    }
}
