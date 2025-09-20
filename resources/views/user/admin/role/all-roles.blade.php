@extends('layouts.app')

@section('title')
    All Roles
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{ route('roles.create') }}" class="btn btn-default waves-effect">
                    Add Role
                </a>
            </div>

            <h4 class="page-title">All Roles</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li class="active">
                    Roles
                </li>
                <li class="active">
                    All Roles
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
                    <th>Role Name</th>
                    <th>Permissions</th>
                    <th width="20px">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{{ $loop->iteration }}.</td>
                        <td>
                            <span class="font-weight-bold text-primary">{{ $role->name }}</span>
                        </td>
                        <td>
                            @if($role->permissions->isNotEmpty())
                                <ul class="list-unstyled m-0">
                                    @foreach($role->permissions as $permission)
                                        <li>
                                            <span class="badge bg-info text-white">{{ $permission->name }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="badge bg-secondary">No Permissions</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group-vertical">
                                <a href="{{ route('roles.edit', $role->id) }}" 
                                   class="btn btn-success waves-effect waves-light">
                                    <i class="fa fa-pencil"></i>
                                </a>

                                <form action="{{ route('roles.destroy', $role->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this role?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-danger waves-effect waves-light">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </form>
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
        });
    </script>
@endsection
