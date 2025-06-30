@extends('layouts.app')

@section('title')
    Stock Status
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">

            <h4 class="page-title">Stock Status </h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Stock Management
                </li>
                <li class="active">
                    All stock
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
                <th>Stock</th>
                <th>Minimum Stock Threshold</th>
                <th width="20px">Action</th>
            </tr>
            </thead>
            <?php $count = 1; ?>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{$count++}} .</td>
                    <td>
                        <img src="/{{ $product->thumbnail }}" alt="" class="img-responsive" width="100px">
                    </td>
                    <td>{{$product->product_name}}</td>
                     @php
                        $sum = $product->materialRequests->sum('requested_quantity');
                    @endphp

                    <td>
                        @if ($sum == 0)
                            0
                        @else
                            {{ number_format($sum) }} {{ $product?->unit?->unit }}
                            ({{ number_format($sum * ($product?->unit?->convert_rate ?? 0)) }} {{ $product?->unit?->child_unit }})
                        @endif
                    </td>
                    <td>{{$product->purses_sum_quantity ?? 0}}</td>
                   
                    <td>{{$product->minimum_stock_threshold ?? 0}}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button"
                                    class="btn btn-danger waves-effect waves-light"
                                    data-toggle="modal"
                                    data-target="#requestModal"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->product_name }}">
                                Request {{ $product->product_name }}
                            </button>
                        </div>
                    </td>


                    <!-- Modal -->
                    <div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form id="materialRequestForm" method="POST" action="{{ route('materials.request.baker.store') }}">
                        @csrf
                        <input type="hidden" name="reference_id" id="reference_id">
                        <input type="hidden" name="type" id="type" value="recipe_product"> <!-- or ready_dish dynamically if needed -->

                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="requestModalLabel">Request Material</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                            <p id="productNameLabel"></p>
                            {{-- <div class="form-group">
                                <label for="requested_quantity">Requested Quantity</label>
                                <input type="number" class="form-control" name="requested_quantity" id="requested_quantity" min="1" required>
                            </div> --}}

                            <div class="form-group">
                                <label class="col-md-6 control-label">Quantity (in Unit):</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="number" step="any" id="quantity" name="requested_quantity" class="form-control" placeholder="Quantity in Unit">
                                        <span class="input-group-addon" id="unit">Unit</span>
                                    </div>
                                </div>

                                <label class="col-md-6 control-label">Quantity (in Child Unit):</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="number" step="any" id="child_quantity" class="form-control" placeholder="Child Unit">
                                        <span class="input-group-addon" id="child_unit">Child</span>
                                    </div>
                                </div>
                            </div>


                            </div>
                            <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Submit Request</button>
                            </div>
                        </div>
                        </form>
                    </div>
                    </div>

                   @section('extra-js')
                   <script>
                    let convertion_rate = 1; // default fallback

                    $('#requestModal').on('show.bs.modal', function (event) {
                        let button = $(event.relatedTarget);
                        let productId = button.data('id');
                        let productName = button.data('name');

                        let modal = $(this);
                        modal.find('#reference_id').val(productId);
                        modal.find('#productNameLabel').text('Requesting: ' + productName);

                        // Fetch unit info
                        $.get('/get-unit-of-product/' + productId, function (data) {
                            console.log('Unit data:', data);

                            const unit = data.unit.unit;
                            const childUnit = data.unit.child_unit;
                            convertion_rate = data.unit.convert_rate;

                            modal.find('#unit').text(unit);
                            modal.find('#child_unit').text(childUnit);

                            // Listen for unit -> child unit conversion
                            modal.find("#quantity").off('input').on('input', function () {
                                const quantity = parseFloat($(this).val()) || 0;
                                modal.find("#child_quantity").val((quantity * convertion_rate).toFixed(2));
                            });

                            // Listen for child unit -> unit conversion
                            modal.find("#child_quantity").off('input').on('input', function () {
                                const childQty = parseFloat($(this).val()) || 0;
                                modal.find("#quantity").val((childQty / convertion_rate).toFixed(2));
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