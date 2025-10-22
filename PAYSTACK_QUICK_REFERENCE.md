# 💳 Paystack Quick Reference Card

One-page cheat sheet for Paystack integration.

---

## 🔑 API Keys Location

```
Dashboard → Settings → API Keys & Webhooks
```

**Test Keys**: Start with `pk_test_` and `sk_test_`  
**Live Keys**: Start with `pk_live_` and `sk_live_`

---

## ⚙️ .env Configuration

```env
PAYSTACK_PUBLIC_KEY=pk_test_xxxxx
PAYSTACK_SECRET_KEY=sk_test_xxxxx
PAYSTACK_MERCHANT_EMAIL=your@email.com
PAYSTACK_BASE_URL=https://api.paystack.co
PAYSTACK_CURRENCY=GHS
```

---

## 💻 Quick Implementation

### Initialize Payment

```php
$paystack = new PaystackPaymentService();
$result = $paystack->createPaymentIntent(100.00, 'GHS', [
    'email' => 'customer@email.com',
    'order_id' => $orderId,
]);

// Redirect user to: $result['authorization_url']
```

### Verify Payment

```php
$result = $paystack->confirmPayment($reference);
if ($result['success'] && $result['status'] === 'success') {
    // Payment confirmed!
}
```

### Process Refund

```php
$result = $paystack->refundPayment($reference, 1000.00);
if ($result['success']) {
    // Refund initiated
}
```

---

## 🧪 Test Cards

### Success
```
Card: 4084 0840 8408 4081
Expiry: 12/26
CVV: 408
PIN: 0000
OTP: 123456
```

### Decline
```
Card: 5060 6666 6666 6666
Expiry: 12/26
CVV: 123
```

---

## 🔗 Important URLs

| Purpose | URL |
|---------|-----|
| Dashboard | https://dashboard.paystack.com |
| API Docs | https://paystack.com/docs/api |
| Test Cards | https://paystack.com/docs/payments/test-payments |
| Status Page | https://status.paystack.com |
| Support | support@paystack.com |

---

## 💰 Currencies Supported

- **GHS** - Ghanaian Cedi (primary)
- **NGN** - Nigerian Naira
- **ZAR** - South African Rand
- **USD** - US Dollar

---

## 📡 Webhook Events

```php
'charge.success'      // Payment successful
'charge.failed'       // Payment failed
'transfer.success'    // Transfer completed
'transfer.failed'     // Transfer failed
'subscription.create' // New subscription
```

### Webhook URL Format
```
https://yourdomain.com/payment/webhook
```

### Verify Signature
```php
$signature = request()->header('x-paystack-signature');
$payload = request()->getContent();
$isValid = $paystack->verifyWebhook($payload, $signature);
```

---

## 💵 Transaction Fees

| Region | Local Cards | International |
|--------|-------------|---------------|
| Nigeria | 1.5% (max ₦2,000) | 3.9% |
| Ghana | 1.95% | 1.95% |
| South Africa | 2.9% | 2.9% |

---

## 🚨 Common Errors

| Error | Solution |
|-------|----------|
| Invalid API key | Check .env file |
| Unauthorized | Using wrong key (test/live) |
| Invalid amount | Amount must be > 0 |
| Reference exists | Use unique reference |
| Webhook failed | Verify signature |

---

## 📱 Payment Channels

```php
// Card payments (default)
// Bank transfer
// USSD (*737# codes)
// Mobile money
// QR code
```

---

## 🔐 Security Checklist

- [ ] Secret key in .env (not committed)
- [ ] Webhook signature verification enabled
- [ ] HTTPS in production
- [ ] Amount validation on server
- [ ] User authorization checks
- [ ] Transaction logging

---

## 🎯 Quick Test Flow

1. Initialize payment → Get authorization URL
2. Redirect user → Paystack payment page
3. User pays → Test card above
4. Callback received → Verify payment
5. Update order → Mark as paid
6. Generate invoice → Email customer

---

## 💡 Pro Tips

✅ **Always verify payments server-side**  
✅ **Store payment reference with orders**  
✅ **Use webhooks for automation**  
✅ **Test thoroughly before going live**  
✅ **Monitor dashboard daily**  
✅ **Handle failed payments gracefully**  

---

## 📞 Need Help?

```bash
# Check logs
tail -f storage/logs/laravel.log

# Test webhook locally
ngrok http 8000

# Verify config
php artisan tinker
>>> config('services.paystack')
```

---

**Print this page for quick reference!** 🖨️
