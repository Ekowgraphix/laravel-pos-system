# PayStack API Connection Troubleshooting Guide

## Error Description
```
unavailable: read tcp 192.168.102.253:59354->34.49.14.144:443: wsarecv: 
A connection attempt failed because the connected party did not properly respond 
after a period of time, or established connection failed because connected host 
has failed to respond.
```

## Root Cause
This error indicates that your application cannot connect to the PayStack API server (`34.49.14.144:443`). This is typically a **network connectivity issue**, not a code problem.

## Possible Causes

### 1. **Firewall Blocking HTTPS Connections**
   - Windows Firewall or antivirus blocking outbound HTTPS connections
   - Corporate firewall blocking API endpoints

### 2. **No Internet Connection**
   - Your local machine doesn't have internet access
   - Network adapter is disabled

### 3. **PayStack API Temporarily Down**
   - PayStack service experiencing issues
   - DNS resolution failing

### 4. **PHP/XAMPP Configuration**
   - PHP's `openssl` extension not enabled
   - cURL not properly configured

## Solutions

### Solution 1: Check Internet Connectivity

1. **Test basic connectivity:**
   ```powershell
   ping 8.8.8.8
   ```

2. **Test HTTPS connectivity:**
   ```powershell
   Test-NetConnection -ComputerName api.paystack.co -Port 443
   ```

3. **Test DNS resolution:**
   ```powershell
   nslookup api.paystack.co
   ```

### Solution 2: Enable Required PHP Extensions

1. **Open php.ini file:**
   - Location: `C:\xampp\php\php.ini`

2. **Find and uncomment these lines** (remove the semicolon):
   ```ini
   extension=curl
   extension=openssl
   extension=fileinfo
   ```

3. **Restart Apache** in XAMPP Control Panel

4. **Verify extensions are loaded:**
   ```powershell
   php -m | Select-String -Pattern "curl|openssl"
   ```

### Solution 3: Configure Windows Firewall

1. **Open Windows Defender Firewall**
2. **Click "Allow an app through firewall"**
3. **Find "Apache HTTP Server" and "php.exe"**
4. **Ensure both Private and Public networks are checked**
5. **Click OK and restart Apache**

### Solution 4: Test PayStack API Manually

Create a test file to verify API connectivity:

**File**: `f:\xampp\htdocs\Laravel POS(SourceCode)\test-paystack-connection.php`

```php
<?php

// Test PayStack API connectivity
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.paystack.co");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);

$verbose = fopen('php://temp', 'w+');
curl_setopt($ch, CURLOPT_STDERR, $verbose);

echo "<h2>Testing PayStack API Connection</h2>";
echo "<pre>";

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

if ($error) {
    echo "❌ <strong>Connection Failed!</strong>\n";
    echo "Error: " . $error . "\n\n";
    
    rewind($verbose);
    $verboseLog = stream_get_contents($verbose);
    echo "Verbose Information:\n";
    echo $verboseLog;
} else {
    echo "✅ <strong>Connection Successful!</strong>\n";
    echo "HTTP Code: " . $httpCode . "\n";
    echo "Response Headers:\n";
    echo substr($response, 0, strpos($response, "\r\n\r\n"));
}

curl_close($ch);
fclose($verbose);

echo "\n\n";
echo "PHP Extensions:\n";
echo "cURL: " . (extension_loaded('curl') ? '✅ Enabled' : '❌ Disabled') . "\n";
echo "OpenSSL: " . (extension_loaded('openssl') ? '✅ Enabled' : '❌ Disabled') . "\n";

echo "</pre>";
```

**Run the test:**
1. Save the file
2. Access in browser: `http://localhost/Laravel%20POS(SourceCode)/test-paystack-connection.php`

### Solution 5: Use Alternative Network Test in Laravel

Create an artisan command to test connectivity:

```bash
php artisan tinker
```

Then run:
```php
$response = \Illuminate\Support\Facades\Http::get('https://api.paystack.co');
echo $response->status();
```

If this fails, the issue is definitely network-related.

### Solution 6: Check Proxy Settings

If you're behind a corporate proxy:

1. **Add proxy to Laravel HTTP client** in `config/services.php`:
   ```php
   'paystack' => [
       'public_key' => env('PAYSTACK_PUBLIC_KEY'),
       'secret_key' => env('PAYSTACK_SECRET_KEY'),
       'base_url' => env('PAYSTACK_BASE_URL', 'https://api.paystack.co'),
       'merchant_email' => env('PAYSTACK_MERCHANT_EMAIL'),
       'currency' => env('PAYSTACK_CURRENCY', 'GHS'),
       'proxy' => env('HTTP_PROXY', null), // Add this line
   ],
   ```

2. **Add to `.env`:**
   ```env
   HTTP_PROXY=http://your-proxy-server:port
   ```

### Solution 7: Temporary Workaround - Test Mode

For development/testing without live API calls, you can create a mock payment service:

**File**: Create `app/Services/PaymentGateway/MockPaystackService.php`

```php
<?php

namespace App\Services\PaymentGateway;

class MockPaystackService extends PaystackPaymentService
{
    public function createPaymentIntent(float $amount, string $currency = 'NGN', array $metadata = []): array
    {
        // Return mock success response for testing
        return [
            'success' => true,
            'payment_id' => 'mock_ref_' . time(),
            'authorization_url' => route('paystack.callback') . '?reference=mock_ref_' . time(),
            'access_code' => 'mock_access_code',
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'pending',
        ];
    }
    
    public function confirmPayment(string $paymentId): array
    {
        // Return mock success response
        return [
            'success' => true,
            'payment_id' => $paymentId,
            'status' => 'success',
            'amount' => 1000,
            'currency' => 'GHS',
            'paid_at' => now(),
            'channel' => 'card',
            'customer' => [
                'email' => 'test@example.com',
                'customer_code' => 'CUS_mock',
            ],
        ];
    }
}
```

**Enable mock mode in `AppServiceProvider.php`:**
```php
if (env('PAYSTACK_MOCK_MODE', false)) {
    $this->app->bind(PaystackPaymentService::class, MockPaystackService::class);
}
```

**Add to `.env`:**
```env
PAYSTACK_MOCK_MODE=true
```

## Quick Diagnosis Checklist

Run these commands in PowerShell:

```powershell
# 1. Test internet connectivity
ping google.com

# 2. Test PayStack API connectivity
Test-NetConnection -ComputerName api.paystack.co -Port 443

# 3. Check if cURL works
php -r "echo (extension_loaded('curl') ? 'cURL enabled' : 'cURL disabled');"

# 4. Check if OpenSSL works
php -r "echo (extension_loaded('openssl') ? 'OpenSSL enabled' : 'OpenSSL disabled');"

# 5. Test HTTP request from PHP
php -r "$ch = curl_init('https://api.paystack.co'); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); $result = curl_exec($ch); echo curl_error($ch) ?: 'Success';"
```

## Still Not Working?

### Check Laravel Logs
```bash
tail -f storage/logs/laravel.log
```

Look for detailed error messages when attempting payment.

### Enable Debug Mode
In `.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

### Contact Support
If none of the above solutions work:

1. **Check PayStack Status**: https://status.paystack.com/
2. **Review PayStack Documentation**: https://paystack.com/docs/api/
3. **Contact PayStack Support**: support@paystack.com

## Expected Behavior After Fix

Once connectivity is restored:

1. User selects PayStack payment method
2. Submits payment form
3. Order is created in database
4. User is redirected to PayStack payment page (external)
5. After payment, user is redirected back to your site
6. Order status is updated to "Success"
7. Invoice is generated

## Testing Without Network Issues

If you cannot resolve the network issue immediately, use **Solution 7 (Mock Service)** above to continue development and testing of the payment flow without making actual API calls.
