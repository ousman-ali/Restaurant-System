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

const fetchConfig = async () => {
    try {
        const response = await axios.get('/web-api/config');
        config.value = response.data;
    } catch (err) {
        console.error('Error fetching config:', err);
    }
};

const fetchOrderById = async () => {
    if (!window.editOrderId) {
        return;
    }

    try {
        console.log('id', window.editOrderId);
        const response = await axios.get(`/get-barman-order-details/${window.editOrderId}`);
        console.log('response update cart', response.data);
        updateOrder.value = response.data;
        
        console.log('order details', response.data.order_details);
        // Map order details to cart items
        const order = response.data.order_details.map((item) => {
            console.log('ready dish', item.ready_dish);
            return {
                cartItemId: item.id,
                productId: item.ready_dish_id,
                name: item.ready_dish?.name,
                price: item.net_price,
                quantity: item.quantity,
                image: item.ready_dish?.thumbnail,
                unit: item.ready_dish?.unit.unit,
                child_unit: item.ready_dish?.unit.child_unit,
                convert_rate: item.ready_dish?.unit.convert_rate,
                child_quantity: item.quantity * item.ready_dish?.unit.convert_rate,
            };
        });

        carts.value = order;
        console.log('carts', carts);

        // Find and set the selected table
        // if (response.data?.table_id && tables.value.length > 0) {
        //     selectedTable.value = tables.value.find(el => el.id === response.data.table_id) || null;
        // }

        // Set discount amount
        discountAmount.value = response.data.discount || 0;
    } catch (err) {
        console.error('Error fetching order details:', err);
    }
};

// Cart manipulation functions
// const addProductToCart = (product) => {
//     // If no specific variant is selected, use the first price option
//     // const variant = selectedVariant || product.dish_prices[0];

//     // Check if this dish variant is already in the cart
//     const existingCartItemIndex = carts.value.findIndex(item =>
//         item.productId === product.id
//     );

//     if (existingCartItemIndex !== -1) {
//         // If the item exists, increase quantity
//         carts.value[existingCartItemIndex].quantity += 1;
//     } else {
//         // If the item doesn't exist, add it to the cart
//         carts.value.push({
//             cartItemId: Date.now(), // Unique ID for the cart item
//             productId: product.id,
//             name: product.name,
//             price: product.price,
//             quantity: 1,
//             image: product.thumbnail,
//             unit: product.unit.unit,
//             child_unit: product.unit.child_unit,
//             convert_rate: product.unit.convert_rate,
//         });
//     }

//     console.log('carts with unit', carts);
// };

const syncChildUnit = (cart) => {
    console.log('carts from sync child', carts);
    cart.child_quantity = parseFloat((cart.quantity * cart.convert_rate).toFixed(2));
};

const syncMainUnit = (cart) => {
    console.log('carts from sync main', carts);
    cart.quantity = parseFloat((cart.child_quantity / cart.convert_rate).toFixed(2));
};

const addProductToCart = (product) => {
    const sourceType = product.source_type || 'supplier'; // default fallback

    const targetCart = sourceType === 'inhouse' ? inhouseCarts : supplierCarts;

    const existingIndex = targetCart.value.findIndex(item => item.productId === product.id);

    if (existingIndex !== -1) {
        const item = targetCart.value[existingIndex];
        item.quantity += 1;
        item.child_quantity = item.quantity * item.convert_rate;
    } else {
        targetCart.value.push({
            cartItemId: Date.now(),
            productId: product.id,
            name: product.name,
            image: product.thumbnail,
            unit: product.unit.unit,
            child_unit: product.unit.child_unit,
            convert_rate: product.unit.convert_rate,
            quantity: 1,
            child_quantity: 1 * product.unit.convert_rate,
            source_type: sourceType,
        });
    }
    console.log('supplierCarts:', supplierCarts.value);
    console.log('inhouseCarts:', inhouseCarts.value);
};


// const addProductToCart = (product) => {
//     const existingCartItemIndex = carts.value.findIndex(item =>
//         item.productId === product.id
//     );

//     if (existingCartItemIndex !== -1) {
//         carts.value[existingCartItemIndex].quantity += 1;
//         carts.value[existingCartItemIndex].child_quantity =
//         carts.value[existingCartItemIndex].quantity * product.unit.convert_rate;
//     } else {
//         carts.value.push({
//             cartItemId: Date.now(),
//             productId: product.id,
//             name: product.name,
//             price: product.price,
//             image: product.thumbnail,
//             unit: product.unit.unit,
//             child_unit: product.unit.child_unit,
//             convert_rate: product.unit.convert_rate,
//             quantity: 1,
//             child_quantity: 1 * product.unit.convert_rate,
//         });
//     }

//     console.log('carts with unit', carts);
// };


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

// Order processing functions
const saveOrder = async (shouldPrint = false) => {
    const orderData = {
        supplier_items: supplierCarts.value.map(item => ({
            ready_dish_id: item.productId,
            quantity: item.quantity,
        })),

        inhouse_items: inhouseCarts.value.map(item => ({
            ready_dish_id: item.productId,
            quantity: item.quantity,
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

        showToast(message || "Order saved successfully.");
        isOrderModalVisible.value = false;

        // Optional redirect
        if (redirect) {
            window.location.href = redirect;
        }

        return response.data;
    } catch (err) {
        console.error('Error saving order:', err);
        showToast("Error saving order. Please try again.");
        throw err;
    }
};


const saveOrderWithLoading = async (event, shouldPrint = false) => {
  const laddaBtn = Ladda.create(event.currentTarget);
  laddaBtn.start();

  try {
    await saveOrder(shouldPrint);
  } catch (err) {
    showToast("Failed to save order.");
  } finally {
    laddaBtn.stop();
  }
};

const printInvoice = async (orderId) => {
    if (!orderId) {
        showToast("Cannot print receipt: Order ID is missing", 3000);
        return;
    }

    try {
        const response = await axios.get(`/print-barman-order/${orderId}`, {
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
        deleteProductFromCart,
        updateCartItemQuantity,
        clearCart,
        saveOrder,
        saveOrderWithLoading,
        printInvoice,
        showToast,
        syncChildUnit,
        syncMainUnit,

        // Make these available for manual refresh if needed
        fetchProducts,
        fetchTables,
        fetchConfig,
        fetchOrderById
    };
}
