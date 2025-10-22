@extends('user.layouts.master')
@section('content')

<style>
    .cart-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 60px 0 40px;
        margin-bottom: 50px;
    }
    
    .cart-hero h1 {
        font-size: 42px;
        font-weight: 800;
        color: white;
        margin-bottom: 10px;
    }
    
    .cart-item-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .cart-item-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.12);
        border-color: #667eea;
    }
    
    .product-image-cart {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .product-name-cart {
        font-size: 18px;
        font-weight: 700;
        color: #212529;
        margin-bottom: 8px;
    }
    
    .product-price-cart {
        font-size: 20px;
        font-weight: 800;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .qty-control {
        display: flex;
        align-items: center;
        gap: 15px;
        background: #f8f9fa;
        padding: 8px 15px;
        border-radius: 50px;
        width: fit-content;
    }
    
    .qty-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        background: white;
        color: #667eea;
        font-size: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .qty-btn:hover {
        background: #667eea;
        color: white;
        transform: scale(1.1);
    }
    
    .qty-input {
        width: 50px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 700;
        font-size: 16px;
        color: #212529;
    }
    
    .remove-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: #ffe5e5;
        color: #dc3545;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .remove-btn:hover {
        background: #dc3545;
        color: white;
        transform: scale(1.1) rotate(90deg);
    }
    
    .cart-summary {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        position: sticky;
        top: 150px;
    }
    
    .summary-title {
        font-size: 24px;
        font-weight: 800;
        color: #212529;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 3px solid #667eea;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .summary-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 15px;
    }
    
    .summary-value {
        font-weight: 700;
        color: #212529;
        font-size: 15px;
    }
    
    .summary-total {
        padding: 20px 0;
        margin-top: 15px;
        border-top: 2px solid #667eea;
    }
    
    .total-label {
        font-size: 18px;
        font-weight: 800;
        color: #212529;
    }
    
    .total-value {
        font-size: 24px;
        font-weight: 900;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .checkout-btn {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 50px;
        font-weight: 700;
        font-size: 16px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        margin-top: 20px;
    }
    
    .checkout-btn:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
    }
    
    .checkout-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .empty-cart {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }
    
    .empty-cart i {
        font-size: 100px;
        color: #667eea;
        margin-bottom: 25px;
        opacity: 0.5;
    }
    
    .empty-cart h3 {
        font-size: 28px;
        font-weight: 800;
        color: #212529;
        margin-bottom: 15px;
    }
    
    .continue-shopping-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 32px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-top: 25px;
    }
    
    .continue-shopping-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        color: white;
    }
    
    @media (max-width: 768px) {
        .cart-item-card {
            padding: 20px 15px;
        }
        
        .product-image-cart {
            width: 80px;
            height: 80px;
        }
        
        .cart-summary {
            position: static;
            margin-top: 30px;
        }
    }
</style>

<!-- Hero Section -->
<div class="cart-hero">
    <div class="container">
        <div class="text-center">
            <h1><i class="fas fa-shopping-cart me-3"></i>Shopping Cart</h1>
            <p class="text-white-50" style="font-size: 16px;">Review your items before checkout</p>
        </div>
    </div>
</div>

<!-- Cart Content -->
<div class="container mb-5">
    <div class="row g-4">
        <!-- Cart Items -->
        <div class="col-lg-8">
            @if(count($cart) > 0)
                <input type="hidden" class="userId" value="{{ auth()->user()->id }}">
                
                @foreach ($cart as $item)
                <div class="cart-item-card">
                    <input type="hidden" class="productId" value="{{ $item->product_id }}">
                    <input type="hidden" id="cartId" value="{{ $item->id }}">
                    
                    <div class="row align-items-center">
                        <!-- Product Image -->
                        <div class="col-auto">
                            <img src="{{asset('productImages/'.$item->image)}}" 
                                 class="product-image-cart" 
                                 alt="{{ $item->name }}">
                        </div>
                        
                        <!-- Product Details -->
                        <div class="col">
                            <h5 class="product-name-cart">{{ $item->name }}</h5>
                            <div class="product-price-cart" id="price">{{ $item->price }} GHS</div>
                        </div>
                        
                        <!-- Quantity Control -->
                        <div class="col-auto">
                            <div class="qty-control">
                                <button class="qty-btn btn-minus">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="text" class="qty-input" id="qty" value="{{ $item->qty }}" readonly>
                                <button class="qty-btn btn-plus">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Item Total -->
                        <div class="col-auto text-end">
                            <div class="product-price-cart" id="eachTotal">{{ $item->price * $item->qty }} GHS</div>
                        </div>
                        
                        <!-- Remove Button -->
                        <div class="col-auto">
                            <button class="remove-btn btn-remove">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Empty Cart State -->
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Your Cart is Empty</h3>
                    <p class="text-muted">Looks like you haven't added anything to your cart yet.</p>
                    <a href="{{ route('shopList') }}" class="continue-shopping-btn">
                        <i class="fas fa-store"></i>
                        <span>Start Shopping</span>
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Cart Summary -->
        <div class="col-lg-4">
            <div class="cart-summary">
                <h4 class="summary-title">
                    <i class="fas fa-receipt me-2"></i>Order Summary
                </h4>
                
                <div class="summary-row">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value" id="subTotal">{{ $totalPrice }} GHS</span>
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Delivery Fee</span>
                    <span class="summary-value">500 GHS</span>
                </div>
                
                <div class="summary-row summary-total">
                    <span class="total-label">Total</span>
                    <span class="total-value" id="finalFee">{{ $totalPrice + 500 }} GHS</span>
                </div>
                
                <button id="proceedCheckOut" 
                        @if (count($cart) == 0) disabled @endif
                        class="checkout-btn">
                    <i class="fas fa-lock me-2"></i>
                    Proceed to Checkout
                </button>
                
                <div class="text-center mt-3">
                    <a href="{{ route('shopList') }}" class="text-decoration-none" style="color: #667eea; font-weight: 600;">
                        <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js-section')
<script>
    $(document).ready(function(){
        // Plus button click
        $('.btn-plus').click(function(){
            $parentNode = $(this).parents(".cart-item-card");
            $price = $parentNode.find("#price").text().replace("GHS","").trim();
            $qty = $parentNode.find('#qty').val();
            $totalPrice = $qty * $price;
            $parentNode.find('#eachTotal').html($totalPrice + ' GHS');
            finalCalculation();
        });
        
        // Minus button click
        $('.btn-minus').click(function(){
            $parentNode = $(this).parents(".cart-item-card");
            $price = $parentNode.find("#price").text().replace("GHS","").trim();
            $qty = $parentNode.find('#qty').val();
            $totalPrice = $qty * $price;
            $parentNode.find('#eachTotal').html($totalPrice + ' GHS');
            finalCalculation();
        });

        // Remove button click
        $(".btn-remove").click(function(){
            if(confirm('Remove this item from cart?')) {
                $parentNode = $(this).parents(".cart-item-card");
                $cartId = $parentNode.find("#cartId").val();
                
                $.ajax({
                    type: 'get',
                    url: 'remove/cart',
                    data: {'cartId': $cartId},
                    dataType: 'json',
                    success: function(response){
                        if(response.message == 'success'){
                            if(typeof showNotification === 'function') {
                                showNotification('Item removed from cart', 'success');
                            }
                            setTimeout(() => location.reload(), 500);
                        }
                    }
                });
            }
        });

        // Proceed to checkout
        $('#proceedCheckOut').click(function(){
            $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');
            
            $orderList = [];
            $orderCode = Math.floor(Math.random() * 10000000);
            $userId = $(".userId").val() * 1;

            $(".cart-item-card").each(function(item, row){
                $productId = $(row).find('.productId').val();
                $qty = $(row).find('#qty').val() * 1;
                $totalPrice = $(row).find('#eachTotal').text().replace("GHS", "").trim() * 1;

                $orderList.push({
                    'user_id': $userId,
                    'product_id': $productId,
                    'order_code': 'POS' + $orderCode,
                    'total_price': $totalPrice,
                    'qty': $qty
                });
            });
            
            $.ajax({
                type: 'get',
                url: 'order',
                data: Object.assign({}, $orderList),
                dataType: 'json',
                success: function(response){
                    if(response.message == 'success'){
                        location.href = "userPayment";
                    }
                }
            });
        });
        
        function finalCalculation(){
            $totalPrice = 0;
            $(".cart-item-card").each(function(item, row){
                $totalPrice += Number($(row).find("#eachTotal").text().replace("GHS","").trim());
            });
            
            $("#subTotal").html(`${$totalPrice} GHS`);
            $("#finalFee").html(`${$totalPrice + 500} GHS`);
        }
    });
</script>
@endsection
