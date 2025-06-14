@extends('layouts.app')

@section('title')
    Item Details
@endsection

@section('content')
    {{--{{$product}}--}}
    <div class="card-box">
        <center>
            <h1 class="text-danger" style="font-size: 75px;"><i class="fa fa-info"></i></h1>
            <p> <span class="text-danger" style="font-size: 25px">Cannot delete <b><a href="{{url('/view-item/'.$product->id)}}">{{$product->product_name}}</a></b></span>
                <br>
                Coz, {{$product->product_name}} use in Dish Type or Purses or Cooked table. you have to delete them first!
            </p>
            <a href="{{url('/all-item')}}" class="btn btn-success">Back to current stock</a>
        </center>

    </div>
@endsection