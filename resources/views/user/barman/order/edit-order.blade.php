@extends('layouts.app')

@section('title')
    New Order
@endsection

@section('content')

    <div id="vueApp">

    </div>

    <script>
        window.componentName = 'readyPos';
        window.editOrderId = {{$order->id}}
    </script>

@endsection
