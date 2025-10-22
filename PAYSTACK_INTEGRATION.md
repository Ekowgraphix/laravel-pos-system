# 💳 Paystack Payment Integration Guide

Complete guide for integrating Paystack payment gateway into your Laravel POS System.

---

## 📋 Table of Contents

- [Why Paystack?](#-why-paystack)
- [Getting Started](#-getting-started)
- [Installation](#-installation)
- [Configuration](#️-configuration)
- [Implementation](#-implementation)
- [Testing](#-testing)
- [Production Deployment](#-production-deployment)
- [Supported Features](#-supported-features)

---

## 🌟 Why Paystack?

### Perfect for African Markets
- **Nigeria (NGN)** - Primary market
- **Ghana (GHS)** - Full support
- **South Africa (ZAR)** - Full support
- **Kenya (KES)** - Coming soon

### Key Benefits
- ✅ No separate package needed (uses Laravel HTTP client)
- ✅ Multiple payment channels (cards, bank transfer, USSD, mobile money)
- ✅ Lower transaction fees than international gateways
- ✅ Local currency support
- ✅ Fast settlement (2-3 business days)
- ✅ Excellent API documentation
- ✅ Built-in fraud prevention

---

## 🚀 Getting Started

### Step 1: Create Paystack Account

1. Visit [Paystack Dashboard](https://dashboard.paystack.com)
2. Sign up with your business email
3. Complete KYC verification (required for live mode)
4. Navigate to **Settings** → **API Keys & Webhooks**

### Step 2: Get Your API Keys

**Test Mode Keys** (for development):
```
Public Key: pk_test_xxxxxxxxxxxxxxxxxx
Secret Key: sk_test_xxxxxxxxxxxxxxxxxx
```

**Live Mode Keys** (for production):
```
Public Key: pk_live_xxxxxxxxxxxxxxxxxx
Secret Key: sk_live_xxxxxxxxxxxxxxxxxx
```

⚠️ **Never commit your secret keys to version control!**

---

## 📦 Installation

### No Package Required!

Paystack integration uses Laravel's built-in HTTP client, so no additional package installation is needed.

Just ensure your Laravel installation has:
```bash
# Already included in Laravel 11
- Illuminate\Support\Facades\Http
```

---

## ⚙️ Configuration

### Step 1: Update .env File

Add these variables to your `.env` file:

```env
# Paystack Configuration
PAYSTACK_PUBLIC_KEY=pk_test_1a4df5381bba3c1895247335598a7875f7ed3764
PAYSTACK_SECRET_KEY=sk_test_8b47f6f9b64ab837b102314e101c081244326292
PAYSTACK_MERCHANT_EMAIL=ekowjeremy@gmail.com
PAYSTACK_BASE_URL=https://api.paystack.co
PAYSTACK_CURRENCY=GHS

# Supported currencies: GHS, NGN, ZAR, USD
```

### Step 2: Update config/services.php

Add Paystack configuration:

```php
<?php

return [
    // ... other services

    'paystack' => [
        'public_key' => env('PAYSTACK_PUBLIC_KEY'),
        'secret_key' => env('PAYSTACK_SECRET_KEY'),
        'base_url' => env('PAYSTACK_BASE_URL', 'https://api.paystack.co'),
        'merchant_email' => env('PAYSTACK_MERCHANT_EMAIL'),
    ],
];
```

### Step 3: Create Routes

Add to `routes/web.php`:

```php
use App\Http\Controllers\PaymentController;

// Payment routes
Route::prefix('payment')->middleware('auth')->group(function() {
    Route::get('/checkout/{order}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/callback', [PaymentController::class, 'callback'])->name('paystack.callback');
    Route::post('/webhook', [PaymentController::class, 'webhook'])->name('paystack.webhook');
});
```

---

## 💻 Implementation

### Create Payment Controller

```bash
php artisan make:controller PaymentController
```

```php
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymentGateway\PaystackPaymentService;
use App\Services\InvoiceGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PaystackPaymentService $paystack;
    protected InvoiceGenerator $invoiceGenerator;

    public function __construct(
        PaystackPaymentService $paystack,
        InvoiceGenerator $invoiceGenerator
    ) {
        $this->paystack = $paystack;
        $this->invoiceGenerator = $invoiceGenerator;
    }

    /**
     * Show checkout page and initialize payment
     */
    public function checkout(Order $order)
    {
        // Ensure order belongs to authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to order');
        }

        // Check if order is already paid
        if ($order->status === 'completed') {
            return redirect()->route('orders.show', $order)
                ->with('info', 'This order has already been paid.');
        }

        // Initialize payment with Paystack
        $result = $this->paystack->createPaymentIntent(
            $order->totalPrice,
            config('services.paystack.currency', 'GHS'),
            [
                'email' => auth()->user()->email,
                'order_id' => $order->id,
                'order_code' => $order->order_code,
                'customer_name' => auth()->user()->name,
                'callback_url' => route('paystack.callback'),
            ]
        );

        if (!$result['success']) {
            return back()->with('error', 'Payment initialization failed: ' . $result['error']);
        }

        // Store payment reference
        $order->update([
            'payment_reference' => $result['payment_id'],
        ]);

        // Redirect to Paystack payment page
        return redirect($result['authorization_url']);
    }

    /**
     * Handle payment callback from Paystack
     */
    public function callback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('orders.index')
                ->with('error', 'No payment reference provided');
        }

        // Verify payment
        $result = $this->paystack->confirmPayment($reference);

        if (!$result['success']) {
            return redirect()->route('orders.index')
                ->with('error', 'Payment verification failed: ' . $result['error']);
        }

        // Find order by reference
        $order = Order::where('payment_reference', $reference)->first();

        if (!$order) {
            return redirect()->route('orders.index')
                ->with('error', 'Order not found');
        }

        // Update order status
        DB::transaction(function () use ($order, $result) {
            $order->update([
                'status' => 'completed',
                'payment_status' => 'completed',
                'paid_at' => now(),
            ]);

            // Generate invoice
            $this->invoiceGenerator->generate($order);
        });

        return redirect()->route('orders.show', $order)
            ->with('success', 'Payment successful! Your order has been confirmed.');
    }

    /**
     * Handle Paystack webhooks
     */
    public function webhook(Request $request)
    {
        // Verify webhook signature
        $signature = $request->header('x-paystack-signature');
        $payload = $request->getContent();

        if (!$this->paystack->verifyWebhook($payload, $signature)) {
            Log::warning('Invalid Paystack webhook signature');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $event = json_decode($payload, true);

        // Handle different event types
        match($event['event']) {
            'charge.success' => $this->handleSuccessfulCharge($event['data']),
            'charge.failed' => $this->handleFailedCharge($event['data']),
            'transfer.success' => $this->handleTransferSuccess($event['data']),
            'transfer.failed' => $this->handleTransferFailed($event['data']),
            default => Log::info('Unhandled webhook event: ' . $event['event']),
        };

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle successful charge webhook
     */
    protected function handleSuccessfulCharge(array $data): void
    {
        $reference = $data['reference'];
        $order = Order::where('payment_reference', $reference)->first();

        if ($order && $order->status !== 'completed') {
            DB::transaction(function () use ($order) {
                $order->update([
                    'status' => 'completed',
                    'payment_status' => 'completed',
                    'paid_at' => now(),
                ]);

                // Generate and email invoice
                $invoicePath = $this->invoiceGenerator->generate($order);
                
                // TODO: Send email notification
                // Mail::to($order->user)->send(new OrderConfirmation($order, $invoicePath));
            });

            Log::info("Order {$order->order_code} marked as paid via webhook");
        }
    }

    /**
     * Handle failed charge webhook
     */
    protected function handleFailedCharge(array $data): void
    {
        $reference = $data['reference'];
        $order = Order::where('payment_reference', $reference)->first();

        if ($order) {
            $order->update([
                'payment_status' => 'failed',
                'payment_error' => $data['gateway_response'] ?? 'Payment failed',
            ]);

            Log::warning("Payment failed for order {$order->order_code}");
        }
    }

    protected function handleTransferSuccess(array $data): void
    {
        // Handle successful transfers (for refunds, etc.)
        Log::info('Transfer successful', $data);
    }

    protected function handleTransferFailed(array $data): void
    {
        // Handle failed transfers
        Log::warning('Transfer failed', $data);
    }
}
```

### Update Order Model

Add payment fields to your `orders` table migration:

```php
$table->string('payment_reference')->nullable();
$table->string('payment_status')->default('pending');
$table->string('payment_error')->nullable();
$table->timestamp('paid_at')->nullable();
```

Run migration:
```bash
php artisan make:migration add_payment_fields_to_orders_table
php artisan migrate
```

---

## 🧪 Testing

### Test Cards (Paystack Test Mode)

**Successful Transactions:**
```
Card Number: 4084 0840 8408 4081
Expiry: Any future date
CVV: 408
PIN: 0000
OTP: 123456
```

**Failed Transactions:**
```
Card Number: 5060 6666 6666 6666
Expiry: Any future date
CVV: 123
```

### Test Process

1. **Start your server:**
```bash
php artisan serve
npm run dev
```

2. **Create a test order** as a customer

3. **Proceed to checkout:**
```
http://localhost:8000/payment/checkout/{order_id}
```

4. **Use test card** on Paystack payment page

5. **Complete 3D Secure** (enter PIN and OTP)

6. **Verify redirect** back to your site

7. **Check order status** - should be "completed"

8. **Verify invoice** generated in `storage/invoices/`

### Test Webhooks Locally

Use Paystack's webhook testing or ngrok:

```bash
# Install ngrok
ngrok http 8000

# Copy the HTTPS URL (e.g., https://abc123.ngrok.io)
# Add webhook in Paystack Dashboard:
# https://abc123.ngrok.io/payment/webhook
```

---

## 🌐 Production Deployment

### Step 1: Switch to Live Keys

Update `.env` with live keys:
```env
PAYSTACK_PUBLIC_KEY=pk_live_your_live_public_key
PAYSTACK_SECRET_KEY=sk_live_your_live_secret_key
```

### Step 2: Configure Webhooks

In Paystack Dashboard:
1. Go to **Settings** → **API Keys & Webhooks**
2. Add webhook URL: `https://yourdomain.com/payment/webhook`
3. Select events:
   - ✅ charge.success
   - ✅ charge.failed
   - ✅ transfer.success
   - ✅ transfer.failed

### Step 3: SSL Certificate

Ensure your site has a valid SSL certificate:
```bash
# If using Laravel Forge/Vapor, SSL is automatic
# If self-hosted, use Let's Encrypt:
sudo certbot --nginx -d yourdomain.com
```

### Step 4: Security Checklist

- [ ] Environment variables secured
- [ ] Webhook signature verification enabled
- [ ] HTTPS enabled
- [ ] Rate limiting configured
- [ ] Error logging enabled
- [ ] Payment testing completed

---

## 🎯 Supported Features

### ✅ Currently Implemented

- **Initialize Payments** - Create payment intents
- **Verify Payments** - Confirm transaction status
- **Process Refunds** - Initiate refunds
- **Webhook Handling** - Automated event processing
- **Get Banks** - Fetch Nigerian banks list
- **Create Recipients** - For transfers/payouts
- **Initiate Transfers** - Send money to bank accounts

### 🔜 Coming Soon (Phase 2)

- **Recurring Payments** - Subscriptions
- **Split Payments** - Multi-merchant settlements
- **Payment Plans** - Installment payments
- **Bulk Charges** - Process multiple cards

---

## 💰 Pricing & Fees

### Nigeria (NGN)
- **Local Cards**: 1.5% capped at ₦2,000
- **International Cards**: 3.9%
- **Bank Transfer**: ₦50 flat fee

### Ghana (GHS) - YOUR MARKET
- **All Cards**: 1.95%
- **Mobile Money**: 1.95%

### South Africa (ZAR)
- **All Cards**: 2.9%

### Settlement Time
- **2-3 business days** to your bank account

---

## 🔐 Security Best Practices

1. **Never expose secret keys**
   ```php
   // ❌ Bad
   $secretKey = 'sk_live_xxxxx';
   
   // ✅ Good
   $secretKey = config('services.paystack.secret_key');
   ```

2. **Always verify webhooks**
   ```php
   if (!$this->paystack->verifyWebhook($payload, $signature)) {
       abort(401);
   }
   ```

3. **Use HTTPS in production**
   ```env
   APP_URL=https://yourdomain.com
   ```

4. **Log all transactions**
   ```php
   Log::info('Payment processed', [
       'reference' => $reference,
       'amount' => $amount,
       'status' => $status,
   ]);
   ```

---

## 📊 Monitoring & Analytics

### Paystack Dashboard

Monitor:
- **Transaction volume**
- **Success rate**
- **Failure reasons**
- **Settlement status**
- **Dispute management**

### Laravel Logging

```php
// Log payment attempts
Log::channel('payments')->info('Payment initialized', [
    'order_id' => $order->id,
    'amount' => $amount,
    'reference' => $reference,
]);
```

---

## 🆘 Troubleshooting

### Common Issues

**"Invalid API Key"**
```
Solution: Check PAYSTACK_SECRET_KEY in .env
Verify you're using the correct environment (test/live)
```

**"Webhook verification failed"**
```
Solution: Ensure you're comparing the signature correctly
Check that payload hasn't been modified
```

**"Payment callback not working"**
```
Solution: Check callback URL is accessible
Verify route is defined correctly
Ensure no middleware blocking the route
```

**"Transaction not found"**
```
Solution: Reference might be incorrect
Check order has payment_reference stored
Verify Paystack API is responding
```

---

## 📞 Support

### Paystack Support
- **Email**: support@paystack.com
- **Phone**: +234 1 888 7272
- **Docs**: https://paystack.com/docs
- **Status**: https://status.paystack.com

### Developer Resources
- **API Reference**: https://paystack.com/docs/api
- **Libraries**: https://github.com/PaystackHQ
- **Community**: Paystack Developers on Slack

---

## 🎉 You're All Set!

Your Laravel POS now supports:
- ✅ Secure payment processing
- ✅ Multiple payment channels
- ✅ Automated webhooks
- ✅ Professional invoicing
- ✅ Bank transfers and payouts

**Next Steps:**
1. Test in sandbox mode
2. Complete a successful transaction
3. Verify webhook events
4. Go live with confidence!

---

**Made with ❤️ for African businesses**  
**Powered by Paystack** 🚀
