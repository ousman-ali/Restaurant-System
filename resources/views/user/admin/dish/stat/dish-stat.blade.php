@extends('layouts.app')

@section('title')
    Dish Report
@endsection

@section('content')
    <link rel="stylesheet" href="{{url('/dashboard/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">
    <div class="card-box">
        <form class="form-horizontal" role="form" method="post" action="{{url('/dish-stat-post')}}" id="formMe" data-parsley-validate novalidate>
            {{csrf_field()}}
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Dish</label>
                <div class="col-sm-7">
                    <select name="kitchen" id="" class="form-control">
                        <option value="0">All</option>
                        @foreach($dishes as $dish)
                            <option value="{{$dish->id}}">{{$dish->dish}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="hori-pass1" class="col-sm-2 control-label">Date Range</label>
                <div class="col-sm-7">
                    <div class="input-daterange input-group" id="date-range">
                        <input type="text" class="form-control" name="start" />
                        <span class="input-group-addon bg-custom b-0 text-white">to</span>
                        <input type="text" class="form-control" name="end" />
                    </div>
                </div>
            </div>
            <div class="form-group  m-b-0">
                <button class="col-md-offset-2 btn btn-primary waves-effect waves-light" type="submit">
                    Submit
                </button>
                <button type="reset" class="btn btn-default waves-effect waves-light m-l-5">
                    Cancel
                </button>
            </div>
        </form>
    </div>
@endsection

@section('extra-js')
    <script src="{{url('/dashboard/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            jQuery('#date-range').datepicker({
                toggleActive: true,
                format : "yyyy-mm-dd"
            });
        })
    </script>
@endsection