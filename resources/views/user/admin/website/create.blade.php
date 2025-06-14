@extends('layouts.app')

@section('title')
    Webpage Sections
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/website')}}" class="btn btn-default waves-effect">All Section <span
                        class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Website</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/home')}}">Home</a>
                </li>
                <li class="active">
                    <a href="{{url('/website')}}">Website</a>
                </li>
                <li class="active">
                    Create Section
                </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" role="form" action="{{ route('website.store') }}" method="post"
                              id="addWebSection"
                              data-parsley-validate novalidate>
                            @csrf

                            <div class="form-group">
                                <label class="col-md-2 control-label">Name </label>
                                <div class="col-md-8">
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                           placeholder="Section Name" parsley-trigger="change" maxlength="255" required>
                                    @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Section ID </label>
                                <div class="col-md-8">
                                    <input type="text" name="section_id" class="form-control"
                                           value="{{ old('section_id') }}"
                                           placeholder="Section ID (used for anchors and references)"
                                           parsley-trigger="change" maxlength="255" required>
                                    <span class="help-block"><small>This will be used as HTML ID and for navigation anchors (e.g., "about", "menu", "contact")</small></span>
                                    @error('section_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Order </label>
                                <div class="col-md-8">
                                    <input type="number" name="order" class="form-control" value="{{ old('order') }}"
                                           placeholder="Display Order" parsley-trigger="change">
                                    <span
                                        class="help-block"><small>Lower numbers appear first on the page</small></span>
                                    @error('order')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Show in Navbar </label>
                                <div class="col-md-8">
                                    <div class="checkbox checkbox-primary">
                                        <input id="in_navbar" type="checkbox" name="in_navbar"
                                               value="1" {{ old('in_navbar') ? 'checked' : '' }}>
                                        <label for="in_navbar">Include in navigation menu</label>
                                    </div>
                                    @error('in_navbar')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Status </label>
                                <div class="col-md-8">
                                    <div class="checkbox checkbox-primary">
                                        <input id="is_active" type="checkbox" name="is_active"
                                               value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                        <label for="is_active">Active</label>
                                    </div>
                                    @error('is_active')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label"></label>
                                <div class="col-md-10">
                                    <button type="submit" class="ladda-button btn btn-purple" data-style="expand-right">
                                        Save Section
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
            var addEmployeeForm = $("#addWebSection");
            addEmployeeForm.on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $(this).speedPost('/website', formData, message = {
                    success: {header: 'Success', body: 'Webpage section added successfully'},
                    error: {header: 'Email address already exist', body: 'Email address found'},
                    warning: {header: 'Internal Server Error', body: 'Internal server error'}
                }, addEmployeeForm);
            });

        })
    </script>
@endsection
