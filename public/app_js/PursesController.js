/**
 * Created by rifat on 8/27/17.
 */
var convertion_rate;
var clicked_supplier_id = 0;
$(document).ready(function () {

    /**
     * It will take the current supplier id for further use
     */
    $("#supplier_id").on('click', function (e) {
        clicked_supplier_id = $("#supplier_id").val();
    });

    /**
     * Supplier dropdown on change Action
     */
    $("#supplier_id").on('change', function () {
        if (purses.length != 0) {
            if (clicked_supplier_id != 0) {
                $(this).val(clicked_supplier_id);
                $.Notification.notify('warning',
                    'top right',
                    'Avast ! Cannot add multiple supplier in a purses',
                    'You cannot change supplier in each purses, ' +
                    'To add another supplier you have to finish this purses first ' +
                    '')
            }
        }
    });

    /**
     * Quantity on value change
     */
    $("#quantity").on('keyup', function (e) {
        console.log("Change");
        $("#grossPrice").val($("#quantity").val() * $("#unitPrice").val());
        $("#child_unit_price").val($("#unitPrice").val() / convertion_rate);
    });

    /**
     * Unit Price on value change
     */
    $("#unitPrice").on('keyup', function (e) {
        $("#grossPrice").val($("#quantity").val() * $("#unitPrice").val());
        $("#child_unit_price").val($("#unitPrice").val() / convertion_rate);
    });

    /**
     * Unit price on mouse scroll value change
     */
    $("#unitPrice").on('wheel', function (e) {
        $("#grossPrice").val($("#quantity").val() * $("#unitPrice").val());
        $("#child_unit_price").val($("#unitPrice").val() / convertion_rate);
    });

    /**
     * Product dropdown on change Action
     */
    $("#product").on('change', function (e) {
        var productId = $("#product").val()
        $.get('/get-unit-of-product/' + productId, function (data) {
            console.log(data);
            $("#unit").text(data.unit.unit);
            $("#child_unit").text(data.unit.child_unit);
            convertion_rate = data.unit.convert_rate;

            unitId = data.unit.id;
            unitName = data.unit.unit;

        });
    });

    /**
     * Action on save purses button click
     */
    $("#savePurses").on('click', function (e) {
        if (purses.length != 0) {
            console.log("You can go on")
        } else {
            console.log("You cannot go on")
        }
    });

    /**
     * Purses Form Submit
     * @type {*}
     */
    var form = $("#purses");
    form.on('submit', function (e) {
        e.preventDefault();
        // console.log('Form Submit');
        // console.log($("#supplier_id").val());

        /**
         * Append value on purse object from form data
         * @type {{pursesId: string, supplier: {supplierId: (*), supplierName: (*)}, product: {productId: (*), productName: (*)}, quantity: (*), unit: {unitId: string, unitName: string, childUnit: number, convertRate: *, unitPrice: (*)}, grossPrice: (*)}}
         */
        purse = {
            pursesId: '215241',
            supplier: {
                supplierId: $("#supplier_id").val(),
                supplierName: $("#supplier_id option:selected").text()
            },
            product: {
                productId: $("#product").val(),
                productName: $("#product option:selected").text()
            },
            quantity: $("#quantity").val(),
            unit: {
                unitId: unitId,
                unitName: unitName,
                childUnit: $("#unitPrice").val() / convertion_rate,
                convertRate: convertion_rate,
                unitPrice: $("#unitPrice").val()
            },
            grossPrice: $("#grossPrice").val()
        }

         //push purse object to purses array
        purses.push(purse);

        //Call render function to render purses list
        $("#pursesDetailsRender").empty();
        $(this).renderHtml(purses);

         //Set default value of all field except supplier in to form
        $("#quantity").val(0);
        $("#unitPrice").val(0);
        $("#child_unit_price").val(0);
        $("#grossPrice").val(0);
        $("#product").val('');
    });

    /**
     * Render Purses List
     * @param data
     */
    $.fn.renderHtml = function (data) {
        // Final Total Cost
        var total = 0;
        $.each(data, function (index, data) {
            total += data.unit.unitPrice * data.quantity;
            $("#pursesDetailsRender").append(
                $("<tr>").append(
                    $("<th>", {text: index + 1}),
                    $("<td>", {text: data.supplier.supplierName}),
                    $("<td>", {text: data.product.productName}),
                    $("<td>").append(
                        $("<input/>", {
                            value: data.quantity,
                            class: 'form-control',
                            type: 'number',
                            onChange: '$(this).updateQuantity(' + index + ')'
                        })
                    ),
                    $("<td>").append(
                        $("<input/>", {
                            value: data.unit.unitPrice,
                            class: 'form-control',
                            type: 'number',
                            onChange: '$(this).updateUnitPrice(' + index + ')'
                        })
                    ),
                    $("<td>", {text: data.unit.unitPrice / data.unit.convertRate}),
                    $("<td>", {text: data.unit.unitPrice * data.quantity}),
                    $("<td>").append(
                        $("<div>", {class: 'btn-group'}).append(
                            $("<button>", {
                                class: 'btn btn-danger btn-sm',
                                onClick: '$(this).deleteFromList(' + index + ')'
                            }).append(
                                $('<i>', {class: 'fa fa-trash-o'})
                            )
                        )
                    )
                )
            )
        });
        // Render bottom of purses list with sum of total cost, payment and others
        if(purses.length != 0){
            $("#pursesDetailsRender").append(
                $("<tr>").append(
                    $("<th>",{colspan:5}),
                    $("<th>", {text: "Total :",class:"text-right"}),
                    $("<th>", {text: total})
                ),
                $("<tr>").append(
                    $("<th>",{colspan:5}),
                    $("<th>", {text: "Payment :",class:"text-right"}),
                    $("<input/>",{type:"number",
                        value:"0", min:"0", style:"width: 120px",class:"form-control",
                        id:"payment",
                        onChange:"$(this).changeDuePayment("+total+")",
                        onkeyup:"$(this).changeDuePayment("+total+")",
                        onwheel:"$(this).changeDuePayment("+total+")"
                    })
                ),
                $("<tr>").append(
                    $("<th>",{colspan:5}),
                    $("<th>", {text: "Due :",class:"text-right"}),
                    $("<th>", {text: total,id:"due"})
                ),
                $("<tr>").append(
                    $("<th>",{colspan:6}),
                    $("<th>").append(
                        $("<button>",{text:"Confirm Purses",class:"btn btn-purple",onClick:'$(this).confirmPurses()'})
                    )
                )
            )
        }
    };

    /**
     * Delete purses form purses list
     * @param index
     */
    $.fn.deleteFromList = function (index) {
        purses.splice(index, 1);
        $("#pursesDetailsRender").empty();
        $(this).renderHtml(purses);
    };

    /**
     * Update quantity of purses list
     * @param index
     */
    $.fn.updateQuantity = function (index) {
        purses[index].quantity = this.val();
        $("#pursesDetailsRender").empty();
        $(this).renderHtml(purses);
    };

    /**
     * Update unit price of purses list
     * @param index
     */
    $.fn.updateUnitPrice = function (index) {
        purses[index].unit.unitPrice = this.val();
        $("#pursesDetailsRender").empty();
        $(this).renderHtml(purses);
    };

    /**
     * Calculate due after pay
     * @param total
     */
    $.fn.changeDuePayment = function (total) {
        $("#due").text(total - $(this).val());
    };

    /**
     * Confirm Purses
     */
    $.fn.confirmPurses = function () {

        var confirmPurses = {
            _token: $("input[name=_token]").val(),
            supplier_id : $("#supplier_id").val(),
            purses : purses,
            payment : $("#payment").val()
        };
       $.post('/save-purses',confirmPurses,function () {
          console.log("success");
       }).done(function (data) {
           $.Notification.notify('success',
               'bottom right',
               'Success ! Purses Has been completed successfully');
           purses = [];
           $("#pursesDetailsRender").empty();
           $(this).renderHtml(purses);
       });
    }

});

var purse = {};
var unitId = '';
var unitName = '';
var purses = [];