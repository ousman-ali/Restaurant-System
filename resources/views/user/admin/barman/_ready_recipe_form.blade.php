
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
                        <form id="multiAddStockForm">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            
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
                                <label>Dish</label>
                                <select id="dishSelector" class="form-control select2">
                                    <option value="">Select Dish</option>
                                    @foreach($order->orderDetails as $detail)

                                        @if($detail->readyDish)
                                            <option value="{{ $detail->readyDish->id }}"
                                                data-quantity="{{ $detail->quantity }}"
                                                data-name="{{ $detail->readyDish->name }}">
                                                {{ $detail->readyDish->name }}
                                            </option>
                                        @endif
                                    @endforeach

                                </select>
                            </div>

                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" id="dishQuantity" class="form-control" >
                            </div>

                            <div class="form-group">
                                <label>Unit Price</label>
                                <input type="number" id="unitPrice" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Gross Price</label>
                                <input type="number" id="gross" class="form-control" >
                            </div>

                            

                            {{-- <div class="form-group">
                                <label>Payment</label>
                                <input type="number" id="payment" class="form-control">
                            </div> --}}

                            <button type="button" class="btn btn-info" id="addToList">Add</button>

                            <hr>
                            <div id="dishList">
                            <table class="table table-bordered" id="dishTable">
                                <thead>
                                    <tr>
                                        <th>Dish</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                        <div class="form-group">
                            <label>Payment Amount</label>
                            <input type="number" id="paymentAmount" class="form-control" required>
                            <small id="paymentValidation" style="display: none;" class="text-danger d-none">Payment exceeds total amount!</small>
                        </div>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary ladda-button " data-style="expand-right" id="submitAllStock">Save All</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    



