@extends('layouts.app')

@section('title')
    Dashboard
@endsection

@section('content')
{{--    Kitchen Dashboard--}}
    @kitchen
    @include('user.kitchen.dashboard')
    @endkitchen

    @waiter
    @include('user.waiter.dashboard')
    @endwaiter

    @admin
    @include('user.admin.dashboard')
    @endadmin

    @manager
    @include('user.admin.dashboard')
    @endmanager

    @barman
    @include('user.barman.dashboard')
    @endbarman

    
    @baker
    @include('user.baker.dashboard')
    @endbaker

    @cashier
    @include('user.cashier.dashboard')
    @endbaker

@endsection
