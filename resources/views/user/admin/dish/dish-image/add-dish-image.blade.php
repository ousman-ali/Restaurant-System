@extends('layouts.app')

@section('title')
    Dish Price - {{$dish->dish}}
@endsection

@section('content')
    <link rel="stylesheet" href="{{url('/dashboard/plugins/magnific-popup/css/magnific-popup.css')}}">
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
            <a href="{{url('/edit-dish/'.$dish->id)}}"  aria-expanded="false">
                <span class="visible-xs"><i class="fa fa-cutlery"></i></span>
                <span class="hidden-xs">{{$dish->dish}}</span>
            </a>
        </li>
        <li class="">
            <a href="{{url('/dish-price/'.$dish->id)}}"  aria-expanded="false">
                <span class="visible-xs"><i class="fa fa-usd"></i></span>
                <span class="hidden-xs">Dish Price</span>
            </a>
        </li>
        <li class="active">
            <a href="{{url('/dish-image/'.$dish->id)}}" data-toggle="tab"  aria-expanded="true">
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
            <form class="form-inline" enctype="multipart/form-data" method="post" action="{{url('/save-dish-image')}}" data-parsley-validate novalidate>
                {{csrf_field()}}
                <input type="hidden" value="{{$dish->id}}" id="dishId" name="dish_id">
                <div class="form-group m-r-10">
                    <div id="image-preview">
                        <label for="image-upload" id="image-label">Choose Photo</label>
                        <input type="file" required name="image" id="image-upload"/>
                    </div>
                </div>
                <div class="form-group m-r-10">
                    <label>Title </label>
                    <div class="input-group">
                        <input type="text" required  name="title" class="form-control" placeholder="Image Title">
                    </div>

                </div>
                <button type="submit"  class="btn btn-default waves-effect waves-light btn-md">
                    Save
                </button>

            </form>
            <hr>
            <div class="row port">
                <div class="portfolioContainer">
                    @foreach($dish->dishImages as $image)
                        <div class="col-sm-6 col-lg-3 col-md-4 webdesign illustrator">
                            <div class="gal-detail thumb">
                                <a href="{{url($image->image)}}" class="image-popup" title="{{$image->title}}">
                                    <img src="{{url($image->image)}}" class="thumb-img" alt="work-thumbnail">
                                </a>
                                <h4>{{$image->title}} <a href="#" onclick="$(this).confirmDelete('/delete-dish-image/{{$image->id}}')" class="pull-right text-danger"><i class="fa fa-trash-o"></i> </a> </h4>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>



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
