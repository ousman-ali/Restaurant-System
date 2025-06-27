// useStore.js
import {ref, computed, readonly, onMounted} from 'vue';
import * as Ladda from 'ladda';
// Create a singleton pattern to ensure the store is only initialized once
let initialized = false;
let initializationPromise = null;



// Common state that will be shared across all instances
const config = ref({
    "hasInstall": "1",
    "currency": {
        "symbol": "$",
        "currency": "USD"
    },
    "vat": {
        "vat_number": "123456",
        "vat_percentage": "5"
    },
    "contact": {
        "phone": "01738070062",
        "address": "Address"
    }
});

const carts = ref([]);
const tables = ref([]);
const products = ref([]);
const readyProducts = ref([]);
const productCategories = ref([]);
const discountAmount = ref(0);
const currentPaymentAmount = ref('');
const updateOrder = ref(null);
const selectedTable = ref(null);
const searchString = ref('');
const isOrderModalVisible = ref(false);
const toastMessage = ref('');
const isToastVisible = ref(false);

// Computed properties
const subTotal = computed(() => {
    return carts.value.reduce((total, item) => {
        return total + (item.price * item.quantity);
    }, 0);
});

const taxAmount = computed(() => {
    const afterDiscountAmount = subTotal.value - discountAmount.value;
    return afterDiscountAmount * (parseInt(config.value.vat.vat_percentage) / 100);
});

const finalTotal = computed(() => {
    return subTotal.value - discountAmount.value + taxAmount.value;
});

// API functions
const fetchProducts = async () => {
    try {
        const response = await axios.get('/web-api/dishes');
        const readyResponse = await axios.get('/web-api/ready-products');
        products.value = response.data;
        readyProducts.value = readyResponse.data;
    } catch (err) {
        console.error('Error fetching products:', err);
    }
};

const fetchTables = async () => { 
    try {
        const response = await axios.get('/web-api/tables');
        tables.value = response.data;
    } catch (err) {
        console.error('Error fetching tables:', err);
    }
};

const fetchDishCategories = async () => {
    try {
        const response = await axios.get('/web-api/dish-categories');
        productCategories.value = response.data;
    } catch (err) {
        console.error('Error fetching product categories:', err);
    }
}

const fetchConfig = async () => {
    try {
        const response = await axios.get('/web-api/config');
        config.value = response.data;
    } catch (err) {
        console.error('Error fetching config:', err);
    }
};

const addReadyProductToCart = (product) => {
    // If no specific variant is selected, use the first price option
    // const variant = selectedVariant || product.dish_prices[0];

    // Check if this dish variant is already in the cart
    const existingCartItemIndex = carts.value.findIndex(item =>
        item.productId === product.id
    );

    if (existingCartItemIndex !== -1) {
        // If the item exists, increase quantity
        carts.value[existingCartItemIndex].quantity += 1;
    } else {
        // If the item doesn't exist, add it to the cart
        carts.value.push({
            cartItemId: Date.now(), // Unique ID for the cart item
            productId: product.id,
            ready_dish_id:product.id,
            dish_id: null,
            name: product.name,
            price: product.price,
            quantity: 1,
            isReadyDish:true,
            image: product.thumbnail
        });

        console.log('carts', carts);
    }
};

const fetchOrderById = async () => {
    if (!window.editOrderId) {
        return;
    }

    try {
        const response = await axios.get(`/get-order-details/${window.editOrderId}`);
        updateOrder.value = response.data;
        console.log('order', response.data);
        const order = response.data.order_details.map((item) => {
            console.log('items', item);
            const productId = item.ready_dish_id ?? item.dish_id;
            return {
                cartItemId: item.id,
                productId: productId,
                dish_id: item.dish_id,
                ready_dish_id: item.ready_dish_id,
                variantId: item.dish_type_id,
                name: item.ready_dish_id ? item.ready_dish.name : item.dish?.dish,
                variantName: item.dish_type?.dish_type,
                price: item.ready_dish_id ? item.ready_dish.price :item.dish_type?.price,
                quantity: item.quantity,
                image: item.ready_dish_id ? item.ready_dish.thumbnail : item.dish?.thumbnail,
                isReadyDish:response.data.is_ready,
            };
        });

        carts.value = order;

        // Find and set the selected table
        if (response.data?.table_id && tables.value.length > 0) {
            selectedTable.value = tables.value.find(el => el.id === response.data.table_id) || null;
        }

        // Set discount amount
        discountAmount.value = response.data.discount || 0;
    } catch (err) {
        console.error('Error fetching order details:', err);
    }
};


const saveOrderWithLoading = async (event, shouldPrint = false) => {
  const laddaBtn = Ladda.create(event.currentTarget);
  laddaBtn.start();

  try {
    await saveOrder(shouldPrint); // your existing function
  } catch (err) {
    showToast("Failed to save order.");
  } finally {
    laddaBtn.stop();
  }
};


// Cart manipulation functions
const addProductToCart = (product, selectedVariant = null) => {
    // If no specific variant is selected, use the first price option
    const variant = selectedVariant || product.dish_prices[0];

    // Check if this dish variant is already in the cart
    const existingCartItemIndex = carts.value.findIndex(item =>
        item.productId === product.id && item.variantId === variant.id
    );

    if (existingCartItemIndex !== -1) {
        // If the item exists, increase quantity
        carts.value[existingCartItemIndex].quantity += 1;
    } else {
        // If the item doesn't exist, add it to the cart
        carts.value.push({
            cartItemId: Date.now(), // Unique ID for the cart item
            productId: product.id,
            ready_dish_id: null,
            dish_id: product.id,
            variantId: variant.id,
            name: product.dish,
            variantName: variant.dish_type,
            price: variant.price,
            quantity: 1,
            image: product.thumbnail,
            isReadyDish: false,
        });

        console.log('carts', carts);
    }
};

const updateCartItemQuantity = (cartItemId, newQuantity) => {
    const index = carts.value.findIndex(item => item.cartItemId === cartItemId);
    if (index !== -1) {
        if (newQuantity <= 0) {
            // Remove item if quantity is zero or negative
            carts.value.splice(index, 1);
        } else {
            // Update quantity
            carts.value[index].quantity = newQuantity;
        }
    }
};

const deleteProductFromCart = (cartItemId) => {
    const index = carts.value.findIndex(item => item.cartItemId === cartItemId);
    if (index !== -1) {
        carts.value.splice(index, 1);
    }
};

const clearCart = () => {
    carts.value = [];
    discountAmount.value = 0;
    currentPaymentAmount.value = '';
    selectedTable.value = null;
};

console.log('carts', carts);
// Order processing functions
const saveOrder = async (shouldPrint = false) => {
    const orderData = {
        table_id: selectedTable.value ? selectedTable.value.id : null,
        payment: currentPaymentAmount.value ? currentPaymentAmount.value : null,
        vat: taxAmount.value ? taxAmount.value : 0,
        change_amount: currentPaymentAmount.value ? (finalTotal.value - currentPaymentAmount.value) : 0,
        discount_amount: discountAmount.value ? discountAmount.value : 0,
        items: carts.value.map(item => ({
            dish_id: item.dish_id,
            ready_dish_id: item.ready_dish_id ?? null,
            dish_type_id: item.variantId,
            quantity: item.quantity,
            net_price: item.price,
            gross_price: item.price * item.quantity,
            is_ready:item.isReadyDish,
        }))
        
    };
    console.log('order data', orderData);


    try {
        let response;

        if (window.editOrderId) {
            response = await axios.put(`/update-order/${window.editOrderId}`, orderData);
            showToast("Order updated successfully.");
        } else {
            console.log('order data', orderData);
            response = await axios.post('/save-order', orderData);
            clearCart();
            showToast("Order saved successfully.");
        }

        isOrderModalVisible.value = false;

        // if (shouldPrint && response.data.id) {
        //     printInvoice(response.data.id);
        // }

        return response.data;
    } catch (err) {
        console.error('Error saving order:', err);
        showToast("Error saving order. Please try again.");
        throw err;
    }
};

const printInvoice = async (orderId) => {
    if (!orderId) {
        showToast("Cannot print receipt: Order ID is missing", 3000);
        return;
    }

    try {
        const response = await axios.get(`/print-order/${orderId}`, {
            responseType: 'text'
        });

        // Open a new window for printing
        const printWindow = window.open('', '', 'width=800,height=600,toolbar=0,menubar=0,location=0');
        if (!printWindow) {
            showToast("Unable to open print window. Please check your pop-up settings.");
            return;
        }

        // Write the HTML from the backend to the new window
        printWindow.document.write(response.data);
        printWindow.document.close();

        // Trigger print when content is loaded
        printWindow.onload = function () {
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
                // Close the window after printing
                printWindow.onafterprint = function () {
                    printWindow.close();
                };
                // Fallback close for browsers that don't support onafterprint
                setTimeout(() => {
                    printWindow.close();
                }, 1000);
            }, 500);
        };
    } catch (error) {
        console.error('Error printing invoice:', error);
        showToast("Error printing receipt. Please try again.");
    }
};

const showToast = (message, duration = 3000) => {
    toastMessage.value = message;
    isToastVisible.value = true;

    // Auto-hide the toast after the specified duration
    setTimeout(() => {
        isToastVisible.value = false;
    }, duration);
};

// Initialize data
const initializeStore = async () => {
    if (initializationPromise) {
        return initializationPromise;
    }

    initializationPromise = (async () => {
        if (!initialized) {
            // Run these in parallel for efficiency
            const promises = [
                await fetchProducts(),
                await fetchTables(),
                await fetchConfig(),
                await fetchDishCategories()
            ];

            await Promise.all(promises);

            // This depends on tables being loaded, so we do it after
            if (window.editOrderId) {
                await fetchOrderById();
            }

            initialized = true;
        }
    })();

    return initializationPromise;
};

export default function useStore() {
    // Initialize data on first use
    onMounted(() => {
        initializeStore();
    });

    return {
        // State
        config,
        products,
        readyProducts,
        productCategories,
        tables,
        selectedTable,
        searchString,
        carts,
        discountAmount,
        currentPaymentAmount,
        updateOrder,
        isOrderModalVisible,
        toastMessage,
        isToastVisible,

        // Computed
        subTotal,
        taxAmount,
        finalTotal,

        // Methods
        addProductToCart,
        addReadyProductToCart,
        deleteProductFromCart,
        updateCartItemQuantity,
        clearCart,
        saveOrder,
        saveOrderWithLoading,
        printInvoice,
        showToast,

        // Make these available for manual refresh if needed
        fetchProducts,
        fetchTables,
        fetchConfig,
        fetchOrderById
    };
}
