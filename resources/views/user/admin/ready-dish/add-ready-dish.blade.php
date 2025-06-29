@extends('layouts.app')

@section('title')
    Add Ready Dish
@endsection

@section('content')
    {{--Page header--}}
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/all-ready-dish')}}" class="btn btn-default waves-effect">All Ready Dish <span class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Create New Ready Dish </h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="active">
                    Ready Dish
                </li>
                <li class="active">
                    Edit Ready Dish
                </li>
            </ol>
        </div>
    </div>

    <ul class="nav nav-tabs">
        <li class="active">
            <a href="{{url('/add-ready-dish')}}" data-toggle="tab" aria-expanded="true">
                <span class="visible-xs"><i class="fa fa-cutlery"></i></span>
                <span class="hidden-xs">Ready Dish Name</span>
            </a>
        </li>
        <li class="disabled">
            <a href="javascript:void(0);" data-toggle="tab" aria-expanded="false">
                <span class="visible-xs"><i class="fa fa-photo"></i></span>
                <span class="hidden-xs">Ready Dish Images</span>
            </a> 
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="home">
            <form class="form-horizontal" role="form" action="{{url('/save-ready-dish')}}" id="addEmployee" method="post"
                  enctype="multipart/form-data" data-parsley-validate novalidate>
                {{csrf_field()}}

                <div class="form-group">
                    <label for="" class="col-md-2 control-label">Thumbnail <span class="text-danger">*</span> </label>
                    <div class="col md-10">
                        <div id="image-preview">
                            <label for="image-upload" id="image-label">Choose Photo</label>
                            <input type="file" name="thumbnail" id="image-upload" required/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Dish Name <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <input type="text" name="dish" class="form-control" value=""
                               placeholder="Dish Name" parsley-trigger="change" maxlength="50" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Minimum Stock Threshold </label>
                    <div class="col-md-8">
                        <input type="number" step="0.01" name="minimum_stock_threshold" class="form-control" placeholder="Minimum Stock Threshold" required min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Source Type <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <select name="source_type" class="form-control select2" required>
                            <option value="">Select Source</option>
                            <option value="inhouse">In-house</option>
                            <option value="supplier">Supplier</option>
                        </select>
                    </div>
                </div>


            <div class="form-group">
                <label class="col-md-2 control-label">Price <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required min="0">
                </div>
            </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Category <span class="text-danger">*</span></label>
                    <div class="col-md-8 category-container">
                        <div class="input-group">
                            <select name="category_id" id="category_select" class="form-control select2" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                            <div class="input-group-btn">
                                <button type="button" class="btn add-category" title="Add New Category"
                                        style="background: transparent">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-md-2 control-label"></label>
                    <div class="col-md-10">
                        <button type="submit" class="ladda-button btn btn-purple" data-style="expand-right">
                            Save Dish And Go Next
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

@endsection

@section('extra-js')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Add category button click handler
            document.querySelector('.add-category').addEventListener('click', function () {
                // Store the current form data in session storage to maintain state
                sessionStorage.setItem('dishFormData', JSON.stringify({
                    dish: document.querySelector('input[name="dish"]').value,
                    // Add other form fields as needed
                }));

                // Open category add page in new window
                var addWindow = window.open("{{ url('/add-dish-type') }}", "addCategoryWindow", "width=800,height=600");

                // Set up window message listener for when the category is added
                window.addEventListener('message', function (event) {
                    if (event.data.type === 'categoryAdded') {
                        console.log('yes category added', event.data)
                        // Add the new category to the dropdown and select it
                        var select = document.getElementById('category_select');
                        var option = new Option(event.data.categoryName, event.data.categoryId);
                        select.appendChild(option);
                        select.value = event.data.categoryId;

                        // Close the popup window if it's still open
                        if (addWindow && !addWindow.closed) {
                            addWindow.close();
                        }
                    }
                }, false);
            });

            // Restore form data when page loads (if available)
            var savedFormData = sessionStorage.getItem('dishFormData');
            if (savedFormData) {
                var formData = JSON.parse(savedFormData);
                document.querySelector('input[name="dish"]').value = formData.dish || '';
                // Restore other fields as needed

                // Clear the saved data
                sessionStorage.removeItem('dishFormData');
            }
        });
    </script>

@endsection
