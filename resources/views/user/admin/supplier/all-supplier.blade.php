@extends('layouts.app')

@section('title')
    All Supplier
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/add-supplier')}}" class="btn btn-default waves-effect">Add Supplier <span
                            class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">All Supplier</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Supplier
                </li>
                <li class="active">
                    All supplier
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
                <th>Supplier Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <th width="120px">Action</th>
            </tr>
            </thead>
            <?php $count = 1; ?>
            <tbody>
            @foreach($suppliers as $supplier)
                <tr>
                    <td>{{$count++}} .</td>
                    <td>{{$supplier->name}}</td>
                    <td>{{$supplier->phone}}</td>
                    <td>{{$supplier->email}}</td>
                    <td>{{$supplier->address}}</td>
                    <td>
                        <div class="btn-group">
                            <a href="{{url('/edit-supplier/'.$supplier->id)}}" class="btn btn-success waves-effect waves-light">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a href="{{url('/view-supplier/'.$supplier->id)}}" class="btn btn-info waves-effect waves-light">
                                <i class="fa fa-info"></i>
                            </a>
                            <a href="#" class="btn btn-danger waves-effect waves-light">
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