@extends('layouts.app')

@section('title')
    {{$supplier->name}}
@endsection

@section('content')
    <?php $totalPursesAmount = 0; $totalPursesPayment = 0; ?>
    <div class="row">
        <div class="card-box">
            <center>
                <h4 class="header-title">{{$supplier->name}}</h4>
            </center>
            <dl class="dl-horizontal m-b-0">
                <dt>
                    Phone Number :
                </dt>
                <dd>
                    {{$supplier->phone}}
                </dd>
                <dt>
                    Email Address :
                </dt>
                <dd>
                    {{$supplier->email}}
                </dd>
                <dt>
                    Address :
                </dt>
                <dd>
                    {{$supplier->address}}
                </dd>
                <dt>
                    Status :
                </dt>
                <dd>
                    {{$supplier->status == 1 ? "Active" : "In-Active"}}
                </dd>
                <dt>
                    Added By :
                </dt>
                <dd>
                    {{$supplier->user->name}}
                </dd>
                <dt>
                    Total Purses :
                </dt>
                <dd>
                    {{count($supplier->purses)}} Time(s)
                </dd>
                <dt>
                    Total Payment :
                </dt>
                <dd>
                    {{count($supplier->payment)}} Time(s)
                </dd>
            </dl>
            <hr>
            <center>
                <h4 class="header-title">{{$supplier->name}} - All Purses</h4>
            </center>
            <hr>
            <div class="table-responsive">
                <table class="table">
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
                    <tbody>
                    @foreach($supplier->purses as $purse)
                        <tr>
                                <?php $pursesValue = $purse->pursesProducts->sum('gross_price');
                                $pursesPayment = $purse->pursesPayments->sum('payment_amount');
                                $totalPursesAmount += $pursesValue;
                                $totalPursesPayment += $pursesPayment;
                                ?>
                            <td>{{$purse->created_at->format('d-m-Y')}}</td>
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
                                    <a href="{{url('/edit-purses/'.$purse->id)}}"
                                       class="btn btn-success waves-effect waves-light">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a href="{{url('/purses-payment/'.$purse->id)}}"
                                       class="btn btn-info waves-effect waves-light">
                                        <i class="fa fa-info"></i>
                                    </a>
                                    <a href="#" class="btn btn-danger waves-effect waves-light">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th>Total purses value:</th>
                        <th>{{$totalPursesAmount}}</th>
                    </tr>
                    <tr>
                        <th>Total Payment:</th>
                        <th>{{$totalPursesPayment}}</th>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <th>{{$totalPursesPayment - $totalPursesAmount}}</th>
                    </tr>
                    </tbody>
                </table>

                <hr>
                <center>
                    <h4 class="header-title">{{$supplier->name}} - All Payment</h4>
                </center>
                <hr>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Purses Id</th>
                            <th>Date</th>
                            <th class="text-right">Amount</th>
                            <th>Payied By</th>
                        </tr>
                        </thead>
                        <tboody>
                            <?php $count = 1; ?>
                            @foreach($supplier->payment as $pursesPayment)
                                <tr>
                                    <td>{{$count++}}</td>
                                    <td><a href="{{url('/purses-payment/'.$pursesPayment->purses->id)}}"
                                           target="_blank">{{$pursesPayment->purses->purses_id}}</a></td>
                                    <td>{{$pursesPayment->created_at}}</td>
                                    <td class="text-right">{{$pursesPayment->payment_amount}}</td>
                                    <td>{{$pursesPayment->user->name}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="1"></th>
                                <th class="text-right">Total :</th>
                                <th>{{$totalPursesPayment}}</th>
                            </tr>
                        </tboody>
                    </table>
                </div>
            </div>


        </div>
    </div>
@endsection
