<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = new App\Services\CurrencyService();
$count = $service->initializeDefaultCurrencies();

echo "✅ Successfully initialized {$count} currencies!\n";
