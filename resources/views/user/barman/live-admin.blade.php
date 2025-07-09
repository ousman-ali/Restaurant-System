@extends('layouts.app')

@section('title')
    Live Admin
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
                <li><a href="{{url('/admin-status')}}">Admin Status</a></li>
            </ol>
        </div>
    </div>

    <div class="row" id="supplierHtml">

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

        
        var supplierOrders = [];

        $(document).ready(function () {
            $("#refresh").on('click',function () {
                $.get('baker-status-waiter-json', function (data) {
                    supplierOrders = data.supplierOrders;
                    $("#supplierHtml").empty();
                    $(this).renderSupplierOrders(supplierOrders, "#supplierHtml");
                });
            });


            $.get('baker-status-waiter-json', function (data) {
                supplierOrders = data.supplierOrders;
                $("#supplierHtml").empty();
                $(this).renderSupplierOrders(supplierOrders, "#supplierHtml");
            });

            

            var purseCompleted = pusher.subscribe('order-served');
            purseCompleted.bind('order-served-event', function (data) {
                $.get("/baker-status-waiter-json", function (data) {
                    supplierOrders = data.supplierOrders;
                    $("#supplierHtml").empty();
                    $("#supplierHtml").renderSupplierOrders(supplierOrders, "#supplierHtml");
                });
            });

            var submitNewOrder = pusher.subscribe('supplier-order-submit');
            submitNewOrder.bind('supplier-order-submit-event', function (data) {
                $.get("/baker-status-waiter-json", function (data) {
                    supplierOrders = data.supplierOrders;
                    $("#supplierHtml").empty();
                    $("#supplierHtml").renderSupplierOrders(supplierOrders, "#supplierHtml");
                });
            });


        
            var updateOrder = pusher.subscribe('update-order');
            updateOrder.bind('order-update-event',function (data) {
                $.get('baker-status-waiter-json', function (data) {
                    supplierOrders = data.supplierOrders;
                    $("#supplierHtml").empty();
                    $(this).renderSupplierOrders(supplierOrders, "#supplierHtml");
                });
            });

         

            $.fn.confirm = function (index) {
                const laddaBtn = Ladda.create(this[0]); 
                laddaBtn.start();
                $.get('/barman-order-confirm/'+supplierOrders[index].id, function (data) {
                    supplierOrders.splice(index,1);
                    $("#supplierHtml").empty();
                    $(this).renderSupplierOrders(supplierOrders, "#supplierHtml");
                });
                setTimeout(() => laddaBtn.stop(), 4000);
            };

     

        $.fn.cancelSupplierOrder = function (index) {
                var orderDetails = supplierOrders[index].order_details;
                var orderId = supplierOrders[index].id;

                const laddaBtn = Ladda.create(this[0]);
                laddaBtn.start();
                $.post('/delete-barman-supplier-order', 
                    {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        orderD: orderDetails,
                        order_id:orderId,
                    },
                    function (data) {
                        supplierOrders.splice(index, 1);
                        $("#supplierHtml").empty();
                        $(this).renderSupplierOrders(supplierOrders, "#supplierHtml");
                    }
                ).fail(function (xhr) {
                    console.log('Failed to delete order: ' + xhr.responseText);
                });
                setTimeout(() => laddaBtn.stop(), 4000);
            
        };

             $.fn.renderSupplierOrders = function (data) {
                $.each(data, function (index, dish) {
                    $("#supplierHtml").append(
                        $("<div>", {class: "col-lg-6"}).append(
                            $("<div>", {class: dish.status == 0 ? "panel panel-color panel-warning" : "panel panel-color panel-custom",
                                style: "height: 430px;"
                            }).append(
                                $("<div>", {class: "panel-heading"}).append(
                                    $("<h3>", {
                                        class: "panel-title",
                                        text: dish.admin ? dish.admin.name : "Admin did not response yet"
                                    }).append(
                                        $("<span>", {class: "pull-right", text: dish.order_by.name})
                                    )
                                ),
                                $("<div>", {class: "panel-body dish-details"}).append(
                                    $("<ul>", {class: 'list-group'}).append(
                                        $.map(dish.order_details, function (index, dishDetails) {
                                            const detail = dish.order_details[dishDetails];
                                            const name = detail.ready_dish?.name || 'Unnamed';
                                            const quantity = detail.quantity || 0;
                                            const unit = detail.ready_dish?.unit?.unit || '';
                                            const childUnit = detail.ready_dish?.unit?.child_unit || '';
                                            const convertRate = detail.ready_dish?.unit?.convert_rate || 1;

                                            const converted = (quantity * convertRate).toFixed(2);
                                            return $("<li>", {
                                                class: "list-group-item",
                                                text: dish.order_details[dishDetails].ready_dish?.name
                                            }).append(
                                               $("<span>", {
                                                    class: "badge badge-success",
                                                    text: `${quantity} ${unit} (${converted} ${childUnit})`
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
                                    dish.purchase_time ? $("<p>", {text: "Purchase Time: "}).append(
                                        $("<span>", {class: "badge badge-warning", text: new Date(dish.purchase_time).toLocaleString("en-US", {
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
                                    dish.confirmation_time ? $("<p>", {text: "Confirmation Time: "}).append(
                                        $("<span>", {class: "badge badge-success", text: new Date(dish.confirmation_time).toLocaleString("en-US", {
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
                                    class: "btn btn-block btn-lg btn-primary waves-effect waves-light ladda-button",
                                    "data-style": "expand-right",
                                    "data-spinner-color": "#fff",
                                    text: "Pending ! Click to cancel order",
                                    onClick:"$(this).cancelSupplierOrder("+ index +")"
                                })
                                    
                                    : (dish.status == 1)
                                        ? $("<button>", {
                                            class: "btn btn-block btn-lg btn-primary waves-effect waves-light ladda-button",
                                            text: "Confirm Purchase",
                                            "data-style": "expand-right",
                                            "data-spinner-color": "#fff",
                                            onClick: "$(this).confirm(" + index + ")"
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