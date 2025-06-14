@extends('layouts.app')

@section('title')
    Edit Product Type
@endsection

@section('content')
    {{--Page header--}}
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/all-product-type')}}" class="btn btn-default waves-effect">All Product Types <span class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Edit Product Type</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Settings
                </li>
                <li class="active">
                    Edit Product Type
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
                    <input type="text" value="{{$product_type->product_type}}" required id="unit" class="form-control" name="product_type" placeholder="I.e : Spices, Meet">

                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-md-2 control-label"></label>
                <div class="col-md-8">
                    <div class="checkbox checkbox-primary">
                        <input id="checkbox0" name="status" type="checkbox" {{$product_type->status == 1 ? "checked" : ''}}>
                        <label for="checkbox0">
                            Active
                        </label>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Update Product Type
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
                $(this).speedPost('/update-product-type/{{$product_type->id}}',formData,message = {
                    success: {header: 'Product Type update successfully', body: 'Product Type update successfully'},
                    error: {header: 'Product Type already exist', body: 'Product Type  found'},
                    warning: {header: 'Internal Server Error', body: 'Internal server error'}
                });
            })
        })
    </script>
@endsection