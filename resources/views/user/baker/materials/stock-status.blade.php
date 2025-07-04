@extends('layouts.app')

@section('title')
    Stock Status
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">

            <h4 class="page-title">Stock Status </h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Stock Management
                </li>
                <li class="active">
                    All stock
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
                <th>#</th>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Stock</th>
                <th>Minimum Stock Threshold</th>
            </tr>
            </thead>
            <?php $count = 1; ?>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{$count++}} .</td>
                    <td>
                        <img src="/{{ $product->thumbnail }}" alt="" class="img-responsive" width="100px">
                    </td>
                    <td>{{$product->product_name}}</td>
                    @php
                        $purses = $product->purses_sum_quantity ?? 0;
                        $cooked = $product->cooked_products_sum_quantity ?? 0;
                        $availableStock = $purses - $cooked;
                    @endphp
                    <td>
                        @if ($availableStock == 0)
                            0
                        @else
                            {{ number_format($availableStock) }} {{ $product?->unit?->unit }}
                            ({{ number_format($availableStock * ($product?->unit?->convert_rate ?? 0)) }} {{ $product?->unit?->child_unit }})
                        @endif
                    </td>
                    <td>{{$product->minimum_stock_threshold ?? 0}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>


@endsection

@section('extra-js')
    <script>
        $(document).ready(function () {
            $("#datatable-responsive").DataTable();
        })
    </script>

@endsection