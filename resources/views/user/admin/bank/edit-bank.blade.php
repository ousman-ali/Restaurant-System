@extends('layouts.app')

@section('title')
    Edit Banks
@endsection

@section('content')
    {{--Page header--}}
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/all-bank')}}" class="btn btn-default waves-effect">All Bank <span class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Banke ({{$bank->name}})</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Settings
                </li>
                <li class="active">
                    Edit bank
                </li>
            </ol>
        </div>
    </div>
    <div class="card-box">

        <form class="form-horizontal" role="form" action="{{ route('update.bank', $bank->id) }}" method="POST" data-parsley-validate novalidate>
            {{csrf_field()}}
            <input type="hidden" id="bankId" value="{{$bank->id}}">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Name*</label>
                <div class="col-sm-7">
                    <input type="text" required  class="form-control" name="name" value="{{$bank->name}}" placeholder="Bank Name">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Account Number <span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" required id="account_number" class="form-control" value="{{$bank->account_number}}" name="account_number" placeholder="Account Number">
                </div>
            </div>

           

           
            <div class="form-group">
                <label for="" class="col-sm-2 control-label"></label>
                <div class="col-sm-7">
                    <div class="checkbox checkbox-custom">
                        <input id="checkbox11" name="status" type="checkbox" {{$bank->status ==1 ? 'checked' : ''}}>
                        <label for="checkbox11">
                            Active
                        </label>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Update Bank
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
            var id = $("#bankId").val();
            bankForm.on('submit',function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $(this).speedPost('/update-bank/'+id, formData, message = {
                    success: {header: 'Bank saved successfully', body: 'Bank updated successfully'},
                    error: {header: 'Bank address already exist', body: 'Bank address found'},
                    warning: {header: 'Internal Server Error', body: 'Internal server error'}
                });
            });
        });
    </script>
@endsection