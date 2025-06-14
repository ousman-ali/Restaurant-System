@extends('layouts.app')

@section('title')
    Dish Image - {{$dish->dish}}
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
            <a href="{{url('/edit-dish/'.$dish->id)}}"  aria-expanded="true">
                <span class="visible-xs"><i class="fa fa-cutlery"></i></span>
                <span class="hidden-xs">{{$dish->dish}}</span>
            </a>
        </li>
        <li class="active">
            <a href="{{url('/dish-price/'.$dish->id)}}" data-toggle="tab" aria-expanded="false">
                <span class="visible-xs"><i class="fa fa-usd"></i></span>
                <span class="hidden-xs">Dish Price</span>
            </a>
        </li>
        <li class="">
            <a href="{{url('/dish-image/'.$dish->id)}}"  aria-expanded="false">
                <span class="visible-xs"><i class="fa fa-photo"></i></span>
                <span class="hidden-xs">Dish Images</span>
            </a>
        </li>
        <li class="">
            <a href="{{url('/dish-recipe/'.$dish->id)}}" aria-expanded="false">
                <span class="visible-xs"><i class="fa fa-photo"></i></span>
                <span class="hidden-xs">Recipe</span>
            </a>
        </li>

    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="home">
            <form class="form-inline" id="dishPriceForm" method="post" action="{{url('/save-dish-price')}}">
                {{csrf_field()}}
                <input type="hidden" value="{{$dish->id}}" id="dishId" name="dish_id">
                <div class="form-group m-r-10">
                    <label>Dish Type </label>
                    <input type="text" name="dish_type" class="form-control"  placeholder="1/3 , 4/5">
                </div>
                <div class="form-group m-r-10">
                    <label>Price </label>
                    <div class="input-group m-t-8">
                        <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                        <input type="number"  name="price" class="form-control" placeholder="..">
                    </div>

                </div>
                <button type="submit"  class="btn btn-default waves-effect waves-light btn-md">
                    Save
                </button>

            </form>
            <hr>
            <ul class="list-unstyled transaction-list">
                @forelse($dish->dishPrices as $dish_price)
                    <li>
                        <i class="ti-download text-success"></i>
                        <span class="text">Dish One - {{$dish_price->dish_type}}</span>
                        <span class="text-success tran-price">$ {{$dish_price->price}}</span>
                        <span class="pull-right">|
                        <a href="{{url('/edit-dish-price/'.$dish_price->id)}}" class="btn btn-link"><i class="fa fa-pencil"></i></a>
                        <a href="#" class="btn btn-link text-danger"><i class="fa fa-trash-o"></i></a>
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

@section('extra-js')
    <script type="text/javascript" src="{{url('/dashboard/plugins/isotope/js/isotope.pkgd.min.js')}}"></script>
    <script type="text/javascript" src="{{url('/dashboard/plugins/magnific-popup/js/jquery.magnific-popup.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.image-popup').magnificPopup({
                type: 'image',
                closeOnContentClick: true,
                mainClass: 'mfp-fade',
                gallery: {
                    enabled: true,
                    navigateByImgClick: true,
                    preload: [0,1]
                }
            });
        });
    </script>
@endsection
