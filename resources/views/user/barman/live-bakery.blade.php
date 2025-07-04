@extends('layouts.app')

@section('title')
    Live Baker
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
                <li><a href="{{url('/baker-status')}}">Baker Status</a></li>
            </ol>
        </div>
    </div>
    <div class="row" id="renderHtmlHear">

    </div>

    <div class="row" id="supplierHtml">

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

        var orders = [];
        var supplierOrders = [];
        var waiterOrders = [];

        $(document).ready(function () {
            $("#refresh").on('click',function () {
                $.get('baker-status-waiter-json', function (data) {
                    orders = data.orders;
                    supplierOrders = data.supplierOrders;
                    waiterOrders = data.waiterOrders;
                    $("#renderHtmlHear").empty();
                    $("#supplierHtml").empty();
                    $("#waiterHtml").empty();
                    $(this).renderOrders(orders, "#renderHtmlHear");
                    $(this).renderSupplierOrders(supplierOrders, "#supplierHtml");
                    $(this).renderWaiterOrders(waiterOrders, "#waiterHtml");
                });
            });


            $.get('baker-status-waiter-json', function (data) {
                orders = data.orders;
                supplierOrders = data.supplierOrders;
                waiterOrders = data.waiterOrders;
                console.log('dataaaaa', data);
                $("#renderHtmlHear").empty();
                $("#supplierHtml").empty();
                $("#waiterHtml").empty();
                $(this).renderOrders(orders, "#renderHtmlHear");
                $(this).renderSupplierOrders(supplierOrders, "#supplierHtml");
                $(this).renderWaiterOrders(waiterOrders, "#waiterHtml");
            });

            var startCooking = pusher.subscribe('start-inhouse-cooking');
            startCooking.bind('kitchen-inhouse-event', function (data) {
                $.get('baker-status-waiter-json', function (data) {
                    orders = data.orders;
                    supplierOrders = data.supplierOrders;
                    waiterOrders = data.waiterOrders;
                    $("#renderHtmlHear").empty();
                    $("#supplierHtml").empty();
                    $("#waiterHtml").empty();
                    $(this).renderOrders(orders, "#renderHtmlHear");
                    $(this).renderSupplierOrders(supplierOrders, "#supplierHtml");
                    $(this).renderWaiterOrders(waiterOrders, "#waiterHtml");
                });
            });


            var completeCooking = pusher.subscribe('complete-inhouse-cooking');
            completeCooking.bind('complete-inhouse-cooking-event',function (data) {
                $.get('baker-status-waiter-json', function (data) {
                    orders = data.orders;
                    supplierOrders = data.supplierOrders;
                    waiterOrders = data.waiterOrders;
                    $("#renderHtmlHear").empty();
                    $("#supplierHtml").empty();
                    $("#waiterHtml").empty();
                    $(this).renderOrders(orders, "#renderHtmlHear");
                    $(this).renderSupplierOrders(supplierOrders, "#supplierHtml");
                    $(this).renderWaiterOrders(waiterOrders, "#waiterHtml");
                });
            });

            var purseCompleted = pusher.subscribe('order-served');
            purseCompleted.bind('order-served-event', function (data) {
                $.get("/baker-status-waiter-json", function (data) {
                    orders = data.orders;
                    supplierOrders = data.supplierOrders;
                    waiterOrders = data.waiterOrders;
                    $("#renderHtmlHear").empty();
                    $("#supplierHtml").empty();
                    $("#waiterHtml").empty();
                    $("#renderHtmlHear").renderOrders(orders, "#renderHtmlHear");
                    $("#supplierHtml").renderSupplierOrders(supplierOrders, "#supplierHtml");
                    $(this).renderWaiterOrders(waiterOrders, "#waiterHtml");
                });
            });



            var updateOrder = pusher.subscribe('update-order');
            updateOrder.bind('order-update-event',function (data) {
                $.get('baker-status-waiter-json', function (data) {
                    orders = data.orders;
                    supplierOrders = data.supplierOrders;
                    waiterOrders = data.waiterOrders;
                    $("#renderHtmlHear").empty();
                    $("#supplierHtml").empty();
                    $("#waiterHtml").empty();
                    $(this).renderOrders(orders, "#renderHtmlHear");
                    $(this).renderSupplierOrders(supplierOrders, "#supplierHtml");
                    $(this).renderWaiterOrders(waiterOrders, "#waiterHtml");
                });
            });

            $.fn.serve = function (index) {
                const laddaBtn = Ladda.create(this[0]); 
                laddaBtn.start();
                $.get('/barman-order-served/'+orders[index].id, function (data) {
                    orders.splice(index,1);
                    $("#renderHtmlHear").empty();
                    $("#supplierHtml").empty();
                    $("#waiterHtml").empty();
                    $(this).renderOrders(orders, "#renderHtmlHear");
                    $(this).renderSupplierOrders(supplierOrders, "#supplierHtml");
                    $(this).renderWaiterOrders(waiterOrders, "#waiterHtml");
                });
                setTimeout(() => laddaBtn.stop(), 4000);
            };


            $.fn.serveReadyOrder = function (index) {
                const laddaBtn = Ladda.create(this[0]); 
                laddaBtn.start();
                $.get('/barman-ready-order-served/'+waiterOrders[index].id, function (data) {
                    waiterOrders.splice(index,1);
                    $("#renderHtmlHear").empty();
                    $("#supplierHtml").empty();
                    $("#waiterHtml").empty();
                    $(this).renderOrders(orders, "#renderHtmlHear");
                    $(this).renderSupplierOrders(supplierOrders, "#supplierHtml");
                    $(this).renderWaiterOrders(waiterOrders, "#waiterHtml");
                });
                setTimeout(() => laddaBtn.stop(), 4000);
            };

            $.fn.confirm = function (index) {
                const laddaBtn = Ladda.create(this[0]); 
                laddaBtn.start();
                $.get('/barman-order-confirm/'+supplierOrders[index].id, function (data) {
                    supplierOrders.splice(index,1);
                    $("#renderHtmlHear").empty();
                    $("#supplierHtml").empty();
                    $("#waiterHtml").empty();
                    $(this).renderOrders(orders, "#renderHtmlHear");
                    $(this).renderSupplierOrders(supplierOrders, "#supplierHtml");
                    $(this).renderWaiterOrders(waiterOrders, "#waiterHtml");
                });
                setTimeout(() => laddaBtn.stop(), 4000);
            };

            

            $.fn.cancelOrder = function (index) {
            
                var orderId = orders[index].id;
                var orderDetails = orders[index].order_details;

                const laddaBtn = Ladda.create(this[0]);
                laddaBtn.start();
                $.post('/delete-barman-inhouse.order', 
                    {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        order_id: orderId,
                        orderD:orderDetails,
                    },
                    function (data) {
                        orders.splice(index, 1);
                        $("#renderHtmlHear").empty();
                        $("#supplierHtml").empty();
                        $("#waiterHtml").empty();
                        $(this).renderOrders(orders, "#renderHtmlHear");
                        $(this).renderSupplierOrders(supplierOrders, "#supplierHtml");
                        $(this).renderWaiterOrders(waiterOrders, "#waiterHtml");
                    }
                ).fail(function (xhr) {
                    console.log('Failed to delete order: ' + xhr.responseText);
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
                        $("#renderHtmlHear").empty();
                        $("#supplierHtml").empty();
                        $("#waiterHtml").empty();
                        $(this).renderOrders(orders, "#renderHtmlHear");
                        $(this).renderSupplierOrders(supplierOrders, "#supplierHtml");
                        $(this).renderWaiterOrders(waiterOrders, "#waiterHtml");
                    }
                ).fail(function (xhr) {
                    console.log('Failed to delete order: ' + xhr.responseText);
                });
                setTimeout(() => laddaBtn.stop(), 4000);
            
        };


            $.fn.renderOrders = function (data) {
                $.each(data, function (index, dish) {
                    $("#renderHtmlHear").append(
                        $("<div>", {class: "col-lg-6"}).append(
                            $("<div>", {class: dish.status == 0 ? "panel panel-color panel-warning" : "panel panel-color panel-custom",
                                style: "height: 430px;"
                            }).append(
                                $("<div>", {class: "panel-heading"}).append(
                                    $("<h3>", {
                                        class: "panel-title",
                                        text: dish.baker ? dish.baker.name : "Baker did not response yet"
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
                                    dish.cook_start_at ? $("<p>", {text: "Cooking Start Time: "}).append(
                                        $("<span>", {class: "badge badge-warning", text: new Date(dish.cook_start_at).toLocaleString("en-US", {
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
                                    dish.cook_complete_at ? $("<p>", {text: "Cooking Complete Time: "}).append(
                                        $("<span>", {class: "badge badge-success", text: new Date(dish.cook_complete_at).toLocaleString("en-US", {
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
                                    dish.accepted_time ? $("<p>", {text: "Accept Time: "}).append(
                                        $("<span>", {class: "badge badge-primary", text: new Date(dish.accepted_time).toLocaleString("en-US", {
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
                                            class: "btn btn-block btn-lg btn-primary waves-effect waves-light ladda-button",
                                            text: "Complete! waiting for accept ",
                                            "data-style": "expand-right",
                                            "data-spinner-color": "#fff",
                                            onClick: "$(this).serve(" + index + ")"
                                        })
                                        : "Oops"
                            )
                        )
                    )
                })
            }

             $.fn.renderSupplierOrders = function (data) {
                $.each(data, function (index, dish) {
                    $("#supplierHtml").append(
                        $("<div>", {class: "col-lg-6"}).append(
                            $("<div>", {class: dish.status == 0 ? "panel panel-color panel-warning" : "panel panel-color panel-custom"}).append(
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