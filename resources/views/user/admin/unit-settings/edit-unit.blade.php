@extends('layouts.app')

@section('title')
    Edit Units
@endsection

@section('content')
    {{--Page header--}}
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/all-unit')}}" class="btn btn-default waves-effect">All Unit <span class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Unite ({{$unit->unit}})</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Settings
                </li>
                <li class="active">
                    Edit unit
                </li>
            </ol>
        </div>
    </div>
    <div class="card-box">

        <form class="form-horizontal" role="form" id="unitForm" method="POST" data-parsley-validate novalidate>
            {{csrf_field()}}
            <input type="hidden" id="unitId" value="{{$unit->id}}">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Unit*</label>
                <div class="col-sm-7">
                    <input type="text" required  class="form-control" name="unit" value="{{$unit->unit}}" placeholder="Unit Name">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Child Unit <span class="text-danger">*</span></label>
                <div class="col-sm-7">
                    <input type="text" required id="child_unit" class="form-control" value="{{$unit->child_unit}}" name="child_unit" placeholder="Unit Name">
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Unit Convert <span class="text-danger">*</span> </label>
                <label for="" class="col-sm-1 control-label">1 <span id="print_unit">{{$unit->unit}}</span> = </label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="convert_rate" value="{{$unit->convert_rate}}" required data-parsley-type="number">
                </div>
                <label for="" class=" control-label" id="print_child_unit">{{$unit->child_unit}}</label>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label"></label>
                <div class="col-sm-7">
                    <div class="checkbox checkbox-custom">
                        <input id="checkbox11" name="status" type="checkbox" {{$unit->status ==1 ? 'checked' : ''}}>
                        <label for="checkbox11">
                            Active
                        </label>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Update Unit
                    </button>

                </div>
            </div>
        </form>

    </div>
@endsection

@section('extra-js')
    <script>
        $(document).ready(function () {
            var unitForm = $("#unitForm");
            var id = $("#unitId").val();
            unitForm.on('submit',function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $(this).speedPost('/update-unit/'+id, formData, message = {
                    success: {header: 'Unit saved successfully', body: 'Unit updated successfully'},
                    error: {header: 'Unit address already exist', body: 'Unit address found'},
                    warning: {header: 'Internal Server Error', body: 'Internal server error'}
                });
            });
        });
    </script>
@endsection