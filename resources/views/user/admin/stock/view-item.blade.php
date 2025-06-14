@extends('layouts.app')

@section('title')
    {{$item->product_name}} - Details
@endsection

@section('content')

    {{--{{$item->cookedProducts}}--}}
    <div class="card-box">
        <center>
            <img src="{{$item->thumbnail ? url($item->thumbnail) : url('/img_assets/avater.png')}}" alt="" width="150px" class="img-responsive"/>
            <h4 class="header-title">{{$item->product_name}} - Summary</h4>
            <p>
                Product Type : <b><i>{{$item->productType->product_type}} </i></b> <br>
                Created By : <b>{{$item->user->name}}</b> at <i>{{$item->created_at->format('d-M-Y')}}</i> <br>
                Total purses : <b>{{$item->purses->sum('quantity')}} {{$item->unit->unit}} |</b>
                Total Cooked : <b>{{$item->cookedProducts->sum('quantity')}} {{$item->unit->unit}}</b> <br>
                Available : <b>{{$item->purses->sum('quantity') - $item->cookedProducts->sum('quantity')}} {{$item->unit->unit}}</b>
            </p>

        </center>
        <h4>Purses history</h4>
        <table id="datatable-responsive"
               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%">
            <thead>
            <tr>
                <th>Supplier</th>
                <th>Purses unit</th>
                <th>Purses Price</th>
                <th>Date</th>

            </tr>
            </thead>
            <?php $count = 1; ?>
            <tbody>
            @foreach($item->purses as $purse)
                <tr>
                    <td>{{$purse->purse->supplier->name}}</td>
                    <td>{{$purse->quantity}} {{$item->unit->unit}}</td>
                    <td>{{config('restaurant.currency.symbol')}} <b>{{$purse->gross_price}}</b> {{config('restaurant.currency.currency')}} </td>
                    <td>{{$purse->created_at->format('d-M-Y')}}</td>
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
    </script>

@endsection