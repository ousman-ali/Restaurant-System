@extends('layouts.app')

@section('title')
    All Banks
@endsection

@section('content')
    {{--Page header--}}
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/add-bank')}}" class="btn btn-default waves-effect">Add Bank <span class="m-l-5"><i class="fa fa-plus"></i></span></a>
            </div>

            <h4 class="page-title">Banks</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Settings
                </li>
                <li class="active">
                    Bank Settings
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
                <th>Bank</th>
                <th>Account</th>
                <th width="80px">Action</th>
            </tr>
            </thead>
            <?php $count = 1; ?>
            <tbody>
            @foreach($banks as $bank)
                <tr>
                    <td>{{$count++}} .</td>
                    <td>
                        {{$bank->name}}
                    </td>
                    <td>
                        {{$bank->account_number}}
                    </td>
                   

                    <td>
                        <div style="display: flex; align-items: center; gap: 5px;">
                            <a href="{{url('/edit-bank/'.$bank->id)}}" class="btn btn-success waves-effect waves-light">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <form action="{{ route('bank.delete')}}" method="post" class="deleteform">
                                @csrf
                                <input type="hidden" name="id" value="{{$bank->id}}">
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
        })
    </script>

@endsection