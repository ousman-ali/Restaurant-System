@extends('layouts.app')

@section('title')
    Add Employee
@endsection

@section('content')
    {{--Page header--}}
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/all-employee')}}" class="btn btn-default waves-effect">All Employee <span class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Create New Employee </h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Employee
                </li>
                <li class="active">
                    Add Employee
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" role="form" action="{{ route('save.employee') }}"  method="post"
                              enctype="multipart/form-data" data-parsley-validate novalidate>
                            {{csrf_field()}}

                            <div class="form-group">
                                <label for="" class="col-md-2 control-label">Photo</label>
                                <div class="col md-10">
                                    <div id="image-preview">
                                        <label for="image-upload" id="image-label">Choose Photo</label>
                                        <input type="file" name="thumbnail" id="image-upload"/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Full Name :</label>
                                <div class="col-md-8">
                                    <input type="text" name="name" class="form-control" value=""
                                           placeholder="Employee Name" parsley-trigger="change" maxlength="50" required>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label" for="example-email">Email</label>
                                <div class="col-md-8">
                                    <input type="email" name="email" class="form-control" placeholder="Employee Email"
                                           parsley-trigger="change" maxlength="50" required>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Password</label>
                                <div class="col-md-8">
                                    <input type="password" minlength="5" maxlength="20" name="password" placeholder="Password" class="form-control"
                                           value="" id="pass1" required>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Retype Password</label>
                                <div class="col-md-8">
                                    <input type="password" placeholder="Retype Password" class="form-control" value=""
                                           data-parsley-equalto="#pass1" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Role :</label>
                                <div class="col-md-6">
                                    <select name="role" class="form-control select2" required>
                                        <option value="">Select One</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Has Restaurant Type?</label>
                                <div class="col-md-6">
                                    <input type="checkbox" id="has_rest_type" name="has_rest_type" value="1">
                                </div>
                            </div>

                            <div class="form-group" id="rest_type_group" style="display: none;">
                                <label class="col-md-2 control-label">Restaurant Type :</label>
                                <div class="col-md-6">
                                    <select name="rest_type" class="form-control select2">
                                        <option value="">Select One</option>
                                        <option value="restaurant">Restaurant</option>
                                        <option value="cafe">Cafe</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Phone </label>
                                <div class="col-md-8">
                                    <input type="text" maxlength="20" name="phone" placeholder="Phone number" class="form-control"
                                           value="" data-parsley-type="digits" required>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Address :</label>
                                <div class="col-md-8">
                                    <textarea  class="form-control" required name="address"
                                              rows="5"></textarea>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label"></label>
                                <div class="col-md-10">
                                    <button type="submit" class="ladda-button btn btn-purple" data-style="expand-right">Save Employee

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
        // Show/hide restaurant type based on checkbox
        $('#has_rest_type').change(function () {
            if ($(this).is(':checked')) {
                $('#rest_type_group').show();
            } else {
                $('#rest_type_group').hide();
                $('select[name="rest_type"]').val("");
            }
        });
    });
</script>
@endsection