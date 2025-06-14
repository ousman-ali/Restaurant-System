@extends('layouts.app')

@section('title')
    Dish Price - {{$dish_price->dish->dish}}
@endsection

@section('content')
    {{--Page header--}}
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/all-dish')}}" class="btn btn-default waves-effect">All Dish <span class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Edit Dish </h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Dish
                </li>
                <li class="active">
                    Edit Dish
                </li>
            </ol>
        </div>
    </div>
    <ul class="nav nav-tabs">
        <li class="">
            <a href="{{url('/edit-dish/'.$dish_price->dish->id)}}"  aria-expanded="true">
                <span class="visible-xs"><i class="fa fa-cutlery"></i></span>
                <span class="hidden-xs">{{$dish_price->dish->dish}}</span>
            </a>
        </li>
        <li class="active">
            <a href="{{url('/dish-price/'.$dish_price->dish->id)}}" data-toggle="tab" aria-expanded="false">
                <span class="visible-xs"><i class="fa fa-usd"></i></span>
                <span class="hidden-xs">Dish Price</span>
            </a>
        </li>
        <li class="">
            <a href="{{url('/dish-image/'.$dish_price->dish->id)}}"  aria-expanded="false">
                <span class="visible-xs"><i class="fa fa-photo"></i></span>
                <span class="hidden-xs">Dish Images</span>
            </a>
        </li>
        <li class="">
            <a href="{{url('/dish-recipe/'.$dish_price->dish->id)}}" aria-expanded="false">
                <span class="visible-xs"><i class="fa fa-photo"></i></span>
                <span class="hidden-xs">Recipe</span>
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="home">
            <form class="form-inline" id="dishPriceForm" method="post" action="{{url('/update-dish-price/'.$dish_price->id)}}" data-parsley-validate novalidate>
                {{csrf_field()}}
                <input type="hidden" value="{{$dish_price->dish->id}}" id="dishId" required name="dish_id">
                <div class="form-group m-r-10">
                    <label>Dish Type </label>
                    <input type="text" name="dish_type" value="{{$dish_price->dish_type}}" required class="form-control"  placeholder="1/3 , 4/5">
                </div>
                <div class="form-group m-r-10">
                    <label>Price </label>
                    <div class="input-group m-t-8">
                        <span class="input-group-addon">{{config('restaurant.currency.symbol')}}</span>
                        <input type="number" value="{{$dish_price->price}}" required name="price" class="form-control" placeholder="..">
                    </div>

                </div>
                <button type="submit"  class="btn btn-default waves-effect waves-light btn-md">
                    Update
                </button>
                <a href="{{url('/dish-price/'.$dish_price->dish->id)}}"  class="btn btn-danger waves-effect waves-light btn-md">
                    Cancel
                </a>

            </form>
            <hr>
            <ul class="list-unstyled transaction-list">
                @forelse($dish_price->dish->dishPrices as $dish_price)
                    <li>
                        <i class="ti-download text-success"></i>
                        <span class="text">Dish One - {{$dish_price->dish_type}}</span>
                        <span class="text-success tran-price">{{config('restaurant.currency.symbol')}} {{number_format($dish_price->price,2)}} {{config('restaurant.currency.currency')}}</span>
                        <span class="pull-right">|
                        <a href="{{url('/edit-dish-price/'.$dish_price->id)}}" class="btn btn-link"><i class="fa fa-pencil"></i></a>
                        <a href="#" onclick="$(this).confirmDelete('/delete-dish-type/'+{{$dish_price->id}})" class="btn btn-link text-danger"><i class="fa fa-trash-o"></i></a>
                    </span>
                        <span class="pull-right text-muted">{{$dish_price->created_at->toDateTimeString()}}</span>
                        <span class="clearfix"></span>
                    </li>
                @empty
                    <p>Noting Found</p>
                @endforelse
            </ul>


        </div>
    </div>
@endsection
