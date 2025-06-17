@extends('layouts.app')

@section('title')
    Add Units
@endsection

@section('content')
    {{--Page header--}}
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/add-product-type')}}" class="btn btn-default waves-effect">Add Product Types <span class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Product Types</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Settings
                </li>
                <li class="active">
                    All Product Type
                </li>
            </ol>
        </div>
    </div>

    <div class="card-box">
        <table id="datatable-responsive"
               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%">
            <thead>
            <tr>
                <th>#</th>
                <th>Product Type</th>
                <th>Added by</th>
                <th>Status</th>
                <th width="80px">Action</th>
            </tr>
            </thead>
            <?php $count = 1; ?>
            <tbody>
            @foreach($product_types as $product_type)
                <tr>
                    <td>{{$count++}} .</td>
                    <td>
                        {{$product_type->product_type}}
                    </td>
                    <td>
                        {{$product_type->user->name}}
                    </td>
                    <td>
                        @if($product_type->status == 1)
                            Active
                        @else
                            InActive
                        @endif
                    </td>

                    <td>
                        <div style="display: flex; align-items: center; gap: 5px;">
                            <a href="{{url('/edit-product-type/'.$product_type->id)}}" class="btn btn-success waves-effect waves-light">
                                <i class="fa fa-pencil"></i>
                            </a>

                            

                            <form action="{{ route('product-type.delete')}}" method="post" class="deleteform">
                                @csrf
                                <input type="hidden" name="id" value="{{$product_type->id}}">
                                <button type="submit" class="btn btn-danger waves-effect waves-light deletebtn">
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