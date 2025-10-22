# Paystack Payment Integration Setup Guide

This guide will help you set up and configure Paystack payment integration for your Laravel POS system.

## 📋 Prerequisites

- PHP 8.2 or higher
- Laravel 11.x
- Composer installed
- A Paystack account ([Sign up here](https://paystack.com))

## 🚀 Installation Complete

All required packages and services have been installed:
- ✅ Paystack Payment Service
- ✅ Invoice Generator (with DomPDF)
- ✅ Payment Controller
- ✅ Routes configured
- ✅ Middleware configured
- ✅ Database migrations run

## ⚙️ Configuration Steps

### 1. Get Your Paystack API Keys

1. Log in to your [Paystack Dashboard](https://dashboard.paystack.com/)
2. Go to **Settings** → **API Keys & Webhooks**
3. Copy your:
   - **Public Key** (starts with `pk_test_` or `pk_live_`)
   - **Secret Key** (starts with `sk_test_` or `sk_live_`)

### 2. Update Environment Variables

Open your `.env` file and add/update these values:

```env
# Paystack Payment Gateway
PAYSTACK_PUBLIC_KEY=pk_test_xxxxxxxxxxxxxxxxxxxxx
PAYSTACK_SECRET_KEY=sk_test_xxxxxxxxxxxxxxxxxxxxx
PAYSTACK_BASE_URL=https://api.paystack.co
PAYSTACK_MERCHANT_EMAIL=your-email@example.com
PAYSTACK_CURRENCY=GHS
```

**Important:** 
- Use **test keys** for development (prefix: `pk_test_` and `sk_test_`)
- Use **live keys** for production (prefix: `pk_live_` and `sk_live_`)
- Set `PAYSTACK_CURRENCY` to your preferred currency (GHS, NGN, ZAR, or USD)

### 3. Configure Webhook URL (For Production)

1. Go to **Paystack Dashboard** → **Settings** → **API Keys & Webhooks**
2. Click on **Webhooks**
3. Add your webhook URL: `https://yourdomain.com/payment/webhook`
4. The webhook is automatically configured to:
   - Verify signatures
   - Handle successful payments
   - Handle failed payments
   - Process refunds

### 4. Clear Configuration Cache

Run these commands to ensure all changes are loaded:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## 📝 Usage

### Initiating a Payment

To redirect users to the payment page, use the checkout route:

```php
// In your controller or blade view
route('payment.checkout', ['order' => $order])
```

### Payment Flow

1. **User clicks "Pay Now"** → Redirected to checkout page
2. **Checkout page displays** → Order summary with payment button
3. **User clicks "Proceed to Payment"** → Redirected to Paystack
4. **User completes payment** → Redirected back to your site
5. **Payment verified** → Order marked as completed & invoice generated
6. **User sees success message** → Can download invoice

### Available Routes

| Route | Method | Description |
|-------|--------|-------------|
| `/payment/checkout/{order}` | GET | Display checkout page |
| `/payment/callback` | GET | Paystack callback (automatic) |
| `/payment/webhook` | POST | Paystack webhook (automatic) |

## 🧪 Testing

### Test Cards

Paystack provides test cards for development:

| Card Number | CVV | Expiry | PIN | OTP | Description |
|-------------|-----|--------|-----|-----|-------------|
| 4084 0840 8408 4081 | 408 | Any future date | 0000 | 123456 | Success |
| 4084 0840 8408 4081 | 408 | Any future date | 0000 | 654321 | Decline |
| 5061 0161 0161 6161 | 606 | Any future date | 1234 | 123456 | Insufficient funds |

### Testing Workflow

1. Create an order in your POS system
2. Navigate to the checkout page
3. Click "Proceed to Payment"
4. Use a test card from above
5. Complete the payment flow
6. Verify the order status changes to "completed"
7. Check that an invoice was generated

## 🔐 Security Features

### Implemented Security Measures

- ✅ **Webhook Signature Verification** - Validates all incoming webhook requests
- ✅ **HTTPS Enforcement** - Paystack only works with HTTPS in production
- ✅ **Secret Key Protection** - Never expose your secret key in frontend code
- ✅ **CSRF Protection** - Webhook route excluded from CSRF (required for external callbacks)
- ✅ **Authorization Checks** - Users can only pay for their own orders

### Best Practices

1. **Never commit `.env` file** - It contains sensitive keys
2. **Use test keys in development** - Switch to live keys only in production
3. **Enable HTTPS** - Required for production use
4. **Rotate keys regularly** - Change your API keys periodically
5. **Monitor transactions** - Check your Paystack dashboard regularly

## 📊 Database Schema

The following columns were added to the `orders` table:

```php
$table->string('payment_reference')->nullable();   // Paystack reference
$table->string('payment_status')->default('pending');  // Payment status
$table->string('payment_error')->nullable();       // Error messages
$table->timestamp('paid_at')->nullable();          // Payment timestamp
```

## 🎨 Customization

### Modify Payment Channels

Edit `config/paystack.php` to enable/disable payment channels:

```php
'channels' => ['card', 'bank', 'ussd', 'qr', 'mobile_money', 'bank_transfer'],
```

### Customize Invoice Template

Edit `resources/views/invoices/template.blade.php` to modify invoice design.

### Customize Checkout Page

Edit `resources/views/user/checkout.blade.php` to modify checkout page layout.

## 🐛 Troubleshooting

### Payment initialization fails

- **Check:** API keys are correct in `.env`
- **Check:** Secret key starts with `sk_test_` or `sk_live_`
- **Solution:** Run `php artisan config:clear`

### Webhook not working

- **Check:** Webhook URL is publicly accessible
- **Check:** URL uses HTTPS (required in production)
- **Check:** CSRF exclusion is configured in `bootstrap/app.php`

### Order not updating after payment

- **Check:** Database migrations were run
- **Check:** `payment_reference` column exists in orders table
- **Check:** Webhook signature verification is passing

### Invoice not generating

- **Check:** DomPDF is installed (`composer show barryvdh/laravel-dompdf`)
- **Check:** `storage/app/invoices` directory exists and is writable
- **Solution:** Run `php artisan storage:link`

## 📞 Support

### Paystack Support
- Documentation: [https://paystack.com/docs](https://paystack.com/docs)
- Email: support@paystack.com
- Twitter: [@PaystackHQ](https://twitter.com/PaystackHQ)

### Common Issues
1. **401 Unauthorized** - Invalid API key
2. **400 Bad Request** - Missing required parameters
3. **Invalid signature** - Webhook secret mismatch

## 🎯 Next Steps

1. ✅ Test the payment flow with test cards
2. ✅ Verify webhooks are working
3. ✅ Test invoice generation
4. ✅ Enable HTTPS for production
5. ✅ Replace test keys with live keys when ready to go live
6. ✅ Set up email notifications for successful payments (optional)

## 📚 Additional Resources

- [Paystack API Documentation](https://paystack.com/docs/api/)
- [Test Cards List](https://paystack.com/docs/payments/test-payments/)
- [Webhook Events](https://paystack.com/docs/payments/webhooks/)
- [Payment Channels](https://paystack.com/docs/payments/payment-channels/)

---

**🎉 Congratulations!** Your Paystack integration is ready to use. Start testing with the test cards above.
