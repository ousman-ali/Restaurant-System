@extends('layouts.app')

@section('title')
    Add Units
@endsection

@section('content')
    {{--Page header--}}
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/all-unit')}}" class="btn btn-default waves-effect">All Unit <span class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Unites</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Settings
                </li>
                <li class="active">
                    Add unit
                </li>
            </ol>
        </div>
    </div>

    <div class="card-box">
        <h4 class="m-t-0 header-title"><b>New Unit</b></h4>
        <p class="text-muted font-13 m-b-30">
            Responsive is an extension for DataTables that resolves that problem by optimising the
        </p>
        <form class="form-horizontal" role="form" id="unitForm" method="POST" data-parsley-validate novalidate>
            {{csrf_field()}}
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Unit <span class="text-danger">*</span> </label>
                <div class="col-sm-7">
                    <input type="text" required id="unit" class="form-control" name="unit" placeholder="Unit Name">
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Child Unit <span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" required id="child_unit" class="form-control" name="child_unit" placeholder="Unit Name">
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Unit Convert <span class="text-danger">*</span> </label>
                <label for="" class="col-sm-1 control-label">1 <span id="print_unit">Unit</span> = </label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="convert_rate" required data-parsley-type="number">
                </div>
                <label for="" class=" control-label" id="print_child_unit"> Child Unit </label>
            </div>



            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Save Unit
                    </button>

                </div>
            </div>
        </form>

    </div>
@endsection

@section('extra-js')
    <script>
        $(document).ready(function () {

            $("#unit").on('keyup',function (e) {
                $("#print_unit").html($("#unit").val());
            });

            $("#child_unit").on('keyup',function (e) {
                $("#print_child_unit").html($("#child_unit").val());
            });

            var unitForm = $("#unitForm");
            unitForm.on('submit',function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $(this).speedPost('/save-unit', formData, message = {
                    success: {header: 'Unit saved successfully', body: 'Unit updated successfully'},
                    error: {header: 'Unit address already exist', body: 'Unit address found'},
                    warning: {header: 'Internal Server Error', body: 'Internal server error'}
                },unitForm);
            });
        });
    </script>
@endsection