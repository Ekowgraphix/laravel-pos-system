# 💳 Paystack Test Credentials & Cards

## 🔑 Test API Keys

### Get Your Own Test Keys:
1. Sign up at: https://dashboard.paystack.com/signup
2. Navigate to: **Settings → API Keys & Webhooks**
3. Toggle to **Test Mode**
4. Copy your keys

### Test Mode Keys Format:
```
Public Key: pk_test_xxxxxxxxxxxxxxxxxxxxxxxxxx
Secret Key: sk_test_xxxxxxxxxxxxxxxxxxxxxxxxxx
```

**Note**: These are YOUR personal test keys. Each developer gets unique keys when they sign up.

---

## 💳 Test Card Numbers

### ✅ Successful Transactions

#### 1. **Standard Success Card**
```
Card Number: 4084 0840 8408 4081
CVV: 408
Expiry: Any future date (e.g., 12/25)
PIN: 0000
OTP: 123456
```
**Result**: ✅ Transaction successful

#### 2. **Visa Success Card**
```
Card Number: 4187 4274 1556 4246
CVV: 123
Expiry: 12/26
PIN: 1234
OTP: 123456
```
**Result**: ✅ Transaction successful

#### 3. **Mastercard Success**
```
Card Number: 5399 8383 8383 8381
CVV: 883
Expiry: 12/25
PIN: 1111
OTP: 123456
```
**Result**: ✅ Transaction successful

#### 4. **Verve Card Success**
```
Card Number: 5060 6666 6666 6666 666
CVV: 123
Expiry: 12/25
PIN: 1234
OTP: 123456
```
**Result**: ✅ Transaction successful

---

### ⚠️ Test Scenarios - Different Responses

#### 5. **Insufficient Funds**
```
Card Number: 5060 6666 6666 6666 666
CVV: 123
Expiry: 12/25
PIN: 1234
OTP: 123456
Amount: Set to a very high amount (e.g., 1,000,000)
```
**Result**: ❌ Insufficient funds error

#### 6. **Timeout Test**
```
Card Number: 5060 9999 9999 9999 999
CVV: 123
Expiry: 12/25
PIN: 1234
```
**Result**: ⏱️ Transaction timeout

#### 7. **Invalid Card**
```
Card Number: 4084 0840 8408 4000
CVV: 408
Expiry: 12/25
```
**Result**: ❌ Invalid card error

---

## 🏦 Test Bank Accounts (Transfer)

### For Testing Bank Transfers:

#### 1. **Successful Transfer**
```
Account Number: 0123456789
Bank: GTBank (058)
Account Name: Test User
```
**Result**: ✅ Transfer successful

#### 2. **Any Bank Account Pattern**
```
Account Number: 0000000000 (10 zeros)
Bank: Any bank
Account Name: Will be resolved automatically
```
**Result**: ✅ Account validation successful

---

## 📱 Mobile Money Test Numbers

### Ghana Mobile Money

#### MTN Ghana
```
Number: 0241234567
Network: MTN
Voucher: 000000
```

#### Vodafone Ghana
```
Number: 0501234567
Network: Vodafone
Voucher: 000000
```

#### AirtelTigo Ghana
```
Number: 0261234567
Network: AirtelTigo
Voucher: 000000
```

**OTP**: Always use `123456` in test mode

---

## 🧪 Testing Different Scenarios

### Amount-Based Test Cases

```javascript
// Test different scenarios based on amount
const testScenarios = {
    // Success
    1000: "✅ Successful transaction",
    5000: "✅ Successful transaction",
    
    // Special test amounts (use with specific cards)
    100000000: "❌ Insufficient funds (with test cards)",
    
    // Standard testing
    "Any amount < 1,000,000": "✅ Success with standard test cards"
};
```

---

## 🎯 Complete Test Card Details

### Card 1: Primary Success Card (Visa)
```yaml
Card Number: 4084084084084081
CVV: 408
Expiry Month: 12
Expiry Year: 2025
PIN: 0000
OTP: 123456
3D Secure: Yes
Billing ZIP: 10001
Country: NG (Nigeria) or GH (Ghana)
```

### Card 2: Mastercard Success
```yaml
Card Number: 5399838383838381
CVV: 883
Expiry Month: 12
Expiry Year: 2026
PIN: 1111
OTP: 123456
3D Secure: Yes
Billing ZIP: 00233
Country: GH (Ghana)
```

### Card 3: Verve Card
```yaml
Card Number: 5060666666666666666
CVV: 123
Expiry Month: 12
Expiry Year: 2025
PIN: 1234
OTP: 123456
3D Secure: Yes
Country: NG (Nigeria)
```

---

## 🔐 Test Environment Setup

### .env Configuration
```env
# Paystack Test Mode
PAYSTACK_PUBLIC_KEY=pk_test_YOUR_PUBLIC_KEY_HERE
PAYSTACK_SECRET_KEY=sk_test_YOUR_SECRET_KEY_HERE
PAYSTACK_PAYMENT_URL=https://api.paystack.co
PAYSTACK_MERCHANT_EMAIL=your-email@example.com
```

### Get Your Keys:
1. Go to: https://paystack.com
2. Sign up for FREE
3. Activate test mode
4. Copy your test API keys

---

## 📋 Test Checklist

### Basic Tests:
- [ ] Successful card payment
- [ ] Failed payment (insufficient funds)
- [ ] Cancelled payment
- [ ] Card validation
- [ ] 3D Secure authentication
- [ ] OTP verification
- [ ] Payment confirmation

### Advanced Tests:
- [ ] Recurring payments
- [ ] Split payments
- [ ] Refunds
- [ ] Webhooks
- [ ] Transfer to bank
- [ ] Mobile money

---

## 🎨 Test Payment Flow

### Step 1: Initialize Transaction
```php
$amount = 10000; // 100.00 GHS (amount in pesewas/kobo)
$email = "customer@example.com";
$reference = 'PAY_' . time();
```

### Step 2: Enter Test Card
```
Card: 4084 0840 8408 4081
CVV: 408
Expiry: 12/25
```

### Step 3: Enter PIN
```
PIN: 0000
```

### Step 4: Enter OTP
```
OTP: 123456
```

### Step 5: Success! ✅
```
Payment Reference: PAY_1234567890
Status: Success
Amount: 100.00 GHS
```

---

## 🌍 Region-Specific Test Data

### 🇬🇭 Ghana
```yaml
Currency: GHS
Test Card: 4084084084084081
Mobile Money: 0241234567 (MTN)
Bank: GCB Bank (040)
Test Amount: 100.00 GHS (10000 pesewas)
```

### 🇳🇬 Nigeria
```yaml
Currency: NGN
Test Card: 5399838383838381
Bank: GTBank (058)
Test Amount: 10,000 NGN (in kobo: 1000000)
```

### 🇰🇪 Kenya
```yaml
Currency: KES
Test Card: 4084084084084081
Mobile Money: M-Pesa
Test Amount: 1,000 KES
```

### 🇿🇦 South Africa
```yaml
Currency: ZAR
Test Card: 4084084084084081
Test Amount: 100.00 ZAR
```

---

## 🧪 Quick Test Commands

### Test API Connection
```bash
curl https://api.paystack.co/bank \
  -H "Authorization: Bearer sk_test_YOUR_SECRET_KEY"
```

### Test Transaction Initialization
```bash
curl https://api.paystack.co/transaction/initialize \
  -H "Authorization: Bearer sk_test_YOUR_SECRET_KEY" \
  -H "Content-Type: application/json" \
  -d '{"email":"customer@email.com","amount":"10000"}' \
  -X POST
```

---

## 📞 Support & Documentation

### Official Resources:
- **Documentation**: https://paystack.com/docs/
- **API Reference**: https://paystack.com/docs/api/
- **Test Cards**: https://paystack.com/docs/payments/test-payments/
- **Support**: support@paystack.com
- **Status Page**: https://status.paystack.com/

### Community:
- **Slack**: https://paystack-community.slack.com
- **Twitter**: @PaystackHQ
- **GitHub**: https://github.com/PaystackHQ

---

## ⚠️ Important Notes

### 1. **Test Mode vs Live Mode**
- 🧪 **Test Mode**: Use test keys (pk_test_xxx, sk_test_xxx)
- 🔴 **Live Mode**: Use live keys (pk_live_xxx, sk_live_xxx)
- ⚠️ **NEVER** use live keys in development!

### 2. **Test Transactions**
- ✅ Test transactions don't charge real money
- ✅ Test data doesn't affect live business
- ✅ You can make unlimited test transactions
- ✅ Test transactions are auto-cleared after 30 days

### 3. **OTP for Test**
- Always use: **123456**
- Never use real OTPs in test mode

### 4. **PINs for Test**
- Common test PINs: **0000**, **1111**, **1234**
- Use with corresponding test cards

---

## 🎯 Common Test Scenarios

### Scenario 1: Standard Purchase (GHS 100)
```
Amount: 10000 (pesewas)
Card: 4084084084084081
CVV: 408
Expiry: 12/25
PIN: 0000
OTP: 123456
✅ Result: Success
```

### Scenario 2: Large Purchase (GHS 5,000)
```
Amount: 500000 (pesewas)
Card: 5399838383838381
CVV: 883
Expiry: 12/26
PIN: 1111
OTP: 123456
✅ Result: Success
```

### Scenario 3: Failed Payment
```
Amount: 100000000 (very high)
Card: 5060666666666666666
CVV: 123
Expiry: 12/25
PIN: 1234
OTP: 123456
❌ Result: Insufficient funds
```

---

## 🔒 Security Best Practices

### DO ✅
- Use environment variables for API keys
- Use test keys in development
- Verify webhooks
- Log all transactions
- Implement proper error handling

### DON'T ❌
- Hardcode API keys
- Use live keys in development
- Store card details
- Skip webhook verification
- Ignore failed transactions

---

## 📊 Sample Test Data Set

### Customer 1
```json
{
  "email": "john.doe@test.com",
  "first_name": "John",
  "last_name": "Doe",
  "phone": "+233241234567",
  "card": "4084084084084081",
  "cvv": "408",
  "expiry": "12/25",
  "pin": "0000"
}
```

### Customer 2
```json
{
  "email": "jane.smith@test.com",
  "first_name": "Jane",
  "last_name": "Smith",
  "phone": "+233501234567",
  "card": "5399838383838381",
  "cvv": "883",
  "expiry": "12/26",
  "pin": "1111"
}
```

### Customer 3
```json
{
  "email": "test.user@example.com",
  "first_name": "Test",
  "last_name": "User",
  "phone": "+233261234567",
  "card": "5060666666666666666",
  "cvv": "123",
  "expiry": "12/25",
  "pin": "1234"
}
```

---

## 🎉 Quick Start Guide

### 1. Get API Keys
```bash
# Sign up at https://paystack.com
# Go to Settings → API Keys
# Toggle Test Mode
# Copy pk_test_xxx and sk_test_xxx
```

### 2. Configure Your App
```env
PAYSTACK_PUBLIC_KEY=pk_test_your_key_here
PAYSTACK_SECRET_KEY=sk_test_your_key_here
```

### 3. Test Payment
```
Card: 4084 0840 8408 4081
CVV: 408
Expiry: 12/25
PIN: 0000
OTP: 123456
```

### 4. Verify Success ✅
```
Check transaction status
Webhook received
Amount credited
```

---

**🎊 You're all set! Start testing your Paystack integration!**

**Need help?** Check the official docs: https://paystack.com/docs/

**Status**: ✅ READY FOR TESTING  
**Updated**: October 22, 2025
