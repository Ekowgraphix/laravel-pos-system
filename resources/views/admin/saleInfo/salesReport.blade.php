@extends('admin.layouts.master')
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header-modern">
            <h1 class="page-title-modern">
                <i class="fas fa-chart-line"></i>
                Sales Report
            </h1>
            <p class="page-subtitle-modern">
                <i class="fas fa-file-invoice-dollar"></i> Detailed sales transactions and revenue analysis
            </p>
        </div>

        <!-- Filter Card -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="fas fa-filter"></i>
                    Filter Options
                </h5>
                <button type="button" class="btn-success-modern btn-modern" onclick="exportTableToExcel('salesTable')" @if(empty($sales) || count($sales) == 0) disabled @endif>
                    <i class="fas fa-file-excel"></i>
                    Export to Excel
                </button>
            </div>
            <div class="card-body-modern">
                <form action="{{ route('salesReport') }}" method="GET">
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

        <!-- Sales Report Table -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h5 class="card-title-modern">
                    <i class="fas fa-table"></i>
                    Sales Report Details
                </h5>
                @if(!empty($sales) && count($sales) > 0)
                    <span class="badge-success-modern badge-modern">
                        <i class="fas fa-check-circle"></i>
                        {{ count($sales) }} Records Found
                    </span>
                @endif
            </div>
            <div class="card-body-modern">
                @if(!empty($sales) && count($sales) > 0)
                    <div class="table-modern-wrapper">
                        <table class="table-modern" id="salesTable">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">Order ID</th>
                                    <th style="width: 15%;">Order Code</th>
                                    <th style="width: 25%;">Product Name</th>
                                    <th style="width: 12%;">Price</th>
                                    <th style="width: 10%;">In Stock</th>
                                    <th style="width: 10%;">Sold</th>
                                    <th style="width: 18%;">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $item)
                                    <tr>
                                        <td>
                                            <span class="badge-primary-modern badge-modern">
                                                #{{ $item->order_id }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong style="color: #667eea;">
                                                <i class="fas fa-hashtag"></i>{{ $item->order_code }}
                                            </strong>
                                        </td>
                                        <td>
                                            <i class="fas fa-box me-2" style="color: #9ca3af;"></i>
                                            {{ $item->productname }}
                                        </td>
                                        <td>
                                            <span class="badge-success-modern badge-modern">
                                                <i class="fas fa-money-bill-wave"></i>
                                                {{ number_format($item->price) }} GHS
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->instock > 0)
                                                <span class="badge-info-modern badge-modern">
                                                    <i class="fas fa-boxes"></i>
                                                    {{ $item->instock }}
                                                </span>
                                            @else
                                                <span class="badge-danger-modern badge-modern">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    Out of Stock
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge-warning-modern badge-modern">
                                                <i class="fas fa-shopping-cart"></i>
                                                {{ $item->sold }}
                                            </span>
                                        </td>
                                        <td>
                                            <i class="far fa-calendar-alt me-2" style="color: #9ca3af;"></i>
                                            {{ $item->created_at->format('F j, Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="text-align: center; padding: 60px 20px;">
                        <i class="fas fa-chart-bar" style="font-size: 64px; color: #cbd5e0; margin-bottom: 20px; display: block;"></i>
                        <h5 style="color: #6b7280; font-weight: 600; margin-bottom: 10px;">No Sales Data Available</h5>
                        <p style="color: #9ca3af; font-size: 14px; margin-bottom: 20px;">
                            @if(request('start_date') || request('end_date'))
                                No sales records found for the selected date range.
                            @else
                                Please select a date range to generate the sales report.
                            @endif
                        </p>
                        <a href="{{ route('salesReportPage') }}" class="btn-primary-modern btn-modern">
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
        function exportTableToExcel(tableId, filename = 'Sales_Report_Details.xlsx') {
            const table = document.getElementById(tableId);
            if (!table) {
                alert('No data available to export!');
                return;
            }
            const workbook = XLSX.utils.table_to_book(table, {
                sheet: "Sales Report"
            });
            XLSX.writeFile(workbook, filename);
        }
    </script>
@endsection
