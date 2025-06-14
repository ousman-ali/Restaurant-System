@extends('layouts.app')

@section('title')
    All Order
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/new-order')}}" class="btn btn-default waves-effect">New Order <span
                        class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Non Paid Orders</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li>
                    <a href="#">Order</a>
                </li>
                <li class="active">
                    Non Paidn Order
                </li>
            </ol>
        </div>
    </div>
    <div class="card-box table-responsive">
        <table id="datatable-responsive"
               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%">
            <thead>
            <tr>
                <th>Order No</th>
                <th>Table No</th>
                <th>Served By</th>
                <th>Order Value</th>
                <th>Kitchen</th>
                <th>Status</th>
                <th width="120px">Action</th>
            </tr>
            </thead>
            <?php $count = 1; ?>
            <tbody>
            @foreach($orders as $order)
                    <?php
                    $orderSum = $order->orderPrice->sum('gross_price');
                    ?>
                <tr>
                    <td>{{str_pad($order->id,4,0,STR_PAD_LEFT)}}</td>
                    <td>{{$order->table ? $order->table->table_no : "Table not selected"}}</td>
                    <td>{{$order->servedBy->name}}</td>
                    <td>
                        {{config('restaurant.currency.symbol')}} {{number_format($orderSum,2)}} {{config('restaurant.currency.currency')}}
                        {{--<dl class="dl-horizontal m-b-0">--}}
                        {{--<dt>--}}
                        {{--Order Value :--}}
                        {{--</dt>--}}
                        {{--<dd>--}}
                        {{--{{config('restaurant.currency.symbol')}} {{number_format($orderSum,'00','.',',')}} {{config('restaurant.currency.currency')}}--}}
                        {{--</dd>--}}
                        {{--<dt>--}}
                        {{--Order Value inc vat:--}}
                        {{--</dt>--}}
                        {{--<dd>--}}
                        {{--{{config('restaurant.currency.symbol')}} {{number_format($orderSum+($orderSum*$order->vat)/100,'00','.',',')}} {{config('restaurant.currency.currency')}}--}}
                        {{--</dd>--}}
                        {{--<dt>--}}
                        {{--Vat:--}}
                        {{--</dt>--}}
                        {{--<dd>--}}
                        {{--{{$order->vat}} %--}}
                        {{--</dd>--}}
                        {{--<dt>--}}
                        {{--Cache:--}}
                        {{--</dt>--}}
                        {{--<dd>--}}
                        {{--{{$order->payment}}--}}
                        {{--</dd>--}}
                        {{--<dt>--}}
                        {{--Change:--}}
                        {{--</dt>--}}
                        {{--<dd>--}}
                        {{--{{$order->change_amount}}--}}
                        {{--</dd>--}}
                        {{--</dl>--}}
                    </td>
                    <td>{{$order->kitchen ? $order->kitchen->name : "No-Kitchen"}}</td>
                    <td>
                        @if($order->status == 0)
                            <span class="text-warning">Pending.....</span>
                        @elseif($order->status == 1)
                            <i>Cooking.....</i>
                        @elseif($order->status == 2)
                            <b class="text-custom"><i>Cooked !</i></b>
                        @elseif($order->status == 3)
                            <b class="text-danger"> Served !</b>
                        @else
                            Unknown Status
                        @endif
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{url('/edit-order/'.$order->id)}}"
                               class="btn btn-success waves-effect waves-light">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <button role="button" onclick="printInvoice({{$order->id}})"
                                    class="btn btn-purple waves-effect waves-light">
                                <i class="fa fa-print"></i>
                            </button>
                            @if($order->status == 0)
                                <a href="#" onclick="$(this).confirmDelete('/delete-order/{{$order->id}}')"
                                   class="btn btn-danger waves-effect waves-light">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
@endsection

@section('extra-js')
    <script>
        $(document).ready(function () {
            $("#datatable-responsive").DataTable({
                order: [0, 'desc']
            });
        })

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
                    printWindow.onload = function () {
                        printWindow.focus();
                        setTimeout(() => {
                            printWindow.print();

                            // Close the window after printing
                            printWindow.onafterprint = function () {
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
    </script>

@endsection
