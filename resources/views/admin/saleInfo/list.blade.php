@extends('admin.layouts.master')
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header-modern">
            <h1 class="page-title-modern">
                <i class="fas fa-chart-line"></i>
                Sales Information
            </h1>
            <p class="page-subtitle-modern">
                <i class="fas fa-receipt"></i> Track all sales transactions and order details
            </p>
        </div>

        <!-- Sale Info Card -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="fas fa-list-ul"></i>
                    All Sales
                </h5>
            </div>
            <div class="card-body-modern">
                <div class="table-modern-wrapper">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th style="width: 10%; text-align: center;">Product</th>
                                <th style="width: 20%;">Product Name</th>
                                <th style="width: 15%;">Customer</th>
                                <th style="width: 12%;">Date</th>
                                <th style="width: 10%; text-align: center;">Quantity</th>
                                <th style="width: 13%;">Amount</th>
                                <th style="width: 20%;">Order Code</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($order as $item)
                                <tr>
                                    <td style="text-align: center;">
                                        <div style="display: inline-block;">
                                            <img class="image-preview-modern" 
                                                 style="width: 50px; height: 50px; max-width: 50px;" 
                                                 src="{{ asset('productImages/'. $item->productimage)}}" 
                                                 alt="{{ $item->productname }}">
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $item->productname }}</strong>
                                    </td>
                                    <td>
                                        <i class="fas fa-user me-2"></i>
                                        {{ $item->username }}
                                    </td>
                                    <td>
                                        <i class="far fa-calendar-alt me-2"></i>
                                        {{ $item->created_at->format('M j, Y') }}
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="badge-info-modern badge-modern">
                                            <i class="fas fa-box"></i>
                                            {{ $item->ordercount }} units
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge-success-modern badge-modern">
                                            <i class="fas fa-money-bill-wave"></i>
                                            {{ number_format($item->price) }} GHS
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{route ('userOrderDetails', $item->order_code)}}" 
                                           style="color: #667eea; font-weight: 700; text-decoration: none;">
                                            <i class="fas fa-hashtag"></i>{{ $item->order_code }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 40px;">
                                        <i class="fas fa-inbox" style="font-size: 48px; color: #cbd5e0; margin-bottom: 15px; display: block;"></i>
                                        <p style="color: #9ca3af; font-weight: 600;">No sales records found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
