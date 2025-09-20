@extends('layouts.app')

@section('title')
    Edit Role
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{ route('roles.index') }}" class="btn btn-default waves-effect">
                    All Roles <span class="m-l-5"></span>
                </a>
            </div>

            <h4 class="page-title">Edit Role</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li class="active">Roles</li>
                <li class="active">Edit Role</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" role="form"
                              action="{{ route('roles.update', $role->id) }}"
                              method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Role Name --}}
                            <div class="form-group">
                                <label class="col-md-2 control-label">Role Name :</label>
                                <div class="col-md-8">
                                    <input type="text" name="name" class="form-control"
                                           value="{{ old('name', $role->name) }}"
                                           placeholder="Role Name" required>
                                </div>
                            </div>

                            {{-- Permissions --}}
                            <div class="form-group">
                                <label class="col-md-2 control-label">Permissions :</label>
                                <div class="col-md-8">
                                    <div class="row">
                                        @foreach($permissions as $permission)
                                            <div class="col-md-4">
                                                <div class="checkbox checkbox-primary">
                                                    <input type="checkbox"
                                                           id="perm_{{ $permission->id }}"
                                                           name="permissions[]"
                                                           value="{{ $permission->name }}"
                                                           {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                    <label for="perm_{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Status (optional if you want roles active/inactive) --}}
                            {{-- 
                            <div class="form-group">
                                <label class="col-md-2 control-label">Status :</label>
                                <div class="col-md-8">
                                    <div class="checkbox checkbox-primary">
                                        <input id="status" type="checkbox" name="status" {{ $role->active ?? false ? 'checked' : '' }}>
                                        <label for="status">Active</label>
                                    </div>
                                </div>
                            </div>
                            --}}

                            {{-- Submit --}}
                            <div class="form-group">
                                <label class="col-md-2 control-label"></label>
                                <div class="col-md-10">
                                    <button type="submit" class="ladda-button btn btn-purple" data-style="expand-right">
                                        Save Role
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
