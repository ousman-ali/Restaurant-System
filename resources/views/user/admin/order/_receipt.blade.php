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
            <div class="info-value">{{str_pad($currentOrder->id,4,0,STR_PAD_LEFT)}}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Date:</div>
            <div class="info-value">{{ date('d/m/Y', strtotime($currentOrder->created_at)) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Time:</div>
            <div class="info-value">{{ date('H:i', strtotime($currentOrder->created_at)) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Server:</div>
            <div class="info-value">{{$currentOrder->servedBy->name}}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Table:</div>
            <div class="info-value">{{$currentOrder->table_id}}</div>
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
            @foreach($currentOrder->orderPrice as $index => $orderDetails)
                <tr>
                    <td class="sl">{{ $index + 1 }}</td>
                    <td class="description">
                        @if($orderDetails->ready_dish_id)
                            {{ $orderDetails->readyDish->name ?? 'Ready Dish' }}
                        @elseif($orderDetails->dish_id)
                            {{ $orderDetails->dish->dish ?? 'Dish' }}
                            <div class="item-variant">{{ $orderDetails->dishType->dish_type ?? '' }}</div>
                        @else
                            Unknown Item
                        @endif
                    </td>
                    <td class="qty">{{ $orderDetails->quantity }}</td>
                    <td class="rate">{{ number_format($orderDetails->net_price, 2) }}</td>
                    <td class="amount">{{ number_format($orderDetails->net_price * $orderDetails->quantity, 2) }}</td>
                </tr>
            @endforeach

        {{-- @foreach($order->orderPrice as $index => $orderDetails)
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
        @endforeach --}}
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
        <div class="total-row">
            <div class="total-label">Subtotal:</div>
            <div
                class="total-value">{{ config('restaurant.currency.symbol') }}{{ number_format($currentOrder->orderPrice->sum('gross_price'), 2) }}</div>
        </div>

        @if($currentOrder->discount_amount > 0)
            <div class="total-row">
                <div class="total-label">Discount:</div>
                <div class="total-value">
                    -{{ config('restaurant.currency.symbol') }}{{ number_format($currentOrder->discount_amount, 2) }}</div>
            </div>
        @endif

        <div class="total-row">
            <div class="total-label">VAT ({{ config('restaurant.vat.vat_percentage') }}%):</div>
            <div class="total-value">{{ config('restaurant.currency.symbol') }}{{ number_format($currentOrder->vat, 2) }}</div>
        </div>

        <div class="total-row grand-total">
            <div class="total-label">TOTAL:</div>
            <div
                class="total-value">{{ config('restaurant.currency.symbol') }}{{ number_format($currentOrder->orderPrice->sum('gross_price')+($currentOrder->orderPrice->sum('gross_price')*$currentOrder->vat)/100 - $currentOrder->discount_amount, 2) }}</div>
        </div>
    </div>

    <!-- Payment Info -->
    <div class="payment-info">
        @if($currentOrder->payment > 0)
            <div class="payment-title">PAYMENT DETAILS</div>

            @if(isset($currentOrder->payment_card) && $currentOrder->payment_card > 0)
                <div class="payment-row">
                    <div class="payment-label">Card:</div>
                    <div
                        class="payment-value">{{ config('restaurant.currency.symbol') }}{{ number_format($currentOrder->payment_card, 2) }}</div>
                </div>
                @if(isset($currentOrder->card_number))
                    <div class="payment-row card-number">
                        <div class="payment-label">Card No:</div>
                        <div class="payment-value">xxxx xxxx xxxx {{ substr($currentOrder->card_number, -4) }}</div>
                    </div>
                @endif
            @endif

            @if(isset($currentOrder->payment_cheque) && $currentOrder->payment_cheque > 0)
                <div class="payment-row">
                    <div class="payment-label">Cheque:</div>
                    <div
                        class="payment-value">{{ config('restaurant.currency.symbol') }}{{ number_format($currentOrder->payment_cheque, 2) }}</div>
                </div>
                @if(isset($currentOrder->cheque_number))
                    <div class="payment-row">
                        <div class="payment-label">Cheque No:</div>
                        <div class="payment-value">{{ $currentOrder->cheque_number }}</div>
                    </div>
                @endif
            @endif

            @if(isset($currentOrder->payment_cash) && $currentOrder->payment_cash > 0)
                <div class="payment-row">
                    <div class="payment-label">Cash:</div>
                    <div
                        class="payment-value">{{ config('restaurant.currency.symbol') }}{{ number_format($currentOrder->payment_cash, 2) }}</div>
                </div>
            @endif

            <div class="payment-row">
                <div class="payment-label">Cash Tendered:</div>
                <div
                    class="payment-value">{{ config('restaurant.currency.symbol') }}{{ number_format($currentOrder->payment, 2) }}</div>
            </div>

            <div class="payment-row">
                <div class="payment-label">Change:</div>
                <div
                    class="payment-value">{{ config('restaurant.currency.symbol') }}{{ number_format($currentOrder->change_amount, 2) }}</div>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="thank-you">Thank You For Your Order</div>
        <div class="visit-again">Please Visit Again</div>
        <div
            class="receipt-id">{{ date('YmdHis', strtotime($currentOrder->created_at)) }}{{ str_pad($currentOrder->id, 4, '0', STR_PAD_LEFT) }}</div>
    </div>
</div>