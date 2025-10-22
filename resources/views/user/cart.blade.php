@extends('user.layouts.master')
@section('content')
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Shopping Cart</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{route('userDashboard')}}">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Shop</a></li>
        <li class="breadcrumb-item active text-white">Cart</li>
    </ol>
</div>
<!-- Single Page Header End -->

<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-left: 4px solid #11998e;">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Cart Items -->
    @if(count($cart) > 0)
        <input type="hidden" value="{{ auth()->user()->id }}" class="userId">
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4" style="border-radius: 15px; border: none;">
                    <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px 15px 0 0; padding: 20px;">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Cart Items ({{ count($cart) }} {{ count($cart) > 1 ? 'items' : 'item' }})
                        </h5>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        <div class="table-responsive">
                            <table class="table mb-0" id="dataTable" style="border-collapse: separate; border-spacing: 0;">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th style="padding: 15px; border: none;">Product</th>
                                        <th style="padding: 15px; border: none;">Price</th>
                                        <th style="padding: 15px; border: none;">Quantity</th>
                                        <th style="padding: 15px; border: none;">Total</th>
                                        <th style="padding: 15px; border: none; text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart as $item)
                                    <tr style="border-bottom: 1px solid #f0f0f0;">
                                        <input type="hidden" value="{{ $item->product_id }}" class="productId">
                                        
                                        <td style="padding: 20px;">
                                            <div class="d-flex align-items-center">
                                                <img src="{{asset('productImages/'.$item->image)}}" 
                                                     style="width: 70px; height: 70px; object-fit: cover; border-radius: 10px; border: 2px solid #e0e0e0;" 
                                                     alt="{{ $item->name }}">
                                                <div class="ms-3">
                                                    <h6 class="mb-1" style="color: #2d3748; font-weight: 600;">{{ $item->name }}</h6>
                                                    <small class="text-muted">SKU: #{{ $item->product_id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td style="padding: 20px; vertical-align: middle;">
                                            <span style="color: #11998e; font-weight: 700; font-size: 16px;" id="price">{{ number_format($item->price) }}</span>
                                            <small class="d-block text-muted">GHS</small>
                                        </td>
                                        
                                        <td style="padding: 20px; vertical-align: middle;">
                                            <div class="input-group" style="width: 130px;">
                                                <button class="btn btn-minus" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border: none; color: white; padding: 8px 12px; border-radius: 8px 0 0 8px; font-weight: 600;">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                                <input type="text" 
                                                       class="form-control text-center" 
                                                       id="qty" 
                                                       value="{{ $item->qty }}" 
                                                       style="border: 2px solid #e0e0e0; font-weight: 700; color: #2d3748;" 
                                                       readonly>
                                                <button class="btn btn-plus" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none; color: white; padding: 8px 12px; border-radius: 0 8px 8px 0; font-weight: 600;">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        
                                        <td style="padding: 20px; vertical-align: middle;">
                                            <span style="color: #667eea; font-weight: 700; font-size: 18px;" id="eachTotal">{{ number_format($item->price * $item->qty) }}</span>
                                            <small class="d-block text-muted">GHS</small>
                                        </td>
                                        
                                        <td style="padding: 20px; vertical-align: middle; text-align: center;">
                                            <input type="hidden" id="cartId" value="{{ $item->id}}">
                                            <button class="btn btn-remove" 
                                                    style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border: none; color: white; width: 40px; height: 40px; border-radius: 10px; box-shadow: 0 4px 10px rgba(250, 112, 154, 0.3);" 
                                                    title="Remove item">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card shadow-sm" style="border-radius: 15px; border: none; position: sticky; top: 100px;">
                    <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px 15px 0 0; padding: 20px;">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-receipt me-2"></i>
                            Order Summary
                        </h5>
                    </div>
                    <div class="card-body" style="padding: 25px;">
                        <div class="d-flex justify-content-between mb-3">
                            <span style="color: #6b7280; font-weight: 600;">Subtotal:</span>
                            <span style="color: #2d3748; font-weight: 700; font-size: 16px;" id="subTotal">{{ number_format($totalPrice) }} GHS</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span style="color: #6b7280; font-weight: 600;">
                                <i class="fas fa-truck me-2" style="color: #667eea;"></i>Delivery Fee:
                            </span>
                            <span style="color: #2d3748; font-weight: 700; font-size: 16px;">500 GHS</span>
                        </div>
                        
                        <hr style="border-top: 2px dashed #e0e0e0; margin: 20px 0;">
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span style="color: #2d3748; font-weight: 700; font-size: 18px;">Total:</span>
                            <span style="color: #667eea; font-weight: 800; font-size: 24px;" id="finalFee">{{ number_format($totalPrice + 500) }} GHS</span>
                        </div>
                        
                        <button id="proceedCheckOut" 
                                class="w-100" 
                                style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none; color: white; padding: 15px; border-radius: 10px; font-weight: 700; font-size: 16px; text-transform: uppercase; box-shadow: 0 6px 20px rgba(17, 153, 142, 0.3); transition: all 0.3s ease;"
                                type="button">
                            <i class="fas fa-lock me-2"></i>Proceed to Checkout
                        </button>
                        
                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Secure payment processing
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Features Card -->
                <div class="card shadow-sm mt-3" style="border-radius: 15px; border: none;">
                    <div class="card-body" style="padding: 20px;">
                        <div class="mb-3">
                            <i class="fas fa-shipping-fast me-2" style="color: #667eea;"></i>
                            <small style="color: #6b7280; font-weight: 600;">Free delivery on orders over 10,000 GHS</small>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-undo-alt me-2" style="color: #11998e;"></i>
                            <small style="color: #6b7280; font-weight: 600;">Easy returns within 7 days</small>
                        </div>
                        <div>
                            <i class="fas fa-headset me-2" style="color: #fa709a;"></i>
                            <small style="color: #6b7280; font-weight: 600;">24/7 customer support</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart State -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-shopping-cart" style="font-size: 100px; color: #e0e0e0;"></i>
            </div>
            <h3 style="color: #6b7280; font-weight: 700;">Your Cart is Empty</h3>
            <p class="text-muted mb-4">Looks like you haven't added anything to your cart yet</p>
            <a href="{{ route('userDashboard') }}" 
               class="btn" 
               style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: white; padding: 12px 30px; border-radius: 10px; font-weight: 600; box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);">
                <i class="fas fa-store me-2"></i>Continue Shopping
            </a>
        </div>
    @endif
</div>
<!-- Cart Page End -->
@endsection

@section('js-section')
    <script>
        $(document).ready(function(){
            // Add hover effect to checkout button
            $('#proceedCheckOut').hover(
                function() { $(this).css('transform', 'translateY(-2px)'); },
                function() { $(this).css('transform', 'translateY(0)'); }
            );
            
            //when plus button click
            $('.btn-plus').click(function(){
                $parentNode = $(this).parents("tr");
                $price = $parentNode.find("#price").text().replace(/,/g, '');
                $qty = $parentNode.find('#qty').val();
                $totalPrice = $qty * $price;
                $parentNode.find('#eachTotal').html(Number($totalPrice).toLocaleString());
                finalCalculation();
            });
            
            //when minus button click
            $('.btn-minus').click(function(){
                $parentNode = $(this).parents("tr");
                $price = $parentNode.find("#price").text().replace(/,/g, '');
                $qty = $parentNode.find('#qty').val();
                $totalPrice = $qty * $price;
                $parentNode.find('#eachTotal').html(Number($totalPrice).toLocaleString());
                finalCalculation();
            });

            //when btn remove click
            $(".btn-remove").click(function(){
                if(!confirm('Remove this item from cart?')) return;
                
                $parentNode = $(this).parents("tr");
                $cartId = $parentNode.find("#cartId").val();
                $deleteData = { 'cartId' : $cartId };

                $.ajax({
                    type : 'get',
                    url  : 'remove/cart',
                    data : $deleteData,
                    dataType : 'json',
                    success : function(response){
                        if(response.message == 'success'){
                            location.reload();
                        }
                    }
                });
            });

            //order process
            $('#proceedCheckOut').click(function(){
                $orderList = [];
                $orderCode = Math.floor(Math.random() * 10000000);
                $userId = $(".userId").val() * 1;

                $(" #dataTable tbody tr ").each(function( item, row){
                    $productId = $(row).find('.productId').val();
                    $qty = $(row).find('#qty').val() * 1;
                    $totalPrice = $(row).find('#eachTotal').text().replace(/,/g, '') * 1;

                    $orderList.push({
                        'user_id' : $userId,
                        'product_id' : $productId,
                        'order_code' : 'POS' + $orderCode,
                        'total_price' : $totalPrice,
                        'qty' : $qty
                    });
                });
                
                $.ajax({
                    type: 'get',
                    url : 'order',
                    data : Object.assign({}, $orderList),
                    dataType : 'json',
                    success : function(response){
                        if(response.message == 'success'){
                            location.href = "userPayment";
                        }
                    }
                });
            });
            
            function finalCalculation(){
                $totalPrice = 0;
                $(" #dataTable tbody tr ").each(function( item, row){
                    $totalPrice += Number($(row).find("#eachTotal").text().replace(/,/g, ''));
                });

                $("#subTotal").html(Number($totalPrice).toLocaleString() + ' GHS');
                $("#finalFee").html(Number($totalPrice + 500).toLocaleString() + ' GHS');
            }
        });
    </script>
@endsection
