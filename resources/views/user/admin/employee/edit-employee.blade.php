@extends('layouts.app')

@section('title')
    Edit Employee
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/all-employee')}}" class="btn btn-default waves-effect">All Employee <span class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">All Employee </h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Employee
                </li>
                <li class="active">
                    Edit Employee
                </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" role="form" action="#" id="addEmployee" method="post"
                              enctype="multipart/form-data" data-parsley-validate novalidate>
                            {{csrf_field()}}
                            <input type="hidden" id="id" value="{{$employee->id}}">
                            <div class="form-group">
                                <label for="" class="col-md-2 control-label">Photo</label>
                                <div class="col md-10">
                                    <div id="image-preview" style="background-image: url({{url($employee->user->image != "" | null ? $employee->user->image : '/img_assets/avater.png')}})">
                                        <label for="image-upload" id="image-label">Choose Photo</label>
                                        <input type="file"  name="thumbnail" id="image-upload"/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Full Name :</label>
                                <div class="col-md-8">
                                    <input type="text" name="name" class="form-control" value="{{$employee->name}}"
                                           placeholder="Employee Name" parsley-trigger="change" maxlength="50" required>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label" for="example-email">Email</label>
                                <div class="col-md-8">
                                    <input type="email" name="email" class="form-control" placeholder="Employee Email"
                                           parsley-trigger="change" maxlength="50" value="{{$employee->email}}" required>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Password</label>
                                <div class="col-md-8">
                                    <input type="password" minlength="5" maxlength="20" name="password" placeholder="Password" class="form-control"
                                           value="" id="pass1">

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Retype Password</label>
                                <div class="col-md-8">
                                    <input type="password" placeholder="Retype Password" class="form-control" value=""
                                           data-parsley-equalto="#pass1">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Employee Type :</label>
                                <div class="col-md-6">
                                    <select name="role" id="" class="form-control" required>
                                        <option value="" >Select One</option>
                                        <option value="2" {{$employee->user->role == 2 ? 'selected' :''}}>Shop Manager</option>
                                        <option value="3" {{$employee->user->role == 3 ? 'selected' :''}}>Kitchen</option>
                                        <option value="4" {{$employee->user->role == 4 ? 'selected' :''}}>Waiter</option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Phone </label>
                                <div class="col-md-8">
                                    <input type="text" maxlength="20" name="phone" placeholder="Phone number" class="form-control"
                                           value="{{$employee->phone}}" data-parsley-type="digits" required>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Address :</label>
                                <div class="col-md-8">
                                    <textarea minlength="10" class="form-control" required name="address"
                                              rows="5">{{$employee->address}}</textarea>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-md-2 control-label"></label>
                                <div class="col-md-8">
                                    <div class="checkbox checkbox-primary">
                                        <input id="checkbox0" name="status" type="checkbox" {{$employee->user->active == 1 ? 'checked' : ''}}>
                                        <label for="checkbox0">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label"></label>
                                <div class="col-md-10">
                                    <button type="submit" class="ladda-button btn btn-purple"  data-style="expand-right">Save Employee

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
            var addEmployeeForm = $("#addEmployee");
            var id = $("#id").val();
            addEmployeeForm.on('submit', function (e) {
                $("#loader").show();
                e.preventDefault();
                var formData = new FormData(this);
                $(this).speedPost('/update-employee/'+id, formData, message = {
                    success: {header: 'Employee Save successfully', body: 'Employee saved'},
                    error: {header: 'Email address already exist', body: 'Email address found'},
                    warning: {header: 'Internal Server Error', body: 'Internal server error'}
                });
            });

        })
    </script>
@endsection