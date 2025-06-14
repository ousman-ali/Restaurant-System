@extends('layouts.app')

@section('title')
    Add Supplier
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <center>
                    <h4 class="m-t-0 header-title">Edit Supplier</h4>
                    <p>Edit Supplier</p>
                </center>
                <hr>


                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" role="form" action="#" id="addSupplier" method="post"
                              enctype="multipart/form-data" data-parsley-validate novalidate>
                            {{csrf_field()}}

                            <input type="hidden" value="{{$supplier->id}}" id="supplierId">

                            <div class="form-group">
                                <label class="col-md-2 control-label">Name </label>
                                <div class="col-md-8">
                                    <input type="text" name="name" class="form-control" value="{{$supplier->name}}"
                                           placeholder="Supplier Name" parsley-trigger="change" maxlength="50" required>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label" for="example-email">Email </label>
                                <div class="col-md-8">
                                    <input type="email" name="email" class="form-control" placeholder="Supplier Email Address"
                                           parsley-trigger="change" maxlength="50" value="{{$supplier->email}}">

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="example-email">Phone </label>
                                <div class="col-md-8">
                                    <input type="text" name="phone" class="form-control" placeholder="Supplier Phone"
                                           parsley-trigger="change" maxlength="50" required value="{{$supplier->phone}}">

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label" for="example-email">Address </label>
                                <div class="col-md-8">
                                    <textarea name="address" minlength="10" cols="20" rows="5" class="form-control" placeholder="Supplier Address" required>{{$supplier->address}}</textarea>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-md-2 control-label"></label>
                                <div class="col-md-8">
                                    <div class="checkbox checkbox-primary">
                                        <input id="checkbox0" name="status" type="checkbox" {{$supplier->status == 1 ? 'checked' : ''}}>
                                        <label for="checkbox0">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-md-2 control-label"></label>
                                <div class="col-md-10">
                                    <button type="submit" class="ladda-button btn btn-purple" data-style="expand-right">Update Supplier

                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-js')

    <script>
        $(document).ready(function (e) {
            var addSupplier = $("#addSupplier");
            addSupplier.on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                var id= $("#supplierId").val();
                $(this).speedPost('/update-supplier/'+id, formData, message = {
                    success: {header: 'Supplier update successfully', body: 'Supplier update successfully'},
                    error: {header: 'Supplier already exist', body: 'Supplier found'},
                    warning: {header: 'Internal Server Error', body: 'Internal server error'}
                });
            });
        });
    </script>

@endsection