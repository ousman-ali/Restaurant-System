<template>
    <!-- Menu Section -->
    <div class="menu-section" id="menu-section">
        <h3 v-if="updateOrder">Order Update : {{ updateOrder.order_no }}</h3>
        <!-- Search Bar -->
        <div class="search-bar">
            <input
                id="dish-search"
                class="search-input"
                type="text"
                v-model.trim="searchString"
                placeholder="Search by name, type or price..."
                autocomplete="off"
            />
            <button id="clear-search" class="clear-search" @click="clearSearch">×</button>
        </div>

        <div class="categories-tabs">
            <div
                class="category-tab"
                :class="{ active: selectedCategoryId === null }"
                @click="selectCategory(null)"
            >
                All
            </div>
            <div
                v-for="category in productCategories"
                :key="category.id"
                class="category-tab"
                :class="{ active: selectedCategoryId === category.id }"
                @click="selectCategory(category.id)"
            >
                {{ category.name }}
            </div>
        </div>

        <div class="menu-items">
            <!-- Dish Item -->
            <div class="menu-item" v-for="product in filteredProducts" :key="product.id"
                 @click="addProductToCart(product)">
                <div
                    class="menu-item-image"
                    :style="{backgroundImage: `url(/${product.thumbnail})`}"
                ></div>
                <div class="menu-item-content">
                    <div class="menu-item-name">{{ product.dish }}</div>
                    <div class="category-tag" v-if="getCategoryName(product.category_id)">
                        {{ getCategoryName(product.category_id) }}
                    </div>
                    <div class="dish-variant-price" v-for="price in product.dish_prices" :key="price.id"
                         @click.stop="addProductToCart(product, price)">
                        <div class="variant-name">{{ price.dish_type }}</div>
                        <div class="variant-price">{{ config.currency.symbol }}{{ price.price }}</div>
                    </div>
                </div>
            </div>

            <div v-if="filteredProducts.length === 0"
                 style="grid-column: 1 / -1; text-align: center; padding: 40px 20px;">
                <div style="font-size: 36px; margin-bottom: 15px;">🍽️</div>
                <h3 style="margin-bottom: 10px; color: #666;">No dishes found</h3>
                <p style="color: #888; max-width: 300px; margin: 0 auto;">
                    We couldn't find any dishes
                    <span v-if="searchString">matching your search criteria.</span>
                    <span v-if="selectedCategoryId">in this category.</span>
                    <span>Please try a different category or search term.</span>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import useStore from "./useStore.js";
import { ref, computed } from "vue";

const { products, productCategories, searchString, addProductToCart, config, updateOrder } = useStore();
const selectedCategoryId = ref(null);

// Function to select a category
const selectCategory = (categoryId) => {
    selectedCategoryId.value = categoryId;
};

// Function to clear search
const clearSearch = () => {
    searchString.value = "";
};

// Function to get category name by id
const getCategoryName = (categoryId) => {
    if (!categoryId) return "";
    const category = productCategories.value.find(cat => cat.id === categoryId);
    return category ? category.name : "";
};

const filteredProducts = computed(() => {
    let result = products.value;

    // Filter by category if selected
    if (selectedCategoryId.value !== null) {
        result = result.filter(product => product.category_id === selectedCategoryId.value);
    }

    // Filter by search string if provided
    if (searchString.value) {
        const search = searchString.value.toLowerCase();
        result = result.filter(product => {
            // Search by dish name
            if (product.dish.toLowerCase().includes(search)) {
                return true;
            }

            // Search by dish type/variant
            if (product.dish_prices.some(price =>
                price.dish_type.toLowerCase().includes(search)
            )) {
                return true;
            }

            // Search by category name
            const categoryName = getCategoryName(product.category_id).toLowerCase();
            if (categoryName.includes(search)) {
                return true;
            }

            // Search by price range (if search is a number)
            const searchNumber = parseFloat(search);
            if (!isNaN(searchNumber)) {
                return product.dish_prices.some(price =>
                    parseFloat(price.price) <= searchNumber
                );
            }

            return false;
        });
    }

    return result;
});
</script>

<style scoped>
/* Menu Section */
.menu-section {
    height: calc(100vh - 70px);
    flex-grow: 1;
    padding: 20px;
    overflow-y: auto;
}

/* Search Bar */
.search-bar {
    display: flex;
    margin-bottom: 15px;
    position: relative;
}

.search-input {
    flex-grow: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.clear-search {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #999;
    cursor: pointer;
    font-size: 16px;
}

.categories-tabs {
    display: flex;
    overflow-x: auto;
    margin-bottom: 20px;
    border-bottom: 1px solid #e0e0e0;
    padding-bottom: 1px;
    scrollbar-width: thin;
}

.categories-tabs::-webkit-scrollbar {
    height: 4px;
}

.categories-tabs::-webkit-scrollbar-thumb {
    background-color: #ccc;
    border-radius: 4px;
}

.category-tab {
    padding: 10px 20px;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    white-space: nowrap;
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.category-tab:hover {
    color: #3498db;
    background-color: #f5f9fd;
}

.category-tab.active {
    border-bottom-color: #3498db;
    color: #3498db;
    font-weight: 600;
    background-color: #f0f8ff;
}

.menu-items {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
}

.menu-item {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.2s;
    background-color: white;
}

.menu-item:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.menu-item-image {
    width: 100%;
    height: 120px;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    transition: all 0.3s ease;
}

.menu-item:hover .menu-item-image {
    transform: scale(1.05);
}

.menu-item-content {
    padding: 12px;
}

.menu-item-name {
    font-weight: 600;
    margin-bottom: 4px;
    font-size: 16px;
}

.category-tag {
    display: inline-block;
    font-size: 11px;
    background-color: #f0f8ff;
    color: #3498db;
    padding: 2px 8px;
    border-radius: 12px;
    margin-bottom: 8px;
    border: 1px solid #d0e5f7;
}

.menu-item-variants {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 8px;
}

.dish-variant {
    background-color: #f0f0f0;
    border-radius: 4px;
    padding: 4px 8px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
}

.dish-variant.active {
    background-color: #3498db;
    color: white;
}

.dish-variant-price {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 4px;
    padding: 6px 8px;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid transparent;
    position: relative;
}

.dish-variant-price:hover {
    background-color: #f0f8ff;
    border-color: #3498db;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.dish-variant-price:active {
    transform: translateY(0);
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.variant-name {
    font-size: 13px;
    color: #666;
}

.variant-price {
    font-weight: 600;
    color: #3498db;
}


/* Floating Cart Button (Mobile Only) */
.floating-cart-btn {
    display: none;
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: #3498db;
    color: white;
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    cursor: pointer;
    z-index: 100;
    font-size: 24px;
    text-align: center;
    line-height: 60px;
}

.floating-cart-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background-color: #e74c3c;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Order Summary Overlay (Mobile Only) */
.order-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 200;
}

.order-modal {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    height: 85%;
    background-color: white;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    z-index: 201;
    box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    transform: translateY(100%);
    transition: transform 0.3s ease-out;
}

.order-modal.active {
    transform: translateY(0);
}

.modal-drag-handle {
    width: 40px;
    height: 5px;
    background-color: #ddd;
    border-radius: 3px;
    margin: 10px auto;
}

.close-modal-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: none;
    border: none;
    font-size: 24px;
    color: #666;
    cursor: pointer;
}

/* Mobile Menu Button */
.mobile-menu-btn {
    display: none;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
}

/* Media Queries for Responsive Design */
@media (max-width: 1023px) {
    .order-area {
        flex-direction: column;
    }

    .order-summary {
        display: none;
    }

    .floating-cart-btn {
        display: block;
    }

    .header-time {
        display: none;
    }
}

@media (max-width: 767px) {
    .header-left h1 {
        font-size: 18px;
    }

    .mobile-menu-btn {
        display: block;
        margin-right: 10px;
    }

    .sidebar {
        position: fixed;
        left: -60px;
        height: 100%;
        transition: left 0.3s;
        z-index: 1000;
    }

    .sidebar.open {
        left: 0;
    }

    .main-content {
        max-width: 100%;
    }

    .actions-grid {
        grid-template-columns: 1fr;
    }

    .btn-primary {
        grid-column: span 1;
    }

    .menu-items {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }

    .menu-item-image {
        height: 100px;
    }

    .table-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

/* Mobile menu overlay */
.menu-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
}

.menu-overlay.active {
    display: block;
}
</style>
