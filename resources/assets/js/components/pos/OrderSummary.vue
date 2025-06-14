<template>
    <!-- Order Summary (Desktop Only) -->
    <div class="order-summary" id="order-summary">
        <div class="order-header">
            <div class="current-table">
                <span id="current-table-display">Table:
                <span v-if="selectedTable">{{ selectedTable.table_no }}</span>
                <span v-else><i>No table selected</i></span>
                </span>
                <span class="change-table-btn" role="button" @click="tableList = !tableList">Change</span>
            </div>
        </div>

        <!-- Table Selection Section (Initially Hidden) -->
        <div class="table-selection" v-if="tableList">
            <h3>Select Table</h3>
            <div class="table-grid">
                <div class="table-item"
                     v-for="(table, index) in tables" :key="`table-${index}`"
                     :class="{'selected' : table?.id === selectedTable?.id}"
                     @click="selectedTable = table; tableList = !tableList">
                    <span class="status-indicator available"></span>
                    {{ table.table_no }}
                </div>
                <div class="table-item" @click="selectedTable = null" :class="{'selected' : selectedTable === null}">
                    <span class="status-indicator available"></span>
                    No table
                </div>
            </div>
        </div>

        <div class="cart-header">
            <h3>Items</h3>
            <button class="clear-cart-btn" @click="clearCart" v-if="carts.length > 0">
                Clear All
            </button>
        </div>

        <div class="cart-items" ref="cartItemsRef">


            <div class="cart-empty" v-if="carts.length === 0">
                <p>Your cart is empty</p>
                <p style="font-size: 12px; margin-top: 8px;">Add items from the menu to get started</p>
            </div>

            <div class="cart-item"
                 v-for="(cart, index) in carts"
                 :key="index"
                 :class="{ 'cart-item-new': animatingItems[cart.cartItemId] }">
                <div class="cart-item-details">
                    <div class="cart-item-name">{{ cart.name }}</div>
                    <div class="cart-item-variant">{{ cart.variantName }}</div>
                    <div class="cart-item-price">${{ cart.price }}</div>
                </div>
                <div class="cart-item-actions">
                    <button class="quantity-btn" @click="updateCartItemQuantity(cart.cartItemId, cart.quantity - 1)">-
                    </button>
                    <span class="item-quantity">{{ cart.quantity }}</span>
                    <button class="quantity-btn" @click="updateCartItemQuantity(cart.cartItemId, cart.quantity + 1)">+
                    </button>
                    <button class="remove-btn" @click="deleteProductFromCart(cart.cartItemId)">×</button>
                </div>
            </div>
        </div>

        <!-- Discount Section -->
        <div class="discount-section">
            <div class="discount-header">
                <label>Discount</label>
            </div>
            <div class="discount-input-group">
                <input
                    type="number"
                    v-model="discountValue"
                    placeholder="Enter discount value"
                    min="0"
                    :max="discountType === 'percentage' ? 100 : null"
                />
                <div class="discount-toggle">
                    <span
                        :class="{ active: discountType === 'percentage' }"
                        @click="discountType = 'percentage'"
                    >%</span>
                    <span
                        :class="{ active: discountType === 'fixed' }"
                        @click="discountType = 'fixed'"
                    >{{ config.currency.symbol }}</span>
                </div>
                <button class="apply-btn" @click="applyDiscount">Apply</button>
            </div>
        </div>

        <div class="order-totals">
            <div class="total-row">
                <span>Subtotal</span>
                <span>{{ config.currency.symbol }}{{ parseFloat(subTotal).toFixed(2) }}</span>
            </div>
            <div class="total-row" v-if="discountAmount > 0">
                <span>Discount</span>
                <span>-{{ config.currency.symbol }}{{ discountAmount.toFixed(2) }}</span>
            </div>
            <div class="total-row">
                <span>Tax ({{ config.vat.vat_percentage }}%)</span>
                <span>{{ config.currency.symbol }}{{ taxAmount.toFixed(2) }}</span>
            </div>
            <div class="total-row final">
                <span>Total</span>
                <span :class="{ 'animate-balance': isBalanceAnimating }">
                    {{ config.currency.symbol }}{{ finalTotal.toFixed(2) }}
                </span>
            </div>
        </div>

        <div class="order-actions">
            <div class="actions-grid">
                <div class="btn btn-outline" role="button" @click="saveOrder">
                    Save
                    <span class="shortcut-badge">F7</span>
                </div>
                <div role="button" class="btn btn-primary" @click="handleOrderAction">
                    <span>✓</span> Save & Pay
                    <span class="shortcut-badge">F6</span>
                </div>
            </div>
        </div>

        <!-- Payment Modal -->
        <div class="payment-modal" v-if="isOrderModalVisible">
            <div class="modal-overlay" @click="isOrderModalVisible = false"></div>
            <div class="modal-content">
                <div class=""
                     style="display: flex; align-items: center; justify-content: space-between; padding: 5px 15px; border-bottom: 1px solid #e4e4e4;">
                    <h3>Payment</h3>
                    <button class="close-btn" @click="isOrderModalVisible = false">×</button>
                </div>
                <div class="modal-body">
                    <div class="payment-summary">
                        <div class="summary-row">
                            <div class="summary-label"><strong>Total amount:</strong></div>
                            <div class="summary-value">
                                <strong>
                                    {{ config.currency.symbol }}
                                    {{ subTotal.toFixed(2) }}
                                </strong>
                            </div>
                        </div>
                        <div class="summary-row" v-if="discountAmount > 0">
                            <div class="summary-label">Total discount:</div>
                            <div class="summary-value">{{ config.currency.symbol }}{{ discountAmount.toFixed(2) }}</div>
                        </div>
                        <div class="summary-row">
                            <div class="summary-label">Total tax amount:</div>
                            <div class="summary-value">{{ config.currency.symbol }}{{ taxAmount.toFixed(2) }}</div>
                        </div>
                        <div class="summary-row total">
                            <div class="summary-label">
                                <strong>Total payable:</strong>
                            </div>
                            <div class="summary-value">
                                <strong>
                                    {{ config.currency.symbol }}
                                    {{ finalTotal.toFixed(2) }}
                                </strong>
                            </div>
                        </div>
                        <div class="summary-row balance"
                             :class="{ 'positive': remainingBalance > 0, 'negative': remainingBalance < 0 }">
                            <div class="summary-label"><strong>Due / Change: </strong></div>
                            <div class="summary-value">
                                <strong>
                                    {{ config.currency.symbol }}
                                    {{ parseFloat(finalTotal - currentPaymentAmount).toFixed(2) }}
                                </strong>
                            </div>
                        </div>
                    </div>


                    <!-- Add Payment -->
                    <div class="add-payment">
                        <h4>Add Payment</h4>
                        <div class="payment-input">
                            <div class="input-group">
                                <span class="currency-symbol">{{ config.currency.symbol }}</span>
                                <input
                                    autofocus
                                    @keydown.enter="saveOrder(true)"
                                    type="number"
                                    v-model.number="currentPaymentAmount"
                                    placeholder="Enter amount"
                                    :max="remainingBalance"
                                />
                            </div>
                        </div>

                    </div>

                    <!-- Complete Order Button -->
                    <div class="model-footer">
                        <div style="display: flex; gap: 15px; width: 100%;">
                            <button class="btn btn-outline" @click="saveOrder" role="button">
                                Complete Order
                            </button>
                            <button class="btn btn-success btn-block" @click="saveOrder(true)" role="button">
                                Complete & Print Order
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import useStore from './useStore';
import {ref, computed, watch, nextTick, onMounted, onUnmounted} from "vue";

// Initialize store
const {
    tables,
    carts,
    discountAmount,
    currentPaymentAmount,
    subTotal,
    taxAmount,
    finalTotal,
    selectedTable,
    isOrderModalVisible,
    deleteProductFromCart,
    updateCartItemQuantity,
    config,
    clearCart,
    saveOrder
} = useStore();

const tableList = ref(false);
const discountType = ref('percentage'); // 'percentage' or 'fixed'
const discountValue = ref(0);
const appliedDiscount = ref(0);
const isSaved = ref(false);
const cartItemsRef = ref(null);
const animatingItems = ref({}); // Track animating items by ID
const isBalanceAnimating = ref(false);
const showClearConfirm = ref(false);
const remainingBalance = ref(0);


const payments = ref([]);


// Compute the action button text
const orderActionText = computed(() => {
    return isSaved.value ? "Pay Now" : "Save & Pay";
});

// Apply discount function
const applyDiscount = () => {
    const previousTotal = finalTotal.value;

    if (discountType.value === 'percentage') {
        const percentage = Math.min(parseFloat(discountValue.value) || 0, 100);
        discountAmount.value = (subTotal.value * percentage / 100);
        appliedDiscount.value = percentage;
    } else {
        const amount = Math.min(parseFloat(discountValue.value) || 0, subTotal.value);
        discountAmount.value = amount;
        appliedDiscount.value = amount;
    }

    // Animate balance if it changed
    if (previousTotal !== finalTotal.value) {
        animateBalance();
    }
};

// Handle order action (Save & Pay or Pay Now)
const handleOrderAction = () => {
    if (!isSaved.value) {
        // Save order logic here
        isSaved.value = true;
        // Additional save logic...
    }

    // Show payment modal
    isOrderModalVisible.value = true;
};

// Function to handle keyboard shortcuts
const handleKeyboardShortcut = (event) => {
    // F2 key for payment (F1 is usually help, so we avoid that)
    if (event.key === 'F6') {
        handleOrderAction();
        event.preventDefault(); // Prevent default browser action
    }

    if (event.key === 'F7') {
        saveOrder();
        event.preventDefault();
    }
};

// Set up keyboard event listeners when component is mounted
onMounted(() => {
    window.addEventListener('keydown', handleKeyboardShortcut);
});

// Clean up event listeners when component is unmounted
onUnmounted(() => {
    window.removeEventListener('keydown', handleKeyboardShortcut);
});

// Auto-scroll cart items to bottom when items are added or modified
const scrollToBottom = () => {
    if (cartItemsRef.value) {
        nextTick(() => {
            cartItemsRef.value.scrollTop = cartItemsRef.value.scrollHeight;
        });
    }
};

// Function to add animation class to new cart items
const animateNewCartItem = (cartItemId) => {
    animatingItems.value[cartItemId] = true;
    setTimeout(() => {
        animatingItems.value[cartItemId] = false;
    }, 500); // Match animation duration
};

// Function to animate balance changes
const animateBalance = () => {
    isBalanceAnimating.value = true;
    setTimeout(() => {
        isBalanceAnimating.value = false;
    }, 700); // Match animation duration
};

// Watch for changes in the cart and handle animations and scrolling
const previousCartLength = ref(carts.value.length);
watch(() => [...carts.value], (newCart, oldCart) => {
    // Check if cart length increased (new item added)
    if (newCart.length > previousCartLength.value) {
        // Get the last item (newly added)
        const newItem = newCart[newCart.length - 1];
        animateNewCartItem(newItem.cartItemId);
        animateBalance();
    } else if (newCart.length !== oldCart.length ||
        JSON.stringify(newCart) !== JSON.stringify(oldCart)) {
        // Cart item was removed or quantities changed
        animateBalance();
    }

    previousCartLength.value = newCart.length;
    scrollToBottom();
}, {deep: true});
</script>

<style scoped>
/* Order Summary */
.order-summary {
    width: 350px;
    background-color: white;
    border-left: 1px solid #e0e0e0;
    display: flex;
    flex-direction: column;
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
    height: calc(100vh - 600px); /* Adjusted for discount section */
}

.cart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    border-bottom: 1px solid #e0e0e0;
    background-color: #f9f9f9;
}

.cart-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 500;
}

.clear-cart-btn {
    background-color: #f8f8f8;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 4px 8px;
    font-size: 12px;
    color: #e74c3c;
    cursor: pointer;
    transition: all 0.2s;
}

.clear-cart-btn:hover {
    background-color: #fee;
    border-color: #e74c3c;
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
    animation-duration: 0.5s;
}

.cart-item-new {
    animation-name: highlight-new-item;
}

@keyframes highlight-new-item {
    0% {
        background-color: rgba(52, 152, 219, 0.2);
        transform: translateX(-5px);
    }
    50% {
        background-color: rgba(52, 152, 219, 0.1);
    }
    100% {
        background-color: transparent;
        transform: translateX(0);
    }
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

/* Discount Section */
.discount-section {
    padding: 15px;
    border-top: 1px solid #e0e0e0;
}

.discount-header {
    margin-bottom: 10px;
}

.discount-header label {
    font-weight: 500;
    font-size: 15px;
    color: #333;
}

.discount-input-group {
    display: flex;
    align-items: center;
    gap: 5px;
}

.discount-input-group input {
    flex: 1;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    outline: none;
}

.discount-toggle {
    display: flex;
    border: 1px solid #ddd;
    border-radius: 4px;
    overflow: hidden;
}

.discount-toggle span {
    padding: 8px 12px;
    background-color: #f9f9f9;
    cursor: pointer;
    font-size: 14px;
}

.discount-toggle span.active {
    background-color: #e8f4fd;
    color: #3498db;
}

.apply-btn {
    padding: 8px 12px;
    background-color: #3498db;
    color: white;
    border: none;
    cursor: pointer;
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

.animate-balance {
    animation: balance-change 0.7s ease;
}

@keyframes balance-change {
    0% {
        color: #2ecc71;
        transform: scale(1.1);
    }
    100% {
        color: inherit;
        transform: scale(1);
    }
}

.order-actions {
    padding: 15px;
    border-top: 1px solid #e0e0e0;
}

.actions-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
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
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-primary span {
    margin-right: 6px;
}

.shortcut-badge {
    background-color: rgba(255, 255, 255, 0.2);
    padding: 2px 5px;
    border-radius: 3px;
    font-size: 11px;
    margin-left: 8px;
}

/* Payment Modal Styles */
.payment-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: white;
    border-radius: 8px;
    width: 450px;
    max-width: 90%;
    max-height: 90vh;
    position: relative;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    z-index: 1001;
    overflow-y: auto;
}

.modal-header {
    padding: 15px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    //top: 0;
    background-color: white;
    z-index: 2;
}

.modal-header h3 {
    margin: 0;
    font-size: 18px;
}

.close-btn {
    background: none;
    border: none;
    font-size: 22px;
    cursor: pointer;
    color: #666;
}

.modal-body {
    padding: 20px;
}

/* Payment Summary */
.payment-summary {
    margin-bottom: 20px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    padding: 15px;
    background-color: #f9f9f9;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}

.summary-row.total {
    border-top: 1px solid #e0e0e0;
    margin-top: 10px;
    padding-top: 10px;
}

.summary-row.balance {
    margin-top: 10px;
    font-size: 18px;
}

.summary-row.positive .summary-value {
    color: #e74c3c; /* Red for due amount */
}

.summary-row.negative .summary-value {
    color: #2ecc71; /* Green for change/excess payment */
}

/* Payments Made */
.payments-made {
    margin-bottom: 20px;
}

.payments-made h4 {
    margin-top: 0;
    margin-bottom: 10px;
    font-size: 16px;
}

.payment-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background-color: #f5f5f5;
    border-radius: 4px;
    margin-bottom: 8px;
}

.payment-info {
    display: flex;
    justify-content: space-between;
    flex-grow: 1;
    margin-right: 10px;
}

.remove-payment-btn {
    background: none;
    border: none;
    color: #e74c3c;
    font-size: 18px;
    cursor: pointer;
    padding: 0 5px;
}

/* Add Payment */
.add-payment {
    margin-bottom: 20px;
}

.add-payment h4 {
    margin-top: 0;
    margin-bottom: 10px;
    font-size: 16px;
}

.payment-input {
    margin-bottom: 15px;
}

.input-group {
    display: flex;
    border: 1px solid #ddd;
    border-radius: 4px;
    overflow: hidden;
}

.currency-symbol {
    background-color: #f9f9f9;
    padding: 8px 12px;
    border-right: 1px solid #ddd;
    font-weight: 500;
}

.input-group input {
    flex-grow: 1;
    padding: 8px;
    border: none;
    outline: none;
    font-size: 16px;
}

.payment-methods {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}

.payment-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 12px 10px;
    border: none;
    border-radius: 4px;
    color: white;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
}

.payment-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.payment-btn:not(:disabled):hover {
    transform: translateY(-2px);
}

.method-icon {
    font-size: 20px;
    margin-bottom: 5px;
}

/* Complete Order */
.complete-order {
    margin-top: 20px;
}

.complete-btn {
    width: 100%;
    padding: 12px;
    background-color: #2ecc71;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.complete-btn:hover {
    background-color: #27ae60;
}

.modal-footer {
    padding: 15px;
    border-top: 1px solid #e0e0e0;
    text-align: right;
    position: sticky;
    bottom: 0;
    background-color: white;
    z-index: 2;
}
</style>
