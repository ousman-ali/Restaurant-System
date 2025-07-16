@extends('layouts.app')

@section('title')
    Barman request
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">

            <h4 class="page-title">Barman request </h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Request Management
                </li>
                <li class="active">
                    All request
                </li>
            </ol>
        </div>
    </div>
    <div class="card-box table-responsive">
        <table id="datatable-responsive"
               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%">
            <thead>
            <tr>
                <th>#</th>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Requested Qunatity</th>
                <th width="20px">Action</th>
            </tr>
            </thead>
            <?php $count = 1; ?>
            <tbody>
            @foreach($requests as $product)
                <tr>
                    <td>{{$count++}} .</td>
                    <td>
                        <img src="/{{ $product->product->thumbnail }}" alt="" class="img-responsive" width="100px">
                    </td>
                    <td>{{$product->product->product_name}}</td>
                    <td>
                        {{ number_format($product->requested_quantity) }} {{ $product->product->unit->unit }}
                        ({{ number_format($product->requested_quantity * $product->product->unit->convert_rate) }} {{ $product->product->unit->child_unit }})
                    </td>
                    <td>
                        <div class="btn-group">
                            @if($product->status == 'approved')
                            <p class="text-sucess">Approved</p>
                            @else
                            <button type="button"
                                    class="btn btn-success waves-effect waves-light"
                                    data-toggle="modal"
                                    data-target="#requestModal"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->product->product_name }}"
                                    data-product-id="{{ $product->product->id }}"
                                    data-requested-quantity="{{ $product->requested_quantity }}">
                                Approve
                            </button>
                            @endif

                            <form action="{{ route('material-requests.kitchen.reject')}}" method="post">
                                @csrf
                                <input type="hidden" name="reference_id" value="{{$product->id}}">
                                <button type="submit"
                                    class="btn btn-danger waves-effect waves-light">
                                Regect
                            </button>
                            </form>
                            
                        </div>
                    </td>


                    <!-- Modal -->
                    <div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form id="materialRequestForm" method="POST" action="{{ route('material-requests.approve') }}">
                                @csrf

                                <!-- Hidden fields -->

                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="requestModalLabel">Approve Request Material</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body row">
                                        <div class="col-md-12">

                                            <div class="form-group">
                                                {{--<label for="" class="col-md-2 control-label">Purses Id</label>--}}
                                                {{--<div class="col-md-2">--}}
                                                {{--<input type="text" name="purses_id" value="2541" class="form-control">--}}
                                                {{--</div>--}}

                                                <input type="hidden" name="reference_id" id="reference_id" value="">

                                                <label for="" class="col-md-6 control-label">Select Supplier</label>
                                                <div class="col-md-6">
                                                    <select name="supplier_id" id="supplier_id" class="form-control select2" required>
                                                        <option value="">Select Supplier</option>
                                                        @foreach($suppliers as $supplier)
                                                            <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <label for="" class="col-md-6 control-label">Select Product</label>
                                                <div class="col-md-6">
                                                    <select name="product_id" id="product" class="form-control select2" required disabled>
                                                        <option value="">Select Product</option>
                                                        @foreach($products as $product)
                                                            <option value="{{$product->id}}">{{$product->product_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <input type="hidden" name="product_id" id="product_hidden">

                                                <label class="col-md-6 control-label" for="example-email">Quantity :</label>
                                                <div class="col-md-6">
                                                    <div class="input-group ">
                                                        <input type="text" id="quantity" name="quantity" class="form-control"
                                                            placeholder="Quantity">
                                                        <span class="input-group-addon" id="unit">Unit</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-6 control-label" for="example-email">Unit Price :</label>
                                                <div class="col-md-6">
                                                    <input type="number" min="1" name="unit_price" class="form-control"
                                                        placeholder="Unit Price"
                                                        parsley-trigger="change" maxlength="50" required id="unitPrice">
                                                </div>
                                                <label for="" class="col-md-6 control-label">Child unit price</label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <input disabled type="text" id="child_unit_price" name="quantity"
                                                            class="form-control"
                                                            placeholder="Child Unit Price">
                                                        <span class="input-group-addon" id="child_unit">Unit</span>
                                                    </div>
                                                </div>

                                                
                                                <input type="hidden" name="child_unit_price" id="child_unit_price_hidden">
                                                <input type="hidden" name="gross_price" id="grossPrice_hidden">

                                                <label class="col-md-6 control-label" for="example-email">Gross Price</label>
                                                <div class="col-md-6">
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

                                     

                                            <hr>

                                            <div class="form-group">
                                                <label>Payment Amount</label>
                                                <input type="number" id="paymentAmount" name="payment_amount" class="form-control" >
                                                <small id="paymentValidation" style="display: none;" class="text-danger">Payment exceeds total amount!</small>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Approve Request</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>


                    @section('extra-js')
                    <script>
                        $(document).ready(function () {
                            $("#datatable-responsive").DataTable();

                            $('#requestModal').on('show.bs.modal', function (event) {
                            let button = $(event.relatedTarget);
                            let referenceId = button.data('id');
                            let productName = button.data('name');
                            let productId = button.data('product-id');
                            let requestedQuantity = button.data('requested-quantity');

                            let modal = $(this);

                            var convertion_rate;
                            var clicked_supplier_id = 0;

                            // Set hidden fields
                            modal.find('#reference_id').val(referenceId);
                            modal.find('#product').val(productId).trigger('change');
                            modal.find('#product_hidden').val(productId);
                            modal.find('#quantity').val(requestedQuantity); 


                            // Trigger unit and conversion logic (if needed)
                            $.get('/get-unit-of-product/' + productId, function (data) {
                                modal.find('#unit').text(data.unit.unit);
                                modal.find('#child_unit').text(data.unit.child_unit);
                                convertion_rate = data.unit.convert_rate;

                                // Optionally pre-fill child unit price
                                const unitPrice = parseFloat(modal.find('#unitPrice').val()) || 0;
                                modal.find('#child_unit_price').val((unitPrice / convertion_rate).toFixed(2));
                            });

                            // Update gross and child price on input changes
                            modal.find("#quantity, #unitPrice").on('input', function () {
                                let quantity = parseFloat(modal.find("#quantity").val()) || 0;
                                let unitPrice = parseFloat(modal.find("#unitPrice").val()) || 0;

                                let gross = (quantity * unitPrice).toFixed(2);
                                modal.find("#grossPrice").val(gross);
                                modal.find("#grossPrice_hidden").val(gross);

                                modal.find("#grossPrice").val((quantity * unitPrice).toFixed(2));
                                if (convertion_rate) {
                                    modal.find("#child_unit_price").val((unitPrice / convertion_rate).toFixed(2));
                                    modal.find("#child_unit_price_hidden").val((unitPrice / convertion_rate).toFixed(2)); 
                                }


                            });

                            // Handle supplier lock
                            modal.find("#supplier_id").on('click', function () {
                                clicked_supplier_id = $(this).val();
                            });

                            modal.find("#supplier_id").on('change', function () {
                                if (clicked_supplier_id !== 0) {
                                    $(this).val(clicked_supplier_id);
                                    $.Notification.notify('warning', 'top right', 'Avast! Cannot add multiple suppliers',
                                        'You cannot change supplier in each purses. To add another supplier, finish this purses first.'
                                    );
                                }
                            });

                            // Payment validation
                            modal.find('#paymentAmount').on('input', function () {
                                let payment = parseFloat($(this).val()) || 0;
                                let gross = parseFloat(modal.find('#grossPrice').val()) || 0;

                                if (payment > gross) {
                                    modal.find('#paymentValidation').show();
                                } else {
                                    modal.find('#paymentValidation').hide();
                                }
                            });
                        });

                        });
                    </script>
                    @endsection



                </tr>
            @endforeach
            </tbody>
        </table>

    </div>


@endsection

@section('extra-js')
    <script>
         
        $(document).ready(function () {
        
            $("#datatable-responsive").DataTable();
        })
    </script>

@endsection