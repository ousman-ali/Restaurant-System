<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receipt</title>
    <style>
        /* Base settings for receipt */
        body {
            font-family: 'Courier New', monospace;
            line-height: 1.2;
            margin: 0;
            padding: 0;
            font-size: 12px;
            width: 80mm; /* Standard thermal receipt width */
        }

        /* Receipt Container */
        .receipt-container {
            width: 80mm;
            max-width: 80mm;
            margin: 0 auto;
            padding: 5mm 0;
            background: white;
            color: black;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .store-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .store-details {
            font-size: 11px;
            line-height: 1.3;
        }

        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 8px 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 5px 0;
        }

        /* Order Info */
        .order-info {
            margin-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 2px;
        }

        .info-label {
            font-weight: bold;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
        }

        .items-table th {
            font-size: 11px;
            text-align: left;
            padding: 5px 2px;
            border-bottom: 1px solid #000;
        }

        .items-table td {
            font-size: 11px;
            padding: 3px 2px;
            border-bottom: 1px dotted #ccc;
        }

        .items-table .sl {
            width: 8%;
            text-align: center;
        }

        .items-table .description {
            width: 42%;
        }

        .items-table .qty {
            width: 10%;
            text-align: center;
        }

        .items-table .rate,
        .items-table .amount {
            width: 20%;
            text-align: right;
        }

        .item-variant {
            font-size: 9px;
            color: #555;
        }

        /* Totals */
        .totals {
            margin-bottom: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 2px;
        }

        .grand-total {
            font-weight: bold;
            font-size: 13px;
            border-top: 1px solid #000;
            padding-top: 3px;
            margin-top: 5px;
        }

        /* Payment Info */
        .payment-info {
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .payment-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 2px;
        }

        .card-number {
            font-family: monospace;
            letter-spacing: 1px;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 10px;
        }

        .thank-you {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 3px;
        }

        .visit-again {
            font-size: 11px;
            margin-bottom: 10px;
        }

        .receipt-id {
            font-size: 10px;
            letter-spacing: 1px;
            font-family: monospace;
        }

        /* Print-specific styles */
        @media print {
            body {
                width: 80mm;
                margin: 0;
                padding: 0;
            }

            .receipt-container {
                width: 100%;
                max-width: 100%;
                margin: 0;
                padding: 0;
                border: none;
            }
        }
    </style>
</head>

<body>
<div class="receipt-container">
    <!-- Header -->
    <div class="header">
        <div class="store-name">{{config('app.name')}}</div>
        <div class="store-details">
            <div>{{config('restaurant.contact.address')}}</div>
            <div>Phone: {{config('restaurant.contact.phone')}}</div>
            <div>VAT No: {{config('restaurant.vat.vat_number')}}</div>
        </div>
    </div>

    <div class="title">RECEIPT</div>

    <!-- Order Info -->
    <div class="order-info">
        <div class="info-row">
            <div class="info-label">Order #:</div>
            <div class="info-value">{{str_pad($order->id,4,0,STR_PAD_LEFT)}}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date:</div>
            <div class="info-value">{{ date('d/m/Y', strtotime($order->created_at)) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Time:</div>
            <div class="info-value">{{ date('H:i', strtotime($order->created_at)) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Server:</div>
            <div class="info-value">{{$order->servedBy->name}}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Table:</div>
            <div class="info-value">{{$order->table_id}}</div>
        </div>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
        <tr>
            <th class="sl">Sl</th>
            <th class="description">Description</th>
            <th class="qty">Qty</th>
            <th class="rate">Rate</th>
            <th class="amount">Amount</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->orderPrice as $index => $orderDetails)
            <tr>
                <td class="sl">{{ $index + 1 }}</td>
                <td class="description">
                    {{ $orderDetails->dish->dish }}
                    <div class="item-variant">{{ $orderDetails->dishType->dish_type }}</div>
                </td>
                <td class="qty">{{ $orderDetails->quantity }}</td>
                <td class="rate">{{ number_format($orderDetails->net_price, 2) }}</td>
                <td class="amount">{{ number_format($orderDetails->net_price * $orderDetails->quantity, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
        <div class="total-row">
            <div class="total-label">Subtotal:</div>
            <div
                class="total-value">{{ config('restaurant.currency.symbol') }}{{ number_format($order->orderPrice->sum('gross_price'), 2) }}</div>
        </div>

        @if($order->discount_amount > 0)
            <div class="total-row">
                <div class="total-label">Discount:</div>
                <div class="total-value">
                    -{{ config('restaurant.currency.symbol') }}{{ number_format($order->discount_amount, 2) }}</div>
            </div>
        @endif

        <div class="total-row">
            <div class="total-label">VAT ({{ config('restaurant.vat.vat_percentage') }}%):</div>
            <div class="total-value">{{ config('restaurant.currency.symbol') }}{{ number_format($order->vat, 2) }}</div>
        </div>

        <div class="total-row grand-total">
            <div class="total-label">TOTAL:</div>
            <div
                class="total-value">{{ config('restaurant.currency.symbol') }}{{ number_format($order->orderPrice->sum('gross_price')+($order->orderPrice->sum('gross_price')*$order->vat)/100 - $order->discount_amount, 2) }}</div>
        </div>
    </div>

    <!-- Payment Info -->
    <div class="payment-info">
        @if($order->payment > 0)
            <div class="payment-title">PAYMENT DETAILS</div>

            @if(isset($order->payment_card) && $order->payment_card > 0)
                <div class="payment-row">
                    <div class="payment-label">Card:</div>
                    <div
                        class="payment-value">{{ config('restaurant.currency.symbol') }}{{ number_format($order->payment_card, 2) }}</div>
                </div>
                @if(isset($order->card_number))
                    <div class="payment-row card-number">
                        <div class="payment-label">Card No:</div>
                        <div class="payment-value">xxxx xxxx xxxx {{ substr($order->card_number, -4) }}</div>
                    </div>
                @endif
            @endif

            @if(isset($order->payment_cheque) && $order->payment_cheque > 0)
                <div class="payment-row">
                    <div class="payment-label">Cheque:</div>
                    <div
                        class="payment-value">{{ config('restaurant.currency.symbol') }}{{ number_format($order->payment_cheque, 2) }}</div>
                </div>
                @if(isset($order->cheque_number))
                    <div class="payment-row">
                        <div class="payment-label">Cheque No:</div>
                        <div class="payment-value">{{ $order->cheque_number }}</div>
                    </div>
                @endif
            @endif

            @if(isset($order->payment_cash) && $order->payment_cash > 0)
                <div class="payment-row">
                    <div class="payment-label">Cash:</div>
                    <div
                        class="payment-value">{{ config('restaurant.currency.symbol') }}{{ number_format($order->payment_cash, 2) }}</div>
                </div>
            @endif

            <div class="payment-row">
                <div class="payment-label">Cash Tendered:</div>
                <div
                    class="payment-value">{{ config('restaurant.currency.symbol') }}{{ number_format($order->payment, 2) }}</div>
            </div>

            <div class="payment-row">
                <div class="payment-label">Change:</div>
                <div
                    class="payment-value">{{ config('restaurant.currency.symbol') }}{{ number_format($order->change_amount, 2) }}</div>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="thank-you">Thank You For Your Order</div>
        <div class="visit-again">Please Visit Again</div>
        <div
            class="receipt-id">{{ date('YmdHis', strtotime($order->created_at)) }}{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>
    </div>
</div>

<!-- Scripts to auto-trigger print -->
<script>
    // Auto-print when page loads
    window.onload = function () {
        // Small delay to ensure content is fully loaded
        // setTimeout(function() {
        //     window.print();
        //
        //     // Optional: Close window after printing (uncomment if desired)
        //     // window.addEventListener('afterprint', function() {
        //     //     window.close();
        //     // });
        // }, 500);
    };
</script>
</body>
</html>
