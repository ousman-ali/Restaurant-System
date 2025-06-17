@extends('layouts.app')

@section('title')
    All Dish Types
@endsection

@section('content')
    <div class="card-box">
        <div class="pull-right"><a href="{{url('/add-dish-type')}}" class="btn btn-success">Add Dish Type</a></div>
        <h3 class="m-t-0 "><b>Dish Types</b></h3>
        <p>sdjlk adfasdf</p>
        <table id="datatable-responsive"
               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%">
            <thead>
            <tr>
                <th>#</th>
                <th>Unit</th>
                <th>Info</th>
                <th width="80px">Action</th>
            </tr>
            </thead>
            <?php $count = 1; ?>
            <tbody>
            @foreach($dish_types as $dish)
                <tr>
                    <td>{{$count++}} .</td>
                    <td>
                        {{$dish->name}}
                    </td>
                    <td>
                        @if($dish->status == 1)
                            Active
                        @else
                            InActive
                        @endif
                    </td>

                    <td>
                        <div style="display: flex; align-items: center; gap: 5px;">
                            <a href="{{ url('/edit-dish-type/' . $dish->id) }}"
                            class="btn btn-success btn-sm waves-effect waves-light">
                                <i class="fa fa-pencil"></i>
                            </a>

                            <form action="{{ route('dish-type.delete') }}" method="post" class="deleteform" style="margin: 0;">
                                @csrf
                                <input type="hidden" name="dish_type_id" value="{{ $dish->id }}">
                                <button type="submit" class="btn btn-danger btn-sm waves-effect waves-light deletebtn">
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
