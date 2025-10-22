<?php

// Quick Paystack Configuration Test
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Paystack Configuration Test ===\n\n";

$publicKey = config('services.paystack.public_key');
$secretKey = config('services.paystack.secret_key');
$currency = config('services.paystack.currency');
$merchantEmail = config('services.paystack.merchant_email');

echo "Public Key: " . ($publicKey ? "✓ Configured (" . substr($publicKey, 0, 10) . "...)" : "✗ Missing") . "\n";
echo "Secret Key: " . ($secretKey ? "✓ Configured (" . substr($secretKey, 0, 10) . "...)" : "✗ Missing") . "\n";
echo "Currency: " . ($currency ?: "✗ Not set") . "\n";
echo "Merchant Email: " . ($merchantEmail ?: "✗ Not set") . "\n";

echo "\n=== Status ===\n";
if ($publicKey && $secretKey) {
    echo "✓ Paystack is configured and ready to use!\n";
    echo "\nKey Type: " . (str_starts_with($secretKey, 'sk_test_') ? 'TEST MODE' : 'LIVE MODE') . "\n";
} else {
    echo "✗ Paystack configuration incomplete. Please check your .env file.\n";
}
