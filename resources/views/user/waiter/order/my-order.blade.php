@extends('layouts.app')

@section('title')
    All Order
@endsection

@section('content')
    <div class="card-box table-responsive">

        <h4 class="m-t-0 header-title"><b>All Order</b></h4>
       
        <div class="d-flex justify-content-end mb-3">
        <button id="printSelectedOrders" class="btn btn-info" style="display:none;">
            Print Selected Orders
        </button>
        </div>
        <table id="datatable-responsive"
               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%">
            <thead>
            <tr>
                {{--<th>#</th>--}}
                <th><input type="checkbox" id="selectAllOrders"></th>
                <th>Order No</th>
                <th>Served By</th>
                <th>Status</th>
                <th>Table</th>
                <th>Unpaid Amount</th>
                <th>Order Value</th>
                <th width="120px">Action</th>
            </tr>
            </thead>
            <?php $count = 1; ?>
            <tbody>
            @foreach($orders as $oder)
                <tr>
                    {{--<td>{{$count++}}</td>--}}
                    <td><input type="checkbox" class="order-checkbox" value="{{ $oder->id }}"></td>
                    <td>{{$oder->id}}</td> 
                    <td>{{$oder->servedBy->name}}</td>
                    @php
                        $amount = ($oder->orderPrice->sum('gross_price') - $oder->discount) + $oder->vat;
                        $payment = $oder->payment;
                        $diff = $amount-$payment;
                    @endphp
                    <td>{{$diff== 0 ? 'Paid' : 'Due' }}</td>
                    <td>{{$oder->table?->name ?? '_'}}</td>
                    <td>{{$diff}}</td>
                    <td>
                        {{$oder->orderPrice->sum('gross_price')}}

                    </td>
                    <td>
                            
                                <div class="btn-group">
                                    @if($oder->status == 0)
                                    <a href="{{url('/edit-order/'.$oder->id)}}"
                                       class="btn btn-success waves-effect waves-light">
                                        <i class="fa fa-pencil"></i>
                                    </a> 
                                    @endif
                                    <button type="button" onclick="printInvoice({{$oder->id}})"
                                            href="{{url('/print-order/'.$oder->id)}}"
                                            class="btn btn-purple waves-effect waves-light">
                                        <i class="fa fa-print"></i>
                                    </button>
                                    @if($oder->status == 0)
                                        <form action="{{ route('order.delete')}}" method="post" class="deleteform">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{$oder->id}}">
                                            <button type="submit" class="btn btn-danger waves-effect waves-light deletebtn">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($diff && $diff > 0)
                                    <button
                                        class="btn btn-primary waves-effect waves-light pay-btn"
                                        data-order-id="{{ $oder->id }}"
                                        data-diff="{{ number_format($diff, 2, '.', '') }}">
                                        <i class="fa fa-credit-card"></i> Pay
                                        </button>

                                    @endif
                                </div>
                        </td>

                        <div id="paymentModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; 
                            background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
                        <div style="background:#fff; padding:20px; border-radius:8px; max-width:400px; width:90%;">
                            <h4>Make Payment</h4>
                            <input type="number" id="paymentAmount" class="form-control" placeholder="Enter payment amount" />
                            <input type="hidden" id="paymentOrderId" />
                            <div style="margin-top:15px; text-align:right;">
                            <button id="cancelPayment" class="btn btn-secondary">Cancel</button>
                            <button id="confirmPayment" class="btn btn-success">Confirm Payment</button>
                            </div>
                        </div>
                        </div>


                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    All Order
@endsection

@section('extra-js')
    <script>
        $(document).ready(function () {
            $("#datatable-responsive").DataTable({
                "order": [[ 0, "desc" ]]
            });



           function togglePrintButton() {
                if ($('.order-checkbox:checked').length > 0) {
                    $('#printSelectedOrders').show();
                } else {
                    $('#printSelectedOrders').hide();
                }
            }

            $('#selectAllOrders').on('change', function() {
                $('.order-checkbox').prop('checked', $(this).prop('checked'));
                togglePrintButton();
            });

            $(document).on('change', '.order-checkbox', function() {
                if (!$(this).prop('checked')) {
                    $('#selectAllOrders').prop('checked', false);
                } else if ($('.order-checkbox:checked').length === $('.order-checkbox').length) {
                    $('#selectAllOrders').prop('checked', true);
                }
                togglePrintButton();
            });


            // Handle batch print button click
            $('#printSelectedOrders').on('click', function () {
                const selectedOrderIds = $('.order-checkbox:checked').map(function () {
                    return $(this).val();
                }).get();

                printMultipleInvoices(selectedOrderIds);
            });

           $(document).on('click', '.pay-btn', function() {
                const orderId = $(this).data('order-id');
                const diff = $(this).data('diff');  // Get the diff amount
                
                $('#paymentOrderId').val(orderId);
                $('#paymentAmount').val(diff); // Autofill with the diff amount
                $('#paymentModal').css('display', 'flex');
                });

            $('#cancelPayment').on('click', function() {
                $('#paymentModal').hide();
            });

            $('#confirmPayment').on('click', function() {
                const orderId = $('#paymentOrderId').val();
                const amount = $('#paymentAmount').val();

                if (!amount || amount <= 0) {
                console.log('Please enter a valid payment amount.');
                return;
                }

                $.ajax({
                url: `/pay-order/${orderId}`,
                method: 'POST',
                data: {
                    amount: amount,
                    _token: '{{ csrf_token() }}' // Laravel CSRF token
                },
                success: function(response) {
                    console.log('Payment successful!');
                    $('#paymentModal').hide();
                    // Optionally reload page or update UI here
                    location.reload();
                },
                error: function() {
                    console.log('Payment failed, please try again.');
                }
                });
            });
        });

        function printInvoice(orderId) {
            if (!orderId) {
                alert("Cannot print receipt: Order ID is missing");
                return;
            }
            // Fetch the receipt HTML
            fetch(`/print-order/${orderId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'text/html'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    // Open a new window for printing
                    const printWindow = window.open('', '', 'width=800,height=600,toolbar=0,menubar=0,location=0');

                    // Insert the styles right after the <head> tag in the response
                    // Write the modified HTML to the new window
                    printWindow.document.write(html);
                    printWindow.document.close();

                    // Trigger print when content is loaded
                    printWindow.onload = function() {
                        printWindow.focus();
                        setTimeout(() => {
                            printWindow.print();

                            // Close the window after printing
                            printWindow.onafterprint = function() {
                                printWindow.close();
                            };

                            // Fallback close for browsers that don't support onafterprint
                            setTimeout(() => {
                                printWindow.close();
                            }, 1000);
                        }, 500);
                    };
                })
                .catch(error => {
                    console.error('Error printing receipt:', error);
                    alert("Error printing receipt. Please try again.");
                });
        }

        function printMultipleInvoices(orderIds) {
            if (!orderIds || orderIds.length === 0) {
                alert("Cannot print receipt: No order IDs selected");
                return;
            }

            // Build query string param with comma-separated IDs
            const idsQuery = encodeURIComponent(orderIds.join(','));

            fetch(`/print-multiple-orders?order_ids=${idsQuery}`, {
                method: 'GET',
                headers: {
                    'Accept': 'text/html'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                const printWindow = window.open('', '', 'width=800,height=600,toolbar=0,menubar=0,location=0');

                console.log('html', html);
                printWindow.document.write(html);
                printWindow.document.close();

                printWindow.onload = function() {
                    printWindow.focus();
                    setTimeout(() => {
                        printWindow.print();

                        printWindow.onafterprint = function() {
                            printWindow.close();
                        };

                        setTimeout(() => {
                            printWindow.close();
                        }, 1000);
                    }, 500);
                };
            })
            .catch(error => {
                console.error('Error printing receipts:', error);
                alert("Error printing receipts. Please try again.");
            });
}




    </script>

@endsection