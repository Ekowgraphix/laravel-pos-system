@extends('user.layouts.master')
@section('content')

<style>
    .orders-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 90px 0 70px;
        position: relative;
        overflow: hidden;
        margin-bottom: 50px;
    }
    
    .orders-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .orders-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .orders-hero h1 {
        font-size: 48px;
        font-weight: 800;
        color: white;
        margin-bottom: 18px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        letter-spacing: -0.5px;
    }
    
    .orders-hero p {
        font-size: 19px;
        color: rgba(255, 255, 255, 0.95);
        letter-spacing: 0.3px;
        line-height: 1.6;
    }
    
    .order-card {
        background: white;
        border-radius: 20px;
        padding: 32px;
        margin-bottom: 24px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 2px solid #f0f0f0;
    }
    
    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0,0,0,0.15);
        border-color: #667eea;
    }
    
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 24px;
        border-bottom: 2px solid #e9ecef;
        min-height: 48px;
    }
    
    .order-code {
        font-size: 21px;
        font-weight: 800;
        color: #667eea;
        display: flex;
        align-items: center;
        gap: 12px;
        letter-spacing: 0.3px;
    }
    
    .order-date {
        font-size: 15px;
        color: #495057;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        letter-spacing: 0.2px;
    }
    
    .order-body {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 24px;
        min-height: 64px;
    }
    
    .order-info {
        flex: 1;
        min-width: 200px;
    }
    
    .order-actions {
        display: flex;
        gap: 16px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .status-badge {
        padding: 12px 24px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        letter-spacing: 0.3px;
        min-height: 42px;
    }
    
    .status-pending {
        background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
        color: #664d03;
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
        border: 1px solid #ffeeba;
    }
    
    .status-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #0f5132;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        border: 1px solid #badbcc;
    }
    
    .status-rejected {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #58151c;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        border: 1px solid #f1aeb5;
    }
    
    .payment-badge {
        padding: 10px 20px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        letter-spacing: 0.4px;
        min-height: 38px;
    }
    
    .payment-completed {
        background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
    }
    
    .payment-failed {
        background: linear-gradient(135deg, #f44336 0%, #da190b 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(244, 67, 54, 0.4);
    }
    
    .payment-unpaid {
        background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(255, 152, 0, 0.4);
    }
    
    .btn-pay {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 14px 32px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        letter-spacing: 0.3px;
        min-height: 48px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    
    .btn-pay:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
        color: white;
    }
    
    .btn-view {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        border: none;
        padding: 14px 32px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(79, 172, 254, 0.4);
        letter-spacing: 0.3px;
        min-height: 48px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    
    .btn-view:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(79, 172, 254, 0.6);
        color: white;
    }
    
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }
    
    .empty-state i {
        font-size: 80px;
        color: #667eea;
        margin-bottom: 25px;
        opacity: 0.5;
    }
    
    .empty-state h3 {
        color: #212529;
        font-weight: 700;
        margin-bottom: 15px;
    }
    
    .empty-state p {
        color: #6c757d;
        margin-bottom: 30px;
    }
    
    @media (max-width: 768px) {
        .orders-hero h1 {
            font-size: 32px;
        }
        
        .order-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .order-body {
            flex-direction: column;
            align-items: stretch;
        }
        
        .order-actions {
            flex-direction: column;
            width: 100%;
        }
        
        .order-actions .btn {
            width: 100%;
        }
    }
</style>

<!-- Hero Section -->
<div class="orders-hero">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center position-relative">
                <h1><i class="fas fa-clipboard-list me-3"></i>My Orders</h1>
                <p>Track and manage all your orders in one place</p>
            </div>
        </div>
    </div>
</div>

<!-- Orders Content -->
<div class="container my-5">
    @if($order->count() > 0)
        @foreach ($order as $item)
        <div class="order-card">
            <div class="order-header">
                <div class="order-code">
                    <i class="fas fa-receipt"></i>
                    {{ $item->order_code }}
                </div>
                <div class="order-date">
                    <i class="far fa-calendar-alt"></i>
                    {{ $item->created_at->format('F j, Y') }}
                </div>
            </div>
            
            <div class="order-body">
                <div class="order-info">
                    <div class="mb-3">
                        <span class="status-badge 
                            @if ($item->status == 0) status-pending
                            @elseif ($item->status == 1) status-success
                            @elseif ($item->status == 2) status-rejected
                            @endif">
                            <i class="fas 
                                @if ($item->status == 0) fa-clock
                                @elseif ($item->status == 1) fa-check-circle
                                @elseif ($item->status == 2) fa-times-circle
                                @endif"></i>
                            @if ($item->status == 0) Pending Order
                            @elseif ($item->status == 1) Completed
                            @elseif ($item->status == 2) Rejected
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="payment-badge 
                            @if ($item->payment_status === 'completed') payment-completed
                            @elseif ($item->payment_status === 'failed') payment-failed
                            @else payment-unpaid
                            @endif">
                            <i class="fas 
                                @if ($item->payment_status === 'completed') fa-check
                                @elseif ($item->payment_status === 'failed') fa-exclamation-circle
                                @else fa-wallet
                                @endif"></i>
                            @if ($item->payment_status === 'completed') Payment Complete
                            @elseif ($item->payment_status === 'failed') Payment Failed
                            @else Awaiting Payment
                            @endif
                        </span>
                    </div>
                </div>
                
                <div class="order-actions">
                    @if ($item->payment_status !== 'completed' && $item->status == 0)
                        <a href="{{ route('payment.checkout', $item->id) }}" class="btn btn-pay">
                            <i class="fas fa-credit-card me-2"></i>Pay Now
                        </a>
                    @endif
                    <a href="{{route ('customerOrders', $item->order_code)}}" class="btn btn-view">
                        <i class="fas fa-eye me-2"></i>View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="empty-state">
            <i class="fas fa-shopping-cart"></i>
            <h3>No Orders Yet</h3>
            <p>Start shopping and your orders will appear here</p>
            <a href="{{route('userDashboard')}}" class="btn btn-pay">
                <i class="fas fa-shopping-bag me-2"></i>Start Shopping
            </a>
        </div>
    @endif
</div>

@endsection



