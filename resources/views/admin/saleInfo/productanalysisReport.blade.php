@extends('admin.layouts.master')
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header-modern">
            <h1 class="page-title-modern">
                <i class="fas fa-box-open"></i>
                Product Analysis Report
            </h1>
            <p class="page-subtitle-modern">
                <i class="fas fa-chart-pie"></i> Track inventory, sales performance, and stock levels
            </p>
        </div>

        <!-- Filter Card -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="fas fa-filter"></i>
                    Filter Options
                </h5>
                <button type="button" class="btn-success-modern btn-modern" onclick="exportTableToExcel('productTable')" @if(empty($stock) || count($stock) == 0) disabled @endif>
                    <i class="fas fa-file-excel"></i>
                    Export to Excel
                </button>
            </div>
            <div class="card-body-modern">
                <form action="{{ route('productReport') }}" method="GET">
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

        <!-- Product Analysis Table -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="fas fa-table"></i>
                    Product Performance Details
                </h5>
                @if(!empty($stock) && count($stock) > 0)
                    <span class="badge-success-modern badge-modern">
                        <i class="fas fa-check-circle"></i>
                        {{ count($stock) }} Products Found
                    </span>
                @endif
            </div>
            <div class="card-body-modern">
                @if(!empty($stock) && count($stock) > 0)
                    <div class="table-modern-wrapper">
                        <table class="table-modern" id="productTable">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">Product ID</th>
                                    <th style="width: 25%;">Product Name</th>
                                    <th style="width: 20%;">Category</th>
                                    <th style="width: 15%;">Initial Stock</th>
                                    <th style="width: 15%;">Units Sold</th>
                                    <th style="width: 15%;">Remaining</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stock as $item)
                                    <tr>
                                        <td>
                                            <span class="badge-primary-modern badge-modern">
                                                #{{ $item->product_id }}
                                            </span>
                                        </td>
                                        <td>
                                            <i class="fas fa-box me-2" style="color: #9ca3af;"></i>
                                            <strong>{{ $item->product_name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge-info-modern badge-modern">
                                                <i class="fas fa-tag"></i>
                                                {{ $item->category_name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge-dark-modern badge-modern">
                                                <i class="fas fa-warehouse"></i>
                                                {{ $item->in_stock }} units
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge-warning-modern badge-modern">
                                                <i class="fas fa-shopping-cart"></i>
                                                {{ $item->units_sold }} sold
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->remaining_stock > 10)
                                                <span class="badge-success-modern badge-modern">
                                                    <i class="fas fa-check-circle"></i>
                                                    {{ $item->remaining_stock }} units
                                                </span>
                                            @elseif($item->remaining_stock > 0)
                                                <span class="badge-warning-modern badge-modern">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    {{ $item->remaining_stock }} units
                                                </span>
                                            @else
                                                <span class="badge-danger-modern badge-modern">
                                                    <i class="fas fa-times-circle"></i>
                                                    Out of Stock
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
                        <i class="fas fa-boxes" style="font-size: 64px; color: #cbd5e0; margin-bottom: 20px; display: block;"></i>
                        <h5 style="color: #6b7280; font-weight: 600; margin-bottom: 10px;">No Product Data Available</h5>
                        <p style="color: #9ca3af; font-size: 14px; margin-bottom: 20px;">
                            @if(request('start_date') || request('end_date'))
                                No product records found for the selected date range.
                            @else
                                Please select a date range to generate the product analysis report.
                            @endif
                        </p>
                        <a href="{{ route('productReportPage') }}" class="btn-primary-modern btn-modern">
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
        function exportTableToExcel(tableId, filename = 'Product_Analysis_Report.xlsx') {
            const table = document.getElementById(tableId);
            if (!table) {
                alert('No data available to export!');
                return;
            }
            const workbook = XLSX.utils.table_to_book(table, {
                sheet: "Product Analysis"
            });
            XLSX.writeFile(workbook, filename);
        }
    </script>
@endsection
