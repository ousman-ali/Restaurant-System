<?php

use App\Http\Controllers\AccountantController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\DishCategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageEditorController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\PursesController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WaiterController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\ReadyDishController;
use App\Http\Controllers\ReadyDishOrderController;
use App\Http\Controllers\BakerController;
use App\Http\Controllers\MaterialRequestController;
use App\Http\Controllers\BarmanController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CashierController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', [WebsiteController::class, 'website']);
Route::post('/installation-complete', [HomeController::class, 'installSuccess']);

// Authentication
Auth::routes();

// Account Disable
Route::get('/account-disable', [HomeController::class, 'accountDisable'])->middleware('inactive.user');

//Routes only access with authenticated users
Route::middleware('active.user')->group(function () {

    // Common route
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Admin Only
    Route::middleware(['admin'])->group(function () {
        //App Settings
        Route::get('/app-settings', [SettingsController::class, 'setting']);
        Route::post('/save-pusher-conf', [SettingsController::class, 'pusherSetting']);
        Route::post('/save-mail-conf', [SettingsController::class, 'mailSetting']);
        Route::post('/save-timezone', [SettingsController::class, 'timezoneSetting']);
        Route::post('/save-currency', [SettingsController::class, 'currencySetting']);
    });


    // Admin & Shop Manager Only
    Route::middleware(['manager'])->group(function () {
        // User Management
        Route::get('/add-employee', [UserController::class, 'addEmployee']);
        Route::get('/all-employee', [UserController::class, 'allEmployees']);
        Route::get('/edit-employee/{id}', [UserController::class, 'editEmployee']);
        Route::post('/delete-employee', [UserController::class, 'deleteEmployee'])->name('employee.delete');
        Route::post('/save-employee', [UserController::class, 'saveEmployee'])->name('save.employee');
        Route::post('/update-employee/{id}', [UserController::class, 'updateEmployee'])->name('update.employee');

        // Unit Management
        Route::get('/add-unit', [UnitController::class, 'addUnit']);
        Route::get('/all-unit', [UnitController::class, 'allUnit']);
        Route::get('/edit-unit/{id}', [UnitController::class, 'editUnit']);
        Route::post('/delete-unit', [UnitController::class, 'deleteUnit'])->name('unit.delete');
        Route::get('/cannot-delete-unit/{id}', [UnitController::class, 'cannotDeleteUnit']);
        Route::post('/save-unit', [UnitController::class, 'saveUnit'])->name('save.unit');
        Route::post('/update-unit/{id}', [UnitController::class, 'updateUnit'])->name('update.unit');
        

        // Bank Management
        Route::get('/add-bank', [BankController::class, 'addBank']);
        Route::get('/all-bank', [BankController::class, 'allBank']);
        Route::get('/edit-bank/{id}', [BankController::class, 'editBank']);
        Route::post('/delete-bank', [BankController::class, 'deleteBank'])->name('bank.delete');
        Route::get('/cannot-delete-bank/{id}', [BankController::class, 'cannotDeleteBank']);
        Route::post('/save-bank', [BankController::class, 'saveBank'])->name('save.bank');
        Route::post('/update-bank/{id}', [BankController::class, 'updateBank'])->name('update.bank');

        // Product Type Management
        Route::get('/add-product-type', [ProductTypeController::class, 'addProductType']);
        Route::get('/all-product-type', [ProductTypeController::class, 'allProductType']);
        Route::get('/edit-product-type/{id}', [ProductTypeController::class, 'editProductType']);
        Route::post('/delete-product-type', [ProductTypeController::class, 'deleteProductType'])->name('product-type.delete');
        Route::get('/cannot-delete-product-type/{id}', [ProductTypeController::class, 'cannotDeleteProductType']);
        Route::post('/save-product-type', [ProductTypeController::class, 'saveProductType'])->name('save.product.type');
        Route::post('/update-product-type/{id}', [ProductTypeController::class, 'updateProductType'])->name('update.product.type');

        // Dish Type Management
        Route::get('/add-dish-type', [DishCategoryController::class, 'addDishType']);
        Route::get('/all-dish-type', [DishCategoryController::class, 'allDishType']);
        Route::get('/edit-dish-type/{id}', [DishCategoryController::class, 'editDishType']);
        Route::post('/delete-dish-type', [DishCategoryController::class, 'deleteDishType'])->name('dish-type.delete');
        Route::post('/save-dish-type', [DishCategoryController::class, 'saveDishType'])->name('save.dish.type');
        Route::post('/update-dish-type/{id}', [DishCategoryController::class, 'updateDishType'])->name('update.dish.type');

        //Dish Management
        Route::get('/add-dish', [DishController::class, 'addDish']);
        Route::get('/all-dish', [DishController::class, 'allDish']);
        Route::get('/view-dish/{id}', [DishController::class, 'viewDish']);
        Route::get('/edit-dish/{id}', [DishController::class, 'editDish']);
        Route::post('/delete-dish', [DishController::class, 'deleteDish'])->name('dish.delete');
        Route::post('/save-dish', [DishController::class, 'saveDish']);
        Route::post('/update-dish/{id}', [DishController::class, 'updateDish']);

         //Ready Dish Management
        Route::get('/add-ready-dish', [ReadyDishController::class, 'add']);
        Route::get('/all-ready-dish', [ReadyDishController::class, 'all']);
        Route::get('/view-ready-dish/{id}', [ReadyDishController::class, 'view']);
        Route::get('/edit-ready-dish/{id}', [ReadyDishController::class, 'edit']);
        Route::post('/delete-ready-dish', [ReadyDishController::class, 'delete'])->name('ready-dish.delete');
        Route::post('/save-ready-dish', [ReadyDishController::class, 'save']);
        Route::post('/update-ready-dish/{id}', [ReadyDishController::class, 'update']);


        // Dish Report
        Route::get('/dish-stat', [DishController::class, 'dishStat']);
        Route::get('/ready-dish-stat', [DishController::class, 'readyDishStat']);
        Route::post('/dish-stat-post', [DishController::class, 'postDishStat']);
        Route::post('/ready-dish-stat-post', [DishController::class, 'postReadyDishStat']);
        Route::get('/dish-stat/dish={id}/start={start_date}/end={end_date}', [DishController::class, 'showDishStat']);
        Route::get('/ready-dish-stat/dish={id}/startd={start_date}/endd={end_date}', [DishController::class, 'showReadyDishStat']);

        // Dish Price
        Route::get('/dish-price/{dish_id}', [DishController::class, 'addDishPrice']);
        Route::get('/edit-dish-price/{id}', [DishController::class, 'editDishPrice']);
        Route::post('/delete-dish-price', [DishController::class, 'deleteDishPrice'])->name('dish-price.delete');
        Route::post('/save-dish-price', [DishController::class, 'saveDishPrice']);
        Route::post('/update-dish-price/{id}', [DishController::class, 'updateDishPrice']);

        // Dish Image
        Route::get('/dish-image/{dish_id}', [DishController::class, 'addDishImage']);
        Route::post('/delete-dish-image', [DishController::class, 'deleteDishImage'])->name('dish-image.delete');
        Route::post('/save-dish-image', [DishController::class, 'saveDishImage']);

        // Dish Recipes
        Route::get('/dish-recipe/{dish_id}', [RecipeController::class, 'addRecipe']);
        Route::get('/edit-recipes/{id}', [RecipeController::class, 'editRecipe']);
        Route::get('/delete-recipes/{id}', [RecipeController::class, 'deleteRecipe']);

        Route::post('/save-recipes/{dish_id}', [RecipeController::class, 'saveRecipe']);
        Route::post('/update-recipes/{id}', [RecipeController::class, 'updateRecipe']);

         // Ready Dish Image
        Route::get('/ready-dish-image/{dish_id}', [ReadyDishController::class, 'addDishImage']);
        Route::post('/delete-ready-dish-image', [ReadyDishController::class, 'deleteDishImage'])->name('dish-image.delete');
        Route::post('/save-ready-dish-image', [ReadyDishController::class, 'saveDishImage']);

        // Ready Dish Recipes
        Route::get('/ready-dish-recipe/{dish_id}', [ReadyDishController::class, 'addRecipe']);
        Route::get('/edit-ready-recipes/{id}', [ReadyDishController::class, 'editRecipe']);
        Route::get('/delete-ready-recipes/{id}', [ReadyDishController::class, 'deleteRecipe']);

        Route::post('/save-ready-recipes/{dish_id}', [ReadyDishController::class, 'saveRecipe']);
        Route::post('/update-ready-recipes/{id}', [ReadyDishController::class, 'updateRecipe']);

        // Table Controller
        Route::get('/all-table', [TableController::class, 'allTable']);
        Route::get('/add-table', [TableController::class, 'addTable']);
        Route::get('/edit-table/{id}', [TableController::class, 'editTable']);
        Route::post('/delete-table', [TableController::class, 'deleteTable'])->name('table.delete');
        Route::post('/save-table', [TableController::class, 'saveTable'])->name('save.table');
        Route::post('/update-table/{id}', [TableController::class, 'updateTable'])->name('update.table');

        // Stock Management
        Route::get('/all-item', [StockController::class, 'allStock']);
        Route::get('/add-item', [StockController::class, 'addStock']);
        Route::get('/edit-item/{id}', [StockController::class, 'editStock']);
        Route::get('/view-item/{id}', [StockController::class, 'viewStock']);
        Route::post('/delete-item', [StockController::class, 'deleteStock'])->name('item.delete');
        Route::get('/cannot-delete-item/{id}', [StockController::class, 'cannotDeleteStock']);
        Route::post('/save-item', [StockController::class, 'saveStock'])->name('save.stock');
        Route::post('/update-item/{id}', [StockController::class, 'updateStock'])->name('update.stock');

        // Purses
        Route::get('/new-purses', [PursesController::class, 'addPurses']);
        Route::get('/all-purses', [PursesController::class, 'allPurses'])->name('all.purses');
        Route::get('/edit-purses/{id}', [PursesController::class, 'editPurses']);
        Route::post('/delete-purses', [PursesController::class, 'deletePurses'])->name('purse.delete');
        Route::get('/delete-purses-product/{id}', [PursesController::class, 'deletePursesProduct']);
        Route::post('/save-purses', [PursesController::class, 'savePurses']);
        Route::post('/save-purses-product/{purses_id}', [PursesController::class, 'savePursesProduct']);
        Route::post('/update-purses/{id}', [PursesController::class, 'updatePurses']);

        // Purses JSON
        Route::get('/get-purses-details/{id}', [PursesController::class, 'getPursesDetails']);
        Route::get('/get-unit-of-product/{id}', [PursesController::class, 'getUnitOfProduct']);

        // Purses payment
        Route::get('/purses-payment/{purses_id}', [PursesController::class, 'pursesPayment']);
        Route::post('/save-purses-payment/{purses_id}', [PursesController::class, 'savePursesPayment']);

        // AccountantController
        Route::get('/account-summary', [AccountantController::class, 'summary']);
        Route::get('/add-expense', [AccountantController::class, 'addExpanse']);
        Route::get('/edit-expanse/{id}', [AccountantController::class, 'editExpanse']);
        Route::post('/save-expanse', [AccountantController::class, 'saveExpanse'])->name('save.expanse');
        Route::post('/update-expanse/{id}', [AccountantController::class, 'updateExpanse'])->name('update.expanse');
        Route::post('/delete-expanse', [AccountantController::class, 'deleteExpanse'])->name('expense.delete');
        Route::get('/all-expanse', [AccountantController::class, 'allExpanse']);
        Route::get('/all-income', [AccountantController::class, 'allIncome']);

        //Supplier
        Route::get('/all-supplier', [SupplierController::class, 'allSupplier']);
        Route::get('/add-supplier', [SupplierController::class, 'addSupplier']);
        Route::get('/view-supplier/{id}', [SupplierController::class, 'viewSupplier']);
        Route::get('/edit-supplier/{id}', [SupplierController::class, 'editSupplier']);
        Route::post('/delete-supplier', [SupplierController::class, 'deleteSupplier'])->name('delete.supplier');
        Route::post('/save-supplier', [SupplierController::class, 'saveSupplier'])->name('save.supplier');
        Route::post('/update-supplier/{id}', [SupplierController::class, 'updateSupplier'])->name('update.supplier');

        // Website
        Route::resource('/website', WebsiteController::class);

        // Page Editor
        Route::get('/page-builder/{id}', [PageEditorController::class, 'editor']);
        Route::post('/website/save-section/{section}', [PageEditorController::class, 'saveSection'])->name('website.save-section');

        // kitchen requests
        Route::get('/kitchen/requests', [MaterialRequestController::class, 'kitchenRequest'])->name('materials.kitchen.requests');
        Route::get('/barman/requests', [MaterialRequestController::class, 'barmanRequest'])->name('materials.barman.requests');
        Route::post('/kitchen/request/approve', [MaterialRequestController::class, 'approveKitchenRequest'])->name('material-requests.approve');
        Route::post('/barman/request/approve', [MaterialRequestController::class, 'approveBarmanRequest'])->name('material-requests.barman.approve');
        Route::post('/kitchen/request/reject', [MaterialRequestController::class, 'rejectKitchenRequest'])->name('material-requests.kitchen.reject');
        Route::post('/barman/request/reject', [MaterialRequestController::class, 'rejectBarmanRequest'])->name('material-requests.barman.reject');

        Route::get('/baker/requests', [MaterialRequestController::class, 'bakerRequest'])->name('materials.baker.requests');
    });

    // Kitchen Only (All kitchen access can also access by admin or shop manager)
    Route::middleware(['kitchen'])->group(function () {
        // Kitchen
        Route::get('/kitchen-orders', [OrderController::class, 'kitchenOrderToJSON']);
        Route::get('/kitchen-start-cooking/{id}', [OrderController::class, 'kitchenStartCooking']);
        Route::get('/kitchen-complete-cooking/{id}', [OrderController::class, 'kitchenCompleteCooking']);
        Route::get('/cooking-history', [KitchenController::class, 'myCookingHistory']);
        // Live Kitchen
        Route::get('/live-kitchen', [KitchenController::class, 'liveKitchen']);
        Route::get('/live-kitchen-admin-json', [KitchenController::class, 'adminLiveKitchenJSON']);
        // Kitchen Stat
        Route::get('/kitchen-stat', [KitchenController::class, 'kitchenStat']);
        Route::post('/kitchen-stat-post', [KitchenController::class, 'postKitchenStat']);
        Route::get('/kitchen-stat/kitchen={id}/start={start_date}/end={end_date}', [KitchenController::class, 'shoeKitchenStat']);

        // kitchen stock
        Route::get('/kitchen/all-stock', [KitchenController::class, 'allStock'])->name('materials.all_stock');
        Route::get('/kitchen/low-stock', [KitchenController::class, 'lowStock'])->name('materials.low_stock');
        Route::post('/kitchen/request', [KitchenController::class, 'requestStock'])->name('material-requests.store');

        Route::get('/get-unit-of-products/{id}', [KitchenController::class, 'getUnitOfProduct']);
    });

    // baker Only (All baker access can also access by admin or shop manager)
    Route::middleware(['baker'])->group(function () {
        // Baker
        Route::get('/baker-orders', [OrderController::class, 'bakerOrderToJSON']);
        Route::get('/baker-start-cooking/{id}', [OrderController::class, 'bakerStartCooking']);
        Route::get('/baker-complete-cooking/{id}', [OrderController::class, 'bakerCompleteCooking']);
        Route::get('/baker-cooking-history', [BakerController::class, 'myCookingHistory']);
        // Live Baker
        Route::get('/live-barman', [BakerController::class, 'liveBarman']);
        Route::get('/live-barman-admin-json', [BakerController::class, 'adminLiveBarmanJSON']);
        // Baker Stat
        Route::get('/baker-stat', [BakerController::class, 'bakerStat']);
        Route::post('/baker-stat-post', [BakerController::class, 'postBakerStat']);
        Route::get('/baker-stat/baker={id}/start={start_date}/end={end_date}', [BakerController::class, 'shoeBakerStat']);

        // Route (web.php)
        Route::get('/get-ready-recipe-form/{dish}', [BakerController::class, 'getRecipeForm']);
        Route::get('/get-ready-recipe-form-all/{orderId}', [BakerController::class, 'getRecipeFormAll']);

        Route::post('/save-ready-purses', [BakerController::class, 'savePurses'])->name('ready-purse.save');

        // baker stock
        Route::get('/baker/all-stock', [BakerController::class, 'allStock'])->name('materials.baker.all_stock');
        Route::get('/baker/low-stock', [BakerController::class, 'lowStock'])->name('materials.baker.low_stock');
        Route::post('/baker/request', [BakerController::class, 'requestStock'])->name('materials.request.baker.store');
        

        Route::get('/get-unit-of-product/{id}', [BakerController::class, 'getUnitOfProduct']);

    });

    // Waiter Only 
    Route::middleware(['waiter'])->group(function () {
        //Dish
        Route::get('/dish-types/{dish_id}', [RecipeController::class, 'getTypesOfDish']);
        // Orders
        Route::get('/new-order', [OrderController::class, 'newOrder']);
        Route::get('/print-order/{id}', [OrderController::class, 'printOrder']);
        // routes/web.php
        Route::get('/print-multiple-orders', [OrderController::class, 'printMultipleOrders'])->name('orders.printMultiple');
        Route::get('/marked-order/{id}', [OrderController::class, 'markOrder']);
        Route::post('/delete-order', [OrderController::class, 'deleteOrder'])->name('order.delete');
        Route::get('/all-order', [OrderController::class, 'allOrder'])->name('all.order');
        Route::get('/non-paid-order', [OrderController::class, 'nonPaidOrder']);
        Route::get('/get-order-details/{id}', [OrderController::class, 'getOrderDetails']);
        Route::get('/edit-order/{id}', [OrderController::class, 'editOrder']);
        Route::post('/save-order', [OrderController::class, 'saveOrder']);
        Route::post('/pay-order/{id}', [OrderController::class, 'payOrder']);
        Route::put('/update-order/{id}', [OrderController::class, 'updateOrder']);
        // Waiter Order
        Route::get('/order-served/{id}', [OrderController::class, 'orderServed']);
        // Order By Waiter
        Route::get('/my-orders', [OrderController::class, 'myOrder']);
        // Live Kitchen for waiter
        Route::get('/kitchen-status', [KitchenController::class, 'waiterLiveKitchen']);
        Route::get('/barman-status', [KitchenController::class, 'waiterLiveBarman']);
        Route::get('/kitchen-status-waiter-json', [KitchenController::class, 'waiterLiveKitchenJSON']);
        Route::get('/barman-status-waiter-json', [KitchenController::class, 'waiterLiveBarmanJSON']);
        // Waiter Stat
        Route::get('/waiter-stat', [WaiterController::class, 'waiterStat']);
        Route::post('/waiter-stat-post', [WaiterController::class, 'postWaiterStat']);
        Route::get('/waiter-stat/waiter={id}/start={start_date}/end={end_date}', [WaiterController::class, 'showWaiterStat']);
    });

    // Cashier Only 
    Route::middleware(['cashier'])->group(function () {
        Route::get('/cashier-orders', [CashierController::class, 'myOrder']);
        Route::post('/cashier-pay-order/{id}', [CashierController::class, 'payOrder']);

        Route::get('/cashier-print-order/{id}', [CashierController::class, 'printOrder']);
        // routes/web.php
        Route::get('/cashier-print-multiple-orders', [CashierController::class, 'printMultipleOrders'])->name('orders.printMultiple');
    });

    // Barman Only 
    Route::middleware(['barman'])->group(function () {
        //Dish
        // Route::get('/dish-types/{dish_id}', [RecipeController::class, 'getTypesOfDish']);
        // Orders
        Route::get('/new-barman-order', [ReadyDishOrderController::class, 'newOrder'])->name('new-barman-order');
        Route::get('/print-barman-order/{id}', [ReadyDishOrderController::class, 'printOrder']);
        Route::get('/marked-barman-order/{id}', [ReadyDishOrderController::class, 'markOrder']);
        Route::post('/delete-barman-order', [ReadyDishOrderController::class, 'deleteOrder'])->name('ready.order.delete');
        Route::post('/delete-barman-supplier-order', [ReadyDishOrderController::class, 'deleteSupplierOrder'])->name('order.supplier.delete');
        Route::post('/delete-barman-inhouse.order', [ReadyDishOrderController::class, 'deleteInhouseOrder'])->name('order.inhouse.delete');
        Route::get('/all-barman-order', [ReadyDishOrderController::class, 'allOrder'])->name('all-barman.order');
        Route::get('/non-paid-barman-order', [ReadyDishOrderController::class, 'nonPaidOrder']);
        Route::get('/get-barman-order-details/{type}/{id}', [ReadyDishOrderController::class, 'getOrderDetails']);
        Route::get('/edit-barman-order/{type}/{id}', [ReadyDishOrderController::class, 'editOrder']);
        Route::post('/save-barman-order', [ReadyDishOrderController::class, 'saveOrder']);
        Route::put('/update-barman-order/{id}', [ReadyDishOrderController::class, 'updateOrder']);
        // barman Order
        Route::get('/barman-order-served/{id}', [ReadyDishOrderController::class, 'orderServed']);
        Route::get('/barman-ready-order-served/{id}', [ReadyDishOrderController::class, 'readyOrderServed']);
        Route::get('/barman-order-confirm/{id}', [ReadyDishOrderController::class, 'orderConfirm']);
        // Order By Barman
        Route::get('/my-barman-orders', [ReadyDishOrderController::class, 'myOrder'])->name('my-barman.order');
        // Live baker for barman
        Route::get('/baker-status', [BakerController::class, 'barmanLiveBaker']);
        Route::get('/waiter-status', [BakerController::class, 'barmanLiveWaiter']);
        Route::get('/admin-status', [BakerController::class, 'barmanLiveAdmin']);
        Route::get('/baker-status-waiter-json', [BakerController::class, 'barmanLiveBakerJSON']);
        // Barman Stat
        Route::get('/barman-stat', [BarmanController::class, 'barmanStat']);
        Route::post('/barman-stat-post', [BarmanController::class, 'postBarmanStat']);
        Route::get('/barman-stat/barman={id}/start={start_date}/end={end_date}', [BarmanController::class, 'showBarmanStat']);

        Route::get('/barman/all-stock', [BarmanController::class, 'allStock'])->name('materials.barman.all_stock');
        Route::get('/barman/low-stock', [BarmanController::class, 'lowStock'])->name('materials.barman.low_stock');
        Route::post('/barman/request', [BarmanController::class, 'requestStock'])->name('materials.request.barman.store');
    });

    //Profile Settings
    Route::get('/profile', [HomeController::class, 'profileInfo']);
    Route::get('/profile-edit', [HomeController::class, 'profileEdit']);
    Route::post('/post-profile', [HomeController::class, 'profileUpdate']);
    Route::post('/post-admin-profile', [HomeController::class, 'adminProfileUpdate']);
    Route::post('/change-password', [HomeController::class, 'changePassword']);

    Route::prefix('/web-api')->group(function () {
        Route::get('/tables', [TableController::class, 'getTables']);
        Route::get('/dishes', [DishController::class, 'getDishes']);
        Route::get('/banks', [DishController::class, 'getBanks']);
        Route::get('/units', [UnitController::class, 'getUnits']);
        Route::get('/ready-dishes', [DishController::class, 'getReadyDishes']);
        Route::get('/ready-products', [DishController::class, 'getReadyProducts']);
        Route::get('/config', [SettingsController::class, 'getConfig']);
        Route::get('/orders', [OrderController::class, 'getOrders']); // Added for order management
        Route::get('/dish-categories', [DishCategoryController::class, 'getDishCategories']);
        Route::get('/codes', [DishController::class, 'getOrderCodes']);
    });

});
