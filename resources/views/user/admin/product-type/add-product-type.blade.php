@extends('layouts.app')

@section('title')
    Add Units
@endsection

@section('content')
    {{--Page header--}}
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/all-product-type')}}" class="btn btn-default waves-effect">All Product Types <span class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Create Product Type</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Settings
                </li>
                <li class="active">
                    Add Product Type
                </li>
            </ol>
        </div>
    </div>

    <div class="card-box">
        <form class="form-horizontal" role="form" id="unitForm" method="POST" data-parsley-validate novalidate>
            {{csrf_field()}}
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Product Type <span class="text-danger">*</span> </label>
                <div class="col-sm-7">
                    <input type="text" required id="unit" class="form-control" name="product_type" placeholder="I.e : Spices, Meet">

                </div>
            </div>


            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Save Product Type
                    </button>

                </div>
            </div>
        </form>

    </div>

@endsection

@section('extra-js')

    <script>
        $(document).ready(function () {
            $("#unitForm").on('submit',function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $(this).speedPost('/save-product-type',formData,message = {
                    success: {header: 'Product Type saved successfully', body: 'Product Type saved successfully'},
                    error: {header: 'Product Type already exist', body: 'Product Type  found'},
                    warning: {header: 'Internal Server Error', body: 'Internal server error'}
                },$("#unitForm"));
            })
        })
    </script>
@endsection