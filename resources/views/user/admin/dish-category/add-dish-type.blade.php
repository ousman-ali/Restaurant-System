@extends('layouts.app')

@section('title')
    Add Dish Category
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
        <h4 class="m-t-0 header-title"><b>Dish Category</b></h4>
        <p class="text-muted font-13 m-b-30">
            Add a new dish category to be used in your menu items
        </p>
        <form class="form-horizontal" role="form" id="dishTypeForm" method="POST" data-parsley-validate novalidate>
            @csrf
            <div class="form-group">
                <label for="dishName" class="col-sm-2 control-label">Dish Category*</label>
                <div class="col-sm-7">
                    <input type="text" required class="form-control" name="name" id="dishName"
                           placeholder="Category Name">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Save Category
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('extra-js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dishTypeForm = document.getElementById('dishTypeForm');
            const isPopup = window.opener && window.opener !== window;

            if (isPopup) {
                document.body.classList.add('is-popup');
            }

            // Show notification function
            function showNotification(type, title, message) {
                // Check if using jQuery notification plugin
                if (typeof $.Notification !== 'undefined') {
                    $.Notification.notify(type, 'top right', title, message);
                } else {
                    // Fallback to alert for testing
                    console.log(`${type}: ${title} - ${message}`);
                }
            }

            // Reset form function
            function resetForm(form) {
                form.reset();
            }

            // Handle form submission
            dishTypeForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                try {
                    // Get CSRF token
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // Create form data
                    const formData = new FormData(this);

                    // Send request using fetch API
                    const response = await fetch('/save-dish-type', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': token
                        },
                        credentials: 'same-origin'
                    });

                    // Parse JSON response
                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(result.message || 'Error submitting form');
                    }

                    // Show success notification
                    showNotification('success', 'Success!', 'Category added successfully');

                    // Reset the form
                    resetForm(dishTypeForm);

                    // If this is a popup, send message to parent window
                    if (isPopup && result) {
                        console.log(result)
                        // Extract category data based on response structure
                        const categoryId = result?.id
                        const categoryName = result?.name

                        if (categoryId && categoryName) {
                            // Send message to parent window
                            window.opener.postMessage({
                                type: 'categoryAdded',
                                categoryId: categoryId,
                                categoryName: categoryName
                            }, '*');

                            // Close popup after short delay
                            setTimeout(() => {
                                window.close();
                            }, 500);
                        }
                    }

                } catch (error) {
                    console.error('Error:', error);

                    // Show error notification
                    if (error.name === 'ValidationError' || error.message.includes('validation')) {
                        showNotification('error', 'Validation Error', 'Please check the form fields');
                    } else {
                        showNotification('warning', 'Error', 'Something went wrong. Please try again.');
                    }
                }
            });
        });
    </script>
@endsection
