<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Attribute::all() as $attr) {
    echo $attr->id . ": " . $attr->name . "\n";
}
