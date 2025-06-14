<template>
    <div class="main-content">

        <!-- Content Container -->
        <div class="content-container">
            <!-- Order Area -->
            <div class="order-area">
                <MenuSection></MenuSection>

                <OrderSummary v-if="!isMobile || isOrderSummaryVisible" :class="{'order-summery-on-mobile': !isMobile || isOrderSummaryVisible}"></OrderSummary>
                <div class="mobile-overlay" @click="toggleOrderSummary" v-if="isMobile && isOrderSummaryVisible">
                    <button type="button" class="mobile-overlay-close-btn">x</button>
                </div>

                <div class="mobile-order-button" @click="toggleOrderSummary" v-if="isMobile && !isOrderSummaryVisible">
                    <div class="cart-badge" v-if="carts.length > 0">{{ carts.length }}</div>
                    <div class="mobile-button-content">
                        <span class="cart-icon">🛒</span>
                        <div class="button-text">
                            <div>Order</div>
                            <div class="total-amount">{{ config.currency.symbol }}{{ finalTotal.toFixed(2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <Toast></Toast>
    </div>
</template>

<script setup>
import OrderSummary from "./OrderSummary.vue";
import MenuSection from "./MenuSection.vue";
import Toast from "./Toast.vue";
import useStore from "./useStore.js";
import {onMounted, onUnmounted, ref} from "vue";

const {carts, config, finalTotal} = useStore();

const isMobile = ref(false);
const isOrderSummaryVisible = ref(false);

onMounted(() => {
    checkDeviceType();
    window.addEventListener('resize', checkDeviceType);
});

// Clean up event listeners when component is unmounted
onUnmounted(() => {
    window.removeEventListener('resize', checkDeviceType);
});

// Function to check if the device is mobile
const checkDeviceType = () => {
    isMobile.value = window.innerWidth <= 768; // Common breakpoint for mobile devices
};

const toggleOrderSummary = () => {
    isOrderSummaryVisible.value = !isOrderSummaryVisible.value;
}

</script>

<style scoped>
.main-content {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    height: calc(100vh - 60px);
    margin-top: -15px;
    margin-right: -17px;
}

.content-container {
    flex-grow: 1;
    display: flex;
}

/* Order Area */
.order-area {
    display: flex;
    flex-grow: 1;
    overflow: hidden;
}


/* Mobile-specific styles */
.mobile-order-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #4299e1;
    color: white;
    border-radius: 8px;
    padding: 10px 16px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    z-index: 100;
    display: flex;
    align-items: center;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.mobile-order-button:active {
    transform: scale(0.95);
}

.mobile-order-button:hover {
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
}

.mobile-button-content {
    display: flex;
    align-items: center;
}

.cart-icon {
    margin-right: 10px;
    font-size: 20px;
}

.button-text {
    display: flex;
    flex-direction: column;
}

.total-amount {
    font-weight: bold;
}

.cart-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background-color: #e74c3c;
    color: white;
    font-size: 12px;
    font-weight: bold;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.mobile-overlay {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.5);
}

.mobile-overlay-close-btn {
    position: absolute;
    left: 70px;
    top: 70px;
    background-color: white;
    color: #333;
    outline: none;
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    cursor: pointer;
    transition: all 0.2s ease;
}

.mobile-overlay-close-btn:hover, .mobile-overlay-close-btn:focus {
    transform: scale(1.05);
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
}

.mobile-overlay-close-btn:active {
    transform: scale(0.95);
}

.order-summery-on-mobile {
    z-index: 1;
}
</style>
