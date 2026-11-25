<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\CostCentreController;
use App\Http\Controllers\PoultryBatchController;
use App\Http\Controllers\AnimalSaleController;
use App\Http\Controllers\MilkProductionController;
use App\Http\Controllers\EggProductionController;
use App\Http\Controllers\MilkProcessingController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesInvoiceController;
use App\Http\Controllers\AnimalFeedController;
use App\Http\Controllers\AnimalExpenseController;
Route::get('/', function () {
    return view('layouts.welcome');
});


Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::get('/test-permission', function () {
    $user = auth()->user();

    if (!$user) {
        return 'first login to view this page: <a href="/login">Login</a>';
    }

    return response()->json([
        'user'           => $user->email,
        'roles'          => $user->getRoleNames(),
        'all_permissions'=> $user->getAllPermissions()->pluck('name'),
        'can_farm_view'  => $user->can('farm.view'),
        'can_farm_edit'  => $user->can('farm.edit'),
        'can_animal_view'=> $user->can('animal.view'),
        'can_role_delete'=> $user->can('role.delete'),
    ]);
})->middleware('auth');



Route::middleware(['auth', 'permission:farm.view'])->get('/farms-test', function () {
    return '✅ you have the farm.view permission — welcome!';
});



Route::middleware(['auth', 'role:Owner'])->get('/owner-test', function () {
    return '👑 you are the Owner — this page is for Owners only!';
});



Route::middleware(['auth', 'permission:farm.create|farm.edit'])->get('/farms-manage', function () {
    return '✅ you have either the farm.create or farm.edit permission — welcome!';
});

Route::middleware(['auth'])->group(function () {
    Route::resource('farms', FarmController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::resource('farms', FarmController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::resource('farms', FarmController::class);
    Route::resource('sections', SectionController::class)->except(['show']);

    Route::resource('animals', AnimalController::class)->except(['show']); 
    // Bulk Feed
Route::get('animals/bulk-feed', [AnimalFeedController::class, 'bulkCreate'])->name('animals.feeds.bulk');
Route::post('animals/bulk-feed', [AnimalFeedController::class, 'bulkStore'])->name('animals.feeds.bulk.store');
    // Animal Show (Cost Summary)
Route::get('animals/{animal}', [AnimalController::class, 'show'])->name('animals.show');

// Animal Feed Records
Route::get('animals/{animal}/feeds', [AnimalFeedController::class, 'index'])->name('animals.feeds.index');
Route::get('animals/{animal}/feeds/create', [AnimalFeedController::class, 'create'])->name('animals.feeds.create');
Route::post('animals/{animal}/feeds', [AnimalFeedController::class, 'store'])->name('animals.feeds.store');
Route::delete('feeds/{record}', [AnimalFeedController::class, 'destroy'])->name('animals.feeds.destroy');

// Animal Expense Page (Dynamic)
Route::get('animal-expenses', [AnimalExpenseController::class, 'page'])->name('animal-expenses.page');
Route::get('animal-expenses/{animal}/info', [AnimalExpenseController::class, 'getAnimalInfo'])->name('animal-expenses.info');
Route::post('animal-expenses/{animal}', [AnimalExpenseController::class, 'storeAjax'])->name('animal-expenses.store.ajax');

// Animal Expenses
Route::get('animals/{animal}/expenses', [AnimalExpenseController::class, 'index'])->name('animals.expenses.index');
Route::get('animals/{animal}/expenses/create', [AnimalExpenseController::class, 'create'])->name('animals.expenses.create');
Route::post('animals/{animal}/expenses', [AnimalExpenseController::class, 'store'])->name('animals.expenses.store');
Route::delete('expenses/{expense}', [AnimalExpenseController::class, 'destroy'])->name('animals.expenses.destroy');
     //  Animal Weight Event 
    Route::get('animals/{animal}/weights', [App\Http\Controllers\AnimalWeightController::class, 'index'])->name('animals.weights.index');
    Route::get('animals/{animal}/weights/create', [App\Http\Controllers\AnimalWeightController::class, 'create'])->name('animals.weights.create');
    Route::post('animals/{animal}/weights', [App\Http\Controllers\AnimalWeightController::class, 'store'])->name('animals.weights.store');
    Route::delete('weights/{weight}', [App\Http\Controllers\AnimalWeightController::class, 'destroy'])->name('animals.weights.destroy');  

    //  Animal Health Event 
Route::get('animals/{animal}/health', [App\Http\Controllers\AnimalHealthController::class, 'index'])->name('animals.health.index');
Route::get('animals/{animal}/health/create', [App\Http\Controllers\AnimalHealthController::class, 'create'])->name('animals.health.create');
Route::post('animals/{animal}/health', [App\Http\Controllers\AnimalHealthController::class, 'store'])->name('animals.health.store');
Route::delete('health/{event}', [App\Http\Controllers\AnimalHealthController::class, 'destroy'])->name('animals.health.destroy');

// Carrying value API
Route::get('animal-sales/carrying-value/{animal}', [AnimalSaleController::class, 'getCarryingValue'])->name('animal-sales.carrying-value');

// Invoice
Route::get('animal-sales/{animalSale}/invoice', [AnimalSaleController::class, 'invoice'])->name('animal-sales.invoice');

Route::resource('animal-sales', AnimalSaleController::class)->except(['show']);


    Route::resource('cost-centres', CostCentreController::class)->except(['show']);
    Route::resource('poultry-batches', PoultryBatchController::class)->except(['show']);
    //  Poultry Daily Activities Nested)
Route::get('poultry-batches/{batch}/daily', [App\Http\Controllers\PoultryDailyActivityController::class, 'index'])->name('poultry-batches.daily.index');
Route::get('poultry-batches/{batch}/daily/create', [App\Http\Controllers\PoultryDailyActivityController::class, 'create'])->name('poultry-batches.daily.create');
Route::post('poultry-batches/{batch}/daily', [App\Http\Controllers\PoultryDailyActivityController::class, 'store'])->name('poultry-batches.daily.store');
Route::delete('poultry-daily/{activity}', [App\Http\Controllers\PoultryDailyActivityController::class, 'destroy'])->name('poultry-batches.daily.destroy');

Route::resource('milk-productions', MilkProductionController::class)->except(['show', 'edit', 'update']);
Route::resource('milk-processings', MilkProcessingController::class)->except(['show', 'edit', 'update']);

Route::resource('egg-productions', EggProductionController::class)->except(['show', 'edit', 'update']);

Route::resource('inventory-items', InventoryItemController::class)->except(['show']);
Route::resource('stock-movements', StockMovementController::class)->only(['index', 'create', 'store']);
Route::resource('suppliers', SupplierController::class)->except(['show']);
Route::resource('purchase-orders', PurchaseOrderController::class);
Route::get('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receiveForm'])->name('purchase-orders.receive');
Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receiveStore'])->name('purchase-orders.receive.store');

Route::resource('customers', CustomerController::class)->except(['show']);
Route::resource('products', ProductController::class)->except(['show']);
Route::resource('sales-invoices', SalesInvoiceController::class)->except(['edit', 'update']);
});