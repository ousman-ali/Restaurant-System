@extends('layouts.app')

@section('title')
    Ready Dish Price - {{$dish->dish}}
@endsection

@section('content')
    {{--Page header--}}
    <div class="row">
        <div class="col-sm-12">
            <div class="btn-group pull-right m-t-15">
                <a href="{{url('/all-ready-dish')}}" class="btn btn-default waves-effect">All Ready Dish <span class="m-l-5"></span></a>
            </div>

            <h4 class="page-title">Edit Ready Dish </h4>
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
            <a href="{{url('/edit-ready-dish/'.$dish->id)}}" aria-expanded="true">
                <span class="visible-xs"><i class="fa fa-cutlery"></i></span>
                <span class="hidden-xs">{{$dish->name}}</span>
            </a>
        </li>
        <li class="">
            <a href="{{url('/ready-dish-image/'.$dish->id)}}" aria-expanded="false">
                <span class="visible-xs"><i class="fa fa-photo"></i></span>
                <span class="hidden-xs">Ready Dish Images</span>
            </a>
        </li>
        @if($dish->source_type == 'inhouse')
        <li class="">
            <a href="{{url('/ready-dish-recipe/'.$dish->id)}}" aria-expanded="false">
                <span class="visible-xs"><i class="fa fa-photo"></i></span>
                <span class="hidden-xs">Recipe</span>
            </a>
        </li>
        @endif
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="home">
            <form class="form-horizontal" role="form" action="#" id="updateDish" method="post"
                  enctype="multipart/form-data" data-parsley-validate novalidate>
                {{csrf_field()}}
                <input type="hidden" value="{{$dish->id}}" id="dishId">
                <div class="form-group">
                    <label for="" class="col-md-2 control-label">Thumbnail <span class="text-danger">*</span> </label>
                    <div class="col md-10">
                        <div id="image-preview"
                             style="background-image: url({{url($dish->thumbnail != "" | null ? $dish->thumbnail : '/img_assets/avater.png')}})">
                            <label for="image-upload" id="image-label">Choose Photo</label>
                            <input type="file" name="thumbnail" id="image-upload"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Dish Name <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <input type="text" name="dish" class="form-control" value="{{$dish->name}}"
                               placeholder="Dish Name" parsley-trigger="change" maxlength="50" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Minimum Stock Threshold </label>
                    <div class="col-md-8">
                        <input type="number" step="0.01" name="minimum_stock_threshold" value="{{$dish->minimum_stock_threshold}}" class="form-control" placeholder="Minimum Stock Threshold" required min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Source Type <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <select name="source_type" class="form-control select2" required>
                            <option value="">Select Source</option>
                            <option value="inhouse" {{$dish->source_type == 'inhouse' ? 'selected' : ''}}>In-house</option>
                            <option value="supplier" {{$dish->source_type == 'supplier' ? 'selected' : ''}}>Supplier</option>
                        </select>
                    </div>
                </div>


            <div class="form-group">
                <label class="col-md-2 control-label">Price <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input type="number" step="0.01" name="price" value="{{$dish->price}}" class="form-control" placeholder="Price" required min="0">
                </div>
            </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">Category <span class="text-danger">*</span></label>
                    <div class="col-md-8 category-container">
                        <div class="input-group">
                            <select name="category_id" id="category_select" class="form-control select2" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}" {{ $dish->category_id == $category->id ? 'selected' : ''  }}>{{$category->name}}</option>
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


                <div class="checkbox checkbox-custom checkbox-circle col-md-offset-2">
                    <input id="checkbox71" name="available" type="checkbox" {{$dish->status == 1 ? 'checked' : ''}}>
                    <label for="checkbox71">
                        Available
                    </label>
                </div>

                <br>
                <div class="form-group">
                    <label class="col-md-2 control-label"></label>
                    <div class="col-md-10">
                        <button type="submit" class="btn btn-purple">Update Dish</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('extra-js')

    <script>
        $(document).ready(function () {

            function storeFormData() {
                var formData = {
                    'dish': $('input[name="dish"]').val()
                    // Add other form fields if needed
                };
                localStorage.setItem('dishFormData', JSON.stringify(formData));
            }

            $('.add-category').on('click', function () {
                storeFormData();
                const addWindow = window.open("/add-dish-type", "addCategoryWindow", "width=800,height=600")
            });

            window.addEventListener('message', function ($event) {
                if (event.data.type === 'categoryAdded') {
                    // Add the new category to the dropdown
                    var newOption = new Option(event.data.categoryName, event.data.categoryId);
                    $('#category_select').append(newOption);

                    // Select the newly added category
                    $('#category_select').val(event.data.categoryId);

                    // Show notification
                    $.Notification.notify('success', 'top right', 'Category Added', 'New category has been added and selected');
                }
            })

            $("#updateDish").on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                var id = $("#dishId").val();
                $(this).speedPost('/update-ready-dish/' + id, formData, message = {
                    success: {header: 'Dish Update successfully', body: 'Dish updated successfully'},
                    error: {header: 'Dish  already exist', body: 'Dish  found'},
                    warning: {header: 'Internal Server Error', body: 'Internal server error'}
                });
            })
        });
    </script>

@endsection
