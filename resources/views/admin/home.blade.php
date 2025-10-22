@extends('admin.layouts.master')

@section('content')
<style>
    /* World-Class Professional Dashboard Styles */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        --dark-gradient: linear-gradient(135deg, #2d3561 0%, #c05c7e 100%);
    }
    
    .dashboard-modern {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 30px 20px;
    }
    
    .dashboard-header {
        margin-bottom: 40px;
        animation: slideDown 0.6s ease-out;
    }
    
    .dashboard-title {
        font-size: 36px;
        font-weight: 900;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 10px;
        text-shadow: 0 0 30px rgba(102, 126, 234, 0.3);
    }
    
    .dashboard-subtitle {
        color: #6c757d;
        font-size: 16px;
        font-weight: 500;
    }
    
    /* Modern Stat Cards */
    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px) saturate(180%);
        border-radius: 24px;
        padding: 30px;
        margin-bottom: 30px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 
            0 8px 32px 0 rgba(31, 38, 135, 0.15),
            0 0 60px rgba(102, 126, 234, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        cursor: pointer;
        animation: fadeInUp 0.6s ease-out;
        animation-fill-mode: both;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .stat-card:hover::before {
        opacity: 1;
    }
    
    .stat-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 
            0 20px 60px 0 rgba(31, 38, 135, 0.3),
            0 0 80px rgba(102, 126, 234, 0.3);
    }
    
    .stat-card.primary::before { background: var(--primary-gradient); }
    .stat-card.success::before { background: var(--success-gradient); }
    .stat-card.warning::before { background: var(--warning-gradient); }
    .stat-card.info::before { background: var(--info-gradient); }
    .stat-card.danger::before { background: var(--danger-gradient); }
    .stat-card.dark::before { background: var(--dark-gradient); }
    
    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: white;
        margin-bottom: 20px;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-icon::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transform: rotate(45deg);
        transition: all 0.6s ease;
    }
    
    .stat-card:hover .stat-icon::before {
        left: 100%;
    }
    
    .stat-card:hover .stat-icon {
        transform: rotate(360deg) scale(1.1);
    }
    
    .stat-icon.primary { background: var(--primary-gradient); }
    .stat-icon.success { background: var(--success-gradient); }
    .stat-icon.warning { background: var(--warning-gradient); }
    .stat-icon.info { background: var(--info-gradient); }
    .stat-icon.danger { background: var(--danger-gradient); }
    .stat-icon.dark { background: var(--dark-gradient); }
    
    .stat-label {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #6c757d;
        margin-bottom: 12px;
    }
    
    .stat-value {
        font-size: 36px;
        font-weight: 900;
        color: #2d3748;
        margin-bottom: 10px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .stat-trend {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 20px;
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    
    .stat-trend.down {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    
    .stat-trend i {
        font-size: 11px;
    }
    
    /* Chart Cards */
    .chart-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px) saturate(180%);
        border-radius: 24px;
        padding: 30px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 
            0 8px 32px 0 rgba(31, 38, 135, 0.15),
            0 0 60px rgba(102, 126, 234, 0.1);
        margin-bottom: 30px;
        animation: fadeInUp 0.8s ease-out;
        animation-fill-mode: both;
    }
    
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f3f4f6;
    }
    
    .chart-title {
        font-size: 22px;
        font-weight: 800;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .chart-title i {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: var(--primary-gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    
    .chart-actions {
        display: flex;
        gap: 10px;
    }
    
    .chart-btn {
        padding: 8px 16px;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        background: white;
        color: #6b7280;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .chart-btn:hover {
        border-color: #667eea;
        color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }
    
    .chart-btn.active {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
    }
    
    /* Quick Actions */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .action-btn {
        background: white;
        padding: 20px;
        border-radius: 16px;
        border: 2px solid #e5e7eb;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        color: #2d3748;
    }
    
    .action-btn:hover {
        transform: translateY(-5px);
        border-color: #667eea;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
        color: #667eea;
    }
    
    .action-btn i {
        font-size: 28px;
        margin-bottom: 12px;
        display: block;
    }
    
    .action-btn span {
        font-size: 14px;
        font-weight: 600;
    }
    
    /* Out of Stock Modal */
    .modern-modal .modal-content {
        border-radius: 24px;
        border: none;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }
    
    .modern-modal .modal-header {
        background: var(--danger-gradient);
        color: white;
        border: none;
        padding: 25px 30px;
    }
    
    .modern-modal .modal-title {
        font-weight: 800;
        font-size: 22px;
    }
    
    .modern-modal .close {
        color: white;
        opacity: 1;
        text-shadow: none;
        font-size: 28px;
    }
    
    .modern-modal .modal-body {
        padding: 30px;
    }
    
    .stock-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 20px;
        background: #f9fafb;
        border-radius: 12px;
        margin-bottom: 12px;
        border-left: 4px solid #f59e0b;
        transition: all 0.3s ease;
    }
    
    .stock-item:hover {
        background: #f3f4f6;
        transform: translateX(5px);
    }
    
    .stock-item-name {
        font-weight: 600;
        color: #2d3748;
        font-size: 15px;
    }
    
    .stock-badge {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 13px;
        box-shadow: 0 4px 12px rgba(245, 87, 108, 0.3);
    }
    
    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.4);
        }
        50% {
            box-shadow: 0 0 40px rgba(102, 126, 234, 0.6);
        }
    }
    
    /* Staggered Animation Delays */
    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; }
    .stat-card:nth-child(5) { animation-delay: 0.5s; }
    .stat-card:nth-child(6) { animation-delay: 0.6s; }
    .stat-card:nth-child(7) { animation-delay: 0.7s; }
    .stat-card:nth-child(8) { animation-delay: 0.8s; }
    
    .chart-card:nth-child(1) { animation-delay: 0.9s; }
    .chart-card:nth-child(2) { animation-delay: 1s; }
    
    /* Responsive */
    @media (max-width: 768px) {
        .dashboard-title {
            font-size: 28px;
        }
        
        .stat-card {
            padding: 20px;
        }
        
        .stat-value {
            font-size: 28px;
        }
        
        .chart-card {
            padding: 20px;
        }
    }
</style>

<div class="dashboard-modern">
    <div class="container-fluid">
        
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <i class="fas fa-chart-line"></i> Dashboard Overview
            </h1>
            <p class="dashboard-subtitle">
                <i class="far fa-calendar-alt"></i> {{ date('l, F j, Y') }}
            </p>
        </div>

        <!-- Stats Cards Row 1 -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card warning" data-toggle="modal" data-target="#outOfStockModal">
                    <div class="stat-icon warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-label">Out of Stock</div>
                    <div class="stat-value" id="outofstock-count" data-count="{{ $outofstock->count() }}">0</div>
                    @if($outofstock->isNotEmpty())
                        <span class="stat-trend down">
                            <i class="fas fa-arrow-down"></i> Action Required
                        </span>
                    @else
                        <span class="stat-trend">
                            <i class="fas fa-check"></i> All Good
                        </span>
                    @endif
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <a href="{{ route('categoryList') }}" class="text-decoration-none">
                    <div class="stat-card dark">
                        <div class="stat-icon dark">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div class="stat-label">Categories</div>
                        <div class="stat-value" id="category-count" data-count="{{ $categoryCount }}">0</div>
                        <span class="stat-trend">
                            <i class="fas fa-arrow-right"></i> View All
                        </span>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card success">
                    <div class="stat-icon success">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="stat-label">Payment Methods</div>
                    <div class="stat-value" id="payment-count" data-count="{{ $paymentType }}">0</div>
                    <span class="stat-trend">
                        <i class="fas fa-check-circle"></i> Active
                    </span>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <a href="{{ route('adminList') }}" class="text-decoration-none">
                    <div class="stat-card primary">
                        <div class="stat-icon primary">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="stat-label">Admin Accounts</div>
                        <div class="stat-value" id="admin-count" data-count="{{ $adminCount }}">0</div>
                        <span class="stat-trend">
                            <i class="fas fa-users-cog"></i> Manage
                        </span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Stats Cards Row 2 -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <a href="{{ route('orderListPage') }}" class="text-decoration-none">
                    <div class="stat-card warning">
                        <div class="stat-icon warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-label">Pending Orders</div>
                        <div class="stat-value" id="orderpending-count" data-count="{{ $orderPending }}">0</div>
                        <span class="stat-trend down">
                            <i class="fas fa-hourglass-half"></i> In Queue
                        </span>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card info">
                    <div class="stat-icon info">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-label">Monthly Sales</div>
                    <div class="stat-value" id="total_sale-count" data-count="{{ $total_sale_amt }}">0</div>
                    <span class="stat-trend">
                        <i class="fas fa-arrow-up"></i> This Month
                    </span>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card success">
                    <div class="stat-icon success">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div class="stat-label">Completed Today</div>
                    <div class="stat-value" id="ordersuccess-count" data-count="{{ $orderSuccess }}">0</div>
                    <span class="stat-trend">
                        <i class="fas fa-calendar-day"></i> Today
                    </span>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <a href="{{ route('userList') }}" class="text-decoration-none">
                    <div class="stat-card primary">
                        <div class="stat-icon primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-label">Total Customers</div>
                        <div class="stat-value" id="user-count" data-count="{{ $userCount }}">0</div>
                        <span class="stat-trend">
                            <i class="fas fa-user-plus"></i> Growing
                        </span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-title">
                            <i class="fas fa-chart-area"></i>
                            Sales Overview
                        </div>
                        <div class="chart-actions">
                            <button class="chart-btn active">30 Days</button>
                            <button class="chart-btn">60 Days</button>
                            <button class="chart-btn">90 Days</button>
                        </div>
                    </div>
                    <div class="chart-body">
                        <canvas id="myAreaChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-title">
                            <i class="fas fa-chart-pie"></i>
                            Top Products
                        </div>
                    </div>
                    <div class="chart-body">
                        <canvas id="productSalesChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Out of Stock Modal -->
<div class="modal fade modern-modal" id="outOfStockModal" tabindex="-1" aria-labelledby="outOfStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="outOfStockModalLabel">
                    <i class="fas fa-exclamation-circle me-2"></i> Low Stock Alert
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if($outofstock->isNotEmpty())
                    <div class="stock-list">
                        @foreach($outofstock as $item)
                            <div class="stock-item">
                                <div class="stock-item-name">
                                    <i class="fas fa-box me-2"></i>{{ $item->name }}
                                </div>
                                <span class="stock-badge">{{ $item->stock }} left</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle" style="font-size: 60px; color: #10b981;"></i>
                        <p class="mt-3 text-muted">All products are well stocked!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-section')
<script>
    // Sales Overview Chart
    document.addEventListener("DOMContentLoaded", function() {
        let salesData = @json($salesOverview);
        let labels = salesData.map(item => item.date);
        let data = salesData.map(item => item.daily_sales);

        var ctx = document.getElementById("myAreaChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: "Daily Sales",
                    data: data,
                    backgroundColor: "rgba(102, 126, 234, 0.1)",
                    borderColor: "#667eea",
                    borderWidth: 3,
                    pointBackgroundColor: "#667eea",
                    pointBorderColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "#667eea",
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#667eea',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6b7280'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            color: '#6b7280'
                        }
                    }
                }
            }
        });
    });

    // Product Sales Pie Chart
    document.addEventListener("DOMContentLoaded", function(){
        let productSalesData = @json($topProducts);
        let labels = productSalesData.map(item => item.product_name);
        let data = productSalesData.map(item => item.total_sold);

        var ctx = document.getElementById("productSalesChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 13,
                                weight: '600'
                            },
                            color: '#2d3748'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    }
                }
            }
        });
    });

    // Animated Counter
    document.addEventListener("DOMContentLoaded", function() {
        let countElements = document.querySelectorAll("[data-count]");

        countElements.forEach((element) => {
            let count = 0;
            let target = parseInt(element.dataset.count);
            let increment = target / 60;

            let counter = setInterval(() => {
                if (count < target) {
                    count += increment;
                    element.innerText = Math.ceil(count);
                } else {
                    element.innerText = target;
                    clearInterval(counter);
                }
            }, 20);
        });
    });
</script>
@endsection
