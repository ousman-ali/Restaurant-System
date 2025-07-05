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

// const carts = ref([]);
const supplierCarts = ref([]);
const inhouseCarts = ref([]);

const carts = computed(() => [...supplierCarts.value, ...inhouseCarts.value]);
const tables = ref([]);
const products = ref([]);
const productCategories = ref([]);
const units = ref([]);
const discountAmount = ref(0);
const currentPaymentAmount = ref('');
const updateOrder = ref(null);
const selectedTable = ref(null);
const searchString = ref('');
const isOrderModalVisible = ref(false);
const toastRMessage = ref('');
const isToastRVissible = ref(false);

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
        const response = await axios.get('/web-api/ready-dishes');
        products.value = response.data;
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

const fetchUnits = async () => {
    try {
        const response = await axios.get('/web-api/units');
        units.value = response.data;
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

console.log('typeee', window.type);

const fetchOrderById = async () => {
    if (!window.editOrderId) return;

    try {
        console.log('type', window.type);
        const response = await axios.get(`/get-barman-order-details/${window.type}/${window.editOrderId}`);
        console.log('response update cart', response.data);

        updateOrder.value = response.data;

        // Clear both carts first
        inhouseCarts.value = [];
        supplierCarts.value = [];

        const orderDetails = response.data.order_details;

        orderDetails.forEach((item) => {
            const readyDish = item.ready_dish;

            if (!readyDish) return;

            const cartItem = {
                cartItemId: item.id,
                productId: readyDish.id,
                name: readyDish.name,
                image: readyDish.thumbnail,
                price: item.net_price,
                quantity: item.quantity,
                unit: readyDish.unit?.unit || '',
                child_unit: readyDish.unit?.child_unit || '',
                convert_rate: readyDish.unit?.convert_rate || 1,
                child_quantity: item.quantity * (readyDish.unit?.convert_rate || 1),
                source_type: readyDish.source_type || 'supplier', // This is crucial
            };

            // Push to correct cart
            if (cartItem.source_type === 'inhouse') {
                inhouseCarts.value.push(cartItem);
            } else {
                supplierCarts.value.push(cartItem);
            }
        });

        console.log('✅ inhouseCarts', inhouseCarts.value);
        console.log('✅ supplierCarts', supplierCarts.value);

        // If discount exists
        discountAmount.value = response.data.discount || 0;
    } catch (err) {
        console.error('Error fetching order details:', err);
    }
};


// Cart manipulation functions


const syncChildUnit = (cart) => {
    console.log('carts from sync child', carts);
    cart.child_quantity = parseFloat((cart.quantity * cart.convert_rate).toFixed(2));
};

const syncMainUnit = (cart) => {
    console.log('carts from sync main', carts);
    cart.quantity = parseFloat((cart.child_quantity / cart.convert_rate).toFixed(2));
};

const addProductToCart = (product) => {
    const sourceType = product.source_type || 'supplier'; 
    const targetCart = sourceType === 'inhouse' ? inhouseCarts : supplierCarts;
    const existingIndex = targetCart.value.findIndex(item => item.productId === product.id);
    

    if (existingIndex !== -1) {
        const item = targetCart.value[existingIndex];
        item.quantity += 1;
    } else {
        targetCart.value.push({
            cartItemId: Date.now(),
            productId: product.id,
            name: product.name,
            image: product.thumbnail,
            unit_id: '',   
            quantity: 1,
            source_type: sourceType,
        });
    }
    console.log('supplierCarts:', supplierCarts.value);
    console.log('inhouseCarts:', inhouseCarts.value);

};

const updateCartUnit = (cart) => {
    const selectedUnit = units.value.find(unit => unit.id === cart.unit_id);
    if (!selectedUnit) return;

    cart.unit_id = selectedUnit.id;
     console.log('supplierCarts:', supplierCarts.value);
    console.log('inhouseCarts:', inhouseCarts.value);
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
    const inhouseIndex = inhouseCarts.value.findIndex(item => item.cartItemId === cartItemId);
    if (inhouseIndex !== -1) {
        inhouseCarts.value.splice(inhouseIndex, 1);
        return;
    }
    const supplierIndex = supplierCarts.value.findIndex(item => item.cartItemId === cartItemId);
    if (supplierIndex !== -1) {
        supplierCarts.value.splice(supplierIndex, 1);
    }
};


const clearCart = () => {
    inhouseCarts.value = [];
    supplierCarts.value = [];
    discountAmount.value = 0;
    currentPaymentAmount.value = '';
    selectedTable.value = null;
};

// Order processing functions
const saveOrder = async (shouldPrint = false) => {
    const orderData = {
        supplier_items: supplierCarts.value.map(item => ({
            ready_dish_id: item.productId,
            quantity: item.quantity,
            unit_id: item.unit_id,
        })),

        inhouse_items: inhouseCarts.value.map(item => ({
            ready_dish_id: item.productId,
            quantity: item.quantity,
            unit_id: item.unit_id,
        })),

    };

    console.log('order data', orderData);

    try {
        let response;

        if (window.editOrderId) {
            response = await axios.put(`/update-barman-order/${window.editOrderId}`, orderData);
        } else {
            response = await axios.post('/save-barman-order', orderData);
            clearCart();
        }

        const { message, redirect } = response.data;

        showRToast(message || "Order saved successfully.");
        isOrderModalVisible.value = false;

        // Optional redirect
        if (redirect) {
            window.location.href = redirect;
        }

        return response.data;
    } catch (err) {
        console.error('Error saving order:', err);
        showRToast("Error saving order. Please try again.");
        throw err;
    }
};


const saveOrderWithLoading = async (event, shouldPrint = false) => {
  const laddaBtn = Ladda.create(event.currentTarget);
  laddaBtn.start();

  try {
    await saveOrder(shouldPrint);
  } catch (err) {
    showRToast("Failed to save order.");
  } finally {
    laddaBtn.stop();
  }
};

const printInvoice = async (orderId) => {
    if (!orderId) {
        showRToast("Cannot print receipt: Order ID is missing", 3000);
        return;
    }

    try {
        const response = await axios.get(`/print-barman-order/${orderId}`, {
            responseType: 'text'
        });

        // Open a new window for printing
        const printWindow = window.open('', '', 'width=800,height=600,toolbar=0,menubar=0,location=0');
        if (!printWindow) {
            showRToast("Unable to open print window. Please check your pop-up settings.");
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
        showRToast("Error printing receipt. Please try again.");
    }
};

const showRToast = (message, duration = 3000) => {
    toastRMessage.value = message;
    isToastRVissible.value = true;

    // Auto-hide the toast after the specified duration
    setTimeout(() => {
        isToastRVissible.value = false;
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
                await fetchDishCategories(),
                await fetchUnits(),
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
        productCategories,
        tables,
        selectedTable,
        searchString,
        carts,
        discountAmount,
        currentPaymentAmount,
        updateOrder,
        isOrderModalVisible,
        toastRMessage,
        isToastRVissible,
        units,

        // Computed
        subTotal,
        taxAmount,
        finalTotal,

        // Methods
        addProductToCart,
        deleteProductFromCart,
        updateCartItemQuantity,
        clearCart,
        saveOrder,
        saveOrderWithLoading,
        printInvoice,
        showRToast,
        syncChildUnit,
        syncMainUnit,
        updateCartUnit,

        // Make these available for manual refresh if needed
        fetchProducts,
        fetchTables,
        fetchConfig,
        fetchOrderById,
        fetchUnits,
    };
}
