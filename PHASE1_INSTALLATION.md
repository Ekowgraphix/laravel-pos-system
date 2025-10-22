# 🚀 Phase 1 Installation Guide

This guide will walk you through installing and configuring Phase 1 features: Payment Gateway Integration, Invoice Generation, and REST API.

---

## 📦 Step 1: Install Required Packages

### Install Stripe PHP SDK
```bash
composer require stripe/stripe-php
```

### Install PDF Generator (DOMPDF)
```bash
composer require barryvdh/laravel-dompdf
```

### Install Paystack (Optional - for African markets)
```bash
composer require unicodeveloper/laravel-paystack
```

### Install Laravel Sanctum (for API)
```bash
php artisan install:api
```

### Install API Documentation (Scribe)
```bash
composer require knuckleswtf/scribe --dev
```

---

## ⚙️ Step 2: Environment Configuration

Add these variables to your `.env` file:

```env
# Stripe Configuration
STRIPE_KEY=pk_test_your_publishable_key
STRIPE_SECRET=sk_test_your_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
STRIPE_CURRENCY=usd

# Paystack Configuration (Optional)
PAYSTACK_PUBLIC_KEY=pk_test_your_public_key
PAYSTACK_SECRET_KEY=sk_test_your_secret_key
PAYSTACK_PAYMENT_URL=https://api.paystack.co
PAYSTACK_MERCHANT_EMAIL=your@email.com

# Company Information (for invoices)
COMPANY_NAME="Your Company Name"
COMPANY_ADDRESS="123 Business St, City, Country"
COMPANY_PHONE="+1234567890"
COMPANY_EMAIL="info@yourcompany.com"
```

---

## 🔑 Step 3: Get Stripe API Keys

1. Go to [Stripe Dashboard](https://dashboard.stripe.com/)
2. Sign up or log in to your account
3. Navigate to **Developers** → **API Keys**
4. Copy your **Publishable key** and **Secret key**
5. For webhook secret:
   - Go to **Developers** → **Webhooks**
   - Click **Add endpoint**
   - URL: `https://yourdomain.com/api/webhooks/stripe`
   - Select events: `payment_intent.succeeded`, `payment_intent.payment_failed`
   - Copy the **Signing secret**

---

## 🗄️ Step 4: Run Migrations

The existing database structure supports payments. No new migrations needed for Phase 1.

If you want to add payment transactions table later:
```bash
php artisan make:migration create_payment_transactions_table
```

---

## 📝 Step 5: Publish Configuration Files

### Publish Sanctum config (for API)
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### Publish DOMPDF config
```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

### Publish Scribe config (API docs)
```bash
php artisan vendor:publish --tag=scribe-config
```

---

## 🔧 Step 6: Update Service Providers

Add this to `config/services.php`:

```php
'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
],

'paystack' => [
    'publicKey' => env('PAYSTACK_PUBLIC_KEY'),
    'secretKey' => env('PAYSTACK_SECRET_KEY'),
    'paymentUrl' => env('PAYSTACK_PAYMENT_URL'),
    'merchantEmail' => env('PAYSTACK_MERCHANT_EMAIL'),
],
```

---

## 🔐 Step 7: Configure Sanctum (API Authentication)

Update `config/sanctum.php`:

```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
    env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
))),

'expiration' => null, // or set to minutes for token expiration
```

Add Sanctum middleware to `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->api(prepend: [
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    ]);
})
```

---

## 📡 Step 8: Create API Routes

The API routes structure is already created in `routes/api.php`. The routes follow this pattern:

```php
Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{id}', [ProductController::class, 'show']);
        // ... more routes
    });
});
```

---

## 🧪 Step 9: Test Payment Integration

### Test Stripe Payment

1. Start your development server:
```bash
php artisan serve
```

2. Use these test card numbers:
   - **Success**: 4242 4242 4242 4242
   - **Decline**: 4000 0000 0000 0002
   - **Requires auth**: 4000 0025 0000 3155

3. Any future expiry date and CVC

### Test Invoice Generation

```bash
php artisan tinker
```

```php
$order = App\Models\Order::first();
$generator = new App\Services\InvoiceGenerator();
$path = $generator->generate($order);
echo $path; // Should show path to generated PDF
```

---

## 📚 Step 10: Generate API Documentation

```bash
php artisan scribe:generate
```

Access documentation at: `http://localhost:8000/docs/api`

---

## 🚀 Step 11: Test API Endpoints

### Using Postman or cURL

#### Register User
```bash
curl -X POST http://localhost:8000/api/v1/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

#### Login
```bash
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

Copy the `token` from response and use it in subsequent requests:

#### Get Products
```bash
curl -X GET http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 🎨 Step 12: Frontend Integration (Optional)

### Stripe Checkout Integration

Add to your checkout page:

```html
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ config("services.stripe.key") }}');
    
    async function createPayment() {
        const response = await fetch('/api/v1/payments/create-intent', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
            body: JSON.stringify({
                amount: 100.00,
                currency: 'usd',
                order_id: orderId
            })
        });
        
        const {client_secret} = await response.json();
        
        const {error} = await stripe.confirmCardPayment(client_secret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: 'Customer Name'
                }
            }
        });
        
        if (error) {
            console.error(error);
        } else {
            // Payment successful!
            console.log('Payment succeeded!');
        }
    }
</script>
```

---

## ✅ Verification Checklist

- [ ] Stripe packages installed
- [ ] DOMPDF installed
- [ ] Sanctum installed
- [ ] Environment variables configured
- [ ] Stripe API keys added
- [ ] Invoice template created
- [ ] API routes configured
- [ ] API documentation generated
- [ ] Test payment successful
- [ ] Test invoice generation successful
- [ ] API endpoints tested

---

## 🐛 Troubleshooting

### Issue: "Class 'Stripe\Stripe' not found"
**Solution**: Run `composer dump-autoload`

### Issue: PDF generation fails
**Solution**: 
1. Check if `storage/invoices` directory exists
2. Run `php artisan storage:link`
3. Check file permissions: `chmod -R 775 storage`

### Issue: API returns 401 Unauthorized
**Solution**: 
1. Make sure token is included in Authorization header
2. Check if token hasn't expired
3. Verify Sanctum is configured correctly

### Issue: Stripe webhook fails
**Solution**:
1. Use ngrok to expose local server: `ngrok http 8000`
2. Update webhook URL in Stripe dashboard
3. Test webhook with Stripe CLI: `stripe listen --forward-to localhost:8000/api/webhooks/stripe`

---

## 📖 Next Steps

After completing Phase 1, you can proceed to:

1. **Phase 2**: Real-time notifications and inventory management
2. **Filament Installation**: Modern admin panel
3. **Advanced Features**: Multi-store support, loyalty system

---

## 🎉 Phase 1 Complete!

You now have:
- ✅ Stripe payment integration
- ✅ PDF invoice generation
- ✅ REST API with authentication
- ✅ API documentation

**Estimated time to complete**: 2-3 hours

---

## 💡 Pro Tips

1. **Use Stripe Test Mode** until you're ready for production
2. **Enable webhook logging** to debug payment issues
3. **Cache invoice PDFs** to improve performance
4. **Rate limit API endpoints** to prevent abuse
5. **Use API resources** for consistent response formatting

---

## 📞 Need Help?

- Check the [UPGRADE_ROADMAP.md](UPGRADE_ROADMAP.md) for full details
- Review Laravel documentation
- Stripe documentation: https://stripe.com/docs
- DomPDF docs: https://github.com/barryvdh/laravel-dompdf

---

**Ready to integrate?** Start with `composer require stripe/stripe-php`! 🚀
