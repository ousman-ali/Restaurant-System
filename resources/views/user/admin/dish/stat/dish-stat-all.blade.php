@extends('layouts.app')

@section('title')
    Dish Report
@endsection

@section('content')
    <link rel="stylesheet" href="{{url('/dashboard/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">
    <div class="card-box">
        <form class="form-horizontal" role="form" method="post" action="{{url('/dish-stat-post')}}" id="formMe"
              data-parsley-validate novalidate>
            {{csrf_field()}}
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Dish</label>
                <div class="col-sm-7">
                    <select name="kitchen" id="" class="form-control">
                        <option value="0">All</option>
                        @foreach($dishes as $dish)
                            <option value="{{$dish->id}}">{{$dish->dish}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="hori-pass1" class="col-sm-2 control-label">Date Range</label>
                <div class="col-sm-7">
                    <div class="input-daterange input-group" id="date-range">
                        <input type="text" class="form-control" name="start"/>
                        <span class="input-group-addon bg-custom b-0 text-white">to</span>
                        <input type="text" class="form-control" name="end"/>
                    </div>
                </div>
            </div>
            <div class="form-group  m-b-0">
                <button class="col-md-offset-2 btn btn-primary waves-effect waves-light" type="submit">
                    Submit
                </button>
                <button type="reset" class="btn btn-default waves-effect waves-light m-l-5">
                    Cancel
                </button>
            </div>
        </form>
        <hr>
        <center>
            <h4 class="text-dark header-title m-t-0">Kitchen Stat</h4>
            <p>Date Range : {{$start}} to {{$end}}</p>
        </center>
        <div id="dishGraph" style="height: 303px;">

        </div>
        <hr>
        @foreach($dish_query as $dish)
            {{--<h4 class="pull-right">{{count($orders)}}   Order</h4>--}}
            <h4>{{$dish->dish}}</h4>
            <br>
            <table id="datatable-responsive"
                   class="table table-striped table-bordered dt-responsive nowrap datatable-responsive" cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th>Order No</th>
                    <th>Quantity</th>
                    <th>Net Price</th>
                    <th>Gross Price</th>
                    <th>Date(d-m-y)</th>
                </tr>
                </thead>
                @foreach($dish->orderDish as $order_dish)
                    @if($order_dish->dish_id == $dish->id)
                        <tbody>
                        <tr>
                            <td> {{str_pad($order_dish->order_id,4,0,STR_PAD_LEFT)}}</td>
                            <td>{{$order_dish->quantity}}</td>
                            <td>{{$order_dish->net_price}}</td>
                            <td>{{$order_dish->gross_price}}</td>
                            <td>{{$order_dish->created_at->format('d-M-Y')}}</td>
                        </tr>
                        </tbody>
                    @endif
                @endforeach
            </table>
        @endforeach
    </div>

@endsection

@section('extra-js')
    <script src="{{url('/dashboard/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    {{--Datatable plugins--}}
    <script src="{{url('/dashboard/plugins/datatables/buttons.html5.min.js')}}"></script>
    <script src="{{url('/dashboard/plugins/datatables/buttons.print.min.js')}}"></script>
    <script src="{{url('/dashboard/plugins/datatables/dataTables.buttons.min.js')}}"></script>
    <script src="{{url('/dashboard/plugins/datatables/jszip.min.js')}}"></script>
    <script src="{{url('/dashboard/plugins/datatables/pdfmake.min.js')}}"></script>

    {{--Chart--}}
    <script src="{{url('/dashboard/plugins/raphael/raphael-min.js')}}"></script>
    <script src="{{url('/dashboard/plugins/morris/morris.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            jQuery('#date-range').datepicker({
                toggleActive: true,
                format: "yyyy-mm-dd"
            });

            $(".datatable-responsive").DataTable({
                order: [0, 'desc'],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'pdf', 'print'
                ],
            });

            new Morris.Bar({
                // ID of the element in which to draw the chart.
                element: 'dishGraph',
                // Chart data records -- each entry in this array corresponds to a point on
                // the chart.
                data: [
                        @foreach($dish_query as $dish)
                    {
                        year: '{{$dish->dish}}' + '',
                        value: {{count(\App\Models\OrderDetails::where('dish_id',$dish->id)->whereBetween('created_at',array($start,$end))->get())}}
                    },
                    @endforeach
                ],
                goalLineColors: ['red', 'green', 'blue'],
                // The name of the data record attribute that contains x-values.
                xkey: 'year',
                // A list of names of data record attributes that contain y-values.
                ykeys: ['value'],
                // Labels for the ykeys -- will be displayed when you hover over the
                // chart.
                labels: ['Total Order'],
                barColors: ['orangered'],
                gridTextColor: '#000',
                gridTextSize: '15px',
                resize: true
            });
        })
    </script>
@endsection
