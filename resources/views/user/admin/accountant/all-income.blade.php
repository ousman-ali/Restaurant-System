@extends('layouts.app')

@section('title')
    All Income
@endsection

@section('content')

    <?php
        $currency_symbol = config('restaurant.currency.symbol');
        $currency = config('restaurant.currency.currency');
    ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/add-expense')}}" class="btn btn-default waves-effect">New Expense <span class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Total Income {{config('restaurant.currency.symbol')}} {{number_format($total_earn->sum('payment'),2)}} {{config('restaurant.currency.currency')}}</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li>
                    <a href="#">Accounting</a>
                </li>
                <li class="active">
                    Income
                </li>
            </ol>
        </div>
    </div>
    <div class="card-box">
        @foreach($orders as $od)
            <h3>{{$od[0]->created_at->format('M-Y')}} - <small>Total Earn</small> : {{config('restaurant.currency.symbol')}} {{number_format($od->sum('payment'),2)}} {{config('restaurant.currency.currency')}}</h3>
            <div class="table-responsive">
                <table id=""
                       class="table datatable-responsive table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th>Order Id</th>
                        <th>Table id</th>
                        <th>Value</th>
                        <th>Value with vat</th>
                        <th>Payment</th>
                        <th>Change</th>
                        <th>Vat</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($od as $d)
                        <tr>
                            <td>{{$d->id}}</td>
                            <td>{{$d->table_id == 0 ? $d->table_id : $d->table->table_no}}</td>
                            <td>{{$currency_symbol}} {{$d->orderPrice->sum('gross_price')}} {{$currency}}</td>
                            <th>{{$currency_symbol}} {{number_format(round($d->payment + ($d->payment * $d->vat) /1000),2)}} {{$currency}}</th>
                            <td>{{$currency_symbol}} {{number_format($d->payment,2)}} {{$currency}}</td>
                            <td>{{$currency_symbol}} {{$d->change_ammount ? $d->change_ammount : 0}} {{$currency}}</td>
                            <td>{{$d->vat}} %</td>
                            <td>{{$d->created_at->format('d-M-Y')}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        @endforeach
    </div>
@endsection

@section('extra-js')
    <script src="{{url('/dashboard/plugins/datatables/buttons.html5.min.js')}}"></script>
    <script src="{{url('/dashboard/plugins/datatables/buttons.print.min.js')}}"></script>
    <script src="{{url('/dashboard/plugins/datatables/dataTables.buttons.min.js')}}"></script>
    <script src="{{url('/dashboard/plugins/datatables/jszip.min.js')}}"></script>
    <script src="{{url('/dashboard/plugins/datatables/pdfmake.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            $(".datatable-responsive").DataTable({
                order: [0, 'desc'],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'pdf','print'
                ],
            });
        })
    </script>
@endsection