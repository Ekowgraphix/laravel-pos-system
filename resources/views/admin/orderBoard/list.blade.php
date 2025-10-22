@extends('admin.layouts.master')
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header-modern">
            <h1 class="page-title-modern">
                <i class="fas fa-shopping-cart"></i>
                Order Management
            </h1>
            <p class="page-subtitle-modern">
                <i class="fas fa-clipboard-list"></i> Track and manage customer orders
            </p>
        </div>

        <!-- Order List Card -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="fas fa-list-alt"></i>
                    All Orders
                </h5>
            </div>
            <div class="card-body-modern">
                <div class="table-modern-wrapper">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th style="width: 15%;">Date</th>
                                <th style="width: 20%;">Order Code</th>
                                <th style="width: 30%;">Customer Name</th>
                                <th style="width: 20%; text-align: center;">Order Status</th>
                                <th style="width: 15%; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($order as $item)
                                <tr>
                                    <input type="hidden" class="orderCode" value="{{ $item->order_code }}">
                                    <td>
                                        <i class="far fa-calendar-alt me-2"></i>
                                        {{ $item->created_at->format('F j, Y') }}
                                    </td>
                                    <td>
                                        <a href="{{route ('userOrderDetails', $item->order_code)}}" 
                                           style="color: #667eea; font-weight: 700; text-decoration: none;">
                                            <i class="fas fa-hashtag"></i>{{ $item->order_code }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{route ('accountProfile', $item->user_id)}}" 
                                           style="color: #2d3748; font-weight: 600; text-decoration: none;">
                                            <i class="fas fa-user me-2"></i>{{ $item->username }}
                                        </a>
                                    </td>
                                    <td style="text-align: center;">
                                        @if ($item->status == 0)
                                            <span class="badge-warning-modern badge-modern">
                                                <i class="fas fa-clock"></i>
                                                Pending
                                            </span>
                                        @elseif ($item->status == 1)
                                            <span class="badge-success-modern badge-modern">
                                                <i class="fas fa-check-circle"></i>
                                                Accepted
                                            </span>
                                        @elseif ($item->status == 2)
                                            <span class="badge-danger-modern badge-modern">
                                                <i class="fas fa-times-circle"></i>
                                                Refund & Reject
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons-modern" style="justify-content: center;">
                                            <a href="{{route ('userOrderDetails', $item->order_code)}}" 
                                               class="action-btn-modern action-btn-view"
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 40px;">
                                        <i class="fas fa-inbox" style="font-size: 48px; color: #cbd5e0; margin-bottom: 15px; display: block;"></i>
                                        <p style="color: #9ca3af; font-weight: 600;">No orders found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($order->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $order->links()}}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('js-section')
    <script>
        $(document).ready(function(){
            $('.statusChange').change(function(){
                $currentStatus = $(this).val();
                $orderCode = $(this).parents("tr").find('.orderCode').val();

                $data ={
                    'status' : $currentStatus,
                    'orderCode' : $orderCode
                }
                // console.log($data);
                $.ajax({
                    type : 'get',
                    url : 'change/status',
                    data  : $data,
                    dataType   : 'json'
                })

            })
        })
    </script>
@endsection
