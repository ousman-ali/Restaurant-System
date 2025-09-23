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
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
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
        //App Settings
        Route::middleware(['auth', 'permission:settings.configure'])->get('/app-settings', [SettingsController::class, 'setting'])->name('settings.configure');
        Route::post('/save-pusher-conf', [SettingsController::class, 'pusherSetting']);
        Route::post('/save-mail-conf', [SettingsController::class, 'mailSetting']);
        Route::post('/save-timezone', [SettingsController::class, 'timezoneSetting']);
        Route::post('/save-currency', [SettingsController::class, 'currencySetting']);

        Route::resource('roles', RoleController::class);
        Route::get('/permissions', [PermissionController::class, 'index'])->name('');


    // Admin & Shop Manager Only
        // User Management
        Route::middleware(['auth', 'permission:employee.create'])->get('/add-employee', [UserController::class, 'addEmployee'])->name('employee.create');
        Route::middleware(['auth', 'permission:employee.list'])->get('/all-employee', [UserController::class, 'allEmployees'])->name('employee.list');
        Route::middleware(['auth', 'permission:employee.edit'])->get('/edit-employee/{id}', [UserController::class, 'editEmployee'])->name('employee.edit');
        Route::middleware(['auth', 'permission:employee.delete'])->post('/delete-employee', [UserController::class, 'deleteEmployee'])->name('employee.delete');
        Route::middleware(['auth', 'permission:employee.save'])->post('/save-employee', [UserController::class, 'saveEmployee'])->name('employee.save');
        Route::middleware(['auth', 'permission:employee.update'])->post('/update-employee/{id}', [UserController::class, 'updateEmployee'])->name('employee.update');

        // Unit Management
        Route::middleware(['auth', 'permission:unit.create'])->get('/add-unit', [UnitController::class, 'addUnit'])->name('unit.create');
        Route::middleware(['auth', 'permission:unit.list'])->get('/all-unit', [UnitController::class, 'allUnit'])->name('unit.list');
        Route::middleware(['auth', 'permission:unit.edit'])->get('/edit-unit/{id}', [UnitController::class, 'editUnit'])->name('unit.edit');
        Route::middleware(['auth', 'permission:unit.delete'])->post('/delete-unit', [UnitController::class, 'deleteUnit'])->name('unit.delete');
        Route::middleware(['auth', 'permission:unit.save'])->post('/save-unit', [UnitController::class, 'saveUnit'])->name('unit.save');
        Route::middleware(['auth', 'permission:unit.update'])->post('/update-unit/{id}', [UnitController::class, 'updateUnit'])->name('unit.update');
        Route::get('/cannot-delete-unit/{id}', [UnitController::class, 'cannotDeleteUnit']);
        

        // Bank Management
        Route::middleware(['auth', 'permission:bank.create'])->get('/add-bank', [BankController::class, 'addBank']);
        Route::middleware(['auth', 'permission:bank.list'])->get('/all-bank', [BankController::class, 'allBank']);
        Route::middleware(['auth', 'permission:bank.edit'])->get('/edit-bank/{id}', [BankController::class, 'editBank']);
        Route::middleware(['auth', 'permission:bank.delete'])->post('/delete-bank', [BankController::class, 'deleteBank'])->name('bank.delete');
        Route::middleware(['auth', 'permission:bank.save'])->post('/save-bank', [BankController::class, 'saveBank'])->name('bank.save');
        Route::middleware(['auth', 'permission:bank.update'])->post('/update-bank/{id}', [BankController::class, 'updateBank'])->name('bank.update');
        Route::get('/cannot-delete-bank/{id}', [BankController::class, 'cannotDeleteBank']);

        // Product Type Management
        Route::middleware(['auth', 'permission:product-type.create'])->get('/add-product-type', [ProductTypeController::class, 'addProductType'])->name('product-type.create');
        Route::middleware(['auth', 'permission:product-type.list'])->get('/all-product-type', [ProductTypeController::class, 'allProductType'])->name('product-type.list');
        Route::middleware(['auth', 'permission:product-type.edit'])->get('/edit-product-type/{id}', [ProductTypeController::class, 'editProductType']);
        Route::middleware(['auth', 'permission:product-type.delete'])->post('/delete-product-type', [ProductTypeController::class, 'deleteProductType'])->name('product-type.delete');
        Route::middleware(['auth', 'permission:product-type.save'])->post('/save-product-type', [ProductTypeController::class, 'saveProductType'])->name('product-type.save');
        Route::middleware(['auth', 'permission:product-type.update'])->post('/update-product-type/{id}', [ProductTypeController::class, 'updateProductType'])->name('product-type.update');
        Route::get('/cannot-delete-product-type/{id}', [ProductTypeController::class, 'cannotDeleteProductType']);

        // Dish Type Management
        Route::middleware(['auth', 'permission:dish-type.create'])->get('/add-dish-type', [DishCategoryController::class, 'addDishType'])->name('dish-type.create');
        Route::middleware(['auth', 'permission:dish-type.list'])->get('/all-dish-type', [DishCategoryController::class, 'allDishType'])->name('dish-type.list');
        Route::middleware(['auth', 'permission:dish-type.edit'])->get('/edit-dish-type/{id}', [DishCategoryController::class, 'editDishType'])->name('dish-type.edit');
        Route::middleware(['auth', 'permission:dish-type.delete'])->post('/delete-dish-type', [DishCategoryController::class, 'deleteDishType'])->name('dish-type.delete');
        Route::middleware(['auth', 'permission:dish-type.save'])->post('/save-dish-type', [DishCategoryController::class, 'saveDishType'])->name('dish-type.save');
        Route::middleware(['auth', 'permission:dish-type.update'])->post('/update-dish-type/{id}', [DishCategoryController::class, 'updateDishType'])->name('dish-type.update');

        //Dish Management
        Route::middleware(['auth', 'permission:dish.create'])->get('/add-dish', [DishController::class, 'addDish'])->name('dish.create');
        Route::middleware(['auth', 'permission:dish.list'])->get('/all-dish', [DishController::class, 'allDish'])->name('dish.list');
        Route::middleware(['auth', 'permission:dish.view'])->get('/view-dish/{id}', [DishController::class, 'viewDish'])->name('dish.view');
        Route::middleware(['auth', 'permission:dish.edit'])->get('/edit-dish/{id}', [DishController::class, 'editDish'])->name('dish.edit');
        Route::middleware(['auth', 'permission:dish.delete'])->post('/delete-dish', [DishController::class, 'deleteDish'])->name('dish.delete');
        Route::middleware(['auth', 'permission:dish.save'])->post('/save-dish', [DishController::class, 'saveDish'])->name('dish.save');
        Route::middleware(['auth', 'permission:dish.update'])->post('/update-dish/{id}', [DishController::class, 'updateDish'])->name('dish.update');

         //Ready Dish Management
        Route::middleware(['auth', 'permission:ready-dish.create'])->get('/add-ready-dish', [ReadyDishController::class, 'add'])->name('ready-dish.create');
        Route::middleware(['auth', 'permission:ready-dish.list'])->get('/all-ready-dish', [ReadyDishController::class, 'all'])->name('ready-dish.list');
        Route::middleware(['auth', 'permission:ready-dish.view'])->get('/view-ready-dish/{id}', [ReadyDishController::class, 'view'])->name('ready-dish.view');
        Route::middleware(['auth', 'permission:ready-dish.edit'])->get('/edit-ready-dish/{id}', [ReadyDishController::class, 'edit'])->name('ready-dish.edit');
        Route::middleware(['auth', 'permission:ready-dish.delete'])->post('/delete-ready-dish', [ReadyDishController::class, 'delete'])->name('ready-dish.delete');
        Route::middleware(['auth', 'permission:ready-dish.save'])->post('/save-ready-dish', [ReadyDishController::class, 'save'])->name('ready-dish.save');
        Route::middleware(['auth', 'permission:ready-dish.update'])->post('/update-ready-dish/{id}', [ReadyDishController::class, 'update'])->name('ready-dish.update');


        // Dish Report
        Route::middleware(['auth', 'permission:dish.dishStat'])->get('/dish-stat', [DishController::class, 'dishStat'])->name('dish.dishStat');
        Route::middleware(['auth', 'permission:ready-dish.readyDishStat'])->get('/ready-dish-stat', [DishController::class, 'readyDishStat'])->name('ready-dish.readyDishStat');
        Route::middleware(['auth', 'permission:dish.postDishStat'])->post('/dish-stat-post', [DishController::class, 'postDishStat'])->name('dish.postDishStat');
        Route::middleware(['auth', 'permission:ready-dish.postReadyDishStat'])->post('/ready-dish-stat-post', [DishController::class, 'postReadyDishStat'])->name('ready-dish.postReadyDishStat');
        Route::middleware(['auth', 'permission:dish.showDishStat'])->get('/dish-stat/dish={id}/start={start_date}/end={end_date}', [DishController::class, 'showDishStat'])->name('dish.showDishStat');
        Route::middleware(['auth', 'permission:ready-dish.showReadyDishStat'])->get('/ready-dish-stat/dish={id}/startd={start_date}/endd={end_date}', [DishController::class, 'showReadyDishStat'])->name('ready-dish.showReadyDishStat');

        // Dish Price
        Route::middleware(['auth', 'permission:dish-price.create'])->get('/dish-price/{dish_id}', [DishController::class, 'addDishPrice'])->name('dish-price.create');
        Route::middleware(['auth', 'permission:dish-price.edit'])->get('/edit-dish-price/{id}', [DishController::class, 'editDishPrice'])->name('dish-price.edit');
        Route::middleware(['auth', 'permission:dish-price.delete'])->post('/delete-dish-price', [DishController::class, 'deleteDishPrice'])->name('dish-price.delete');
        Route::middleware(['auth', 'permission:dish-price.save'])->post('/save-dish-price', [DishController::class, 'saveDishPrice'])->name('dish-price.save');
        Route::middleware(['auth', 'permission:dish-price.update'])->post('/update-dish-price/{id}', [DishController::class, 'updateDishPrice'])->name('dish-price.update');

        // Dish Image
        Route::middleware(['auth', 'permission:dish-image.create'])->get('/dish-image/{dish_id}', [DishController::class, 'addDishImage'])->name('dish-image.create');
        Route::middleware(['auth', 'permission:dish-image.delete'])->post('/delete-dish-image', [DishController::class, 'deleteDishImage'])->name('dish-image.delete');
        Route::middleware(['auth', 'permission:dish-image.save'])->post('/save-dish-image', [DishController::class, 'saveDishImage'])->name('dish-image.save');

        // Dish Recipes
        Route::middleware(['auth', 'permission:dish-recipe.create'])->get('/dish-recipe/{dish_id}', [RecipeController::class, 'addRecipe'])->name('dish-recipe.create');
        Route::middleware(['auth', 'permission:dish-recipe.edit'])->get('/edit-recipes/{id}', [RecipeController::class, 'editRecipe'])->name('dish-recipe.edit');
        Route::middleware(['auth', 'permission:dish-recipe.delete'])->get('/delete-recipes/{id}', [RecipeController::class, 'deleteRecipe'])->name('dish-recipe.delete');

        Route::middleware(['auth', 'permission:recipe.save'])->post('/save-recipes/{dish_id}', [RecipeController::class, 'saveRecipe'])->name('recipe.save');
        Route::middleware(['auth', 'permission:recipe.update'])->post('/update-recipes/{id}', [RecipeController::class, 'updateRecipe'])->name('recipe.update');

         // Ready Dish Image
        Route::middleware(['auth', 'permission:ready-dish-image.create'])->get('/ready-dish-image/{dish_id}', [ReadyDishController::class, 'addDishImage'])->name('ready-dish-image.create');
        Route::middleware(['auth', 'permission:ready-dish-image.delete'])->post('/delete-ready-dish-image', [ReadyDishController::class, 'deleteDishImage'])->name('ready-dish-image.delete');
        Route::middleware(['auth', 'permission:ready-dish-image.save'])->post('/save-ready-dish-image', [ReadyDishController::class, 'saveDishImage'])->name('ready-dish-image.save');

        // Ready Dish Recipes
        Route::middleware(['auth', 'permission:ready-dish-recipe.create'])->get('/ready-dish-recipe/{dish_id}', [ReadyDishController::class, 'addRecipe'])->name('ready-dish-recipe.create');
        Route::middleware(['auth', 'permission:ready-dish-recipe.edit'])->get('/edit-ready-recipes/{id}', [ReadyDishController::class, 'editRecipe'])->name('ready-dish-recipe.edit');
        Route::middleware(['auth', 'permission:ready-dish-recipe.delete'])->get('/delete-ready-recipes/{id}', [ReadyDishController::class, 'deleteRecipe'])->name('ready-dish-recipe.delete');

        Route::middleware(['auth', 'permission:ready-dish-recipe.save'])->post('/save-ready-recipes/{dish_id}', [ReadyDishController::class, 'saveRecipe'])->name('ready-dish-recipe.save');
        Route::middleware(['auth', 'permission:ready-dish-recipe.update'])->post('/update-ready-recipes/{id}', [ReadyDishController::class, 'updateRecipe'])->name('ready-dish-recipe.update');

        // Table Controller
        Route::middleware(['auth', 'permission:table.list'])->get('/all-table', [TableController::class, 'allTable'])->name('table.list');
        Route::middleware(['auth', 'permission:table.create'])->get('/add-table', [TableController::class, 'addTable'])->name('table.create');
        Route::middleware(['auth', 'permission:table.edit'])->get('/edit-table/{id}', [TableController::class, 'editTable'])->name('table.edit');
        Route::middleware(['auth', 'permission:table.delete'])->post('/delete-table', [TableController::class, 'deleteTable'])->name('table.delete');
        Route::middleware(['auth', 'permission:table.save'])->post('/save-table', [TableController::class, 'saveTable'])->name('table.save');
        Route::middleware(['auth', 'permission:table.update'])->post('/update-table/{id}', [TableController::class, 'updateTable'])->name('table.update');

        // Stock Management
        Route::middleware(['auth', 'permission:stock-item.list'])->get('/all-item', [StockController::class, 'allStock'])->name('stock-item.list');
        Route::middleware(['auth', 'permission:stock-item.create'])->get('/add-item', [StockController::class, 'addStock'])->name('stock-item.create');
        Route::middleware(['auth', 'permission:stock-item.edit'])->get('/edit-item/{id}', [StockController::class, 'editStock'])->name('stock-item.edit');
        Route::middleware(['auth', 'permission:stock-item.view'])->get('/view-item/{id}', [StockController::class, 'viewStock'])->name('stock-item.view');
        Route::middleware(['auth', 'permission:stock-item.delete'])->post('/delete-item', [StockController::class, 'deleteStock'])->name('stock-item.delete');
        Route::middleware(['auth', 'permission:stock-item.save'])->post('/save-item', [StockController::class, 'saveStock'])->name('stock-item.save');
        Route::middleware(['auth', 'permission:stock-item.update'])->post('/update-item/{id}', [StockController::class, 'updateStock'])->name('stock-item.update');
        Route::get('/cannot-delete-item/{id}', [StockController::class, 'cannotDeleteStock']);

        // Purses
        Route::middleware(['auth', 'permission:purchase.create'])->get('/new-purses', [PursesController::class, 'addPurses'])->name('purchase.create');
        Route::middleware(['auth', 'permission:purchase.list'])->get('/all-purses', [PursesController::class, 'allPurses'])->name('purchase.list');
        Route::middleware(['auth', 'permission:purchase.edit'])->get('/edit-purses/{id}', [PursesController::class, 'editPurses'])->name('purchase.edit');
        Route::middleware(['auth', 'permission:purchase.delete'])->post('/delete-purses', [PursesController::class, 'deletePurses'])->name('purchase.delete');
        Route::middleware(['auth', 'permission:purchase.delete-product'])->get('/delete-purses-product/{id}', [PursesController::class, 'deletePursesProduct'])->name('purchase.delete-product');
        Route::middleware(['auth', 'permission:purchase.save'])->post('/save-purses', [PursesController::class, 'savePurses'])->name('purchase.save');
        Route::middleware(['auth', 'permission:purchase.save-product'])->post('/save-purses-product/{purses_id}', [PursesController::class, 'savePursesProduct'])->name('purchase.save-product');
        Route::middleware(['auth', 'permission:purchase.update'])->post('/update-purses/{id}', [PursesController::class, 'updatePurses'])->name('purchase.update');

        // Purses JSON
        Route::get('/get-purses-details/{id}', [PursesController::class, 'getPursesDetails']);
        Route::get('/get-unit-of-product/{id}', [PursesController::class, 'getUnitOfProduct']);

        // Purses payment
        Route::middleware(['auth', 'permission:purchase.pay'])->get('/purses-payment/{purses_id}', [PursesController::class, 'pursesPayment'])->name('purchase.pay');
        Route::middleware(['auth', 'permission:purchase.save-payment'])->post('/save-purses-payment/{purses_id}', [PursesController::class, 'savePursesPayment'])->name('purchase.save-payment');

        // AccountantController
        Route::middleware(['auth', 'permission:expense.summary'])->get('/account-summary', [AccountantController::class, 'summary'])->name('expense.summary');
        Route::middleware(['auth', 'permission:expense.create'])->get('/add-expense', [AccountantController::class, 'addExpanse'])->name('expense.create');
        Route::middleware(['auth', 'permission:expense.edit'])->get('/edit-expanse/{id}', [AccountantController::class, 'editExpanse'])->name('expense.edit');
        Route::middleware(['auth', 'permission:expense.save'])->post('/save-expanse', [AccountantController::class, 'saveExpanse'])->name('expanse.save');
        Route::middleware(['auth', 'permission:expense.update'])->post('/update-expanse/{id}', [AccountantController::class, 'updateExpanse'])->name('expanse.update');
        Route::middleware(['auth', 'permission:expense.delete'])->post('/delete-expanse', [AccountantController::class, 'deleteExpanse'])->name('expense.delete');
        Route::middleware(['auth', 'permission:expense.list'])->get('/all-expanse', [AccountantController::class, 'allExpanse'])->name('expense.list');
        Route::middleware(['auth', 'permission:income.list'])->get('/all-income', [AccountantController::class, 'allIncome'])->name('income.list');

        //Supplier
        Route::middleware(['auth', 'permission:supplier.list'])->get('/all-supplier', [SupplierController::class, 'allSupplier'])->name('supplier.list');
        Route::middleware(['auth', 'permission:supplier.create'])->get('/add-supplier', [SupplierController::class, 'addSupplier'])->name('supplier.create');
        Route::middleware(['auth', 'permission:supplier.view'])->get('/view-supplier/{id}', [SupplierController::class, 'viewSupplier'])->name('supplier.view');
        Route::middleware(['auth', 'permission:supplier.edit'])->get('/edit-supplier/{id}', [SupplierController::class, 'editSupplier'])->name('supplier.edit');
        Route::middleware(['auth', 'permission:supplier.delete'])->post('/delete-supplier', [SupplierController::class, 'deleteSupplier'])->name('supplier.delete');
        Route::middleware(['auth', 'permission:supplier.save'])->post('/save-supplier', [SupplierController::class, 'saveSupplier'])->name('supplier.save');
        Route::middleware(['auth', 'permission:supplier.update'])->post('/update-supplier/{id}', [SupplierController::class, 'updateSupplier'])->name('supplier.update');

        // Website
        Route::resource('/website', WebsiteController::class);

        // Page Editor
        Route::get('/page-builder/{id}', [PageEditorController::class, 'editor']);
        Route::post('/website/save-section/{section}', [PageEditorController::class, 'saveSection'])->name('website.save-section');

        // kitchen requests
        Route::middleware(['auth', 'permission:material-requests.kitchen'])->get('/kitchen/requests', [MaterialRequestController::class, 'kitchenRequest'])->name('material-requests.kitchen');
        Route::middleware(['auth', 'permission:material-requests.barman'])->get('/barman/requests', [MaterialRequestController::class, 'barmanRequest'])->name('material-requests.barman');
        Route::middleware(['auth', 'permission:material-requests.kitchen-approve'])->post('/kitchen/request/approve', [MaterialRequestController::class, 'approveKitchenRequest'])->name('material-requests.kitchen-approve');
        Route::middleware(['auth', 'permission:material-requests.barman-approve'])->post('/barman/request/approve', [MaterialRequestController::class, 'approveBarmanRequest'])->name('material-requests.barman-approve');
        Route::middleware(['auth', 'permission:material-requests.kitchen-reject'])->post('/kitchen/request/reject', [MaterialRequestController::class, 'rejectKitchenRequest'])->name('material-requests.kitchen-reject');
        Route::middleware(['auth', 'permission:material-requests.barman-reject'])->post('/barman/request/reject', [MaterialRequestController::class, 'rejectBarmanRequest'])->name('material-requests.barman-reject');

        Route::middleware(['auth', 'permission:material-requests.baker'])->get('/baker/requests', [MaterialRequestController::class, 'bakerRequest'])->name('material-requests.baker');
    
        

    // Kitchen Only (All kitchen access can also access by admin or shop manager)
        // Kitchen
        Route::middleware(['auth', 'permission:kitchen.orders'])->get('/kitchen-orders', [OrderController::class, 'kitchenOrderToJSON'])->name('kitchen.orders');
        Route::middleware(['auth', 'permission:kitchen.start-cooking'])->get('/kitchen-start-cooking/{id}', [OrderController::class, 'kitchenStartCooking'])->name('kitchen.start-cooking');
        Route::middleware(['auth', 'permission:kitchen.complete-cooking'])->get('/kitchen-complete-cooking/{id}', [OrderController::class, 'kitchenCompleteCooking'])->name('kitchen.complete-cooking');
        Route::middleware(['auth', 'permission:kitchen.my-cooking-history'])->get('/cooking-history', [KitchenController::class, 'myCookingHistory'])->name('kitchen.my-cooking-history');
        
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
    
        

    // baker Only (All baker access can also access by admin or shop manager)
        // Baker
        Route::middleware(['auth', 'permission:baker.orders'])->get('/baker-orders', [OrderController::class, 'bakerOrderToJSON'])->name('baker.orders');
        Route::middleware(['auth', 'permission:baker.start-cooking'])->get('/baker-start-cooking/{id}', [OrderController::class, 'bakerStartCooking'])->name('baker.start-cooking');
        Route::middleware(['auth', 'permission:baker.complete-cooking'])->get('/baker-complete-cooking/{id}', [OrderController::class, 'bakerCompleteCooking'])->name('baker.complete-cooking');
        Route::middleware(['auth', 'permission:baker.my-cooking-history'])->get('/baker-cooking-history', [BakerController::class, 'myCookingHistory'])->name('baker.my-cooking-history');
        
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

    
        

    // Waiter Only
        //Dish
        Route::middleware(['auth', 'permission:waiter.dish-type'])->get('/dish-types/{dish_id}', [RecipeController::class, 'getTypesOfDish'])->name('waiter.dish-type');
        
        // Orders
        Route::middleware(['auth', 'permission:waiter.create-order'])->get('/new-order', [OrderController::class, 'newOrder'])->name('waiter.create-order');
        Route::middleware(['auth', 'permission:waiter.print-order'])->get('/print-order/{id}', [OrderController::class, 'printOrder'])->name('waiter.print-order');
        
        // routes/web.php
        Route::middleware(['auth', 'permission:waiter.print-multiple-orders'])->get('/print-multiple-orders', [OrderController::class, 'printMultipleOrders'])->name('orders.printMultiple');
        Route::middleware(['auth', 'permission:waiter.mark-order'])->get('/marked-order/{id}', [OrderController::class, 'markOrder'])->name('waiter.mark-order');
        Route::middleware(['auth', 'permission:waiter.delete-order'])->post('/delete-order', [OrderController::class, 'deleteOrder'])->name('waiter.delete-order');
        Route::middleware(['auth', 'permission:waiter.all-orders'])->get('/all-order', [OrderController::class, 'allOrder'])->name('waiter.all-orders');
        Route::middleware(['auth', 'permission:waiter.non-paid-order'])->get('/non-paid-order', [OrderController::class, 'nonPaidOrder'])->name('waiter.non-paid-order');
        Route::middleware(['auth', 'permission:waiter.order-details'])->get('/get-order-details/{id}', [OrderController::class, 'getOrderDetails'])->name('waiter.order-details');
        Route::middleware(['auth', 'permission:waiter.edit-order'])->get('/edit-order/{id}', [OrderController::class, 'editOrder'])->name('waiter.edit-order');
        Route::middleware(['auth', 'permission:waiter.save-order'])->post('/save-order', [OrderController::class, 'saveOrder'])->name('waiter.save-order');
        Route::middleware(['auth', 'permission:waiter.pay-order'])->post('/pay-order/{id}', [OrderController::class, 'payOrder'])->name('waiter.pay-order');
        Route::middleware(['auth', 'permission:waiter.update-order'])->put('/update-order/{id}', [OrderController::class, 'updateOrder'])->name('waiter.update-order');
        
        // Waiter Order
        Route::middleware(['auth', 'permission:waiter.served-order'])->get('/order-served/{id}', [OrderController::class, 'orderServed'])->name('waiter.served-order');
        
        // Order By Waiter
        Route::middleware(['auth', 'permission:waiter.my-orders'])->get('/my-orders', [OrderController::class, 'myOrder'])->name('waiter.my-orders');
        
        // Live Kitchen for waiter
        Route::get('/kitchen-status', [KitchenController::class, 'waiterLiveKitchen']);
        Route::get('/barman-status', [KitchenController::class, 'waiterLiveBarman']);
        Route::get('/kitchen-status-waiter-json', [KitchenController::class, 'waiterLiveKitchenJSON']);
        Route::get('/barman-status-waiter-json', [KitchenController::class, 'waiterLiveBarmanJSON']);
        
        // Waiter Stat
        Route::get('/waiter-stat', [WaiterController::class, 'waiterStat']);
        Route::post('/waiter-stat-post', [WaiterController::class, 'postWaiterStat']);
        Route::get('/waiter-stat/waiter={id}/start={start_date}/end={end_date}', [WaiterController::class, 'showWaiterStat']);
    
        
    // Cashier Only 
        Route::middleware(['auth', 'permission:cashier.my-orders'])->get('/cashier-orders', [CashierController::class, 'myOrder'])->name('cashier.my-orders');
        Route::post('/cashier-pay-order/{id}', [CashierController::class, 'payOrder']);

        Route::get('/cashier-print-order/{id}', [CashierController::class, 'printOrder']);
        // routes/web.php
        Route::get('/cashier-print-multiple-orders', [CashierController::class, 'printMultipleOrders'])->name('orders.printMultiple');
    
        

    // Barman Only 
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
        Route::middleware(['auth', 'permission:barman.my-orders'])->get('/my-barman-orders', [ReadyDishOrderController::class, 'myOrder'])->name('barman.my-orders');
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
