@extends('layouts.app')

@section('title')
    Add Item
@endsection

@section('content')
    {{--Page header--}}
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/all-item')}}" class="btn btn-default waves-effect">All Product <span class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Add Product </h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Stock Management
                </li>
                <li class="active">
                    Add Product
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" role="form" action="#" id="addItem" method="post"
                              enctype="multipart/form-data" data-parsley-validate novalidate>
                            {{csrf_field()}}

                            <div class="form-group">
                                <label for="" class="col-md-2 control-label">Thumbnail</label>
                                <div class="col md-10">
                                    <div id="image-preview">
                                        <label for="image-upload" id="image-label">Choose Photo</label>
                                        <input type="file" name="thumbnail" id="image-upload"/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="example-email">Product name :</label>
                                <div class="col-md-8">
                                    <input type="text" name="product_name" class="form-control" placeholder="Product name"
                                           parsley-trigger="change" maxlength="50" required>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="example-email">Product Category :</label>
                                <div class="col-md-8">
                                    <select name="product_type_id" id="" class="form-control">
                                        <option value="">Select One</option>
                                        @foreach($product_type as $type)
                                            <option value="{{$type->id}}">{{$type->product_type}}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="example-email">Unit :</label>
                                <div class="col-md-8">
                                    <select name="unit_id" id="" class="form-control">
                                        <option value="">Select One</option>
                                        @foreach($units as $unit)
                                            <option value="{{$unit->id}}">{{$unit->unit}}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-2 control-label"></label>
                                <div class="col-md-10">
                                    <button type="submit" class="ladda-button btn btn-purple" data-style="expand-right">Save Item

                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-js')

    <script>
        $(document).ready(function (e) {
            var addItemForm = $("#addItem");
            addItemForm.on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $(this).speedPost('/save-item', formData, message = {
                    success: {header: 'Item saved successfully', body: 'Item saved successfully'},
                    error: {header: 'Item already exist', body: 'Item found'},
                    warning: {header: 'Internal Server Error', body: 'Internal server error'}
                },addItemForm);
            });

        });
    </script>
@endsection