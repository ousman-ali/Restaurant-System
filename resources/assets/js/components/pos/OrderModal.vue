<template>
    <div v-if="store.isOrderModalVisible" class="order-overlay" @click="store.closeOrderModal">
        <div class="order-modal" :class="{ active: store.isOrderModalVisible }" @click.stop>
            <div class="modal-drag-handle"></div>
            <button class="close-modal-btn" @click="store.closeOrderModal">×</button>

            <!-- Order Content -->
            <div class="order-header">
                <div class="current-table">
                    <span>Table {{ store.currentTable }}</span>
                    <span class="change-table-btn" @click="store.toggleTableSelection">Change</span>
                </div>
                <p>Waiter: {{ store.username }}</p>
            </div>

            <!-- Table Selection Section (Initially Hidden) -->
            <div :class="['table-selection', { active: store.isTableSelectionVisible }]">
                <h3>Select Table</h3>
                <div class="table-grid">
                    <div
                        v-for="table in store.tables"
                        :key="table.number"
                        :class="['table-item', { selected: store.currentTable === table.number }]"
                        @click="store.selectTable(table.number)"
                    >
                        <span :class="['status-indicator', table.status]"></span>
                        Table {{ table.number }}
                    </div>
                </div>
            </div>

            <!-- Empty Cart Message -->
            <div v-if="store.cartItems.length === 0" class="cart-empty">
                Your cart is empty.<br>Add items from the menu.
            </div>

            <!-- Cart Items -->
            <div v-else class="cart-items">
                <div
                    v-for="item in store.cartItems"
                    :key="item.id"
                    class="cart-item"
                >
                    <div class="cart-item-details">
                        <div class="cart-item-name">{{ item.name }}</div>
                        <div class="cart-item-variant">Portion: {{ item.portion }}</div>
<!--                        <div class="cart-item-price">${{ item.price.toFixed(2) }}</div>-->
                    </div>
                    <div class="cart-item-actions">
                        <button class="quantity-btn" @click="store.updateCartItemQuantity(item.id, -1)">-</button>
                        <span class="item-quantity">{{ item.quantity }}</span>
                        <button class="quantity-btn" @click="store.updateCartItemQuantity(item.id, 1)">+</button>
                        <button class="remove-btn" @click="store.removeCartItem(item.id)">×</button>
                    </div>
                </div>
            </div>

            <!-- Order Totals -->
            <div class="order-totals">
                <div class="total-row">
                    <span>Subtotal</span>
<!--                    <span>${{ store.subtotal.toFixed(2) }}</span>-->
                </div>
                <div class="total-row">
                    <span>Tax (8%)</span>
<!--                    <span>${{ store.tax.toFixed(2) }}</span>-->
                </div>
                <div class="total-row final">
                    <span>Total</span>
<!--                    <span>${{ store.total.toFixed(2) }}</span>-->
                </div>
            </div>

            <!-- Order Actions -->
            <div class="order-actions">
                <div class="actions-grid">
                    <div class="btn btn-outline">Save Order</div>
                    <div class="btn btn-outline">Split Bill</div>
                    <div class="btn btn-outline">Discount</div>
                    <div class="btn btn-outline">Print</div>
                    <div class="btn btn-primary">
                        <span>✓</span> Pay Now
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import useStore from './useStore';

// Initialize store
const store = useStore();
</script>

<style scoped>
/* Order Summary Overlay (Mobile Only) */
.order-overlay {
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

.order-header {
    padding: 15px;
    border-bottom: 1px solid #e0e0e0;
}

.current-table {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 5px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.change-table-btn {
    font-size: 14px;
    font-weight: normal;
    color: #3498db;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 4px;
}

.change-table-btn:hover {
    background-color: #f0f7fc;
}

.table-selection {
    padding: 15px;
    border-bottom: 1px solid #e0e0e0;
    display: none;
}

.table-selection.active {
    display: block;
}

.table-selection h3 {
    margin-bottom: 10px;
    font-size: 16px;
}

.table-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
}

.table-item {
    padding: 8px;
    border: 1px solid #eee;
    border-radius: 4px;
    cursor: pointer;
    text-align: center;
    font-size: 14px;
}

.table-item.selected {
    background-color: #e8f4fd;
    border-color: #3498db;
}

.table-item .status-indicator {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 4px;
}

.status-indicator.available {
    background-color: #2ecc71;
}

.status-indicator.occupied {
    background-color: #e74c3c;
}

.status-indicator.reserved {
    background-color: #f39c12;
}

.order-header p {
    font-size: 14px;
    color: #666;
}

.cart-items {
    overflow-y: auto;
    flex-grow: 1;
}

.cart-empty {
    padding: 30px;
    text-align: center;
    color: #888;
}

.cart-item {
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
}

.cart-item-details {
    flex-grow: 1;
}

.cart-item-name {
    font-weight: 500;
}

.cart-item-variant {
    font-size: 12px;
    color: #666;
    margin-top: 2px;
}

.cart-item-price {
    font-size: 14px;
    color: #666;
    margin-top: 2px;
}

.cart-item-actions {
    display: flex;
    align-items: center;
}

.quantity-btn {
    width: 24px;
    height: 24px;
    border: 1px solid #ddd;
    background: white;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.item-quantity {
    margin: 0 8px;
}

.remove-btn {
    width: 24px;
    height: 24px;
    border: none;
    background: none;
    cursor: pointer;
    color: #e74c3c;
    font-size: 16px;
    margin-left: 8px;
}

.order-totals {
    padding: 15px;
    border-top: 1px solid #e0e0e0;
}

.total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}

.total-row.final {
    margin-top: 12px;
    font-weight: 600;
    font-size: 16px;
}

.order-actions {
    padding: 15px;
    border-top: 1px solid #e0e0e0;
}

.actions-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.btn {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-align: center;
    cursor: pointer;
    font-size: 14px;
}

.btn-outline {
    background-color: white;
    color: #333;
}

.btn-primary {
    background-color: #3498db;
    color: white;
    border-color: #3498db;
    grid-column: span 2;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-primary span {
    margin-right: 6px;
}

@media (max-width: 767px) {
    .actions-grid {
        grid-template-columns: 1fr;
    }

    .btn-primary {
        grid-column: span 1;
    }
}
</style>
