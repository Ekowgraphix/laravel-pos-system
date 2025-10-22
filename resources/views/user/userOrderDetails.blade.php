@extends('user.layouts.master')
@section('content')

<style>
    .order-details-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 70px 0;
        position: relative;
        overflow: hidden;
        margin-bottom: 50px;
    }
    
    .order-details-hero::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        top: -100px;
        right: -50px;
    }
    
    .order-details-hero h1 {
        font-size: 42px;
        font-weight: 800;
        color: white;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        letter-spacing: -0.5px;
        line-height: 1.2;
    }
    
    .back-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 2px solid white;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    
    .back-btn:hover {
        background: white;
        color: #667eea;
    }
    
    .order-status-card {
        background: white;
        border-radius: 20px;
        padding: 35px;
        margin-bottom: 35px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        border: 2px solid #f0f0f0;
    }
    
    .status-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 24px;
        min-height: 52px;
    }
    
    .order-code-display {
        font-size: 26px;
        font-weight: 800;
        color: #667eea;
        display: flex;
        align-items: center;
        gap: 14px;
        letter-spacing: 0.3px;
    }
    
    .payment-complete-badge {
        background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
        color: white;
        padding: 16px 34px;
        border-radius: 50px;
        font-size: 18px;
        font-weight: 700;
        box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
        display: inline-flex;
        align-items: center;
        gap: 12px;
        letter-spacing: 0.3px;
        min-height: 52px;
    }
    
    .pay-now-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 16px 42px;
        border-radius: 50px;
        font-size: 18px;
        font-weight: 700;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
        letter-spacing: 0.3px;
        min-height: 52px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    
    .pay-now-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.6);
        color: white;
    }
    
    .product-card {
        background: white;
        border-radius: 20px;
        padding: 28px;
        margin-bottom: 22px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 2px solid #e9ecef;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0,0,0,0.15);
        border-color: #667eea;
    }
    
    .product-image-wrapper {
        width: 120px;
        height: 120px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    
    .product-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .product-info {
        flex: 1;
        padding: 0 25px;
    }
    
    .product-name {
        font-size: 21px;
        font-weight: 700;
        color: #212529;
        margin-bottom: 12px;
        letter-spacing: 0.2px;
        line-height: 1.4;
    }
    
    .product-meta {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
        margin-top: 15px;
    }
    
    .meta-item {
        display: flex;
        flex-direction: column;
    }
    
    .meta-label {
        font-size: 12px;
        color: #495057;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin-bottom: 6px;
        font-weight: 600;
    }
    
    .meta-value {
        font-size: 19px;
        font-weight: 700;
        color: #212529;
        letter-spacing: 0.2px;
    }
    
    .price-highlight {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 30px;
        letter-spacing: -0.5px;
    }
    
    .order-summary {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 20px;
        padding: 35px;
        margin-top: 35px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .summary-title {
        font-size: 26px;
        font-weight: 800;
        color: #212529;
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        gap: 14px;
        letter-spacing: 0.2px;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 0;
        border-bottom: 2px solid rgba(255, 255, 255, 0.5);
        min-height: 52px;
    }
    
    .summary-row:last-child {
        border-bottom: none;
        padding-top: 20px;
        margin-top: 10px;
        border-top: 3px solid #667eea;
    }
    
    .summary-label {
        font-size: 17px;
        color: #212529;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    
    .summary-value {
        font-size: 19px;
        font-weight: 700;
        color: #212529;
        letter-spacing: 0.2px;
    }
    
    .grand-total {
        font-size: 38px !important;
        font-weight: 900 !important;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -1px;
        line-height: 1;
    }
    
    .print-receipt-btn {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        border: none;
        padding: 16px 42px;
        border-radius: 50px;
        font-size: 18px;
        font-weight: 700;
        box-shadow: 0 10px 30px rgba(17, 153, 142, 0.4);
        transition: all 0.3s ease;
        letter-spacing: 0.3px;
        min-height: 52px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        text-decoration: none;
        margin-left: 15px;
    }
    
    .print-receipt-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(17, 153, 142, 0.6);
        color: white;
    }
    
    @media print {
        /* Hide non-essential elements when printing */
        .order-details-hero,
        .back-btn,
        .pay-now-btn,
        .print-receipt-btn,
        header,
        footer,
        nav,
        .navbar {
            display: none !important;
        }
        
        body {
            background: white !important;
        }
        
        .container {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 20px !important;
        }
        
        .order-status-card {
            box-shadow: none !important;
            border: 2px solid #000 !important;
            page-break-inside: avoid;
        }
        
        .product-card {
            box-shadow: none !important;
            border: 1px solid #000 !important;
            page-break-inside: avoid;
        }
        
        .order-summary {
            background: white !important;
            border: 2px solid #000 !important;
            page-break-inside: avoid;
        }
        
        .price-highlight {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color: #667eea !important;
        }
        
        .grand-total {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color: #667eea !important;
        }
        
        .payment-complete-badge {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        /* Print header */
        .print-header {
            display: block !important;
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #000;
        }
        
        .print-header h1 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 10px;
        }
        
        .print-date {
            display: block !important;
            text-align: right;
            font-weight: 600;
            margin-bottom: 20px;
        }
    }
    
    .print-header {
        display: none;
    }
    
    .print-date {
        display: none;
    }
    
    @media (max-width: 768px) {
        .order-details-hero h1 {
            font-size: 28px;
        }
        
        .product-card {
            flex-direction: column;
            text-align: center;
        }
        
        .product-image-wrapper {
            margin: 0 auto 20px;
        }
        
        .product-info {
            padding: 0;
        }
        
        .product-meta {
            justify-content: center;
        }
        
        .status-header {
            flex-direction: column;
            text-align: center;
        }
        
        .print-receipt-btn {
            margin-left: 0;
            margin-top: 10px;
        }
    }
</style>

<!-- Hero Section -->
<div class="order-details-hero">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <a href="{{ route('orderList')}}" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>
                <h1 class="mt-4"><i class="fas fa-file-invoice me-3"></i>Order Details</h1>
            </div>
        </div>
    </div>
</div>

<!-- Order Content -->
<div class="container my-5">
    <!-- Print Header (Only visible when printing) -->
    <div class="print-header">
        <h1>{{ config('app.name', 'Winniema\'s Enterprise') }}</h1>
        <p style="margin: 0; font-size: 16px;">Payment Receipt</p>
    </div>
    
    <div class="print-date">
        <strong>Print Date:</strong> {{ now()->format('F d, Y h:i A') }}
    </div>
    
    <!-- Order Status Card -->
    <div class="order-status-card">
        <div class="status-header">
            <div class="order-code-display">
                <i class="fas fa-hashtag"></i>
                {{ $orderInfo->order_code }}
            </div>
            <div>
                @if($orderInfo && $orderInfo->payment_status !== 'completed' && $orderInfo->status == 0)
                    <a href="{{ route('payment.checkout', $orderInfo->id) }}" class="pay-now-btn">
                        <i class="fas fa-credit-card me-2"></i>Pay Now
                    </a>
                @elseif($orderInfo && $orderInfo->payment_status === 'completed')
                    <span class="payment-complete-badge">
                        <i class="fas fa-check-circle"></i> Payment Complete
                    </span>
                    <button onclick="window.print()" class="print-receipt-btn">
                        <i class="fas fa-print"></i> Print Receipt
                    </button>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Customer & Payment Info (Shows on print) -->
    @if($orderInfo && $orderInfo->payment_status === 'completed')
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="order-status-card" style="padding: 20px;">
                <h5 style="font-weight: 700; margin-bottom: 15px;">
                    <i class="fas fa-user me-2"></i>Customer Information
                </h5>
                <p style="margin-bottom: 8px;"><strong>Name:</strong> {{ $orderInfo->user->name ?? 'N/A' }}</p>
                <p style="margin-bottom: 8px;"><strong>Email:</strong> {{ $orderInfo->user->email ?? 'N/A' }}</p>
                <p style="margin-bottom: 8px;"><strong>Phone:</strong> {{ $orderInfo->user->phone ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="order-status-card" style="padding: 20px;">
                <h5 style="font-weight: 700; margin-bottom: 15px;">
                    <i class="fas fa-credit-card me-2"></i>Payment Information
                </h5>
                <p style="margin-bottom: 8px;"><strong>Status:</strong> <span style="color: #4caf50;">Paid</span></p>
                <p style="margin-bottom: 8px;"><strong>Date:</strong> {{ $orderInfo->paid_at ? \Carbon\Carbon::parse($orderInfo->paid_at)->format('F d, Y h:i A') : 'N/A' }}</p>
                <p style="margin-bottom: 8px;"><strong>Reference:</strong> {{ $orderInfo->payment_reference ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Products Section -->
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4" style="font-weight: 700; color: #212529;">
                <i class="fas fa-shopping-bag me-2"></i>Order Items
            </h2>
        </div>
    </div>
    
    @php
        $subtotal = 0;
    @endphp
    
    @foreach ($order as $item)
        @php
            $itemTotal = $item->ordercount * $item->productprice;
            $subtotal += $itemTotal;
        @endphp
        
        <div class="product-card d-flex align-items-center">
            <div class="product-image-wrapper">
                <img src="{{ asset('productImages/' . $item->productimage) }}" alt="{{ $item->productname }}">
            </div>
            
            <div class="product-info">
                <div class="product-name">{{ $item->productname }}</div>
                
                <div class="product-meta">
                    <div class="meta-item">
                        <span class="meta-label">Unit Price</span>
                        <span class="meta-value">GH₵ {{ number_format($item->productprice, 2) }}</span>
                    </div>
                    
                    <div class="meta-item">
                        <span class="meta-label">Quantity</span>
                        <span class="meta-value">{{ $item->ordercount }} {{ $item->ordercount > 1 ? 'items' : 'item' }}</span>
                    </div>
                    
                    <div class="meta-item">
                        <span class="meta-label">Item Total</span>
                        <span class="meta-value price-highlight">GH₵ {{ number_format($itemTotal, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    
    <!-- Order Summary -->
    <div class="order-summary">
        <div class="summary-title">
            <i class="fas fa-calculator"></i>
            Order Summary
        </div>
        
        <div class="summary-row">
            <span class="summary-label">Subtotal</span>
            <span class="summary-value">GH₵ {{ number_format($subtotal, 2) }}</span>
        </div>
        
        <div class="summary-row">
            <span class="summary-label">Tax & Fees</span>
            <span class="summary-value">GH₵ 0.00</span>
        </div>
        
        <div class="summary-row">
            <span class="summary-label" style="font-size: 20px; font-weight: 700;">Grand Total</span>
            <span class="summary-value grand-total">GH₵ {{ number_format($subtotal, 2) }}</span>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($order->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $order->links() }}
        </div>
    @endif
</div>

@endsection
