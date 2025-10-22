# 🇬🇭 Ghana-Specific Paystack Setup

**Currency**: Ghana Cedis (GHS)  
**Market**: Ghana  
**Transaction Fee**: 1.95%

---

## 🎯 Quick Setup for Ghana

### Step 1: Environment Configuration

Update your `.env` file:

```env
# Paystack Configuration for Ghana
PAYSTACK_PUBLIC_KEY=pk_test_your_public_key_here
PAYSTACK_SECRET_KEY=sk_test_your_secret_key_here
PAYSTACK_MERCHANT_EMAIL=your@business.com.gh
PAYSTACK_BASE_URL=https://api.paystack.co
PAYSTACK_CURRENCY=GHS

# Company Information (for invoices)
COMPANY_NAME="Your Ghana Business"
COMPANY_ADDRESS="Your Address, Accra, Ghana"
COMPANY_PHONE="+233 XX XXX XXXX"
COMPANY_EMAIL="info@yourbusiness.com.gh"
```

---

## 💳 Ghana Payment Methods

### ✅ Supported in Ghana

1. **Credit/Debit Cards**
   - Visa
   - Mastercard
   - Verve
   - Fee: 1.95%

2. **Mobile Money**
   - MTN Mobile Money
   - Vodafone Cash
   - AirtelTigo Money
   - Fee: 1.95%

3. **Bank Transfer**
   - Direct bank transfers
   - Available for all major Ghana banks

---

## 🏦 Popular Banks in Ghana

The system supports all major Ghanaian banks:
- GCB Bank
- Ecobank Ghana
- Stanbic Bank
- Standard Chartered
- Zenith Bank
- Fidelity Bank
- CalBank
- And many more...

---

## 💰 Pricing for Ghana

### Transaction Fees
- **All Cards**: 1.95%
- **Mobile Money**: 1.95%
- **No hidden fees**
- **No setup costs**
- **No monthly fees**

### Examples

| Transaction Amount | Fee (1.95%) | You Receive |
|-------------------|-------------|-------------|
| GH₵ 100 | GH₵ 1.95 | GH₵ 98.05 |
| GH₵ 500 | GH₵ 9.75 | GH₵ 490.25 |
| GH₵ 1,000 | GH₵ 19.50 | GH₵ 980.50 |
| GH₵ 5,000 | GH₵ 97.50 | GH₵ 4,902.50 |

### Settlement
- **Timeline**: 2-3 business days
- **To**: Your Ghana bank account
- **Currency**: Ghana Cedis (GHS)

---

## 🧪 Test Cards for Ghana

### Successful Payment
```
Card Number: 4084 0840 8408 4081
Expiry Date: 12/26
CVV: 408
PIN: 0000
OTP: 123456
```

### Declined Payment
```
Card Number: 5060 6666 6666 6666
Expiry Date: 12/26
CVV: 123
```

---

## 📱 Mobile Money Integration

### MTN Mobile Money
- Most popular in Ghana
- Instant confirmation
- 1.95% transaction fee

### How it works:
1. Customer selects Mobile Money
2. Enters phone number
3. Receives USSD prompt
4. Approves payment on phone
5. Payment confirmed instantly

### Code Example:
```php
$paystack = new PaystackPaymentService();
$result = $paystack->createPaymentIntent(
    100.00, // Amount in GHS
    'GHS',
    [
        'email' => 'customer@email.com',
        'channels' => ['mobile_money', 'card'], // Allow both
    ]
);
```

---

## 🏪 Typical Ghana Prices (Examples)

### Small Items
```php
// Small purchase (GH₵ 50)
$paystack->createPaymentIntent(50.00, 'GHS', [...]);
// Fee: GH₵ 0.98
```

### Medium Purchases
```php
// Medium purchase (GH₵ 200)
$paystack->createPaymentIntent(200.00, 'GHS', [...]);
// Fee: GH₵ 3.90
```

### Large Orders
```php
// Large order (GH₵ 1,000)
$paystack->createPaymentIntent(1000.00, 'GHS', [...]);
// Fee: GH₵ 19.50
```

---

## 📞 Ghana Support

### Paystack Ghana Contact
- **Email**: support@paystack.com
- **Phone**: Available through dashboard
- **Response Time**: Usually within 2 hours
- **Business Hours**: 24/7 support

### Local Documentation
- Ghana-specific guides available
- Mobile Money integration docs
- Bank integration examples

---

## 🎓 Ghana Business Requirements

### For Test Mode
- ✅ Email address
- ✅ Business name
- ✅ Phone number
- ✅ That's it! Start testing immediately

### For Live Mode (Production)
- ✅ Valid Ghana business registration
- ✅ Ghana bank account
- ✅ Business documents
- ✅ Director ID verification
- ✅ Complete KYC (Know Your Customer)

**Timeline**: Usually 2-3 business days for approval

---

## 💡 Ghana-Specific Tips

### Best Practices

1. **Mobile Money First**
   - Most Ghanaians prefer mobile money
   - Enable it as primary option
   - Card payments as secondary

2. **Pricing in Ghana Cedis**
   - Always show prices in GH₵
   - Round to 2 decimal places
   - Example: GH₵ 99.99 not GH₵ 99.995

3. **Customer Communication**
   - Send SMS for payment confirmations
   - Use WhatsApp for receipts
   - Email invoices as PDF

4. **Peak Times**
   - End of month: Higher volumes
   - Fridays: Increased transactions
   - Prepare for traffic spikes

### Common Challenges & Solutions

**Challenge**: Customer's mobile money transaction delayed  
**Solution**: Enable webhook to auto-update when payment confirms

**Challenge**: Card declined  
**Solution**: Offer mobile money as alternative

**Challenge**: Customer wants to pay cash  
**Solution**: Generate QR code for later payment

---

## 🚀 Quick Start Example

### Complete Ghana Payment Flow

```php
<?php

namespace App\Http\Controllers;

use App\Services\PaymentGateway\PaystackPaymentService;
use App\Models\Order;
use Illuminate\Http\Request;

class GhanaPaymentController extends Controller
{
    public function checkout(Order $order)
    {
        $paystack = new PaystackPaymentService();
        
        // Initialize payment in Ghana Cedis
        $result = $paystack->createPaymentIntent(
            $order->total_amount,
            'GHS',
            [
                'email' => $order->customer->email,
                'order_id' => $order->id,
                'channels' => ['mobile_money', 'card', 'bank'], // All Ghana options
                'metadata' => [
                    'customer_name' => $order->customer->name,
                    'customer_phone' => $order->customer->phone,
                    'order_number' => $order->order_number,
                ]
            ]
        );
        
        if ($result['success']) {
            // Store payment reference
            $order->update([
                'payment_reference' => $result['payment_id']
            ]);
            
            // Redirect to Paystack payment page
            return redirect($result['authorization_url']);
        }
        
        return back()->with('error', 'Payment initialization failed');
    }
    
    public function callback(Request $request)
    {
        $reference = $request->query('reference');
        $paystack = new PaystackPaymentService();
        
        // Verify the payment
        $result = $paystack->confirmPayment($reference);
        
        if ($result['success'] && $result['status'] === 'success') {
            $order = Order::where('payment_reference', $reference)->first();
            
            $order->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);
            
            // Send confirmation SMS/WhatsApp
            // Generate and email invoice
            
            return redirect()
                ->route('orders.success', $order)
                ->with('success', 'Payment of GH₵ ' . number_format($result['amount'], 2) . ' received!');
        }
        
        return redirect()
            ->route('orders.failed')
            ->with('error', 'Payment verification failed');
    }
}
```

---

## 📊 Ghana Market Insights

### Payment Preferences (2024)
1. **Mobile Money**: 60% of transactions
2. **Debit Cards**: 30% of transactions
3. **Credit Cards**: 8% of transactions
4. **Bank Transfer**: 2% of transactions

### Popular Transaction Times
- **Morning**: 9 AM - 11 AM (moderate)
- **Lunch**: 12 PM - 2 PM (high)
- **Evening**: 6 PM - 9 PM (highest)
- **Weekends**: Lower volume

---

## ✅ Pre-Launch Checklist for Ghana

### Technical
- [ ] Ghana Cedis (GHS) set as default currency
- [ ] Mobile money enabled
- [ ] Test transaction completed
- [ ] Webhook configured
- [ ] Invoice template updated with Ghana branding

### Business
- [ ] Business registration complete
- [ ] Ghana bank account linked
- [ ] KYC documents submitted
- [ ] Test mode thoroughly tested
- [ ] Customer support number ready

### Legal
- [ ] Terms & Conditions in place
- [ ] Privacy Policy updated
- [ ] Ghana data protection compliance
- [ ] Return/Refund policy clear

---

## 🎉 You're Ready for Ghana!

Your POS system is now configured for the Ghana market with:
- ✅ Ghana Cedis (GHS) as default currency
- ✅ 1.95% transaction fee (competitive!)
- ✅ Mobile Money support (MTN, Vodafone, AirtelTigo)
- ✅ All major Ghana banks supported
- ✅ Fast 2-3 day settlements
- ✅ 24/7 support

---

## 🚦 Next Steps

1. **Get Live Keys** (2-3 days)
   - Submit business documents
   - Complete KYC verification
   - Link Ghana bank account

2. **Test Everything**
   - Mobile money flow
   - Card payments
   - Bank transfers
   - Refund process

3. **Go Live!**
   - Switch to live keys
   - Monitor first transactions
   - Celebrate! 🎊

---

**Akwaaba! Welcome to seamless payments in Ghana!** 🇬🇭

*Need help? Check `PAYSTACK_INTEGRATION.md` for detailed guides*
