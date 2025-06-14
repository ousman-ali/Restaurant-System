@extends('layouts.app')

@section('title')
    New Purses 1
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box" id="app">
                <center>
                    <h4 class="m-t-0 header-title"><b>Purses</b></h4>
                    <p>

                    </p>
                </center>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" role="form" action="#" id="purses" method="post"
                              enctype="multipart/form-data" data-parsley-validate novalidate>
                            {{csrf_field()}}


                            <div class="form-group">
                                {{--<label for="" class="col-md-2 control-label">Purses Id</label>--}}
                                {{--<div class="col-md-2">--}}
                                {{--<input type="text" name="purses_id" value="2541" class="form-control">--}}
                                {{--</div>--}}

                                <label for="" class="col-md-2 control-label">Select Supplier</label>
                                <div class="col-md-2">
                                    <select name="supplier_id" id="supplier_id" class="form-control select2" required>
                                        <option value="">Select One</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <label for="" class="col-md-1 control-label">Select Product</label>
                                <div class="col-md-3">
                                    <select name="product_id" id="product" class="form-control" required>
                                        <option value="">Select One</option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}">{{$product->product_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <label class="col-md-1 control-label" for="example-email">Quantity :</label>
                                <div class="col-md-2">
                                    <div class="input-group ">
                                        <input type="text" id="quantity" name="quantity" class="form-control"
                                               placeholder="Quantity">
                                        <span class="input-group-addon" id="unit">Unit</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="example-email">Unit Price :</label>
                                <div class="col-md-2">
                                    <input type="number" min="1" name="unit_price" class="form-control"
                                           placeholder="Unit Price"
                                           parsley-trigger="change" maxlength="50" required id="unitPrice">
                                </div>
                                <label for="" class="col-md-1 control-label">Child unit price</label>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input disabled type="text" id="child_unit_price" name="quantity"
                                               class="form-control"
                                               placeholder="Child Unit Price">
                                        <span class="input-group-addon" id="child_unit">Unit</span>
                                    </div>
                                </div>

                                <label class="col-md-1 control-label" for="example-email">Gross Price</label>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon"
                                              id="">{{config('restaurant.currency.symbol')}}</span>
                                        <input disabled type="number" min="1" name="product_name" class="form-control"
                                               placeholder="Gross Price"
                                               parsley-trigger="change" maxlength="50" required id="grossPrice">
                                        <span class="input-group-addon"
                                              id="">{{config('restaurant.currency.currency')}}</span>
                                    </div>

                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-2 control-label"></label>
                                <div class="col-md-10">
                                    <button type="submit" class="btn btn-purple">
                                        Purses Now

                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="p-20">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Supplier</th>
                                <th>Product</th>
                                <th width="100px">Quantity</th>
                                <th width="150px">Unit Price</th>
                                <th>Child Unit Price</th>
                                <th>Gross Price</th>
                                <th width="95px">Action</th>
                            </tr>
                            </thead>
                            <tbody id="pursesDetailsRender">


                            </tbody>
                        </table>

                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection

@section('extra-js')

    <script src="{{ url('/app_js/PursesController.js') }}"></script>



@endsection
