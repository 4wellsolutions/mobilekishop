<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

echo "Current head_code in DB:\n";
$setting = SiteSetting::where('key', 'head_code')->first();
echo $setting ? $setting->value : "NOT FOUND";
echo "\n\nCache value:\n";
echo Cache::get('site_setting.head_code', 'NOT IN CACHE');
echo "\n";
