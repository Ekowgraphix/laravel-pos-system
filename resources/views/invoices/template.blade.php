<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 20px;
        }
        .company-info {
            flex: 1;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 10px;
        }
        .invoice-info {
            text-align: right;
        }
        .invoice-title {
            font-size: 32px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 10px;
        }
        .invoice-details {
            margin-top: 10px;
        }
        .invoice-details p {
            margin: 5px 0;
        }
        .billing-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .billing-info {
            flex: 1;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table thead {
            background-color: #4F46E5;
            color: white;
        }
        .items-table th,
        .items-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .items-table th {
            font-weight: bold;
        }
        .items-table tbody tr:hover {
            background-color: #f9fafb;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            margin-left: auto;
            width: 300px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .totals-row.total {
            font-size: 20px;
            font-weight: bold;
            border-top: 2px solid #4F46E5;
            border-bottom: 2px solid #4F46E5;
            margin-top: 10px;
            color: #4F46E5;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }
        .thank-you {
            font-size: 18px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <div class="company-name">{{ $company['name'] }}</div>
                <p>{{ $company['address'] }}</p>
                <p>Phone: {{ $company['phone'] }}</p>
                <p>Email: {{ $company['email'] }}</p>
            </div>
            <div class="invoice-info">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-details">
                    <p><strong>Invoice #:</strong> {{ $invoice_number }}</p>
                    <p><strong>Order Code:</strong> {{ $order_code }}</p>
                    <p><strong>Date:</strong> {{ $date }}</p>
                </div>
            </div>
        </div>

        <!-- Billing Information -->
        <div class="billing-section">
            <div class="billing-info">
                <div class="section-title">Bill To</div>
                <p><strong>{{ $customer['name'] }}</strong></p>
                <p>{{ $customer['email'] }}</p>
                @if($customer['phone'])
                <p>Phone: {{ $customer['phone'] }}</p>
                @endif
                @if($customer['address'])
                <p>{{ $customer['address'] }}</p>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td class="text-right">{{ $item['quantity'] }}</td>
                    <td class="text-right">${{ number_format($item['price'], 2) }}</td>
                    <td class="text-right">${{ number_format($item['total'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <div class="totals-row">
                <span>Subtotal:</span>
                <span>${{ number_format($subtotal, 2) }}</span>
            </div>
            @if($tax > 0)
            <div class="totals-row">
                <span>Tax:</span>
                <span>${{ number_format($tax, 2) }}</span>
            </div>
            @endif
            <div class="totals-row total">
                <span>TOTAL:</span>
                <span>${{ number_format($total, 2) }}</span>
            </div>
        </div>

        <!-- Thank You Message -->
        <div class="thank-you">
            Thank you for your business!
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is a computer-generated invoice and does not require a signature.</p>
            <p>For any questions regarding this invoice, please contact {{ $company['email'] }}</p>
        </div>
    </div>
</body>
</html>
