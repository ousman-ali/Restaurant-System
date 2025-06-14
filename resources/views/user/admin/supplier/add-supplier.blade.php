@extends('layouts.app')

@section('title')
    Add Supplier
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/all-supplier')}}" class="btn btn-default waves-effect">All Supplier <span
                            class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Create new supplier</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Supplier
                </li>
                <li class="active">
                    Add supplier
                </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" role="form" action="#" id="addSupplier" method="post"
                              enctype="multipart/form-data" data-parsley-validate novalidate>
                            {{csrf_field()}}



                            <div class="form-group">
                                <label class="col-md-2 control-label">Name </label>
                                <div class="col-md-8">
                                    <input type="text" name="name" class="form-control" value=""
                                           placeholder="Supplier Name" parsley-trigger="change" maxlength="50" required>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label" for="example-email">Email </label>
                                <div class="col-md-8">
                                    <input type="email" name="email" class="form-control" placeholder="Supplier Email Address"
                                           parsley-trigger="change" maxlength="50">

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="example-email">Phone </label>
                                <div class="col-md-8">
                                    <input type="text" name="phone" class="form-control" placeholder="Supplier Phone"
                                           parsley-trigger="change" maxlength="50" required>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="example-email">Address </label>
                                <div class="col-md-8">
                                    <textarea name="address" minlength="10" cols="20" rows="5" class="form-control" placeholder="Supplier Address" required></textarea>

                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-2 control-label"></label>
                                <div class="col-md-10">
                                    <button type="submit" class="ladda-button btn btn-purple" data-style="expand-right">Save Supplier

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
            var addSupplier = $("#addSupplier");
            addSupplier.on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $(this).speedPost('/save-supplier', formData, message = {
                    success: {header: 'Supplier saved successfully', body: 'Supplier saved successfully'},
                    error: {header: 'Supplier already exist', body: 'Supplier found'},
                    warning: {header: 'Internal Server Error', body: 'Internal server error'}
                },addSupplier);
            });
        });
    </script>

@endsection