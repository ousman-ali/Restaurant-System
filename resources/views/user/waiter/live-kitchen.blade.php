@extends('layouts.app')

@section('title')
    Live Kitchen
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
                <li><a href="{{url('/kitchen-status')}}">Kitchen Status</a></li>
            </ol>
        </div>
    </div>
    <div class="row" id="renderHtmlHear">

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

        var orders = [];
        $(document).ready(function () {
            $("#refresh").on('click',function () {
                $.get('/kitchen-status-waiter-json', function (data) {
                    console.log(data);
                    orders = data;
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(orders);
                });
            });


            $.get('/kitchen-status-waiter-json', function (data) {
                orders = data;
                $("#renderHtmlHear").empty();
                $(this).renderHTML(orders);
            });

            var startCooking = pusher.subscribe('start-cooking');
            startCooking.bind('kitchen-event', function (data) {
                $.get('/kitchen-status-waiter-json', function (data) {
                    orders = data;
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(orders);
                });
            });


            var completeCooking = pusher.subscribe('complete-cooking');
            completeCooking.bind('complete-cooking-event',function (data) {
                $.get('/kitchen-status-waiter-json', function (data) {
                    orders = data;
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(orders);
                });
            });

            var updateOrder = pusher.subscribe('update-order');
            updateOrder.bind('order-update-event',function (data) {
                $.get('/kitchen-status-waiter-json', function (data) {
                    orders = data;
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(orders);
                });
            });

            $.fn.serve = function (index) {
                $.get('/order-served/'+orders[index].id, function (data) {
                    orders.splice(index,1);
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(orders);
                });
            };

            // $.fn.cancelOrder = function (index) {
            //     var conf = confirm('Are you sure ?');
            //     if(conf){
            //         $.get('/delete-order/'+orders[index].id,function (data) {
            //             orders.splice(index,1);
            //             $("#renderHtmlHear").empty();
            //             $(this).renderHTML(orders);
            //         });

            //     }

            // };

            $.fn.cancelOrder = function (index) {
            var conf = confirm('Are you sure ?');
            if (conf) {
                var orderId = orders[index].id;

                $.post('/delete-order', 
                    {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        order_id: orderId
                    },
                    function (data) {
                        orders.splice(index, 1);
                        $("#renderHtmlHear").empty();
                        $(this).renderHTML(orders);
                    }
                ).fail(function (xhr) {
                    alert('Failed to delete order: ' + xhr.responseText);
                });
            }
        };


            $.fn.renderHTML = function (data) {
                $.each(data, function (index, dish) {
                    $("#renderHtmlHear").append(
                        $("<div>", {class: "col-lg-6"}).append(
                            $("<div>", {class: dish.status == 0 ? "panel panel-color panel-warning" : "panel panel-color panel-custom",
                                style: "height: 400px; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;"
                            }).append(
                                $("<div>", {class: "panel-heading"}).append(
                                    $("<h3>", {
                                        class: "panel-title",
                                        text: dish.kitchen ? dish.kitchen.name : "Kitchen did not response yet"
                                    }).append(
                                        $("<span>", {class: "pull-right", text: dish.served_by.name})
                                    )
                                ),
                                $("<div>", {class: "panel-body dish-details"}).append(
                                    $("<ul>", {class: 'list-group'}).append(
                                        $.map(dish.order_details, function (index, dishDetails) {
                                            return $("<li>", {
                                                class: "list-group-item",
                                                text: dish.order_details[dishDetails].dish?.dish 
                                            }).append(
                                                $("<span>", {
                                                    class: "badge badge-success",
                                                    text: dish.order_details[dishDetails].dish_type?.dish_type
                                                }),
                                                $("<span>", {
                                                    class: "badge badge-primary ml-2",
                                                    text: "Qty: " + dish.order_details[dishDetails].quantity
                                                }),
                                                dish.table && dish.table.table_no
                                                    ? $("<span>", {
                                                        class: "badge badge-primary ml-2",
                                                        text: "Table: " + dish.table.table_no
                                                    })
                                                    : ''
                                            )
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
                                    dish.cook_start_time ? $("<p>", {text: "Cooking Start Time: "}).append(
                                        $("<span>", {class: "badge badge-warning", text: new Date(dish.cook_start_time).toLocaleString("en-US", {
                                            weekday: "long",
                                            year: "numeric",
                                            month: "long",
                                            day: "numeric",
                                            hour: "2-digit",
                                            minute: "2-digit",
                                            second: "2-digit",
                                            hour12: true 
                                        })})
                                    ) : "",
                                    dish.cook_complete_time ? $("<p>", {text: "Cooking Complete Time: "}).append(
                                        $("<span>", {class: "badge badge-success", text: new Date(dish.cook_complete_time).toLocaleString("en-US", {
                                            weekday: "long",
                                            year: "numeric",
                                            month: "long",
                                            day: "numeric",
                                            hour: "2-digit",
                                            minute: "2-digit",
                                            second: "2-digit",
                                            hour12: true 
                                        })})
                                    ) : "",
                                    dish.serve_time ? $("<p>", {text: "Serve Time: "}).append(
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
                                    class: "btn btn-block btn-lg btn-primary waves-effect waves-light",
                                    text: "Pending ! Click to cancel order",
                                    onClick:"$(this).cancelOrder("+ index +")"
                                })
                                    : (dish.status == 1)
                                    ? $("<button>", {
                                        class: "btn btn-block btn-lg btn-primary waves-effect waves-light",
                                        text: "Cooking"
                                    })
                                    : (dish.status == 2)
                                        ? $("<button>", {
                                            class: "btn btn-block btn-lg btn-primary waves-effect waves-light",
                                            text: "Complete! waiting for serve ",
                                            onClick: "$(this).serve(" + index + ")"
                                        })
                                        : "Oops"
                            )
                        )
                    )
                })
            }
        })
    </script>
@endsection