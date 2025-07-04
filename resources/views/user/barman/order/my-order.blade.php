@extends('layouts.app')

@section('title')
    All Order
@endsection

@section('content')
    <div class="card-box table-responsive">

        <h4 class="m-t-0 header-title"><b>All Order</b></h4>
        <p class="text-muted font-13 m-b-30">
            Responsive is an extension for DataTables that resolves that problem by optimising the
            table's layout for different screen sizes through the dynamic insertion and removal of
            columns from the table.
        </p>

        <table id="datatable-responsive"
               class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
               width="100%">
            <thead>
            <tr>
                {{--<th>#</th>--}}
                <th>Order No</th>
                <th>Ordered By</th>
                <th>Status</th>
                <th width="120px">Action</th>
            </tr>
            </thead>
            <?php $count = 1; ?>
            <tbody>
            @foreach($orders as $oder)
                <tr>
                    {{--<td>{{$count++}}</td>--}}
                    <td>{{$oder->order_no}}</td> 
                    <td>{{$oder->orderBy->name}}</td>
                    <td>{{$oder->status == 0 ? 'Due' : 'Paid' }}</td>
                    
                    <td>
                            @if($oder->user_id ==0)
                                <div class="btn-group">
                                    <a href="{{url('/edit-barman-order/'.$oder->id)}}"
                                       class="btn btn-success waves-effect waves-light">
                                        <i class="fa fa-pencil"></i>
                                    </a> 
                                    <button type="button" onclick="printInvoice({{$oder->id}})"
                                            href="{{url('/print-barman-order/'.$oder->id)}}"
                                            class="btn btn-purple waves-effect waves-light">
                                        <i class="fa fa-print"></i>
                                    </button>
                                    @if($oder->status == 0)
                                        {{-- <a href="#" onclick="$(this).confirmDelete('/delete-order/'+{{$o->id}})"
                                           class="btn btn-danger waves-effect waves-light">
                                            <i class="fa fa-trash-o"></i>
                                        </a> --}}

                                        <form action="{{ route('order.delete')}}" method="post" class="deleteform">
                                            @csrf
                                            <input type="hidden" name="order_id" value="{{$oder->id}}">
                                            <button type="submit" class="btn btn-danger waves-effect waves-light deletebtn">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </form>

                                    @endif
                                </div>
                            @else
                                <a href="{{url('/print-order/'.$oder->id)}}"
                                   class="btn btn-purple waves-effect waves-light">
                                    <i class="fa fa-print"></i>
                                </a>
                            @endif
                        </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    All Order
@endsection

@section('extra-js')
    <script>
        $(document).ready(function () {
            $("#datatable-responsive").DataTable({
                "order": [[ 0, "desc" ]]
            });
        });

        function printInvoice(orderId) {
            if (!orderId) {
                alert("Cannot print receipt: Order ID is missing");
                return;
            }
            // Fetch the receipt HTML
            fetch(`/print-order/${orderId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'text/html'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.text();
                })
                .then(html => {
                    // Open a new window for printing
                    const printWindow = window.open('', '', 'width=800,height=600,toolbar=0,menubar=0,location=0');

                    // Insert the styles right after the <head> tag in the response
                    // Write the modified HTML to the new window
                    printWindow.document.write(html);
                    printWindow.document.close();

                    // Trigger print when content is loaded
                    printWindow.onload = function() {
                        printWindow.focus();
                        setTimeout(() => {
                            printWindow.print();

                            // Close the window after printing
                            printWindow.onafterprint = function() {
                                printWindow.close();
                            };

                            // Fallback close for browsers that don't support onafterprint
                            setTimeout(() => {
                                printWindow.close();
                            }, 1000);
                        }, 500);
                    };
                })
                .catch(error => {
                    console.error('Error printing receipt:', error);
                    alert("Error printing receipt. Please try again.");
                });
        }
    </script>

@endsection