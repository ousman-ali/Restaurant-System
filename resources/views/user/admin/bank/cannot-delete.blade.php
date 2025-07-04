@extends('layouts.app')

@section('title')
    Cannot delete Unit
@endsection

@section('content')
    <div class="card-box">
        <center>
            <h1 class="text-danger" style="font-size: 75px;"><i class="fa fa-info"></i></h1>
            <p> <span class="text-danger" style="font-size: 25px">Cannot delete <b>{{$unit->unit}}</b></span>
                <br>
                Coz, {{$unit->unit}} use in Product table. you have to delete products first!
            </p>
            <a href="{{url('/all-unit')}}" class="btn btn-success">Back to all unit</a>
        </center>

    </div>
@endsection