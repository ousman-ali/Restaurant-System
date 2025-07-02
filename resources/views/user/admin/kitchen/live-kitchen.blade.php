@extends('layouts.app')

@section('title')
    Live Kitchen
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="#" onclick="$(this).refreshList()" class="btn btn-default waves-effect">Refresh <span
                            class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Live Kitchen</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    <a href="#">Live Kitchen</a>
                </li>
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
            $.fn.refreshList = function () {
                $.get('/live-kitchen-admin-json', function (data) {
//                console.log(data);
                    orders = data;
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(data);
                });
            };

            $.get('/live-kitchen-admin-json', function (data) {
//                console.log(data);
                orders = data;
                $("#renderHtmlHear").empty();
                $(this).renderHTML(data);
            });

            $.fn.serve = function (index) {
                var conf = confirm('Are you sre ?')
                if(conf){
                    $.get('/order-served/'+orders[index].id, function (data) {
                        orders.splice(index,1);
                        $("#renderHtmlHear").empty();
                        $(this).renderHTML(orders);
                    });
                }
            };
            // Pusher function
            var channel = pusher.subscribe('order');
            channel.bind('order-event', function (data) {
                $.get("/live-kitchen-admin-json", function (data) {
                    orders = data;
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(data);
                });
            });

            var startCooking = pusher.subscribe('start-cooking');
            startCooking.bind('kitchen-event', function (data) {
                $.get("/live-kitchen-admin-json", function (data) {
                    orders = data;
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(data);
                });
            });

            var completeCooking = pusher.subscribe('complete-cooking');
            completeCooking.bind('complete-cooking-event',function (data) {
                $.get("/live-kitchen-admin-json", function (data) {
                    orders = data;
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(data);
                });
            });

            var orderServed = pusher.subscribe('order-served');
            orderServed.bind('order-served-event',function (data) {
                $.get("/live-kitchen-admin-json", function (data) {
                    orders = data;
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(data);
                });
            });

            var orderCancel = pusher.subscribe('cancel-order');
            orderCancel.bind('order-cancel-event',function (data) {
                $.get("/live-kitchen-admin-json", function (data) {
                    orders = data;
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(data);
                });
            });

            var updateOrder = pusher.subscribe('update-order');
            updateOrder.bind('order-update-event',function (data) {
                $.get("/live-kitchen-admin-json", function (data) {
                    orders = data;
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(data);
                });
            });


            $.fn.renderHTML = function (data) {
                $.each(data, function (index, dish) {
                    $("#renderHtmlHear").append(
                        $("<div>", {class: "col-lg-6"}).append(
                            $("<div>", {class: dish.status == 0 ? "panel panel-color panel-warning" : "panel panel-color panel-custom",
                                style: "height: 360px; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;"
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
                                                text: dish.order_details[dishDetails].dish.dish
                                            }).append(
                                                $("<span>", {
                                                    class: "badge badge-success",
                                                    text: dish.order_details[dishDetails].dish_type.dish_type
                                                }),
                                                $("<span>", {
                                                    class: "badge badge-primary ml-2",
                                                    text: "Qty: " + dish.order_details[dishDetails].quantity
                                                })
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
                                            text: "Pending for kitchen response"
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

        });
    </script>

@endsection