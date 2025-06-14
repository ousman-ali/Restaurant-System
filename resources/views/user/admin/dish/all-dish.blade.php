@extends('layouts.app')

@section('title')
    All Dish
@endsection

@section('content')
    <div class="row">
        @foreach($dishes as $dish)
            <div class="col-sm-6 col-lg-4">
                <div class="card-box">
                    <div class="contact-card">
                        <a class="pull-left" href="#">
                            <img class="" src="{{$dish->thumbnail}}" alt="">
                        </a>
                        <div class="member-info">
                            <h4 class="m-t-0 m-b-5 header-title"><b>{{$dish->dish}}</b></h4>
                            <p class="text-muted">{{$dish->status == 1 ? 'Active' : 'In-Active'}}</p>
                            <h4 class=""><i class="md md-business m-r-10"></i>Order :{{count($dish->orderDish)}}</h4>
                            <div class="contact-action">
                                <a href="{{url('/edit-dish/'.$dish->id)}}" class="btn btn-success btn-sm"><i
                                            class="md md-mode-edit"></i></a>
                                <a href="{{url('/view-dish/'.$dish->id)}}" class="btn btn-info btn-sm"><i
                                            class="md md-announcement"></i></a>
                                <a href="#" onclick="$(this).confirmDelete('/delete-dish/'+{{$dish->id}})"
                                   class="btn btn-danger btn-sm"><i class="md md-close"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('extra-js')
    <script>
        $(document).ready(function () {

        });
    </script>

@endsection