@extends('layouts.app')

@section('title')
    Payment on Purses-{{$purses->purses_id}}
@endsection

@section('content')

    <ol class="breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
        </li>
        <li>
            <a href="{{url('/all-purses')}}">All Purses</a>
        </li>
        <li>
            <a href="{{url('/purses-payment/'.$purses->id)}}">Purses-{{$purses->purses_id}}</a>
        </li>
    </ol>

    <?php
    $purseValue = $purses->pursesProducts->sum('gross_price');
    $pursePayment = $purses->pursesPayments->sum('payment_amount');
    $purseDue = $purses->pursesProducts->sum('gross_price') - $purses->pursesPayments->sum('payment_amount');
    ?>

    <div class="row">
        <div class="card-box">
            <center>
                <h4 class="header-title">Payment on Purses-{{$purses->purses_id}}</h4>
            </center>
            <dl class="dl-horizontal m-b-0">
                <dt>
                    Purses Id :
                </dt>
                <dd>
                    {{$purses->purses_id}}
                </dd>
                <dt>
                    Purses By :
                </dt>
                <dd>
                    {{$purses->user->name}}
                </dd>
                <dt>Supplier :</dt>
                <dd>
                    <a href="{{url('/view-supplier/'.$purses->supplier->id)}}" class="btn btn-link">{{$purses->supplier->name}}</a>
                </dd>
                <dt>
                    Purses At :
                </dt>
                <dd>
                    {{$purses->created_at}}
                </dd>
                <dt>
                    Purses Value :
                </dt>
                <dd>
                    <b>{{config('restaurant.currency.symbol')}} {{ number_format($purseValue,2,'.',',') }} {{config('restaurant.currency.currency')}}</b>
                </dd>
                <dt>
                    Purses Payment :
                </dt>
                <dd>
                    <b>{{config('restaurant.currency.symbol')}} {{number_format($pursePayment,2,'.',',')}} {{config('restaurant.currency.currency')}}</b>
                </dd>
                <dt>
                    Purses Due :
                </dt>
                <dd>
                    <b>
                        {{config('restaurant.currency.symbol')}}  {{ number_format($purseDue,2,'.',',') }} {{config('restaurant.currency.currency')}}
                    </b>
                </dd>
                <dt>
                    Purses Details :
                </dt>
                <dd>
                    <button class="btn btn-link btn-xs" data-toggle="collapse" data-target="#pursesHistoty">Click To
                        view
                        details
                    </button>
                    <div id="pursesHistoty" class="collapse">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Product Code</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Child unit price</th>
                                    <th class="text-right">Gross price</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $count = 1; ?>
                                @foreach($purses->pursesProducts as $pursesProduct)
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>{{$pursesProduct->product->product_name}}</td>
                                        <td>{{$pursesProduct->product->product_code}}</td>
                                        <td>{{$pursesProduct->quantity}}  {{$pursesProduct->product->unit->unit}}</td>
                                        <td>{{number_format($pursesProduct->unit_price,2,'.',',')}}
                                            / {{$pursesProduct->product->unit->unit}}</td>
                                        <td>{{number_format($pursesProduct->child_unit_price,2,'.',',')}}
                                            / {{$pursesProduct->product->unit->child_unit}}</td>
                                        <td class="text-right">{{number_format($pursesProduct->gross_price,2,'.',',')}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="6"></th>
                                    <th class="text-right">Total :{{$purseValue}}</th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </dd>
                <dt>Payment Details :</dt>
                <dd>
                    <button class="btn btn-link btn-xs" data-toggle="collapse" data-target="#paymentHistory">Click To
                        view
                        details
                    </button>

                    <div class="collapse" id="paymentHistory">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th class="text-right">Amount</th>
                                    <th>Payied By</th>
                                </tr>
                                </thead>
                                <tboody>
                                    <?php $count = 1; ?>
                                    @foreach($purses->pursesPayments as $pursesPayment)
                                        <tr>
                                            <td>{{$count++}}</td>
                                            <td>{{$pursesPayment->created_at}}</td>
                                            <td class="text-right">{{$pursesPayment->payment_amount}}</td>
                                            <td>{{$pursesPayment->user->name}}</td>
                                        </tr>
                                    @endforeach
                                        <tr>
                                            <th colspan="1"></th>
                                            <th class="text-right">Total :</th>
                                            <th>{{$pursePayment}}</th>
                                        </tr>
                                </tboody>
                            </table>
                        </div>
                    </div>

                </dd>
            </dl>
            <hr>
            <center>
                <h4 class="header-title">Make a new payment</h4>
                @if($purseDue != 0)
                    <div class="row">
                        <div class="col-md-offset-3 col-md-6">
                            <form action="{{url('/save-purses-payment/'.$purses->id)}}" method="post"
                                  class="form-horizontal">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <label for="" class="col-md-3 control-label">Current Due</label>
                                    <div class="col-md-9">
                                        <input type="text" readonly class="form-control" id="currentDue"
                                               value="{{$purseDue}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Todays Payment :</label>
                                    <div class="col-md-9">
                                        <input type="number" min="1" required name="payment" class="form-control"
                                               id="payment" value="">
                                        @if ($errors->has('msg'))
                                            <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('msg') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group m-b-0">
                                    <div class="col-sm-9">
                                        <button type="submit" class="btn btn-success waves-effect waves-light">Make
                                            Payment
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <p>There is no due to pay</p>
                @endif
            </center>
        </div>
    </div>
@endsection

@section('extra-js')
    <script>
        $(document).ready(function () {
            var currentDue = $("#currentDue").val();
            $("#payment").on('keyup', function (e) {
                $("#currentDue").val(currentDue - $(this).val());
            });
        });
    </script>

@endsection