@extends('layouts.app')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="row" id="renderHtmlHear">

    </div>


@endsection

@section('extra-js')

    <style>
        .dish-details{
            height: 200px;
            overflow-y: scroll;
        }

    </style>


    <script>
        var orders = [];
        var socketId = null;
        var sender = false;
        $(document).ready(function () {
            console.log('ready.............');

            $.get("/kitchen-orders", function (data) {
                console.log(data);
                orders = data;
                $("#renderHtmlHear").empty();
                $(this).renderHTML(orders);

            });





            $.fn.renderHTML = function (data) {
                $.each(data, function (index, dish) {
                    $("#renderHtmlHear").append(
                        $("<div>", {class: "col-lg-4"}).append(
                            $("<div>", {class: dish.status == 0 ? "panel panel-color panel-warning" : "panel panel-color panel-custom"}).append(
                                $("<div>", {class: "panel-heading"}).append(
                                    $("<h3>", {
                                        class: "panel-title",
                                        text: dish.status == 0 ? "Waiting for Kitchen Response" : "My Kitchen"
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
                                                })
                                            )
                                        })
                                    )
                                ),
                                (dish.status == 0) ?
                                    $("<button>", {
                                        class: "btn btn-block btn-lg btn-primary waves-effect waves-light",
                                        text: "Let's Cook",
                                        onClick: "$(this).letsCook(" + dish.id + ")"
                                    })
                                    : $("<button>", {
                                    class: "btn btn-block btn-lg btn-primary waves-effect waves-light",
                                    text: "Click To Complete",
                                    onClick: "$(this).completeCooking(" + dish.id + ")"
                                })
                            )
                        )
                    )
                })
            }

            $.fn.letsCook = function (id) {
                console.log("Let's Cook Clicked " + id)
                $.get("/kitchen-start-cooking/" + id, function (data) {
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(data);
                });
            }

            $.fn.completeCooking = function (id) {
                console.log("Compleate Cooking " + id)
                $.get("/kitchen-complete-cooking/" + id, function (data) {
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(data);
                });
            };



            var channel = pusher.subscribe('order');
            channel.bind('order-event', function(data) {
                $.get("/kitchen-orders", function (data) {
                    console.log(data);
                    orders = data;
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(orders);
                });
            });

            var startCooking = pusher.subscribe('start-cooking');
            startCooking.bind('kitchen-event',function (data) {
                $.get("/kitchen-orders", function (data) {
                    console.log(data);
                    orders = data;
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(orders);

                });
            });

            var orderCancel = pusher.subscribe('cancel-order');
            orderCancel.bind('order-cancel-event',function (data) {
                $.get("/kitchen-orders", function (data) {
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(data);
                });
            });

            var updateOrder = pusher.subscribe('update-order');
            updateOrder.bind('order-update-event',function (data) {
                $.get("/kitchen-orders", function (data) {
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(data);
                });
            });



        })
    </script>


@endsection