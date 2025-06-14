@extends('layouts.app')

@section('title')
    New Order
@endsection

@section('content')

    <div id="vueApp">

    </div>

    <script>
        window.componentName = 'pos'; // Set the name of the component here dynamically
        window.editOrderId = {{$order->id}}
    </script>

@endsection
