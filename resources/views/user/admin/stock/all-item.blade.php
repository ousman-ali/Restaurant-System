@extends('layouts.app')

@section('title')
    Current Stock
@endsection

@section('content')

    <style>
        .out-of-stock {
            border-color: red;
        }

        .low-stock {
            border-color: yellow;
        }

        .product-head {
            /*background-color: grey;*/
            padding: 5px;
        }

        .product-head a:first-child {
            color: orangered;
        }

        .blink {
            animation: blink-animation 1s steps(5, start) infinite;
            -webkit-animation: blink-animation 1s steps(5, start) infinite;
        }
        @keyframes blink-animation {
            50% { opacity: 0; }
        }
        @-webkit-keyframes blink-animation {
            50% { opacity: 0; }
        }
    </style>
    {{--Page header--}}
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/add-item')}}" class="btn btn-default waves-effect">Add new product <span
                            class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Current Stock</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Stock Management
                </li>
                <li class="active">
                    Current Stock
                </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="tabs-vertical-env">
                <ul class="nav tabs-vertical">
                    <?php $active_tab = 0; ?>
                    @foreach($product_types as $type)
                        <?php $active_tab++ ?>
                        <li class="{{$active_tab == 1 ? 'active' : ''}}">
                            <a href="#product_type_{{$type->id}}" id="" data-toggle="tab"
                               aria-expanded="false">
                                <span class="badge pull-right">{{count($type->products)}}</span>
                                {{$type->product_type}}
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <a href="#out_of_stock" data-toggle="tab"
                           aria-expanded="false">Product out of stock &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="badge" id="showOutOfStock"></span></a>

                    </li>
                </ul>

                <div class="tab-content">
                    <?php $active_content = 0; $out_of_stock = array(); ?>
                    @foreach($product_types as $type)
                        <?php $active_content++ ?>
                        <div class="tab-pane {{ $active_content == 1 ? 'active' : '' }}"
                             id="product_type_{{$type->id}}">
                            <div class="row">
                                @forelse($type->products as $product)
                                    <?php
                                    $total_purses = $product->purses->sum('quantity');
                                    $total_cooked = $product->cookedProducts->sum('quantity');
                                    $available_product = $total_purses - $total_cooked;
                                    $product_unit = $product->unit->unit;
                                    if($available_product <= 0){
                                        array_push($out_of_stock,$product);
                                    }
                                    ?>
                                    <div class="col-md-6">
                                        <div class="col-md-4">
                                            <img src="{{$product->thumbnail != '' | null ? $product->thumbnail : url('/img_assets/avater.png') }}"
                                                 class="img-responsive thumbnail m-r-15">
                                        </div>
                                        <div class="col-md-8">
                                            <h4 class="product-head">
                                                <a href="{{url('/view-item/'.$product->id)}}" class="{{$available_product <= 0 ? 'blink' : ''}}">{{$product->product_name}}</a>
                                                <div class="pull-right">
                                                    <a href="{{url('/edit-item/'.$product->id)}}"><i
                                                                class="fa fa-pencil text-success"></i></a>
                                                    |
                                                    <a href="#"
                                                       onclick="$(this).confirmDelete('/delete-item/{{$product->id}}')"
                                                       class="text-danger"><i class="fa fa-trash-o"></i></a>
                                                </div>
                                            </h4>

                                            <p>
                                                Use in recipe : <b>{{count($product->recipes)}}</b> <br>
                                                Total Purse &nbsp;&nbsp;&nbsp;: <b>{{$total_purses}}
                                                    | {{$product_unit}}</b> <br>
                                                Total Cooked : <b> {{$total_cooked}} | {{$product_unit}}</b> <br>
                                                Available &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                                                <b>{{$available_product}} | {{$product_unit}}</b> <br>
                                                Last Purses
                                                &nbsp;&nbsp;&nbsp;: {{$product->purses->last() ? $product->purses->last()->created_at->format('d-M-Y') : "Not Purses Yet"}}
                                                <br>
                                                Added By &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{$product->user->name}}
                                            </p>

                                        </div>
                                    </div>

                                @empty
                                    <div class="col-md-12">
                                        <center>
                                            <h1>Nothing Found is this product category</h1>
                                        </center>
                                    </div>

                                @endforelse
                            </div>
                        </div>
                    @endforeach

                    <div class="tab-pane" id="out_of_stock">
                        <div class="row">
                            <input type="hidden" value="{{count($out_of_stock)}}" id="outOfStock">
                            @foreach($out_of_stock as $product)
                                <?php
                                $total_purses = $product->purses->sum('quantity');
                                $total_cooked = $product->cookedProducts->sum('quantity');
                                $available_product = $total_purses - $total_cooked;
                                $product_unit = $product->unit->unit; ?>
                                <div class="col-md-6">
                                    <div class="col-md-4">
                                        <img src="{{$product->thumbnail != '' | null ? $product->thumbnail : url('/img_assets/avater.png')}}"
                                             class="img-responsive thumbnail m-r-15">
                                    </div>
                                    <div class="col-md-8">
                                        <h4 class="product-head">
                                            <a href="{{url('/view-item/'.$product->id)}}" class="{{$available_product <= 0 ? 'blink' : ''}}">{{$product->product_name}}</a>
                                            <div class="pull-right">
                                                <a href="{{url('/edit-item/'.$product->id)}}"><i
                                                            class="fa fa-pencil text-success"></i></a>
                                                |
                                                <a href="#"
                                                   onclick="$(this).confirmDelete('/delete-item/{{$product->id}}')"
                                                   class="text-danger"><i class="fa fa-trash-o"></i></a>
                                            </div>
                                        </h4>

                                        <p>
                                            Category : <b>{{$product->productType->product_type}}</b> <br>
                                            Use in recipe : <b>{{count($product->recipes)}}</b> <br>
                                            Total Purse &nbsp;&nbsp;&nbsp;: <b>{{$total_purses}}
                                                | {{$product_unit}}</b> <br>
                                            Total Cooked : <b> {{$total_cooked}} | {{$product_unit}}</b> <br>
                                            Available &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                                            <b>Out Of Stock</b> <br>
                                            Last Purses
                                            &nbsp;&nbsp;&nbsp;: {{$product->purses->last() ? $product->purses->last()->created_at->format('d-M-Y') : "Not Purses Yet"}}
                                            <br>
                                            Added By &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{$product->user->name}}
                                        </p>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>  <!-- end row -->





@endsection

@section('extra-js')
    <script>
        $(document).ready(function () {
            var out_stock = $("#outOfStock").val();
            $("#showOutOfStock").text(out_stock);
        });
    </script>

@endsection