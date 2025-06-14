@extends('layouts.app')

@section('title')
    All Expanse
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/add-expense')}}" class="btn btn-default waves-effect">New Expense <span
                            class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">All
                Expanse {{config('restaurant.currency.symbol')}} {{number_format($office_expanse->sum('expanse'),2)}} {{config('restaurant.currency.currency')}}</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li>
                    <a href="#">Accounting</a>
                </li>
                <li class="active">
                    Expanse
                </li>
                <li class="active">
                    All Expanse
                </li>
            </ol>
        </div>
    </div>
    <div class="card-box">
        @foreach($expenses as $expanse)

                <?php $purses_expanse = \App\Models\Purse::whereMonth('created_at', $expanse[0]->created_at->format('m'))
                ->whereYear('created_at', $expanse[0]->created_at->format('Y'))
                ->get();
                $purses_cost = 0;
                foreach ($purses_expanse as $ex) {
                    $purses_cost += $ex->pursesProducts->sum('gross_price');
                }
                ?>

            <h3>{{$expanse[0]->created_at->format('M-Y')}} -
                <small>Total expanse :
                </small> {{config('restaurant.currency.symbol')}} {{number_format($expanse->sum('expanse')+$purses_cost ,2)}}  {{config('restaurant.currency.currency')}}
            </h3>
            <h4>
                Office Expanse :
                {{config('restaurant.currency.symbol')}}
                {{number_format($expanse->sum('expanse') ,2)}}
                {{config('restaurant.currency.currency')}} |
                Purses expanse :
                {{config('restaurant.currency.symbol')}}
                {{number_format($purses_cost ,2)}}
                {{config('restaurant.currency.currency')}}
            </h4>
            <div class="table-responsive">
                <table id=""
                       class="table datatable-responsive table-striped table-bordered dt-responsive nowrap"
                       cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th>Month</th>
                        <th>Coz if expanse</th>
                        <th> Value</th>
                        <th>Expanse By</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($expanse as $_expanse)
                        <tr>
                            <td>{{$_expanse->created_at->format('M - Y')}}</td>
                            <td>{{$_expanse->title}}
                                <a href="{{url('/edit-expanse/'.$_expanse->id)}}"><i class="fa fa-pencil"></i></a>
                                <a href="javascript:void(0);" class="text-danger"
                                   onclick="$(this).confirmDelete('/delete-expanse/{{$_expanse->id}}')"><i
                                            class="fa fa-trash-o"></i></a>
                            </td>
                            <th>{{config('restaurant.currency.symbol')}} {{number_format($_expanse->expanse,2)}} {{config('restaurant.currency.currency')}}</th>
                            <td>{{$_expanse->user->name}}</td>
                            <td>{{$_expanse->created_at->format('d-M-Y')}}</td>
                        </tr>
                    @endforeach

                    @foreach($purses_expanse as $p_expanse)
                        <tr>
                            <td>{{$p_expanse->created_at->format('M - Y')}}</td>
                            <td><a href="{{url('/purses-payment/'.$p_expanse->id)}}" target="_blank">Purses <i
                                            class="fa fa-external-link"></i> </a></td>
                            <th>{{config('restaurant.currency.symbol')}} {{number_format($p_expanse->pursesProducts->sum('gross_price'),2)}} {{config('restaurant.currency.currency')}}</th>
                            <td>{{$p_expanse->user->name}}</td>
                            <td>{{$p_expanse->created_at->format('d-M-Y')}}</td>
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
                    'copy', 'excel', 'pdf', 'print'
                ],
            });
        })
    </script>

@endsection
