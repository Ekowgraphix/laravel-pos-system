@extends('admin.layouts.master')
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header-modern">
            <h1 class="page-title-modern">
                <i class="fas fa-chart-pie"></i>
                Profit & Loss Report
            </h1>
            <p class="page-subtitle-modern">
                <i class="fas fa-dollar-sign"></i> Financial performance analysis and profitability tracking
            </p>
        </div>

        <!-- Filter Card -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="fas fa-filter"></i>
                    Filter Options
                </h5>
                <button type="button" class="btn-success-modern btn-modern" onclick="exportTableToExcel('profitTable')" @if(empty($productsales) || count($productsales) == 0) disabled @endif>
                    <i class="fas fa-file-excel"></i>
                    Export to Excel
                </button>
            </div>
            <div class="card-body-modern">
                <form action="{{ route('profitlossReport') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <div class="form-group-modern">
                                <label for="start_date" class="form-label-modern">
                                    <i class="fas fa-calendar-alt"></i> Start Date
                                </label>
                                <input type="date" 
                                       name="start_date" 
                                       id="start_date"
                                       class="form-control-modern" 
                                       value="{{ request('start_date') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-modern">
                                <label for="end_date" class="form-label-modern">
                                    <i class="fas fa-calendar-alt"></i> End Date
                                </label>
                                <input type="date" 
                                       name="end_date" 
                                       id="end_date"
                                       class="form-control-modern" 
                                       value="{{ request('end_date') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-modern" style="margin-bottom: 0;">
                                <button type="submit" class="btn-primary-modern btn-modern w-100">
                                    <i class="fas fa-search"></i>
                                    Generate Report
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if(!empty($productsales) && count($productsales) > 0)
            @php
                $totalRevenue = $productsales->sum(function($item) {
                    return $item->sell_price * $item->units_sold;
                });
                $totalCost = $productsales->sum(function($item) {
                    return $item->purchase_price * $item->units_sold;
                });
                $totalProfit = $productsales->sum('total_profit');
                $totalUnitsSold = $productsales->sum('units_sold');
            @endphp

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card-modern" style="background: linear-gradient(135deg, rgba(17, 153, 142, 0.1) 0%, rgba(56, 239, 125, 0.1) 100%); border-left: 4px solid #11998e;">
                        <div class="card-body-modern">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <p style="color: #6b7280; font-size: 13px; font-weight: 600; margin: 0; text-transform: uppercase;">Total Revenue</p>
                                    <h3 style="color: #11998e; font-weight: 700; margin: 10px 0 0 0; font-size: 24px;">
                                        {{ number_format($totalRevenue) }} GHS
                                    </h3>
                                </div>
                                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-dollar-sign" style="color: white; font-size: 24px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card-modern" style="background: linear-gradient(135deg, rgba(250, 112, 154, 0.1) 0%, rgba(254, 225, 64, 0.1) 100%); border-left: 4px solid #fa709a;">
                        <div class="card-body-modern">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <p style="color: #6b7280; font-size: 13px; font-weight: 600; margin: 0; text-transform: uppercase;">Total Cost</p>
                                    <h3 style="color: #fa709a; font-weight: 700; margin: 10px 0 0 0; font-size: 24px;">
                                        {{ number_format($totalCost) }} GHS
                                    </h3>
                                </div>
                                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-shopping-cart" style="color: white; font-size: 24px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card-modern" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border-left: 4px solid #667eea;">
                        <div class="card-body-modern">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <p style="color: #6b7280; font-size: 13px; font-weight: 600; margin: 0; text-transform: uppercase;">Net Profit</p>
                                    <h3 style="color: #667eea; font-weight: 700; margin: 10px 0 0 0; font-size: 24px;">
                                        {{ number_format($totalProfit) }} GHS
                                    </h3>
                                </div>
                                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-chart-line" style="color: white; font-size: 24px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card-modern" style="background: linear-gradient(135deg, rgba(79, 172, 254, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%); border-left: 4px solid #4facfe;">
                        <div class="card-body-modern">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <p style="color: #6b7280; font-size: 13px; font-weight: 600; margin: 0; text-transform: uppercase;">Units Sold</p>
                                    <h3 style="color: #4facfe; font-weight: 700; margin: 10px 0 0 0; font-size: 24px;">
                                        {{ number_format($totalUnitsSold) }}
                                    </h3>
                                </div>
                                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-box" style="color: white; font-size: 24px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Profit & Loss Table -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="fas fa-table"></i>
                    Detailed Profit & Loss Analysis
                </h5>
                @if(!empty($productsales) && count($productsales) > 0)
                    <span class="badge-success-modern badge-modern">
                        <i class="fas fa-check-circle"></i>
                        {{ count($productsales) }} Products
                    </span>
                @endif
            </div>
            <div class="card-body-modern">
                @if(!empty($productsales) && count($productsales) > 0)
                    <div class="table-modern-wrapper">
                        <table class="table-modern" id="profitTable">
                            <thead>
                                <tr>
                                    <th style="width: 30%;">Product Name</th>
                                    <th style="width: 15%;">Sell Price</th>
                                    <th style="width: 15%;">Purchase Price</th>
                                    <th style="width: 15%;">Units Sold</th>
                                    <th style="width: 12.5%;">Profit/Unit</th>
                                    <th style="width: 12.5%;">Total Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productsales as $item)
                                    @php
                                        $profitPerUnit = $item->sell_price - $item->purchase_price;
                                    @endphp
                                    <tr>
                                        <td>
                                            <i class="fas fa-box me-2" style="color: #9ca3af;"></i>
                                            <strong>{{ $item->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge-success-modern badge-modern">
                                                <i class="fas fa-tag"></i>
                                                {{ number_format($item->sell_price) }} GHS
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge-warning-modern badge-modern">
                                                <i class="fas fa-money-bill"></i>
                                                {{ number_format($item->purchase_price) }} GHS
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge-info-modern badge-modern">
                                                <i class="fas fa-shopping-cart"></i>
                                                {{ $item->units_sold }} units
                                            </span>
                                        </td>
                                        <td>
                                            @if($profitPerUnit > 0)
                                                <span class="badge-success-modern badge-modern">
                                                    <i class="fas fa-arrow-up"></i>
                                                    {{ number_format($profitPerUnit) }} GHS
                                                </span>
                                            @elseif($profitPerUnit < 0)
                                                <span class="badge-danger-modern badge-modern">
                                                    <i class="fas fa-arrow-down"></i>
                                                    {{ number_format(abs($profitPerUnit)) }} GHS
                                                </span>
                                            @else
                                                <span class="badge-dark-modern badge-modern">
                                                    <i class="fas fa-minus"></i>
                                                    0 GHS
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->total_profit > 0)
                                                <span class="badge-success-modern badge-modern" style="font-size: 14px; font-weight: 700;">
                                                    <i class="fas fa-plus-circle"></i>
                                                    {{ number_format($item->total_profit) }} GHS
                                                </span>
                                            @elseif($item->total_profit < 0)
                                                <span class="badge-danger-modern badge-modern" style="font-size: 14px; font-weight: 700;">
                                                    <i class="fas fa-minus-circle"></i>
                                                    {{ number_format(abs($item->total_profit)) }} GHS
                                                </span>
                                            @else
                                                <span class="badge-dark-modern badge-modern">
                                                    <i class="fas fa-equals"></i>
                                                    0 GHS
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="text-align: center; padding: 60px 20px;">
                        <i class="fas fa-chart-line" style="font-size: 64px; color: #cbd5e0; margin-bottom: 20px; display: block;"></i>
                        <h5 style="color: #6b7280; font-weight: 600; margin-bottom: 10px;">No Financial Data Available</h5>
                        <p style="color: #9ca3af; font-size: 14px; margin-bottom: 20px;">
                            @if(request('start_date') || request('end_date'))
                                No profit/loss records found for the selected date range.
                            @else
                                Please select a date range to generate the profit & loss report.
                            @endif
                        </p>
                        <a href="{{ route('profitlossreportpage') }}" class="btn-primary-modern btn-modern">
                            <i class="fas fa-sync"></i>
                            Reset Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('js-section')
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script>
        function exportTableToExcel(tableId, filename = 'Profit_Loss_Report.xlsx') {
            const table = document.getElementById(tableId);
            if (!table) {
                alert('No data available to export!');
                return;
            }
            const workbook = XLSX.utils.table_to_book(table, {
                sheet: "Profit & Loss"
            });
            XLSX.writeFile(workbook, filename);
        }
    </script>
@endsection
