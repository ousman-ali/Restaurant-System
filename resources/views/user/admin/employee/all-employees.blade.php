@extends('layouts.app')

@section('title')
    All Employee
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/add-employee')}}" class="btn btn-default waves-effect">Add Employee <span class="m-l-5"></span></a>
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
                    All Employee
                </li>
            </ol>
        </div>
    </div>
    <div class="card-box table-responsive">
        <table id="datatable-responsive"
               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%">
            <thead>
            <tr>
                <th>#</th>
                <th>Photo</th>
                <th>Info</th>
                <th>Role</th>
                <th width="20px">Action</th>
            </tr>
            </thead>
            <?php $count = 1; ?>
            <tbody>
            @foreach($employees as $employee)
                <tr>
                    <td>{{$count++}} .</td>
                    <td>
                        <img src="{{$employee->user->image != '' | null ? $employee->user->image : url('/img_assets/avater.png')}}" alt="" class="img-responsive" width="100px">
                    </td>
                    <td>
                        <dl class="dl-horizontal m-b-0">
                            <dt>
                                Email :
                            </dt>
                            <dd>
                                {{$employee->email}}
                            </dd>
                            <dt>
                                Phone :
                            </dt>
                            <dd>
                                {{$employee->phone}}
                            </dd>
                            <dt>
                                Address :
                            </dt>
                            <dd>
                                {{$employee->address}}
                            </dd>
                            <dt>
                                Created at :
                            </dt>
                            <dd>
                                {{$employee->created_at->diffForHumans()}}
                            </dd>



                        </dl>

                    </td>
                    <td>
                        <dl class="m-b-0">
                            <dt>
                                Role
                            </dt>
                            <dd>
                                @if($employee->user->role == 2)
                                    <span class="label label-primary">Manager</span>
                                @elseif($employee->user->role == 3)
                                    <span class="label label-purple">Kitchen</span>
                                @elseif($employee->user->role == 4)
                                    <span class="label label-pink">Waiter</span>
                                @endif
                            </dd>
                            <dt>
                                Status
                            </dt>
                            <dd>
                                @if($employee->user->active == 1)
                                    <span class="label label-primary">Active</span>
                                @else
                                    <span class="label label-danger">InActive</span>
                                @endif
                            </dd>

                        </dl>

                    </td>
                    <td>
                        <div class="btn-group-vertical">
                            <a href="{{url('/edit-employee/'.$employee->id)}}" class="btn btn-success waves-effect waves-light">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <a href="#" onclick="$(this).confirmDelete('/delete-employee/{{$employee->id}}')" class="btn btn-danger waves-effect waves-light">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

@endsection

@section('extra-js')
    <script>
        $(document).ready(function () {
            $("#datatable-responsive").DataTable();
        })
    </script>

@endsection