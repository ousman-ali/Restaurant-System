@extends('layouts.app')

@section('title')
    Add Banks
@endsection

@section('content')
    {{--Page header--}}
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/all-bank')}}" class="btn btn-default waves-effect">All Banks <span class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Banks</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Settings
                </li>
                <li class="active">
                    Add Bank
                </li>
            </ol>
        </div>
    </div>

    <div class="card-box">
        <h4 class="m-t-0 header-title"><b>New Bank</b></h4>
       
        <form class="form-horizontal" role="form" id="bankForm" method="POST" data-parsley-validate novalidate>
            {{csrf_field()}}
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Bank <span class="text-danger">*</span> </label>
                <div class="col-sm-7">
                    <input type="text" required id="name" class="form-control" name="name" placeholder="Bank Name">
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Account Number <span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" required id="account_number" class="form-control" name="account_number" placeholder="Account Number">
                </div>
            </div>

            

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Save Bank
                    </button>

                </div>
            </div>
        </form>

    </div>
@endsection

@section('extra-js')
    <script>
        $(document).ready(function () {

            

            var bankForm = $("#bankForm");
            bankForm.on('submit',function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $(this).speedPost('/save-bank', formData, message = {
                    success: {header: 'Bank saved successfully', body: 'Bank updated successfully'},
                    error: {header: 'Bank address already exist', body: 'Bank address found'},
                    warning: {header: 'Internal Server Error', body: 'Internal server error'}
                },bankForm);
            });
        });
    </script>
@endsection