@extends('layouts.app')

@section('title')
    All Ready Dish
@endsection

@section('content')
    <div class="row">
        @foreach($dishes as $dish)
        @php
            $stock = 0;
            if ($dish->source_type === 'inhouse') {
                $stock = $dish->producedBatches->sum('ready_quantity');
            } elseif ($dish->source_type === 'supplier') {
                $stock = $dish->purchasedBatches->sum('ready_quantity');
            }
        @endphp
            <div class="col-sm-6 col-lg-4">
                <div class="card-box">
                    <div class="contact-card">
                        <a class="pull-left" href="#">
                            <img class="" src="{{$dish->thumbnail}}" alt="">
                        </a>
                        <div class="member-info">
                            <h4 class="m-t-0 m-b-5 header-title"><b>{{$dish->name}}</b></h4>
                            <p class="text-muted">{{$dish->status == 1 ? 'Active' : 'In-Active'}}</p>
                            @if ($stock <= 0)
                                <span class="label label-danger">Out of Stock</span>
                            @else
                                <h5 class="text-primary">Stock: {{ $stock }}</h5>
                            @endif
                            <h4 class=""><i class="md md-business m-r-10"></i>Order :{{count($dish->orderDish)}}</h4>
                            <div class="contact-action">
                                <a href="{{url('/edit-ready-dish/'.$dish->id)}}" class="btn btn-success btn-sm"><i
                                            class="md md-mode-edit"></i></a>
                                <a href="{{url('/view-ready-dish/'.$dish->id)}}" class="btn btn-info btn-sm"><i
                                            class="md md-announcement"></i></a>
                                <form action="{{ route('ready-dish.delete')}}" method="post" class="deleteform">
                                    @csrf
                                    <input type="hidden" name="dish_id" value="{{$dish->id}}">
                                    <button type="submit" class="btn btn-danger waves-effect waves-light deletebtn">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </form>
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