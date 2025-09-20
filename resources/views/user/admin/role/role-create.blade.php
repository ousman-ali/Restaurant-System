@extends('layouts.app')

@section('title')
    Add Role
@endsection

@section('content')
    {{-- Page header --}}
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{ route('roles.index') }}" class="btn btn-default waves-effect">
                    All Roles <span class="m-l-5"></span>
                </a>
            </div>

            <h4 class="page-title">Create New Role</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li class="active">
                    Roles
                </li>
                <li class="active">
                    Add Role
                </li>
            </ol>
        </div>
    </div>

    {{-- Form --}}
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" role="form"
                              action="{{ route('roles.store') }}"
                              method="POST"
                              data-parsley-validate novalidate>
                            @csrf

                            {{-- Role Name --}}
                            <div class="form-group">
                                <label class="col-md-2 control-label">Role Name :</label>
                                <div class="col-md-8">
                                    <input type="text" name="name" class="form-control"
                                           value="{{ old('name') }}"
                                           placeholder="Role Name"
                                           required maxlength="50">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Permissions --}}
                            <div class="form-group">
                                <label class="col-md-2 control-label">Assign Permissions :</label>
                                <div class="col-md-8">
                                    <div class="row">
                                        @foreach($permissions as $permission)
                                            <div class="col-md-6">
                                                <div class="checkbox checkbox-primary">
                                                    <input type="checkbox"
                                                           id="perm-{{ $permission->id }}"
                                                           name="permissions[]"
                                                           value="{{ $permission->name }}">
                                                    <label for="perm-{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('permissions')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="form-group">
                                <label class="col-md-2 control-label"></label>
                                <div class="col-md-10">
                                    <button type="submit"
                                            class="ladda-button btn btn-purple"
                                            data-style="expand-right">
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
