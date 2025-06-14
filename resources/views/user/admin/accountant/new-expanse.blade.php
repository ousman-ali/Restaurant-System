@extends('layouts.app')

@section('title')
    New Expanse
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/all-table')}}" class="btn btn-default waves-effect">All Expense <span class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">New Expanse </h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li>
                    <a href="#">Accounting</a>
                </li>
                <li class="active">
                    Expanse
                </li>
                <li class="active">
                    New Expanse
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="card-box">

            <form class="form-horizontal" role="form" method="post" id="expanseForm" action="#" data-parsley-validate novalidate>
               {{csrf_field()}}
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-3 control-label">Cause Of Expanse :</label>
                    <div class="col-sm-6">
                        <input type="text" name="title" required class="form-control" id="inputEmail3" placeholder="Cause of expanse">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-3 control-label">Date of Expanse :</label>
                    <div class="col-sm-6">
                        <input type="text" name="date" required class="form-control" placeholder="mm/dd/yyyy" id="datepicker-autoclose">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword4" class="col-sm-3 control-label">Expanse Cost</label>
                    <div class="col-sm-6">
                        <input type="text" name="expanse" required  data-parsley-type="number" class="form-control" id="inputPassword4" placeholder="Cost ">
                    </div>
                </div>

                <div class="form-group m-b-0">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-success waves-effect waves-light">Save now</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('extra-js')
    <link rel="stylesheet" href="{{url('/dashboard/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">
    <script src="{{url('/dashboard/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $("#datepicker-autoclose").datepicker();

            var expanseForm = $("#expanseForm");
            expanseForm.on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $(this).speedPost('/save-expanse', formData, message = {
                    success: {header: 'New Expanse saved successfully', body: 'New Expanse saved successfully'},
                    error: {header: 'Something went wrong', body: 'Something went wrong'},
                    warning: {header: 'Internal Server Error', body: 'Internal server error'}
                },expanseForm);
            });


        })
    </script>
@endsection