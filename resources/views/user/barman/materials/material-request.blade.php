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
                    <td>{{$product->name}}</td>
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
                    {{-- <td>{{$product->materialRequests->sum('requested_quantity') }}</td> --}}
                    <td>{{$product->purchased_batches_sum_ready_quantity ?? 0}}</td>
                  
                    <td>{{$product->minimum_stock_threshold ?? 0}}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button"
                                    class="btn btn-danger waves-effect waves-light"
                                    data-toggle="modal"
                                    data-target="#requestModal"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}">
                                Request {{ $product->name }}
                            </button>
                        </div>
                    </td>


                    <!-- Modal -->
                    <div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form id="materialRequestForm" method="POST" action="{{ route('materials.request.barman.store') }}">
                        @csrf
                        <input type="hidden" name="reference_id" id="reference_id">
                        <input type="hidden" name="type" id="type" value="ready_dish"> <!-- or ready_dish dynamically if needed -->

                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="requestModalLabel">Request Material</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                            <p id="productNameLabel"></p>
                            <div class="form-group">
                                <label for="requested_quantity">Requested Quantity</label>
                                <input type="number" class="form-control" name="requested_quantity" id="requested_quantity" min="1" required>
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
                    $('#requestModal').on('show.bs.modal', function (event) {
                        let button = $(event.relatedTarget);
                        let productId = button.data('id');
                        let productName = button.data('name');

                        let modal = $(this);
                        modal.find('#reference_id').val(productId);
                        modal.find('#productNameLabel').text('Requesting: ' + productName);
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