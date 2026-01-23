<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Country::all() as $country) {
    echo $country->id . ": " . $country->country_name . " (" . $country->country_code . ")\n";
}
