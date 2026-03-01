<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$oppo = \App\Models\Brand::where('name', 'like', '%oppo%')->first();
echo "Oppo Brand Slug: " . ($oppo ? $oppo->slug : 'Not found') . "\n";

$product = \App\Models\Product::where('name', 'like', '%find x6 pro%')->first();
echo "Find X6 Pro Slug: " . ($product ? $product->slug : 'Not found') . "\n";

// Get route names for pta and brand
echo "PTA URL: " . route('pta.calculator') . "\n";
if ($oppo) {
    echo "Brand URL: " . route('brands.by.category', ['category_slug' => $oppo->slug]) . "\n";
}
if ($product) {
    echo "Product URL: " . url($product->slug) . "\n";
}
