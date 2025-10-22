# Paystack Payment Testing Guide

Your Paystack integration is now configured and ready for testing! 🚀

## ✅ Current Status

- **Mode**: TEST MODE (Safe to test)
- **Public Key**: Configured ✓
- **Secret Key**: Configured ✓
- **Merchant Email**: ekowjeremy@gmail.com ✓
- **Routes**: All registered ✓

## 🧪 How to Test

### Step 1: Create an Order

1. Log into your POS system as a user
2. Add a product to cart
3. Place an order

### Step 2: Access Payment Checkout

Navigate to the checkout URL for your order:
```
http://localhost/payment/checkout/{order_id}
```

Or add a "Pay Now" button to your order details page:
```blade
@if($order->payment_status !== 'completed')
    <a href="{{ route('payment.checkout', $order) }}" class="btn btn-primary">
        <i class="fas fa-credit-card"></i> Pay Now
    </a>
@endif
```

### Step 3: Test with Paystack Test Cards

Use these test cards on the Paystack payment page:

#### ✅ Successful Payment
```
Card Number: 4084 0840 8408 4081
CVV: 408
Expiry: Any future date (e.g., 12/25)
PIN: 0000
OTP: 123456
```

#### ❌ Declined Payment
```
Card Number: 4084 0840 8408 4081
CVV: 408
Expiry: Any future date
PIN: 0000
OTP: 654321
```

#### ⚠️ Insufficient Funds
```
Card Number: 5061 0161 0161 6161
CVV: 606
Expiry: Any future date
PIN: 1234
OTP: 123456
```

### Step 4: Verify Payment Flow

1. **Checkout Page** → Should display order summary
2. **Click "Proceed to Payment"** → Redirected to Paystack
3. **Enter test card details** → Complete payment
4. **Automatic redirect** → Back to your site
5. **Order updated** → Status changed to "completed"
6. **Invoice generated** → Available for download

## 📊 What to Check

### After Successful Payment

✅ Order status changes to "completed"  
✅ `payment_status` set to "completed"  
✅ `payment_reference` saved in database  
✅ `paid_at` timestamp recorded  
✅ Invoice PDF generated in `storage/app/invoices/`  
✅ User sees success message

### Database Verification

Check the `orders` table:
```sql
SELECT id, order_code, payment_reference, payment_status, paid_at 
FROM orders 
WHERE id = {your_order_id};
```

### Check Logs

View Laravel logs for payment processing:
```bash
tail -f storage/logs/laravel.log
```

## 🔍 Testing Webhook (Optional)

To test webhooks locally, you'll need to expose your local server:

### Using Ngrok

1. Install ngrok: https://ngrok.com/download
2. Run your Laravel app: `php artisan serve`
3. Expose it: `ngrok http 8000`
4. Copy the HTTPS URL (e.g., `https://abc123.ngrok.io`)
5. Add webhook in Paystack Dashboard:
   - URL: `https://abc123.ngrok.io/payment/webhook`
6. Test a payment and monitor webhook events

## 🐛 Troubleshooting

### Payment Initialization Fails

**Symptom**: Error when clicking "Proceed to Payment"

**Solutions**:
- Verify API keys in `.env`
- Run `php artisan config:clear`
- Check `storage/logs/laravel.log` for errors
- Ensure you have internet connection

### Callback Not Working

**Symptom**: Payment successful but order not updated

**Solutions**:
- Check `payment/callback` route is accessible
- Verify payment reference is saved
- Check Laravel logs
- Ensure database connection is working

### Invoice Not Generating

**Symptom**: Payment works but no invoice created

**Solutions**:
```bash
# Create invoices directory
mkdir -p storage/app/invoices

# Set permissions
chmod -R 775 storage/app/invoices

# Clear cache
php artisan cache:clear
```

## 📱 Testing Scenarios

### Scenario 1: Complete Payment Flow
1. Create order → View order → Click "Pay Now"
2. Enter successful test card details
3. Verify order marked as paid
4. Download invoice

### Scenario 2: Failed Payment
1. Start payment process
2. Use declined test card (OTP: 654321)
3. Verify order remains unpaid
4. Check error message is displayed

### Scenario 3: Multiple Orders
1. Create 3 orders
2. Pay for order 1 and 3
3. Leave order 2 unpaid
4. Verify each has correct status

## 🎯 Quick Test Commands

```bash
# Clear all caches
php artisan config:clear && php artisan cache:clear

# Check routes
php artisan route:list --name=payment

# View logs
tail -20 storage/logs/laravel.log

# Test configuration
php test-paystack-config.php

# Check database
php artisan tinker
>>> Order::where('payment_status', 'completed')->count()
```

## 📈 Monitor in Paystack Dashboard

1. Log into [Paystack Dashboard](https://dashboard.paystack.com)
2. Go to **Transactions** → **Test Mode**
3. You'll see all test transactions
4. Click on a transaction to view details

## ✨ Ready for Production?

Before going live:

### Pre-Launch Checklist

- [ ] Replace test keys with live keys in `.env`
- [ ] Enable HTTPS on your domain
- [ ] Set up webhook URL in Paystack Dashboard
- [ ] Test on production environment
- [ ] Set up email notifications (optional)
- [ ] Configure proper error handling
- [ ] Set up monitoring/logging

### Switch to Live Mode

Update `.env`:
```env
PAYSTACK_PUBLIC_KEY=pk_live_xxxxxxxxxxxxxxxxxxxxx
PAYSTACK_SECRET_KEY=sk_live_xxxxxxxxxxxxxxxxxxxxx
```

Then run:
```bash
php artisan config:clear
```

## 🎉 Next Steps

1. Add currency to `.env` file
2. Test with the test cards above
3. Verify order updates correctly
4. Check invoice generation
5. Test webhook (optional)
6. When ready, switch to live keys

---

**Need Help?**
- Check `PAYSTACK_SETUP.md` for detailed setup info
- Review `PAYSTACK_QUICK_REFERENCE.md` for API examples
- Contact Paystack support: support@paystack.com
