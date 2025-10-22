<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SaleInfoController;
use App\Http\Controllers\Admin\OrderBoardController;
use App\Http\Controllers\Admin\RoleChangeController;
use App\Http\Controllers\Admin\AdminDashboardController;

//admin
Route::group(['prefix' => 'admin' , 'middleware' => ['auth','admin']], function(){
    Route::get('/home', [AdminDashboardController::class, 'index'])->name('adminDashboard');

    //category
    Route::prefix('category')->group(function(){
        Route::get('list',[CategoryController::class, 'list'])->name('categoryList');
        Route::get('create',[CategoryController::class, 'createPage'])->name('categoryCreatePage');
        Route::post('create',[CategoryController::class, 'create'])->name('categoryCreate');
        Route::get('delete/{id}',[CategoryController::class, 'delete'])->name('categoryDelete');
        Route::get('edit/{id}',[CategoryController::class, 'edit'])->name('categoryEdit');
        Route::post('update',[CategoryController::class, 'update'])->name('categoryUpdate');

    });

    //product
    Route::prefix('product')->group(function(){
        Route::get('list',[ProductController::class, 'list'])->name('productList');
        Route::get('create',[ProductController::class, 'createPage'])->name('productCreate');
        Route::post('create',[ProductController::class, 'productCreate'])->name('product#Create');
        Route::get('delete/{id}',[ProductController::class, 'delete'])->name('productDelete');
        Route::get('details/{id}',[ProductController::class, 'details'])->name('productDetails');
        Route::get('edit/{id}',[ProductController::class, 'edit'])->name('productEdit');
        Route::post('update',[ProductController::class, 'update'])->name('productUpdate');

    });

    //payment
    Route::prefix('payment')->group(function(){
        Route::get('list', [PaymentController::class, 'list'])->name('paymentList');
        Route::get('create',[PaymentController::class, 'createPage'])->name('paymentCreatePage');
        Route::post('create',[PaymentController::class, 'create'])->name('paymentCreate');
        Route::get('delete/{id}',[PaymentController::class, 'delete'])->name('paymentDelete');
        Route::get('edit/{id}',[PaymentController::class, 'edit'])->name('paymentEdit');
        Route::post('update',[PaymentController::class, 'update'])->name('paymentUpdate');

    });

    //password
    Route::prefix('password')->group(function(){
        Route::get('change', [AuthController::class, 'changePasswordPage'])->name('passwordChange');
        Route::post('change', [AuthController::class, 'changePassword'])->name('changePassword');
        Route::get('reset', [AuthController::class, 'resetPasswordPage'])->name('resetPasswordPage');
        Route::post('resetPassword', [AuthController::class, 'resetPassword'])->name('resetPassword');

    });

    //profile
    Route::prefix('profile')->group(function(){
        Route::get('detail', [ProfileController::class, 'profileDetails'])->name('profileDetails');
        Route::post('update', [ProfileController::class, 'update'])->name('adminProfileUpdate');
        Route::get('account/{id}', [ProfileController::class, 'accountProfile'])->name('accountProfile');


        Route::get('create/adminAccount', [ProfileController::class, 'createAdminAccount'])->name('createAdminAccount');
        Route::post('create/adminAccount', [ProfileController::class, 'create'])->name('createAdmin');
    });

    //role
    Route::prefix('role')->group(function(){
        Route::get('list',[RoleChangeController::class, 'adminList'])->name('adminList');
        Route::get('deleteAdminAccount/{id}',[RoleChangeController::class, 'deleteAdminAccount'])->name('deleteAdminAccount');
        Route::get('changeUserRole/{id}',[RoleChangeController::class, 'changeUserRole'])->name('changeUserRole');

        Route::get('userList',[RoleChangeController::class, 'userList'])->name('userList');
        Route::get('deleteUserAccount/{id}',[RoleChangeController::class, 'deleteUserAccount'])->name('deleteUserAccount');
        Route::get('changeAdminRole/{id}',[RoleChangeController::class, 'changeAdminRole'])->name('changeAdminRole');

    });

    //Order Board Page
    Route::prefix('order')->group(function(){
        Route::get('list',[OrderBoardController::class, 'orderListPage'])->name('orderListPage');
        Route::get('details/{orderCode}',[OrderBoardController::class, 'userOrderDetails'])->name('userOrderDetails');
        Route::get('change/status',[OrderBoardController::class, 'changeStatus'])->name('changeStatus');
        Route::post('update/status', [OrderBoardController::class, 'updateStatus'])->name('updateOrderStatus');
        Route::post('reject', [OrderBoardController::class, 'rejectOrder']);
        Route::post('removereject', [OrderBoardController::class, 'removeRejectReason']);
    });

    //Sale Info
    Route::prefix('saleinfo')->group(function(){
        Route::get('list', [SaleInfoController::class, 'saleInfoList'])->name('saleInfoList');
    });

    // Stores Management
    Route::prefix('store')->group(function(){
        Route::get('list', [App\Http\Controllers\Admin\StoreController::class, 'index'])->name('admin#storeList');
        Route::get('create', [App\Http\Controllers\Admin\StoreController::class, 'create'])->name('admin#storeCreate');
        Route::post('create', [App\Http\Controllers\Admin\StoreController::class, 'store'])->name('admin#storeStore');
        Route::get('edit/{id}', [App\Http\Controllers\Admin\StoreController::class, 'edit'])->name('admin#storeEdit');
        Route::post('update/{id}', [App\Http\Controllers\Admin\StoreController::class, 'update'])->name('admin#storeUpdate');
        Route::get('delete/{id}', [App\Http\Controllers\Admin\StoreController::class, 'delete'])->name('admin#storeDelete');
        Route::get('inventory/{id}', [App\Http\Controllers\Admin\StoreController::class, 'inventory'])->name('admin#storeInventory');
    });

    // Purchase Orders
    Route::prefix('purchase-order')->group(function(){
        Route::get('list', [App\Http\Controllers\Admin\PurchaseOrderController::class, 'index'])->name('admin#purchaseOrderList');
        Route::get('create', [App\Http\Controllers\Admin\PurchaseOrderController::class, 'create'])->name('admin#purchaseOrderCreate');
        Route::post('create', [App\Http\Controllers\Admin\PurchaseOrderController::class, 'store'])->name('admin#purchaseOrderStore');
        Route::get('detail/{id}', [App\Http\Controllers\Admin\PurchaseOrderController::class, 'show'])->name('admin#purchaseOrderDetail');
        Route::post('receive/{id}', [App\Http\Controllers\Admin\PurchaseOrderController::class, 'receive'])->name('admin#purchaseOrderReceive');
        Route::get('cancel/{id}', [App\Http\Controllers\Admin\PurchaseOrderController::class, 'cancel'])->name('admin#purchaseOrderCancel');
    });

    // Loyalty & Discounts
    Route::prefix('loyalty')->group(function(){
        Route::get('overview', [App\Http\Controllers\Admin\LoyaltyController::class, 'overview'])->name('admin#loyaltyOverview');
        
        // Discount codes
        Route::get('discount-codes', [App\Http\Controllers\Admin\LoyaltyController::class, 'discountCodeList'])->name('admin#discountCodeList');
        Route::get('discount-codes/create', [App\Http\Controllers\Admin\LoyaltyController::class, 'createDiscountCode'])->name('admin#discountCodeCreate');
        Route::post('discount-codes/create', [App\Http\Controllers\Admin\LoyaltyController::class, 'storeDiscountCode'])->name('admin#discountCodeStore');
        Route::get('discount-codes/toggle/{id}', [App\Http\Controllers\Admin\LoyaltyController::class, 'toggleDiscountCode'])->name('admin#discountCodeToggle');
        
        // Customer segments
        Route::get('segments', [App\Http\Controllers\Admin\LoyaltyController::class, 'segmentList'])->name('admin#segmentList');
        Route::get('segments/create', [App\Http\Controllers\Admin\LoyaltyController::class, 'createSegment'])->name('admin#segmentCreate');
        Route::post('segments/create', [App\Http\Controllers\Admin\LoyaltyController::class, 'storeSegment'])->name('admin#segmentStore');
        Route::get('segments/refresh/{id}', [App\Http\Controllers\Admin\LoyaltyController::class, 'refreshSegment'])->name('admin#segmentRefresh');
    });

    // Stock Alerts
    Route::prefix('stock-alert')->group(function(){
        Route::get('list', [App\Http\Controllers\Admin\StockAlertController::class, 'index'])->name('admin#stockAlertList');
        Route::get('acknowledge/{id}', [App\Http\Controllers\Admin\StockAlertController::class, 'acknowledge'])->name('admin#stockAlertAcknowledge');
        Route::post('resolve/{id}', [App\Http\Controllers\Admin\StockAlertController::class, 'resolve'])->name('admin#stockAlertResolve');
        Route::get('run-check', [App\Http\Controllers\Admin\StockAlertController::class, 'runCheck'])->name('admin#stockAlertRunCheck');
    });

    // Reports
    Route::prefix('reports')->group(function(){
        Route::get('salesReportPage', [SaleInfoController::class, 'salesReportPage'])->name('salesReportPage');
        Route::get('sales',[SaleInfoController::class, 'salesReport'])->name('salesReport');
        Route::get('productReportPage',[SaleInfoController::class, 'productReportPage'])->name('productReportPage');
        Route::get('productReport',[SaleInfoController::class, 'productReport'])->name('productReport');
        Route::get('profitlossreportpage',[SaleInfoController::class, 'profitlossreportpage'])->name('profitlossreportpage');
        Route::get('profitlossReport',[SaleInfoController::class, 'profitlossReport'])->name('profitlossReport');

    });


});
