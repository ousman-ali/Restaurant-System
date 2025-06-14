/**
 * Created by rifat on 8/30/17.
 */
var selectedDish = false;
var convert_rate = 0;
var current_dish_id = 0;
var current_dish_type_id = 0;
$(document).ready(function () {
    console.log("Recipe Controller is ready to work................................");

    $("#dish").on('click', function (e) {
        current_dish_id = $("#dish").val();
    });

    $("#dish_type").on('click',function (e) {
        current_dish_type_id = $(this).val();
    });

    $("#dish").on('change', function () {
        if (recipes.length != 0) {
            $(this).val(current_dish_id);
            $.Notification.notify('warning',
                'top right',
                'Avast ! Cannot add multiple Dish in a Recipe',
                'You cannot change dish in each recipe, ' +
                'To add another dish you have to finish this recipe first ' +
                '')
        }else{
            $.get('/dish-types/' + $("#dish").val(), function (data) {
                console.log(data);
                $("#dish_type").empty();
                $.each(data, function (index, type) {
                    $("#dish_type").append(
                        $("<option>", {value: type.id, text: type.dish_type})
                    );
                });
            });
        }
    });

    $("#dish_type").on('change',function () {
       if(recipes.length != 0){
            $(this).val(current_dish_type_id);
           $.Notification.notify('warning',
               'top right',
               'Avast ! Cannot add multiple dish type in a recipe',
               'You cannot change dish type in each recipe, ' +
               'To add another dish type you have to finish this recipe first ' +
               '')
       }
    });

    $("#product").on('change', function () {
        $.get('/get-unit-of-product/' + $("#product").val(), function (data) {
            console.log(data);
            $("#unit").text(data.unit.unit);
            $("#childUnit").text(data.unit.child_unit);
            convert_rate = data.unit.convert_rate;
        });
    });

    $("#unit_input").on('keyup', function () {
        if (convert_rate != 0) {
            $("#child_unit_input").val($("#unit_input").val() * convert_rate);
        }

    });

    $("#child_unit_input").on('keyup', function () {
        if (convert_rate != 0) {
            $("#unit_input").val($("#child_unit_input").val() / convert_rate);
        }
    });

    $("#addItem").on('submit', function (e) {
        e.preventDefault();
        recipe = {
            dish: $("#dish").val(),
            dish_type_id: $("#dish_type").val(),
            product_id: $("#product").val(),
            unit: $("#unit_input").val(),
            child_unit: $("#child_unit_input").val(),
            dish_name: $("#dish option:selected").text(),
            dish_type_name: $("#dish_type option:selected").text(),
            product_name: $("#product option:selected").text(),
            unit_text : $("#unit").text(),
            child_unit_text : $("#childUnit").text()
        };
        recipes.push(recipe);
        $("#productListUnderRecipe").empty();
        $(this).renderRecipeTable(recipes);
        $(this).formReset();
    });

    $.fn.saveRecipe = function () {
        console.log("Save recipe...........................");
        var postRecipe = {
            _token : $("input[name=_token]").val(),
            recipe_name: $("#recipe_name").val(),
            recipes: recipes
        }
        $.post('/save-recipes',postRecipe,function (data) {
            console.log(data);
        }).done(function (data) {
            $("#productListUnderRecipe").empty();
            $(this).formReset();
            $("#recipe_name").val('');
            $("#dish").val('');
            $("#dish_type").val('');
            $.Notification.notify('success',
                'bottom right',
                'Saved ! Your Recipe has been saved successfully',
                'Recipe has been saved successfully, ' +
                ' ' +
                '')
        })
    };

    $.fn.deleteRecipe = function (index) {
        recipes.splice(index, 1);
        $("#productListUnderRecipe").empty();
        if(recipes.length != 0){
            $(this).renderRecipeTable(recipes);
        }

    };

    $.fn.formReset = function () {
      $("#product").val('');
      $("#unit_input").val(0);
      $("#child_unit_input").val(0);
    };

    $.fn.renderRecipeTable = function (data) {
        $("#productListUnderRecipe").append(
            $("<div>", {class: 'p-20'}).append(
                $("<div>", {class: "table-responsive"}).append(
                    $("<table>", {class: "table table-bordered m-0"}).append(
                        $("<thead>").append(
                            $("<tr>").append(
                                $("<th>", {text: '#'}),
                                $("<th>", {text: 'Dish Info'}),
                                $("<th>", {text: 'Product'}),
                                $("<th>", {text: 'Unit'}),
                                $("<th>", {text: 'Child Unit'}),
                                $("<th>", {text: 'Action'})
                            )
                        ),
                        $("<tbody>", {id: 'tableBody'})
                    )
                )
            )
        )
        $.each(data, function (index, recipe) {
            $("#tableBody").append(
                $("<tr>").append(
                    $("<th>", {text: index + 1}),
                    $("<td>", {text: recipe.dish_name}).append("<br>").append(recipe.dish_type_name),
                    $("<td>", {text: recipe.product_name}),
                    $("<td>", {text: recipe.unit}).append(" -"+recipe.unit_text),
                    $("<td>", {text: recipe.child_unit}).append(" -"+recipe.child_unit_text),
                    $("<td>").append(
                        $("<button>", {
                            class: 'btn btn-danger btn-xs',
                            text: "delete",
                            onClick: "$(this).deleteRecipe(" + index + ")"
                        })
                    )
                )
            )
        });

        $("#productListUnderRecipe").append(
            $("<div>", {class: 'row'}).append(
                $("<div>", {class: 'col-md-offset-8 col-md-4'}).append(
                    $("<button>", {
                        class: 'btn btn-purple btn-block btn-lg',
                        text: 'Save Recipe',
                        onClick: "$(this).saveRecipe()"
                    })
                )
            )
        )
    };

});

var recipe = {};
var recipes = [];