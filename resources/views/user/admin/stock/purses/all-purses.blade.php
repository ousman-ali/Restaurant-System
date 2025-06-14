@extends('layouts.app')

@section('title')
    Purses List
@endsection

@section('content')
    {{--Page header--}}
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/new-purses')}}" class="btn btn-default waves-effect">Purses Now <span class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">All Purses </h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Stock Management
                </li>
                <li class="active">
                    All Purses
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

                <th>Date</th>
                <th>Purses Id</th>
                <th>Purses Value</th>
                <th>Purses By</th>
                <th>Supplied By</th>
                <th>Status</th>

                <th width="120px">Action</th>
            </tr>
            </thead>
            <?php $count = 1; ?>
            <tbody>
            @foreach($purses as $purse)
                <tr>
                    <?php $pursesValue = $purse->pursesProducts->sum('gross_price'); $pursesPayment = $purse->pursesPayments->sum('payment_amount'); ?>
                    <td>{{$purse->created_at->format('d-m-Y')}} </td>
                    <td>{{$purse->purses_id}} </td>
                    <th>{{config('restaurant.currency.symbol')}} {{ number_format($pursesValue,2,'.',',') }} {{config('restaurant.currency.currency')}} </th>
                    <td>{{$purse->user->name}} </td>
                    <td>{{$purse->supplier->name}} </td>
                    <td>
                        @if($pursesValue-$pursesPayment <= 0)
                            Paid
                            @else
                            Not Paid
                        @endif

                    </td>


                    <td>
                        <div class="btn-group">
                            <a href="{{url('/edit-purses/'.$purse->id)}}" class="btn btn-success waves-effect waves-light">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a href="{{url('/purses-payment/'.$purse->id)}}" class="btn btn-info waves-effect waves-light">
                                <i class="fa fa-info"></i>
                            </a>
                            <a href="#" onclick="$(this).confirmDelete('/delete-purses/'+{{$purse->id}})" class="btn btn-danger waves-effect waves-light">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

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
            $("#datatable-responsive").DataTable({
//                order: [0, 'desc'],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'pdf','print'
                ],
            });
        })
    </script>

@endsection