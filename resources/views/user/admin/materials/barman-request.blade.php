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
                <th>Status</th>
                <th width="20px">Action</th>
            </tr>
            </thead>
            <?php $count = 1; ?>
            <tbody>
            @foreach($requests as $product)
            
                <tr>
                    <td>{{$count++}} .</td>
                    <td>
                        <img src="/{{ $product->readyDish->thumbnail }}" alt="" class="img-responsive" width="100px">
                    </td>
                    <td>{{$product->readyDish->name}}</td>
                    <td>
                        {{ number_format($product->requested_quantity) }} {{ $product->product->unit->unit }}
                        ({{ number_format($product->requested_quantity * $product->product->unit->convert_rate) }} {{ $product->product->unit->child_unit }})
                    </td>
                    <td>{{$product->status}}</td>
                    <td>
                        
                        <div class="btn-group">
                           @if($product->status == 'rejected' || $product->status == 'pending')
                            <button type="button"
                                    class="btn btn-success waves-effect waves-light"
                                    data-toggle="modal"
                                    data-target="#requestModal"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->readyDish->name }}"
                                    data-product-id="{{ $product->product->id }}"
                                    data-requested-quantity="{{ $product->requested_quantity }}">
                                Approve
                            </button>
                            @else

                            <form action="{{ route('material-requests.barman.reject')}}" method="post">
                                @csrf
                                <input type="hidden" name="reference_id" value="{{$product->id}}">
                                <button type="submit"
                                    class="btn btn-danger waves-effect waves-light">
                                Regect
                            </button>
                            </form>
                            @endif
                            
                        </div>
                    </td>


                    <!-- Modal -->
                    <div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form id="materialRequestForm" method="POST" action="{{ route('material-requests.barman.approve') }}">
                            @csrf

                            <!-- Hidden fields -->
                            <input type="hidden" name="reference_id" id="reference_id">
                            <input type="hidden" name="product_id" id="hiddenDishSelector">
                            <input type="hidden" name="type" value="ready_dish">

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="requestModalLabel">Request Material</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body row">
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <label>Supplier</label>
                                            <select name="supplier_id" class="form-control select2" required>
                                                <option value="">Select Supplier</option>
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Product</label>
                                            <select id="dishSelector" class="form-control select2" name="product_id" disabled>
                                                <option value="">Select Dish</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}"
                                                            data-quantity="{{ $product->quantity }}"
                                                            data-name="{{ $product->name }}">
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Quantity</label>
                                            <input type="number" id="dishQuantity" name="quantity" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Unit Price</label>
                                            <input type="number" id="unitPrice" name="unit_price" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Gross Price</label>
                                            <input type="number" id="gross" name="gross_price" class="form-control" readonly>
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

                            // Fill hidden fields
                            modal.find('#reference_id').val(referenceId);
                            modal.find('#hiddenDishSelector').val(productId);

                            // Fill and trigger select2 for product
                            modal.find('#dishSelector').val(productId).trigger('change');

                            // Fill requested quantity
                            modal.find('#dishQuantity').val(requestedQuantity);

                            // Calculate gross price when unit price or quantity changes
                            modal.find('#unitPrice, #dishQuantity').on('input', function () {
                                let unitPrice = parseFloat(modal.find('#unitPrice').val()) || 0;
                                let quantity = parseFloat(modal.find('#dishQuantity').val()) || 0;
                                modal.find('#gross').val((unitPrice * quantity).toFixed(2));
                            });

                            // Payment validation
                            modal.find('#paymentAmount').on('input', function () {
                                let payment = parseFloat($(this).val()) || 0;
                                let gross = parseFloat(modal.find('#gross').val()) || 0;

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