@php
    $categories = \App\Models\DishCategory::where('status',1)->get();
    $dishes = \App\Models\Dish::where('status', 1)->get();
@endphp

    <!-- Menu Section -->
<section class="py-5" id="menu">
    <div class="container">
        <h2 class="text-center section-title">Our Delicious Menu</h2>

        <!-- Menu Categories -->
        <div class="d-flex flex-wrap justify-content-center mb-4">
            <button
                class="btn category-tab active"
                data-category-id="all"
                onclick="filterDishes('all')"
            >
                All Dishes
            </button>
            @foreach($categories as $category)
                <button
                    class="btn category-tab"
                    data-category-id="{{ $category->id }}"
                    onclick="filterDishes({{ $category->id }})"
                >
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        <!-- Menu Items -->
        <div class="row" id="menu-items-container">
            @foreach($dishes as $dish)
                <div class="col-lg-4 mb-4 menu-item" data-category="{{ $dish->category_id ?? 'uncategorized' }}" data-dish-id="{{ $dish->id }}">
                    <div class="card menu-card h-100">
                        <div class="position-relative">
                            <img
                                src="{{ $dish->thumbnail }}"
                                class="card-img-top menu-img"
                                alt="{{ $dish->dish }}"
                            />
                            @if($dish->category_id && ($category = \App\Models\DishCategory::find($dish->category_id)))
                                <span class="menu-badge bg-custom-primary">{{ $category->name }}</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">{{ $dish->dish }}</h4>
                            <div class="mt-3">
                                <div class="portion-options">
                                    <div class="d-flex flex-column gap-2">
                                        @foreach($dish->dishPrices as $price)
                                            <div class="portion-option p-2 border rounded bg-light">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="fw-bold">{{ $price->dish_type }}</span>
                                                    <span class="badge bg-custom-primary">{{ $price->price }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden div to store dish images -->
                            <div class="dish-images-data" style="display: none;">
                                @foreach($dish->dishImages as $image)
                                    <div
                                        class="dish-image-item"
                                        data-image="{{ $image->image }}"
                                        data-title="{{ $image->title }}"
                                    ></div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- No Results Message -->
        <div id="no-results-message" class="text-center py-5" style="display: none;">
            <div style="font-size: 64px;">🍽️</div>
            <h3 class="mt-3 mb-2">No dishes found in this category</h3>
            <p class="text-muted">Try selecting a different category or check back later for new additions.</p>
        </div>
    </div>
</section>

<!-- Add this modal structure to the end of your body tag but before the script tags -->
<div class="modal fade" id="dishModal" tabindex="-1" aria-labelledby="dishModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"
                 style="background-color: #FF6B6B; color: #fff; position: relative; padding: 15px 20px;">
                <h5 class="modal-title" id="dishModalLabel" style="font-weight: 600; font-size: 22px;">Dish
                    Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Main dish image -->
                        <img src="" id="dishMainImage" class="img-fluid rounded mb-4 w-100" alt="Dish Image"
                             style="height: 300px; object-fit: cover;">

                        <!-- Dish options with clear styling -->
                        <div class="dish-options-container mb-4">
                            <h5 style="color: #333; font-weight: 600; margin-bottom: 12px; border-left: 4px solid #FF6B6B; padding-left: 10px;">
                                Portion Options</h5>
                            <div class="dish-options"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- More photos -->
                        <h5 style="color: #333; font-weight: 600; margin-bottom: 12px; border-left: 4px solid #FF6B6B; padding-left: 10px;">
                            More Photos</h5>
                        <div class="dish-gallery row g-2"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #eee; padding: 15px;">
                <button type="button" class="btn" data-bs-dismiss="modal"
                        style="background-color: #4ECDC4; color: white; padding: 8px 24px; border-radius: 30px; font-weight: 500;">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add this JavaScript code before the closing body tag -->
<script>
    function filterDishes(categoryId) {
        const menuItems = document.querySelectorAll('.menu-item');
        const categoryTabs = document.querySelectorAll('.category-tab');
        const noResultsMessage = document.getElementById('no-results-message');

        // Remove active class from all tabs
        categoryTabs.forEach(tab => tab.classList.remove('active'));

        // Add active class to clicked tab
        document.querySelector(`[data-category-id="${categoryId}"]`).classList.add('active');

        let visibleCount = 0;

        // Show/hide menu items based on category
        menuItems.forEach(item => {
            if (categoryId === 'all' || item.dataset.category === categoryId.toString()) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Show/hide "no results" message
        if (visibleCount === 0) {
            noResultsMessage.style.display = 'block';
        } else {
            noResultsMessage.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Get all menu items
        const menuItems = document.querySelectorAll('.menu-card');

        // Add click event to each menu item
        menuItems.forEach(item => {
            item.addEventListener('click', function () {
                // Get parent container with dish ID
                const menuItemContainer = this.closest('[data-dish-id]');
                const dishId = menuItemContainer ? menuItemContainer.dataset.dishId : null;

                // Get dish information from the clicked card
                const dishName = this.querySelector('.card-title').textContent;
                const dishImage = this.querySelector('.menu-img')?.src || this.querySelector('img')?.src;

                // Get dish price options
                const dishOptions = this.querySelectorAll('.portion-option') || [];

                // Find the modal elements
                const modal = document.getElementById('dishModal');
                const modalTitle = modal.querySelector('.modal-title');
                const modalImage = document.getElementById('dishMainImage');
                const modalOptions = modal.querySelector('.dish-options');
                const modalGallery = modal.querySelector('.dish-gallery');

                // Set the modal content
                modalTitle.textContent = dishName;
                modalImage.src = dishImage;
                modalImage.alt = dishName;

                // Clear previous options and gallery
                modalOptions.innerHTML = '';
                modalGallery.innerHTML = '';

                // Add dish options to modal with improved styling
                if (dishOptions.length > 0) {
                    const optionsList = document.createElement('div');
                    optionsList.className = 'd-flex flex-column gap-2';

                    dishOptions.forEach(option => {
                        const optionClone = option.cloneNode(true);
                        // Ensure text visibility
                        const nameElement = optionClone.querySelector('.fw-bold');
                        const priceElement = optionClone.querySelector('.badge');

                        if (nameElement) nameElement.style.color = '#333';
                        if (priceElement) {
                            priceElement.style.backgroundColor = '#FF6B6B';
                            priceElement.style.color = '#fff';
                            priceElement.style.fontWeight = '600';
                            priceElement.style.padding = '6px 12px';
                        }

                        optionClone.style.backgroundColor = '#f8f9fa';
                        optionClone.style.border = '1px solid #dee2e6';
                        optionClone.style.borderRadius = '8px';
                        optionClone.style.padding = '10px 15px';

                        optionsList.appendChild(optionClone);
                    });

                    modalOptions.appendChild(optionsList);
                } else {
                    // If no options found in modern format, create new buttons with clear styling
                    const selectElement = this.querySelector('.portion-select');
                    if (selectElement) {
                        const options = Array.from(selectElement.options);

                        const optionsList = document.createElement('div');
                        optionsList.className = 'd-flex flex-column gap-2';

                        options.forEach(option => {
                            const optionDiv = document.createElement('div');
                            optionDiv.className = 'portion-option';
                            optionDiv.style.backgroundColor = '#f8f9fa';
                            optionDiv.style.border = '1px solid #dee2e6';
                            optionDiv.style.borderRadius = '8px';
                            optionDiv.style.padding = '10px 15px';
                            optionDiv.style.transition = 'all 0.2s ease';
                            optionDiv.style.cursor = 'pointer';

                            const innerDiv = document.createElement('div');
                            innerDiv.className = 'd-flex justify-content-between align-items-center';

                            const nameSpan = document.createElement('span');
                            nameSpan.style.fontWeight = '600';
                            nameSpan.style.color = '#333';
                            nameSpan.textContent = option.text.split('-')[0].trim();

                            const priceSpan = document.createElement('span');
                            priceSpan.className = 'badge';
                            priceSpan.style.backgroundColor = '#FF6B6B';
                            priceSpan.style.color = '#fff';
                            priceSpan.style.fontWeight = '600';
                            priceSpan.style.padding = '6px 12px';
                            priceSpan.style.borderRadius = '20px';
                            priceSpan.textContent = option.text.split('-')[1]?.trim() || '';

                            innerDiv.appendChild(nameSpan);
                            innerDiv.appendChild(priceSpan);
                            optionDiv.appendChild(innerDiv);

                            // Add hover effect
                            optionDiv.addEventListener('mouseover', function () {
                                this.style.backgroundColor = '#f0f0f0';
                                this.style.transform = 'translateX(5px)';
                            });

                            optionDiv.addEventListener('mouseout', function () {
                                this.style.backgroundColor = '#f8f9fa';
                                this.style.transform = 'translateX(0)';
                            });

                            optionsList.appendChild(optionDiv);
                        });

                        modalOptions.appendChild(optionsList);
                    }
                }

                // Get dish images from the hidden data div
                let dishImages = [];
                const dishImagesData = this.querySelector('.dish-images-data');

                if (dishImagesData) {
                    const imageItems = dishImagesData.querySelectorAll('.dish-image-item');
                    imageItems.forEach(item => {
                        dishImages.push({
                            image: item.dataset.image,
                            title: item.dataset.title
                        });
                    });
                }

                // If no images found in the hidden div, check for dish images elsewhere
                if (dishImages.length === 0) {
                    // Try to find dish images by dish ID
                    if (dishId) {
                        // Find the dish images container for this dish
                        const imagesContainer = document.querySelector(`[data-dish-id="${dishId}"] .dish-images-data`);
                        if (imagesContainer) {
                            const imageItems = imagesContainer.querySelectorAll('.dish-image-item');
                            imageItems.forEach(item => {
                                dishImages.push({
                                    image: item.dataset.image,
                                    title: item.dataset.title
                                });
                            });
                        }
                    }
                }

                // If still no images found, use the main image as fallback
                if (dishImages.length === 0) {
                    dishImages = [
                        { image: dishImage, title: dishName },
                        { image: dishImage, title: dishName },
                        { image: dishImage, title: dishName }
                    ];
                }

                // Add gallery images to modal with improved styling
                dishImages.forEach(img => {
                    const colDiv = document.createElement('div');
                    colDiv.className = 'col-6 mb-3';

                    const imgContainer = document.createElement('div');
                    imgContainer.style.overflow = 'hidden';
                    imgContainer.style.borderRadius = '10px';
                    imgContainer.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
                    imgContainer.style.cursor = 'pointer';
                    imgContainer.title = img.title || dishName;

                    const imgElement = document.createElement('img');
                    imgElement.src = img.image;
                    imgElement.className = 'img-fluid w-100';
                    imgElement.style.height = '140px';
                    imgElement.style.objectFit = 'cover';
                    imgElement.style.transition = 'transform 0.3s ease';
                    imgElement.alt = img.title || dishName;

                    // Add hover effect to gallery images
                    imgContainer.addEventListener('mouseover', function () {
                        imgElement.style.transform = 'scale(1.1)';
                    });

                    imgContainer.addEventListener('mouseout', function () {
                        imgElement.style.transform = 'scale(1)';
                    });

                    // Make gallery image clickable to show as main image
                    imgContainer.addEventListener('click', function() {
                        modalImage.src = img.image;
                        modalImage.alt = img.title || dishName;
                    });

                    imgContainer.appendChild(imgElement);
                    colDiv.appendChild(imgContainer);
                    modalGallery.appendChild(colDiv);
                });

                // Open the modal
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
            });
        });
    });
</script>

<!-- Add these CSS styles to your existing style tag -->
<style>
    .menu-card {
        cursor: pointer;
    }

    .modal-content {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        border: none;
    }

    .btn-close {
        opacity: 1;
    }

    /* Animation for modal */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
        transform: scale(0.9);
    }

    .modal.show .modal-dialog {
        transform: scale(1);
    }
</style>
