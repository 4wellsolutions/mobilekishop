<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

$newCode = '<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=GT-KFLGKWJ"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag(\'js\', new Date());

  gtag(\'config\', \'GT-KFLGKWJ\');
  gtag(\'config\', \'G-1TRC97HYME\');
</script>';

SiteSetting::set('head_code', $newCode);
Cache::forget('site_setting.head_code');

echo "Database successfully updated with the new Google Tag code!\n";
echo "Cache cleared.\n";
