@extends('layouts.app')

@section('title')
    Live Barman
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="#" onclick="$(this).refreshList()" class="btn btn-default waves-effect">Refresh <span
                            class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Live Barman</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    <a href="#">Live Barman</a>
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
                $.get('/live-barman-admin-json', function (data) {
//                console.log(data);
                    orders = data;
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(data);
                });
            };

            $.get('/live-barman-admin-json', function (data) {
//                console.log(data);
                orders = data;
                $("#renderHtmlHear").empty();
                $(this).renderHTML(data);
            });

            $.fn.serve = function (index) {
                var conf = confirm('Are you sre ?')
                if(conf){
                    $.get('/barman-order-served/'+orders[index].id, function (data) {
                        orders.splice(index,1);
                        $("#renderHtmlHear").empty();
                        $(this).renderHTML(orders);
                    });
                }
            };
            // Pusher function
            var channel = pusher.subscribe('order');
            channel.bind('order-event', function (data) {
                $.get("/live-barman-admin-json", function (data) {
                    orders = data;
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(data);
                });
            });

            var purseCompleted = pusher.subscribe('order-served');
            purseCompleted.bind('order-served-event',function (data) {
                $.get("/live-barman-admin-json", function (data) {
                    orders = data;
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(data);
                });
            });

            var orderCancel = pusher.subscribe('cancel-order');
            orderCancel.bind('order-cancel-event',function (data) {
                $.get("/live-barman-admin-json", function (data) {
                    orders = data;
                    $("#renderHtmlHear").empty();
                    $(this).renderHTML(data);
                });
            });

            var updateOrder = pusher.subscribe('update-order');
            updateOrder.bind('order-update-event',function (data) {
                $.get("/live-barman-admin-json", function (data) {
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
                                style: "height: 400px; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;"
                            }).append(
                                $("<div>", {class: "panel-heading"}).append(
                                    $("<h3>", {
                                        class: "panel-title",
                                        text: dish.status == 0 ? "Waiting for Admin Response" : "Admin"
                                    }).append(
                                        $("<span>", {class: "pull-right", text: dish.order_by.name})
                                    )
                                ),
                                $("<div>", {class: "panel-body dish-details"}).append(
                                    $("<ul>", {class: 'list-group'}).append(
                                        $.map(dish.order_details, function (index, dishDetails) {
                                            return $("<li>", {
                                                class: "list-group-item",
                                                text: dish.order_details[dishDetails].ready_dish?.name
                                            }).append(
                                                $("<span>", {
                                                    class: "badge badge-primary ml-2",
                                                    text: "Requested Stock: " + dish.order_details[dishDetails].quantity
                                                })
                                            );
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
                                    dish.purchased_at ? $("<p>", {text: "Purchase Time: "}).append(
                                        $("<span>", {class: "badge badge-warning", text: new Date(dish.purchased_at).toLocaleString("en-US", {
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
                                    
                                ),
                                (dish.status == 0)
                                    ? $("<button>", {
                                        class: "btn btn-block btn-lg btn-primary waves-effect waves-light",
                                        text: "Add Stock",
                                        click: function () {
                                            const orderId = dish?.id;

                                            $('#add-stock-modal-body').html('<p class="text-center">Loading...</p>');
                                            $.get('/get-ready-recipe-form-all/' + orderId, function (formHtml) {
                                                $('#add-stock-modal-body').html(formHtml);
                                                $.getScript('/app_js/PursesController.js', function () {
                                                    $('#addStockModal').modal('show');
                                                });
                                            });
                                        }
                                    })
                                    : "Oops"
                            )
                        )
                    )
                })
            }

        });
    </script>

<!-- Modal -->
<div class="modal fade" id="addStockModal" tabindex="-1" role="dialog" aria-labelledby="addStockLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Product to Recipe</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>

            <div class="modal-body" id="add-stock-modal-body">
                <!-- Form will be dynamically loaded here -->
            </div>
        </div>
    </div>
</div>



@endsection

