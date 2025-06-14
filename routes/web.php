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
        Route::get('/delete-employee/{id}', [UserController::class, 'deleteEmployee']);
        Route::post('/save-employee', [UserController::class, 'saveEmployee']);
        Route::post('/update-employee/{id}', [UserController::class, 'updateEmployee']);

        // Unit Management
        Route::get('/add-unit', [UnitController::class, 'addUnit']);
        Route::get('/all-unit', [UnitController::class, 'allUnit']);
        Route::get('/edit-unit/{id}', [UnitController::class, 'editUnit']);
        Route::get('/delete-unit/{id}', [UnitController::class, 'deleteUnit']);
        Route::get('/cannot-delete-unit/{id}', [UnitController::class, 'cannotDeleteUnit']);
        Route::post('/save-unit', [UnitController::class, 'saveUnit']);
        Route::post('/update-unit/{id}', [UnitController::class, 'updateUnit']);

        // Product Type Management
        Route::get('/add-product-type', [ProductTypeController::class, 'addProductType']);
        Route::get('/all-product-type', [ProductTypeController::class, 'allProductType']);
        Route::get('/edit-product-type/{id}', [ProductTypeController::class, 'editProductType']);
        Route::get('/delete-product-type/{id}', [ProductTypeController::class, 'deleteProductType']);
        Route::get('/cannot-delete-product-type/{id}', [ProductTypeController::class, 'cannotDeleteProductType']);
        Route::post('/save-product-type', [ProductTypeController::class, 'saveProductType']);
        Route::post('/update-product-type/{id}', [ProductTypeController::class, 'updateProductType']);

        // Dish Type Management
        Route::get('/add-dish-type', [DishCategoryController::class, 'addDishType']);
        Route::get('/all-dish-type', [DishCategoryController::class, 'allDishType']);
        Route::get('/edit-dish-type/{id}', [DishCategoryController::class, 'editDishType']);
        Route::get('/delete-dish-type/{id}', [DishCategoryController::class, 'deleteDishType']);
        Route::post('/save-dish-type', [DishCategoryController::class, 'saveDishType']);
        Route::post('/update-dish-type/{id}', [DishCategoryController::class, 'updateDishType']);

        //Dish Management
        Route::get('/add-dish', [DishController::class, 'addDish']);
        Route::get('/all-dish', [DishController::class, 'allDish']);
        Route::get('/view-dish/{id}', [DishController::class, 'viewDish']);
        Route::get('/edit-dish/{id}', [DishController::class, 'editDish']);
        Route::get('/delete-dish/{id}', [DishController::class, 'deleteDish']);
        Route::post('/save-dish', [DishController::class, 'saveDish']);
        Route::post('/update-dish/{id}', [DishController::class, 'updateDish']);

        // Dish Report
        Route::get('/dish-stat', [DishController::class, 'dishStat']);
        Route::post('/dish-stat-post', [DishController::class, 'postDishStat']);
        Route::get('/dish-stat/dish={id}/start={start_date}/end={end_date}', [DishController::class, 'showDishStat']);

        // Dish Price
        Route::get('/dish-price/{dish_id}', [DishController::class, 'addDishPrice']);
        Route::get('/edit-dish-price/{id}', [DishController::class, 'editDishPrice']);
        Route::post('/save-dish-price', [DishController::class, 'saveDishPrice']);
        Route::post('/update-dish-price/{id}', [DishController::class, 'updateDishPrice']);

        // Dish Image
        Route::get('/dish-image/{dish_id}', [DishController::class, 'addDishImage']);
        Route::get('/delete-dish-image/{id}', [DishController::class, 'deleteDishImage']);
        Route::post('/save-dish-image', [DishController::class, 'saveDishImage']);

        // Dish Recipes
        Route::get('/dish-recipe/{dish_id}', [RecipeController::class, 'addRecipe']);
        Route::get('/edit-recipes/{id}', [RecipeController::class, 'editRecipe']);
        Route::get('/delete-recipes/{id}', [RecipeController::class, 'deleteRecipe']);

        Route::post('/save-recipes/{dish_id}', [RecipeController::class, 'saveRecipe']);
        Route::post('/update-recipes/{id}', [RecipeController::class, 'updateRecipe']);

        // Table Controller
        Route::get('/all-table', [TableController::class, 'allTable']);
        Route::get('/add-table', [TableController::class, 'addTable']);
        Route::get('/edit-table/{id}', [TableController::class, 'editTable']);
        Route::get('/delete-table/{id}', [TableController::class, 'deleteTable']);
        Route::post('/save-table', [TableController::class, 'saveTable']);
        Route::post('/update-table/{id}', [TableController::class, 'updateTable']);

        // Stock Management
        Route::get('/all-item', [StockController::class, 'allStock']);
        Route::get('/add-item', [StockController::class, 'addStock']);
        Route::get('/edit-item/{id}', [StockController::class, 'editStock']);
        Route::get('/view-item/{id}', [StockController::class, 'viewStock']);
        Route::get('/delete-item/{id}', [StockController::class, 'deleteStock']);
        Route::get('/cannot-delete-item/{id}', [StockController::class, 'cannotDeleteStock']);
        Route::post('/save-item', [StockController::class, 'saveStock']);
        Route::post('/update-item/{id}', [StockController::class, 'updateStock']);

        // Purses
        Route::get('/new-purses', [PursesController::class, 'addPurses']);
        Route::get('/all-purses', [PursesController::class, 'allPurses']);
        Route::get('/edit-purses/{id}', [PursesController::class, 'editPurses']);
        Route::get('/delete-purses/{id}', [PursesController::class, 'deletePurses']);
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
        Route::post('/save-expanse', [AccountantController::class, 'saveExpanse']);
        Route::post('/update-expanse/{id}', [AccountantController::class, 'updateExpanse']);
        Route::get('/delete-expanse/{id}', [AccountantController::class, 'deleteExpanse']);
        Route::get('/all-expanse', [AccountantController::class, 'allExpanse']);
        Route::get('/all-income', [AccountantController::class, 'allIncome']);

        //Supplier
        Route::get('/all-supplier', [SupplierController::class, 'allSupplier']);
        Route::get('/add-supplier', [SupplierController::class, 'addSupplier']);
        Route::get('/view-supplier/{id}', [SupplierController::class, 'viewSupplier']);
        Route::get('/edit-supplier/{id}', [SupplierController::class, 'editSupplier']);
        Route::get('/delete-supplier/{id}', [SupplierController::class, 'deleteSupplier']);
        Route::post('/save-supplier', [SupplierController::class, 'saveSupplier']);
        Route::post('/update-supplier/{id}', [SupplierController::class, 'updateSupplier']);

        // Website
        Route::resource('/website', WebsiteController::class);

        // Page Editor
        Route::get('/page-builder/{id}', [PageEditorController::class, 'editor']);
        Route::post('/website/save-section/{section}', [PageEditorController::class, 'saveSection'])->name('website.save-section');
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
    });

    // Waiter Only
    Route::middleware(['waiter'])->group(function () {
        //Dish
        Route::get('/dish-types/{dish_id}', [RecipeController::class, 'getTypesOfDish']);
        // Orders
        Route::get('/new-order', [OrderController::class, 'newOrder']);
        Route::get('/print-order/{id}', [OrderController::class, 'printOrder']);
        Route::get('/marked-order/{id}', [OrderController::class, 'markOrder']);
        Route::get('/delete-order/{id}', [OrderController::class, 'deleteOrder']);
        Route::get('/all-order', [OrderController::class, 'allOrder']);
        Route::get('/non-paid-order', [OrderController::class, 'nonPaidOrder']);
        Route::get('/get-order-details/{id}', [OrderController::class, 'getOrderDetails']);
        Route::get('/edit-order/{id}', [OrderController::class, 'editOrder']);
        Route::post('/save-order', [OrderController::class, 'saveOrder']);
        Route::put('/update-order/{id}', [OrderController::class, 'updateOrder']);
        // Waiter Order
        Route::get('/order-served/{id}', [OrderController::class, 'orderServed']);
        // Order By Waiter
        Route::get('/my-orders', [OrderController::class, 'myOrder']);
        // Live Kitchen for waiter
        Route::get('/kitchen-status', [KitchenController::class, 'waiterLiveKitchen']);
        Route::get('/kitchen-status-waiter-json', [KitchenController::class, 'waiterLiveKitchenJSON']);
        // Waiter Stat
        Route::get('/waiter-stat', [WaiterController::class, 'waiterStat']);
        Route::post('/waiter-stat-post', [WaiterController::class, 'postWaiterStat']);
        Route::get('/waiter-stat/waiter={id}/start={start_date}/end={end_date}', [WaiterController::class, 'showWaiterStat']);
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
        Route::get('/config', [SettingsController::class, 'getConfig']);
        Route::get('/orders', [OrderController::class, 'getOrders']); // Added for order management
        Route::get('/dish-categories', [DishCategoryController::class, 'getDishCategories']);
    });

});
