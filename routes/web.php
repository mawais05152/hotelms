<?php

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\DamagedItem;
use App\Models\BookingTable;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MessMenuController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\StockItemController;
use App\Http\Controllers\VariationController;
use App\Http\Controllers\DamagedItemController;
use App\Http\Controllers\MessFinanceController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\StaffSalaryController;
use App\Http\Controllers\BookingTableController;
use App\Http\Controllers\MenuMaterialController;
use App\Http\Controllers\DishVariationController;
use App\Http\Controllers\RestaurantAssetController;
use App\Http\Controllers\CustomerFeedbackController;
use App\Http\Controllers\MessDistributionController;
use App\Http\Controllers\AssetPriceHistoryController;
use App\Http\Controllers\MessItemsPurchaseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|///
|///Route::get('/orders/{order_id}/summary', [OrderItemController::class, 'orderSummary']);


| Here is where you can register web routes for your application. These
  Route::get('/orders/check/{id}', [OrderItemController::class, 'checkOrder']);
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::resource('bookingtables', BookingTableController::class);
Route::resource('categories', CategoryController::class);
Route::resource('users', UserController::class);
Route::resource('staff-salaries', StaffSalaryController::class);
Route::resource('expenses', ExpenseController::class);
Route::resource('tables', BookingTableController::class);
Route::resource('products', ProductController::class);
Route::get('/products/{id}/variations', [ProductController::class, 'showVariations']);
Route::resource('variations', VariationController::class);

Route::get('/products/by-category/{id}', [ProductController::class, 'getByCategory']);
Route::resource('restaurant-assets', RestaurantAssetController::class);

Route::resource('purchases', PurchaseController::class);
//test
Route::post('/purchases/update-price/{id}', [PurchaseController::class, 'updatePrice'])->name('purchases.updatePrice');
Route::get('/asset-price-history/{asset}', [AssetPriceHistoryController::class, 'index'])->name('asset-price-history.index');





Route::get('/get-items/{type}', [PurchaseController::class, 'getItems']);
Route::get('/get-product-variations/{productId}', [ProductController::class, 'getVariations']);

Route::resource('orders', OrderController::class);
Route::get('/order-status', [OrderStatusController::class, 'index'])->name('order_status.index');
Route::post('/orders/{order}/status', [OrderController::class, 'storeStatus'])->name('orders.status.store');
Route::post('/orders/{order}/status-update', [OrderStatusController::class, 'updateStatus'])->name('orders.status.update');
Route::get('order-status/index/{id}', [OrderStatusController::class, 'index']);


Route::get('/get-products-by-category/{categoryId}', [OrderController::class, 'getProductsByCategory']);
Route::get('/get-dish-variations/{dishId}', [OrderController::class, 'getDishVariations']);
Route::get('/get-product-variations/{productId}', [OrderController::class, 'getProductVariations']);

//test
Route::get('/get-meal_names-by-category/{categoryId}', [OrderController::class, 'index']);
Route::get('/order-items/create/{order_id}', [OrderItemController::class, 'customCreate']);
Route::resource('order-items', OrderItemController::class);
Route::resource('customer-feedback', CustomerFeedbackController::class);
Route::get('/order-items', [OrderItemController::class, 'index'])->name('order-items.index');
Route::resource('payments', PaymentController::class);
Route::get('payments/get-order-items/{id}', [PaymentController::class, 'orderitem']);
Route::resource('stock-items', StockItemController::class);

Route::get('/get-variations/{productId}', [StockItemController::class, 'getVariations']);

Route::resource('damaged_items', DamagedItemController::class);
Route::get('/product-sales-report', [ReportController::class, 'productSalesReport'])->name('product.sales.report');

Route::resource('mess_menus', MessMenuController::class);
Route::resource('menu-materials',MenuMaterialController::class);
Route::resource('mess_items_purchases', MessItemsPurchaseController::class);
Route::resource('mess-distributions', MessDistributionController::class);
Route::resource('mess-finances',MessFinanceController::class);
Route::resource('dish_variations', DishVariationController::class);

// Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])
//     ->name('dashboard');


Route::get('/', function () {
    return view('dashboard', [
        'users' => User::count(),
        'tables' => BookingTable::count(),
        'products' => Product::count(),
        'ordersToday' => Order::whereDate('created_at', today())->count(),
    ]);
})->middleware(['auth'])->name('dashboard');





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// //test
// Route::get('/get-products/{categoryId}', [ProductController::class, 'getProducts']);
// //
// Route::get('/get-products-or-meals-by-category/{categoryId}', [OrderController::class, 'getItemsByCategory']);
// Route::get('/get-variations/{itemId}/{type}', [OrderController::class, 'getVariations']);


