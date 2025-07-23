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
const cartOrderToType = ref(null);
const tables = ref([]);
const selectedBank = ref('');
const selectedCode = ref('');
const products = ref([]);
const readyProducts = ref([]);
const productCategories = ref([]);
const banks = ref([]);
const codes = ref([]);
const discountAmount = ref(0);
const currentPaymentAmount = ref('');
const updateOrder = ref(null);
const selectedTable = ref(null);
const searchString = ref('');
const isOrderModalVisible = ref(false);
const isOrderCodeVisible = ref(false);
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


const getCartTypeFromProduct = (product, isReadyDish = false) => {
    if (isReadyDish) return 'ready';
    return product.order_to === 'kitchen' ? 'kitchen' : 'barman';
};

const isCompatible = (newType) => {
    if (!cartOrderToType.value) return true; // cart empty
    if (cartOrderToType.value === newType) return true;
    if (
        (cartOrderToType.value === 'ready' && newType === 'barman') ||
        (cartOrderToType.value === 'barman' && newType === 'ready')
    ) return true;
    return false;
};


const fetchBanks = async () => {
    try {
        const response = await axios.get('/web-api/banks');
        banks.value = response.data;
    } catch (err) {
        console.error('Error fetching banks:', err);
    }
}

const fetchCodes = async () => {
    try {
        const response = await axios.get('/web-api/codes');
        console.log('codesssssssssss', response.data);
        codes.value = response.data;
    } catch (err) {
        console.error('Error fetching code:', err);
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
    console.log('productssssss', product);
    let stock = 0;
    if(product.source_type == 'inhouse'){
        stock = product.total_ready_quantity ?? 0;
    }else if(product.source_type == 'supplier'){
        stock = product.total_purchased_quantity ?? 0;
    }

    const newType = getCartTypeFromProduct(product, true);

    if (!isCompatible(newType)) {
        showToast('Cannot mix ready dishes with kitchen dishes.');
        return;
    }

    const existingCartItemIndex = carts.value.findIndex(item =>
        item.productId === product.id
    );

    if (existingCartItemIndex !== -1) {
        carts.value[existingCartItemIndex].quantity += 1;
    } else {
        carts.value.push({
            cartItemId: Date.now(),
            productId: product.id,
            ready_dish_id: product.id,
            dish_id: null,
            name: product.name,
            price: product.price,
            quantity: 1,
            isReadyDish: true,
            image: product.thumbnail,
            additional_note: '',
            stock: stock 
        });
        cartOrderToType.value = newType;
    }
    console.log('type', cartOrderToType.value);
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
                from_ready: item.from_ready,
                additional_note: item.additional_note,
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
    console.log('productttttttttts', product);
    const variant = selectedVariant || product.dish_prices[0];
    const newType = getCartTypeFromProduct(product, false);

    if (!isCompatible(newType)) {
        showToast('Cannot mix dishes from kitchen and bar, or ready dishes with kitchen dishes.');
        return;
    }

    for (const recipe of product.dish_recipes) {
        const cooked = recipe.product?.cooked_products?.reduce((sum, item) => sum + (item.quantity || 0), 0) || 0;
        const purchased = recipe.product?.purses?.reduce((sum, item) => sum + (item.quantity || 0), 0) || 0;
        const available = purchased - cooked;

        // Get current quantity in cart for this variant
        const existingCartItem = carts.value.find(item =>
            item.productId === product.id && item.variantId === variant.id
        );
        const currentQty = existingCartItem ? existingCartItem.quantity + 1 : 1;

        const totalNeeded = recipe.unit_needed * currentQty;

        if (totalNeeded > available) {
            showToast(`Insufficient stock for ingredient "${recipe.product.product_name}". Needed: ${totalNeeded.toFixed(2)}, Available: ${available.toFixed(2)}.`);
            return;
        }
    }

    const existingCartItemIndex = carts.value.findIndex(item =>
        item.productId === product.id && item.variantId === variant.id
    );

    if (existingCartItemIndex !== -1) {
        carts.value[existingCartItemIndex].quantity += 1;
    } else {
        carts.value.push({
            cartItemId: Date.now(),
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
            additional_note: ''
        });
        cartOrderToType.value = newType;
    }
    console.log('type', cartOrderToType.value);
};

const updateCartItemQuantity = (cartItemId, newQuantity) => {
    const index = carts.value.findIndex(item => item.cartItemId === cartItemId);
    if (index === -1) return;

    const cartItem = carts.value[index];

    // 🔹 For ready dishes
    if (cartItem.isReadyDish) {
        if (newQuantity > cartItem.stock) {
            showToast('The added amount exceeds available stock.');
            return;
        }
    }

    // 🔸 For kitchen-made dishes
    else {
        const product = products.value.find(p => p.id === cartItem.productId);
        const variant = product?.dish_prices?.find(v => v.id === cartItem.variantId);

        if (!product || !variant) {
            showToast('Product data not found.');
            return;
        }

        for (const recipe of product.dish_recipes) {
            const cooked = recipe.product?.cooked_products?.reduce((sum, item) => sum + (item.quantity || 0), 0) || 0;
            const purchased = recipe.product?.purses?.reduce((sum, item) => sum + (item.quantity || 0), 0) || 0;
            const available = purchased - cooked;

            const totalNeeded = recipe.unit_needed * newQuantity;

            if (totalNeeded > available) {
                showToast(`Insufficient stock for ingredient "${recipe.product.product_name}". Needed: ${totalNeeded.toFixed(2)}, Available: ${available.toFixed(2)}.`);
                return;
            }
        }
    }

    // ✅ Passed validation – proceed
    if (newQuantity <= 0) {
        carts.value.splice(index, 1);
    } else {
        carts.value[index].quantity = newQuantity;
    }
};



// const updateCartItemQuantity = (cartItemId, newQuantity) => {
//     const index = carts.value.findIndex(item => item.cartItemId === cartItemId);
//     if (index !== -1) {
//         const cartItem = carts.value[index];
//         if (newQuantity > cartItem.stock) {
//             showToast('The added amount exceeds available stock.');
//             return;
//         }
//         if (newQuantity <= 0) {
//             // Remove item if quantity is zero or negative
//             carts.value.splice(index, 1);
//         } else {
//             // Update quantity
//             carts.value[index].quantity = newQuantity;
//         }
//     }
// };

const deleteProductFromCart = (cartItemId) => {
    const index = carts.value.findIndex(item => item.cartItemId === cartItemId);
    if (index !== -1) {
        carts.value.splice(index, 1);
    }
};

const clearCart = () => {
    carts.value = [];
    cartOrderToType.value = null;
    discountAmount.value = 0;
    currentPaymentAmount.value = '';
    selectedTable.value = null;
};


console.log('carts', carts);
// Order processing functions
const saveOrder = async (shouldPrint = false) => {
    console.log('status', !selectedCode.value || selectedCode.value === '');
    if(!selectedCode.value || selectedCode.value === ''){
        showToast('Please select an order code.');
        return;
    }
    const orderData = {
        table_id: selectedTable.value ? selectedTable.value.id : null,
        payment: currentPaymentAmount.value ? currentPaymentAmount.value : null,
        vat: taxAmount.value ? taxAmount.value : 0,
        change_amount: currentPaymentAmount.value ? (finalTotal.value - currentPaymentAmount.value) : 0,
        discount_amount: discountAmount.value ? discountAmount.value : 0,
        bank_id: selectedBank.value || null,
        code : selectedCode.value,
        order_to: cartOrderToType.value,
        items: carts.value.map(item => ({
            dish_id: item.dish_id,
            ready_dish_id: item.ready_dish_id ?? null,
            dish_type_id: item.variantId,
            quantity: item.quantity,
            net_price: item.price,
            gross_price: item.price * item.quantity,
            is_ready:item.isReadyDish,
            from_ready: item.ready_dish_id != null,
            additional_note: item.additional_note ?? null,
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
        isOrderCodeVisible.value = false;

        // if (shouldPrint && response.data.id) {
        //     printInvoice(response.data.id);
        // }

        const { message, redirect } = response.data;
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
                await fetchDishCategories(),
                await fetchBanks(),
                await fetchCodes(),
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
        selectedBank,
        selectedCode,
        readyProducts,
        productCategories,
        banks,
        codes,
        tables,
        selectedTable,
        searchString,
        carts,
        discountAmount,
        currentPaymentAmount,
        updateOrder,
        isOrderModalVisible,
        isOrderCodeVisible,
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
