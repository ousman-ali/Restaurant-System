@extends('layouts.app')

@section('title')
    Add Table
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/all-table')}}" class="btn btn-default waves-effect">All Table <span class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Add Table </h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Table Management
                </li>
                <li class="active">
                    Add table
                </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" role="form" action="#" id="addTable" method="post"
                              enctype="multipart/form-data" data-parsley-validate novalidate>
                            {{csrf_field()}}

                            <div class="form-group">
                                <label class="col-md-2 control-label">Table :</label>
                                <div class="col-md-8">
                                    <input type="text" name="table_no" class="form-control" value=""
                                           placeholder="Table No / Table Name" parsley-trigger="change" maxlength="50" required>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Capacity :</label>
                                <div class="col-md-8">
                                    <input type="number" min="1" name="table_capacity" class="form-control" value=""
                                           placeholder="Table capacity" parsley-trigger="change" maxlength="50" required>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label"></label>
                                <div class="col-md-10">
                                    <button type="submit" class="ladda-button btn btn-purple" data-style="expand-right">Save Table

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
        $(document).ready(function () {
            $("#addTable").on('submit',function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                var addTableForm = $("#addTable");
                $(this).speedPost('/save-table', formData, message = {
                    success: {header: 'Table Saved successfully', body: 'Table Saved successfully'},
                    error: {header: 'Table already exist', body: 'Table found'},
                    warning: {header: 'Internal Server Error', body: 'Internal server error'}
                },addTableForm);
            })
        })
    </script>

@endsection