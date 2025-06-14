@extends('layouts.app')

@section('title')
    Add Table
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/add-table')}}" class="btn btn-default waves-effect">Add Table <span class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Tables </h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Table Management
                </li>
                <li class="active">
                    All table
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
                <th>Table No</th>
                <th>Capacity</th>
                <th>Status</th>
                <th width="100px">Action</th>
            </tr>
            </thead>
            <?php $count = 1; ?>
            <tbody>
            @foreach($tables as $table)
                <tr>
                    <td>{{$count++}} .</td>
                    <td>{{$table->table_no}}</td>
                    <td>{{$table->table_capacity}}</td>
                    <td>
                        @if($table->status == 1)
                            Active
                            @else
                        In-Active
                            @endif
                    </td>

                    <td>
                        <div class="btn-group">
                            <a href="{{url('/edit-table/'.$table->id)}}" class="btn btn-success waves-effect waves-light">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a href="#" onclick="$(this).confirmDelete('/delete-table/'+{{$table->id}})" class="btn btn-danger waves-effect waves-light">
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