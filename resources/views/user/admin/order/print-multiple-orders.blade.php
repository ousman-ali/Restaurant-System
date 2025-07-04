<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 80mm;
            margin: 0;
            padding: 0;
        }

        .receipt-container {
            width: 80mm;
            margin: 0 auto;
            padding: 10px 0;
        }

        .header, .footer {
            text-align: center;
        }

        .store-name {
            font-size: 16px;
            font-weight: bold;
        }

        .store-details {
            font-size: 11px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 5px 0;
            text-align: center;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .items-table th, .items-table td {
            font-size: 11px;
            padding: 3px;
            text-align: left;
        }

        .items-table th {
            border-bottom: 1px solid #000;
        }

        .items-table .sl {
            width: 5%;
            text-align: center;
        }

        .items-table .description {
            width: 45%;
        }

        .items-table .qty {
            width: 10%;
            text-align: center;
        }

        .items-table .rate, .items-table .amount {
            width: 20%;
            text-align: right;
        }

        .totals {
            margin-top: 10px;
            font-size: 12px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .grand-total {
            font-weight: bold;
            font-size: 13px;
            border-top: 1px solid #000;
            padding-top: 4px;
            margin-top: 5px;
        }

        .thank-you {
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
        }

        .receipt-id {
            font-size: 10px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
<div class="receipt-container">

    {{-- Header --}}
    <div class="header">
        <div class="store-name">{{ config('app.name') }}</div>
        <div class="store-details">
            <div>{{ config('restaurant.contact.address') }}</div>
            <div>Phone: {{ config('restaurant.contact.phone') }}</div>
            <div>VAT No: {{ config('restaurant.vat.vat_number') }}</div>
        </div>
    </div>

    <div class="title">Combined Receipt</div>

    {{-- Items Table --}}
    <table class="items-table">
        <thead>
        <tr>
            <th class="sl">#</th>
            <th class="description">Description</th>
            <th class="qty">Qty</th>
            <th class="rate">Rate</th>
            <th class="amount">Amount</th>
        </tr>
        </thead>
        <tbody>
        @php
            $sl = 1;
            $subtotal = 0;
            $discount = 0;
            $vat = 0;
        @endphp
        @foreach($orders as $order)
            @foreach($order->orderPrice as $item)
                <tr>
                    <td class="sl">{{ $sl++ }}</td>
                    <td class="description">
                        @if($item->ready_dish_id)
                            {{ $item->readyDish->name ?? 'Ready Dish' }}
                        @elseif($item->dish_id)
                            {{ $item->dish->dish ?? 'Dish' }}
                            <div class="item-variant">{{ $item->dishType->dish_type ?? '' }}</div>
                        @else
                            Unknown Item
                        @endif
                    </td>
                    <td class="qty">{{ $item->quantity }}</td>
                    <td class="rate">{{ number_format($item->net_price, 2) }}</td>
                    <td class="amount">{{ number_format($item->net_price * $item->quantity, 2) }}</td>
                </tr>
                @php
                    $subtotal += $item->gross_price;
                @endphp
            @endforeach
            @php
                $discount += $order->discount_amount;
                $vat += $order->vat;
            @endphp
        @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <div class="totals">
        <div class="total-row">
            <div>Subtotal:</div>
            <div>{{ config('restaurant.currency.symbol') }}{{ number_format($subtotal, 2) }}</div>
        </div>

        @if($discount > 0)
        <div class="total-row">
            <div>Discount:</div>
            <div>-{{ config('restaurant.currency.symbol') }}{{ number_format($discount, 2) }}</div>
        </div>
        @endif

        <div class="total-row">
            <div>VAT:</div>
            <div>{{ config('restaurant.currency.symbol') }}{{ number_format($vat, 2) }}</div>
        </div>

        <div class="total-row grand-total">
            <div>TOTAL:</div>
            <div>{{ config('restaurant.currency.symbol') }}{{ number_format($subtotal + $vat - $discount, 2) }}</div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="thank-you">Thank You For Your Order</div>
        <div class="receipt-id">Printed: {{ now()->format('Y-m-d H:i:s') }}</div>
    </div>
</div>

<script>
    window.onload = function () {
        window.print();
        window.onafterprint = function () {
            window.close();
        };
    };
</script>
</body>
</html>
