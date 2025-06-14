@extends('layouts.app')

@section('title')
    Webpage Sections
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/website/create')}}" class="btn btn-default waves-effect">Add Section <span
                        class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Website</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/home')}}">Home</a>
                </li>
                <li class="active">
                    Website
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
                <th>Name</th>
                <th>Section ID</th>
                <th>Order</th>
                <th>In Navbar</th>
                <th>Status</th>
                <th width="200px">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sections as $index => $section)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $section->name }}</td>
                    <td>{{ $section->section_id }}</td>
                    <td>{{ $section->order }}</td>
                    <td>
                        @if($section->in_navbar)
                            <span class="label label-success">Yes</span>
                        @else
                            <span class="label label-default">No</span>
                        @endif
                    </td>
                    <td>
                        @if($section->is_active)
                            <span class="label label-success">Active</span>
                        @else
                            <span class="label label-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('website.edit', $section->id) }}" class="btn btn-success waves-effect waves-light">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <a href="{{ url('/page-builder', $section->id) }}" class="btn btn-primary waves-effect waves-light">
                                <i class="fa fa-code"></i>
                            </a>

                            <form action="{{ route('website.destroy', $section->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger waves-effect waves-light" onclick="return confirm('Are you sure you want to delete this section?')">
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
        })
    </script>

@endsection
