@extends('layouts.app')

@section('title')
    Add Recipe
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title"><b>Add Item</b></h4>
                <p>

                </p>
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" role="form" action="#" id="addItem" method="post"
                              enctype="multipart/form-data" data-parsley-validate novalidate>
                            {{csrf_field()}}

                            <div class="form-group">
                                <label for="" class="col-md-2 control-label">Recipe Name :</label>
                                    <div class="col-md-8">
                                        <input type="text" id="recipe_name" required name="recipe_name" class="form-control">
                                    </div>

                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Dish :</label>
                                <div class="col-md-4">
                                    <select name="" id="dish" class="form-control" required>
                                        <option value="">Select Dish</option>
                                        @foreach($dishes as $dish)
                                            <option value="{{$dish->id}}">{{$dish->dish}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-1 control-label">Dish Type :</label>
                                <div class="col-md-3">
                                    <select name="" id="dish_type" class="form-control" required>
                                        <option value="">Select One</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">

                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label" for="example-email">Product :</label>
                                <div class="col-md-8">
                                    <select name="" id="product" class="form-control" required>
                                        <option value="">Select One</option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}">{{$product->product_name}} &nbsp;&nbsp;&nbsp; {{$product->product_code}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="example-email">Unit need to cook :</label>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="unit_input" step="0.01" required>
                                        <span class="input-group-addon" id="unit">.00</span>
                                    </div>
                                </div>
                                <label class="col-md-2 control-label" for="example-email">Child Unit need to cook
                                    :</label>
                                <div class="col-md-3 input-group">
                                    <input type="number" class="form-control" id="child_unit_input" step="0.01"
                                           required>
                                    <span class="input-group-addon" id="childUnit">.00</span>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-2 control-label"></label>
                                <div class="col-md-10">
                                    <button type="submit" class="btn btn-purple">Add Product to recipe

                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <div id="productListUnderRecipe">

                </div>

            </div>
        </div>
    </div>
@endsection

@section('extra-js')
    <script src="{{url('/app_js/RecipeController.js')}}"></script>
@endsection