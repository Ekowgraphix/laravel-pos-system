@extends('user.layouts.master')
@section('content')

<style>
    .checkout-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 80px 0;
        position: relative;
        overflow: hidden;
        margin-bottom: 60px;
    }
    
    .checkout-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="white" stroke-width="0.5" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    }
    
    .checkout-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .checkout-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 70px rgba(0,0,0,0.2);
    }
    
    .checkout-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px;
        position: relative;
    }
    
    .checkout-header h2 {
        color: white;
        font-weight: 700;
        margin: 0;
        font-size: 28px;
    }
    
    .checkout-body {
        padding: 45px;
        background: white;
    }
    
    .info-section {
        background: #f8f9fc;
        border-radius: 15px;
        padding: 28px;
        margin-bottom: 28px;
        border-left: 5px solid #667eea;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .info-section h5 {
        color: #667eea;
        font-weight: 700;
        margin-bottom: 15px;
        font-size: 18px;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #e9ecef;
        min-height: 42px;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        color: #495057;
        font-weight: 600;
        font-size: 15px;
        letter-spacing: 0.3px;
    }
    
    .info-value {
        color: #212529;
        font-weight: 700;
        font-size: 15px;
        text-align: right;
    }
    
    .price-summary {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 18px;
        padding: 35px;
        margin: 35px 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .price-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 0;
        font-size: 17px;
        min-height: 52px;
    }
    
    .total-row {
        border-top: 3px solid #667eea;
        padding-top: 20px;
        margin-top: 15px;
    }
    
    .total-amount {
        font-size: 42px;
        font-weight: 900;
        color: #667eea;
        letter-spacing: -1px;
        line-height: 1;
    }
    
    .security-badge {
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        border-left: 5px solid #4caf50;
        border-radius: 12px;
        padding: 24px;
        margin: 30px 0;
        display: flex;
        align-items: center;
        gap: 18px;
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.15);
    }
    
    .security-badge i {
        font-size: 32px;
        color: #4caf50;
    }
    
    .security-text {
        flex: 1;
    }
    
    .security-text h6 {
        margin: 0;
        font-weight: 700;
        color: #1b5e20;
        font-size: 16px;
        letter-spacing: 0.3px;
    }
    
    .security-text p {
        margin: 6px 0 0;
        color: #2e7d32;
        font-size: 14px;
        line-height: 1.6;
    }
    
    .btn-checkout {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 18px 40px;
        font-size: 18px;
        font-weight: 700;
        border-radius: 50px;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        width: 100%;
        margin-top: 20px;
    }
    
    .btn-checkout:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.6);
        color: white;
    }
    
    .btn-cancel {
        background: white;
        border: 2px solid #e0e0e0;
        padding: 15px 40px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 50px;
        color: #6c757d;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 15px;
    }
    
    .btn-cancel:hover {
        border-color: #667eea;
        color: #667eea;
        background: #f8f9fa;
    }
    
    .payment-methods {
        background: white;
        border-radius: 20px;
        padding: 40px;
        margin-top: 35px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        border: 1px solid #e9ecef;
    }
    
    .payment-methods h5 {
        color: #212529;
        font-weight: 700;
        margin-bottom: 25px;
        text-align: center;
        font-size: 20px;
    }
    
    .payment-method-item {
        text-align: center;
        padding: 25px 20px;
        border-radius: 15px;
        transition: all 0.3s ease;
        background: #f8f9fc;
        border: 2px solid #e9ecef;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    .payment-method-item:hover {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        transform: translateY(-5px);
    }
    
    .payment-method-item i {
        font-size: 40px;
        margin-bottom: 15px;
        display: block;
    }
    
    .payment-method-item p {
        margin: 0;
        font-weight: 700;
        color: #212529;
        font-size: 14px;
        letter-spacing: 0.3px;
    }
    
    .order-badge {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 700;
        display: inline-block;
    }
    
    @media (max-width: 768px) {
        .checkout-body {
            padding: 25px;
        }
        
        .total-amount {
            font-size: 28px;
        }
    }
</style>

<!-- Hero Section -->
<div class="checkout-hero">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="text-white fw-bold mb-3" style="font-size: 42px;">Secure Checkout</h1>
                <p class="text-white-50" style="font-size: 18px;">Complete your purchase securely with Paystack</p>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Content -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            
            <!-- Main Checkout Card -->
            <div class="checkout-card">
                <div class="checkout-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2><i class="fas fa-shopping-bag me-3"></i>Order Summary</h2>
                        <span class="order-badge">{{ $order->order_code }}</span>
                    </div>
                </div>
                
                <div class="checkout-body">
                    <!-- Order Details -->
                    <div class="info-section">
                        <h5><i class="fas fa-box me-2"></i>Product Information</h5>
                        <div class="info-row">
                            <span class="info-label">Product Name</span>
                            <span class="info-value">{{ $order->product->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Quantity</span>
                            <span class="info-value">{{ $order->count }} {{ $order->count > 1 ? 'items' : 'item' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Unit Price</span>
                            <span class="info-value">GH₵ {{ number_format($order->product->price, 2) }}</span>
                        </div>
                    </div>
                    
                    <!-- Customer Details -->
                    <div class="info-section">
                        <h5><i class="fas fa-user me-2"></i>Customer Information</h5>
                        <div class="info-row">
                            <span class="info-label">Full Name</span>
                            <span class="info-value">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email Address</span>
                            <span class="info-value">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                    
                    <!-- Price Summary -->
                    <div class="price-summary">
                        <h5 class="mb-4" style="color: #667eea; font-weight: 700;"><i class="fas fa-calculator me-2"></i>Payment Breakdown</h5>
                        <div class="price-row">
                            <span>Subtotal</span>
                            <span class="fw-bold">GH₵ {{ number_format($order->totalPrice, 2) }}</span>
                        </div>
                        <div class="price-row">
                            <span>Tax & Fees</span>
                            <span class="fw-bold">GH₵ 0.00</span>
                        </div>
                        <div class="price-row total-row">
                            <span style="font-size: 20px; font-weight: 700;">Total Amount</span>
                            <span class="total-amount">GH₵ {{ number_format($order->totalPrice, 2) }}</span>
                        </div>
                    </div>
                    
                    <!-- Security Badge -->
                    <div class="security-badge">
                        <i class="fas fa-shield-alt"></i>
                        <div class="security-text">
                            <h6>Secure Payment Guaranteed</h6>
                            <p>Your payment information is encrypted and secure with Paystack's PCI-DSS compliant gateway</p>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-grid">
                        <form action="{{ route('payment.checkout', $order) }}" method="GET">
                            <button type="submit" class="btn-checkout">
                                <i class="fas fa-lock me-2"></i>Proceed to Secure Payment
                            </button>
                        </form>
                        <a href="{{ route('orderList') }}" class="btn-cancel">
                            <i class="fas fa-arrow-left me-2"></i>Return to Orders
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Payment Methods -->
            <div class="payment-methods">
                <h5>Accepted Payment Methods</h5>
                <div class="row g-4">
                    <div class="col-md-4 col-sm-6">
                        <div class="payment-method-item">
                            <i class="fab fa-cc-visa" style="color: #1A1F71;"></i>
                            <p>Visa & Mastercard</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="payment-method-item">
                            <i class="fas fa-university" style="color: #28a745;"></i>
                            <p>Bank Transfer</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="payment-method-item">
                            <i class="fas fa-mobile-alt" style="color: #17a2b8;"></i>
                            <p>Mobile Money</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

@endsection
