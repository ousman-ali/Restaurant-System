@extends('layouts.app')

@section('title')
    Edit Dish Category
@endsection

@section('extra-css')
    <style>
        /* Styles for popup mode */
        body.is-popup .topbar,
        body.is-popup .side-menu,
        body.is-popup .footer {
            display: none !important;
        }

        body.is-popup .content-page {
            margin-left: 0 !important;
            padding-top: 0 !important;
        }

        body.is-popup .wrapper {
            padding-top: 0 !important;
        }

        body.is-popup .container {
            width: 100% !important;
            max-width: 100% !important;
        }
    </style>
@endsection

@section('content')
    <div class="card-box">
        <h4 class="m-t-0 header-title"><b>Edit Dish Category</b></h4>
        <p class="text-muted font-13 m-b-30">
            Edit dish category
        </p>
        <form class="form-horizontal" role="form" id="unitForm" method="POST" data-parsley-validate novalidate>
            {{csrf_field()}}
            <input type="hidden" value="{{$dish_type->id}}" id="id">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Dish*</label>
                <div class="col-sm-7">
                    <input type="text" required class="form-control" name="name" value="{{$dish_type->name}}" placeholder="1/2, 3/4">
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label"></label>
                <div class="col-sm-7">
                    <div class="checkbox checkbox-custom">
                        <input id="checkbox11" name="status" type="checkbox" {{$dish_type->status ==1 ? 'checked' : ''}}>
                        <label for="checkbox11">
                            Active
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Save Unit
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('extra-js')
    <script>
        $(document).ready(function () {
            var unitForm = $("#unitForm");
            var id = $("#id").val();

            // Check if this is a popup window
            var isPopup = window.opener && window.opener !== window;

            // If this is a popup, add class to body to hide navigation
            if (isPopup) {
                $('body').addClass('is-popup');
            }

            unitForm.on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);

                // Use speedPost with callback to handle popup communication
                $(this).speedPost('/update-dish-type/'+id, formData, message = {
                    success: {header: 'Dish type updated successfully', body: 'Dish type updated successfully'},
                    error: {header: 'Dish type already exist', body: 'Dish type found'},
                    warning: {header: 'Internal Server Error', body: 'Internal server error'}
                }, unitForm, function(response) {
                    // If this is a popup and we got a response, send data back to parent
                    if (isPopup && response) {
                        try {
                            // Extract data from response based on your API response format
                            var categoryId = response.data ? response.data.id : (response.id || id);
                            var categoryName = response.data ? response.data.name : (response.name || $('input[name="name"]').val());

                            // Send message to parent window
                            window.opener.postMessage({
                                type: 'categoryUpdated',
                                categoryId: categoryId,
                                categoryName: categoryName
                            }, '*');

                            // Close popup after a short delay
                            setTimeout(function() {
                                window.close();
                            }, 1000);
                        } catch (error) {
                            console.error('Error sending message to parent:', error);
                        }
                    }
                });
            });
        });
    </script>
@endsection
