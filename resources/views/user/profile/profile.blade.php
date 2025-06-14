@extends('layouts.app')

@section('title')
    Profile
@endsection

@section('content')
    <div class="card-box">
        <center>
            <div class="row">
                <img src="{{auth()->user()->image ? auth()->user()->image : 'img_assets/default-thumbnail.jpg'}}"
                     class="img-responsive img-circle" width="250px" alt="">
                <h3>{{auth()->user()->name}}</h3>
                <p>Role : <b> @if(auth()->user()->role ==1) Admin @elseif(auth()->user()->role ==2) Resturant
                    Manager @elseif(auth()->user()->role ==3) Kitchen @else Waiter @endif</b>
                    <br>
                    Registered Science : {{auth()->user()->created_at->format('d-M-Y')}}
                    <br>
                    @if(auth()->user()->role != 1)
                        Phone : {{auth()->user()->employee->phone}} <br>
                        Address : {{auth()->user()->employee->address}}
                    @endif
                </p>
            </div>
        </center>
    </div>

@endsection