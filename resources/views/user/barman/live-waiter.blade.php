@extends('layouts.app')

@section('title')
    Live Waiter
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <button id="refresh" type="button" class="btn btn-default waves-effect" data-toggle="dropdown" aria-expanded="false">Refresh <span class="m-l-5"><i class="fa fa-cog"></i></span></button>
            </div>

            <h4 class="page-title">{{config('app.name')}}</h4>
            <ol class="breadcrumb">
                <li><a href="{{url('/')}}">Dashboard</a></li>
                <li><a href="{{url('/waiter-status')}}">Waiter Status</a></li>
            </ol>
        </div>
    </div>

    <div class="row" id="waiterHtml">

    </div>

@endsection

@section('extra-js')

    <style>
        .dish-details{
            width: 100%;
            height: 200px;
            overflow-y: scroll;
        }

    </style>

    <script>

        var waiterOrders = [];

        $(document).ready(function () {
            $("#refresh").on('click',function () {
                $.get('baker-status-waiter-json', function (data) {
                    waiterOrders = data.waiterOrders;
                    $("#waiterHtml").empty();
                    $(this).renderWaiterOrders(waiterOrders, "#waiterHtml");
                });
            });


            $.get('baker-status-waiter-json', function (data) {
                waiterOrders = data.waiterOrders;
                $("#waiterHtml").empty();
                $(this).renderWaiterOrders(waiterOrders, "#waiterHtml");
            });

            

             var submitNewInhouseOrder = pusher.subscribe('inhouse-order-submit');
            submitNewInhouseOrder.bind('inhouse-order-submit-event', function (data) {
                $.get("/baker-status-waiter-json", function (data) {
                    waiterOrders = data.waiterOrders;
                    $("#waiterHtml").empty();
                    $(this).renderWaiterOrders(waiterOrders, "#waiterHtml");
                });
            });

            var dishServed = pusher.subscribe('order-served');
            dishServed.bind('order-served-event', function (data) {
                $.get("/baker-status-waiter-json", function (data) {
                    waiterOrders = data.waiterOrders;
                    $("#waiterHtml").empty();
                    $(this).renderWaiterOrders(waiterOrders, "#waiterHtml");
                });
            });


            $.fn.serveReadyOrder = function (index) {
                const laddaBtn = Ladda.create(this[0]); 
                laddaBtn.start();
                $.get('/barman-ready-order-served/'+waiterOrders[index].id, function (data) {
                    waiterOrders.splice(index,1);
                    $("#waiterHtml").empty();
                    $(this).renderWaiterOrders(waiterOrders, "#waiterHtml");
                });
                setTimeout(() => laddaBtn.stop(), 4000);
            };

            $.fn.renderWaiterOrders = function (data) {
                $.each(data, function (index, dish) {
                    $("#waiterHtml").append(
                        $("<div>", {class: "col-lg-6"}).append(
                            $("<div>", {class: dish.status == 0 ? "panel panel-color panel-warning" : "panel panel-color panel-custom",
                                style: "height: 430px;"
                            }).append(
                                $("<div>", {class: "panel-heading"}).append(
                                    $("<h3>", {
                                        class: "panel-title",
                                        text: "Waiter Ready Dish Order"
                                    }).append(
                                        $("<span>", {class: "pull-right", text: dish.serve_by?.name})
                                    )
                                ),
                                $("<div>", { class: "panel-body dish-details" }).append(
                                    $("<ul>", { class: 'list-group' }).append(
                                        $.map(dish.order_details, function (orderDetail) {
                                            const isDish = orderDetail.dish !== null;
                                            const isReadyDish = orderDetail.ready_dish !== null;

                                            const dishName = isDish
                                                ? orderDetail.dish?.dish
                                                : isReadyDish
                                                    ? orderDetail.ready_dish?.name
                                                    : 'Unknown Dish';

                                            const dishType = isDish
                                                ? orderDetail.dish_type?.dish_type
                                                : null;

                                            const addtionalNote = orderDetail.additional_note ?? '';

                                            // Create the main list item
                                            const $li = $("<li>", { class: "list-group-item" });

                                            // Add dish name
                                            $li.append($("<span>").text(dishName));

                                            // Add dish type badge
                                            if (dishType) {
                                                $li.append($("<span>", {
                                                    class: "badge badge-success ml-2",
                                                    text: dishType,
                                                }));
                                            }

                                            // Add quantity badge
                                            $li.append($("<span>", {
                                                class: "badge badge-primary ml-2",
                                                text: "Qty: " + orderDetail.quantity,
                                            }));

                                            // Add table badge if exists
                                            if (dish.table && dish.table.table_no) {
                                                $li.append($("<span>", {
                                                    class: "badge badge-primary ml-2",
                                                    text: "Table: " + dish.table.table_no,
                                                }));
                                            }

                                            // Add additional note under the dish name (styled small and muted)
                                            if (addtionalNote) {
                                                $li.append($("<div>", {
                                                    class: "text-muted mt-1 small",
                                                    text: "Note: " + addtionalNote,
                                                }));
                                            }

                                            return $li;
                                        })
                                    )
                                ),


                                $("<div>", {class: "panel-body order-info"}).append(
                                    $("<p>", {text: "Order Time: "}).append(
                                        $("<span>", {class: "badge badge-info", text: new Date(dish.created_at).toLocaleString("en-US", {
                                            weekday: "long",
                                            year: "numeric",
                                            month: "long",
                                            day: "numeric",
                                            hour: "2-digit",
                                            minute: "2-digit",
                                            second: "2-digit",
                                            hour12: true 
                                        })})
                                    ),
                                    dish.serve_time ? $("<p>", {text: "Accept Time: "}).append(
                                        $("<span>", {class: "badge badge-primary", text: new Date(dish.serve_time).toLocaleString("en-US", {
                                            weekday: "long",
                                            year: "numeric",
                                            month: "long",
                                            day: "numeric",
                                            hour: "2-digit",
                                            minute: "2-digit",
                                            second: "2-digit",
                                            hour12: true 
                                        })})
                                    ) : ""
                                ),
                            
                                (dish.status == 0)
                                    ? $("<button>", {
                                    class: "btn btn-block btn-lg btn-primary waves-effect waves-light ladda-button",
                                    "data-style": "expand-right",
                                    "data-spinner-color": "#fff",
                                    text: "Serve Order",
                                    onClick:"$(this).serveReadyOrder("+ index +")"
                                }): "Oops"
                            )
                        )
                    )
                })
            }



        })
    </script>
@endsection