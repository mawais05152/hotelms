<?php

use App\Models\{User,Order , Product,Category, DamagedItem, BookingTable};
use Illuminate\Support\Facades\{Log, Mail, Route};


Route::get('/', function () {
    return view('dashboard', ['users' => User::count(),'tables' => BookingTable::count(),'products' => Product::count(),'ordersToday' => Order::whereDate('created_at', today())->count(),]);
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('bookingtables', App\Http\Controllers\BookingTableController::class);
    Route::resource('categories', App\Http\Controllers\CategoryController::class);
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('staff-salaries', App\Http\Controllers\StaffSalaryController::class);
    Route::resource('expenses', App\Http\Controllers\ExpenseController::class);
    Route::resource('tables', App\Http\Controllers\BookingTableController::class);
    Route::resource('products', App\Http\Controllers\ProductController::class);
    Route::get('/products/{id}/variations', [App\Http\Controllers\ProductController::class, 'showVariations']);
    Route::resource('variations', App\Http\Controllers\VariationController::class);
    Route::get('/products/by-category/{id}', [App\Http\Controllers\ProductController::class, 'getByCategory']);
    Route::resource('restaurant-assets', App\Http\Controllers\RestaurantAssetController::class);
    Route::resource('purchases', App\Http\Controllers\PurchaseController::class);
    Route::post('/purchases/update-price/{id}', [App\Http\Controllers\PurchaseController::class, 'updatePrice'])->name('purchases.updatePrice');
    Route::get('/asset-price-history/{asset}', [App\Http\Controllers\AssetPriceHistoryController::class, 'index'])->name('asset-price-history.index');
    Route::get('/get-product-variations/{product}', [App\Http\Controllers\OrderController::class, 'getProductVariations']);
    Route::get('/get-items/{type}', [App\Http\Controllers\PurchaseController::class, 'getItems']);
    Route::get('/get-product-variations/{productId}', [App\Http\Controllers\ProductController::class, 'getVariations']);
    Route::resource('orders', App\Http\Controllers\OrderController::class);
    Route::get('check-stock/{product_id}/{variation_id?}',[App\Http\Controllers\OrderController::class, 'checkStock']);
    Route::get('/order-status', [App\Http\Controllers\OrderStatusController::class, 'index'])->name('order_status.index');
    Route::post('/orders/{order}/status', [App\Http\Controllers\OrderController::class, 'storeStatus'])->name('orders.status.store');
    Route::post('/orders/{order}/status-update', [App\Http\Controllers\OrderStatusController::class, 'updateStatus'])->name('orders.status.update');
    Route::get('order-status/index/{id}', [App\Http\Controllers\OrderStatusController::class, 'index']);
    Route::get('/get-products-by-category/{categoryId}', [App\Http\Controllers\OrderController::class, 'getProductsByCategory']);
    Route::get('/orders-process/{id}', [App\Http\Controllers\OrderController::class, 'processOrders'])->name('orders.pay.create');
    Route::post('/orders-pay/{id}', [App\Http\Controllers\OrderController::class, 'ordersPay'])->name('orders.pay.store');
    Route::get('/orders/{id}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::resource('customer-feedback', App\Http\Controllers\CustomerFeedbackController::class);
    Route::resource('stock-items', App\Http\Controllers\StockItemController::class);
    Route::get('/stock-items/{id}/variation', [App\Http\Controllers\StockItemController::class, 'showVariation']);
    Route::get('/get-variations/{productId}', [App\Http\Controllers\StockItemController::class, 'getVariations']);
    Route::resource('damaged_items', App\Http\Controllers\DamagedItemController::class);
    Route::get('/product-sales-report', [App\Http\Controllers\ReportController::class, 'productSalesReport'])->name('product.sales.report');
    Route::resource('mess_menus', App\Http\Controllers\MessMenuController::class);
    Route::resource('menu-materials',App\Http\Controllers\MenuMaterialController::class);
    Route::resource('mess_items_purchases', App\Http\Controllers\MessItemsPurchaseController::class);
    Route::get('/purchase-invoices', [App\Http\Controllers\MessItemsPurchaseController::class,'invoices'])->name('purchases.invoices');
    Route::get('/purchase-invoice/{invoice_no}', [App\Http\Controllers\MessItemsPurchaseController::class,'showInvoice'])->name('purchases.showInvoice');
    Route::resource('mess-distributions', App\Http\Controllers\MessDistributionController::class);
    Route::resource('mess-finances',App\Http\Controllers\MessFinanceController::class);
    Route::resource('dish_variations', App\Http\Controllers\DishVariationController::class);
    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
});

require __DIR__.'/auth.php';



//email send testing route
// Route::get('/test-mail', function() {
//     try {
//         Log::info('Test mail triggered');
//         Mail::raw('Test mail', function($message) {
//             $message->to('muhammadawais05152@gmail.com')
//                     ->subject('Test Email');
//         });
//         Log::info('Test mail sent successfully');
//         return 'Mail sent successfully';
//     } catch (\Exception $e) {
//         Log::error('Mail sending failed: '.$e->getMessage());
//         return 'Mail sending failed: ' . $e->getMessage();
//     }
// });

