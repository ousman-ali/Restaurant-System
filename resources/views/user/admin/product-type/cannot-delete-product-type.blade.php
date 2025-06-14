@extends('layouts.app')

@section('title')
    Item Details
@endsection

@section('content')
    {{--{{$product}}--}}
    <div class="card-box">
        <center>
            <h1 class="text-danger" style="font-size: 75px;"><i class="fa fa-info"></i></h1>
            <p> <span class="text-danger" style="font-size: 25px">Cannot delete <b><a href="{{url('/all-product-type/'.$product_type->id)}}">{{$product_type->product_type}}</a></b></span>
                <br>
                Coz, {{$product_type->product_type}} use in Product. you have to delete them first!
            </p>
            <a href="{{url('/all-product-type')}}" class="btn btn-success">Back to all product type</a>
        </center>

    </div>
@endsection