<?php

use App\Models\StockRequest;
use App\Models\PurchaseRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\BeritaAcaraController;
use App\Http\Controllers\StockRequestController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseRequestController;

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

Route::get('/', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);

// Route::get('/register/edit/{user}', [RegisterController::class, 'edit'])->name('register.edit');

Route::middleware(['auth', 'isAdmin'])->group(function() {

    // REGISTER ROUTE
    Route::get('/register', [RegisterController::class, 'index']);
    Route::get('/register/create', [RegisterController::class, 'create']);
    Route::post('/register/store', [RegisterController::class, 'store']);
    Route::put('/register/update/{user}', [RegisterController::class, 'update']);
    Route::get('/register/setText/{user}', [RegisterController::class, 'setText'])->name('setText.register');

    // DIVISION ROUTE
    Route::get('/division', [DivisionController::class, 'index']);
    Route::get('/division/create', [DivisionController::class, 'create']);
    Route::post('/division/store', [DivisionController::class, 'store']);
    Route::get('/division/setText/{id}', [DivisionController::class, 'setText'])->name('setText.division');
    Route::put('/division/update/{id}', [DivisionController::class, 'update']);

    // PURCHASE REQUEST ROUTE
    Route::get('/purchaseRequest', [PurchaseRequestController::class, 'index']);
    Route::get('/purchaseRequest/create', [PurchaseRequestController::class, 'create']);
    Route::post('/purchaseRequest/store', [PurchaseRequestController::class, 'store']);
    Route::get('/purchaseRequest/edit/{purchase_request}', [PurchaseRequestController::class, 'edit'])->name('purchaseRequest.edit');
    Route::put('/purchaseRequest/update/{purchase_request}', [PurchaseRequestController::class, 'update']);
    Route::delete('/purchaseRequest/destroy/{purchase_request}', [PurchaseRequestController::class, 'destroy']);
    Route::get('/purchaseRequest/setDetail/{pr_id}', [PurchaseRequestController::class, 'setDetail'])->name('setDetail.purchaseRequest');
    Route::get('/purchaseRequest/setText/{sr_id}', [PurchaseRequestController::class, 'setText'])->name('setText.purchaseRequest');

});

Route::middleware(['auth'])->group(function () {

    // DASHBOARD ROUTE
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // ITEM CATEGORY ROUTE
    Route::get('/category', [CategoryController::class, 'index']);
    Route::get('/category/create', [CategoryController::class, 'create']);
    Route::post('/category/store', [CategoryController::class, 'store']);
    Route::get('/category/edit/{category}', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/category/update/{category}', [CategoryController::class, 'update']);
    Route::delete('/category/destroy/{category}', [CategoryController::class, 'destroy']);
    
    // ITEM ROUTE
    Route::get('/item', [ItemController::class, 'index']);
    Route::get('item/create', [ItemController::class, 'create']);
    Route::post('item/store', [ItemController::class, 'store']);
    Route::get('/item/edit/{item}', [ItemController::class, 'edit'])->name('item.edit');
    Route::put('/item/update/{item}', [ItemController::class, 'update']);
    Route::delete('/item/destroy/{item}', [ItemController::class, 'destroy']);

    // STOCK REQUEST ROUTE
    Route::get('/stockRequest', [StockRequestController::class, 'index']);
    Route::get('/stockRequest/create', [StockRequestController::class, 'create']);
    Route::post('/stockRequest/store', [StockRequestController::class, 'store']);
    Route::get('/stockRequest/edit/{stock_request}', [StockRequestController::class, 'edit'])->name('stockRequest.edit');
    Route::put('/stockRequest/update/{stockRequest}', [StockRequestController::class, 'update']);
    Route::delete('/stockRequest/destroy/{stockRequest}', [StockRequestController::class, 'destroy']);
    Route::get('/stockRequest/setText/{sr_id}', [StockRequestController::class, 'setText'])->name('setText.stockRequest');

    // BERITA ACARA ROUTE
    Route::get('/beritaAcara', [BeritaAcaraController::class, 'index']);
    Route::get('/beritaAcara/create', [BeritaAcaraController::class, 'create']);
    Route::get('/beritaAcara/setText/{po_id}', [BeritaAcaraController::class, 'setText'])->name('setText.beritaAcara');
    Route::post('/beritaAcara/store', [BeritaAcaraController::class, 'store']);
    Route::get('/beritaAcara/edit/{berita_acara}', [BeritaAcaraController::class, 'edit'])->name('beritaAcara.edit');
    Route::put('/beritaAcara/update/{berita_acara}', [BeritaAcaraController::class, 'update']);
    Route::delete('/beritaAcara/destroy/{berita_acara}', [BeritaAcaraController::class, 'destroy']);
    Route::get('/beritaAcara/setDetail/{ba_id}', [BeritaAcaraController::class, 'setDetail'])->name('setDetail.detailBa');

    // PEMAKAIAN
    Route::get('/pemakaian', [PemakaianController::class, 'index']);
    Route::get('/pemakaian/create', [PemakaianController::class, 'create']);
    Route::post('/pemakaian/store', [PemakaianController::class, 'store']);
    Route::get('pemakaian/setDetail/{use_id}', [PemakaianController::class, 'setDetail'])->name('setDetail.detailUse');
    Route::get('/pemakaian/edit/{use_id}', [PemakaianController::class, 'edit'])->name('pemakaian.edit');
    Route::delete('/pemakaian/destroy/{use_id}', [PemakaianController::class, 'destroy']);
    Route::put('/pemakaian/update/{pemakaian}', [PemakaianController::class, 'update']);

});

Route::middleware(['auth', 'isProc'])->group(function () {
    // SUPPLIER ROUTE
    Route::get('/supplier', [SupplierController::class, 'index']);
    Route::get('/supplier/create', [SupplierController::class, 'create']);
    Route::post('/supplier/store', [SupplierController::class, 'store']);
    Route::get('/supplier/edit/{supplier}', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::put('/supplier/update/{supplier}', [SupplierController::class, 'update']);
    Route::delete('/supplier/destroy/{supplier}', [SupplierController::class, 'destroy']);
    Route::get('/supplier/setText/{sp_id}', [SupplierController::class, 'setText'])->name('setText.supplier');

    // PURCHASE ORDER ROUTE
    Route::get('/purchaseOrder', [PurchaseOrderController::class, 'index']);
    Route::get('/purchaseOrder/create', [PurchaseOrderController::class, 'create']);
    Route::get('/purchaseOrder/setText/{pr_id}', [PurchaseOrderController::class, 'setText'])->name('setText.purchaseOrder');
    Route::post('/purchaseOrder/store', [PurchaseOrderController::class, 'store']);
    Route::get('/purchaseOrder/edit/{purchase_order}', [PurchaseOrderController::class, 'edit'])->name('purchaseOrder.edit');
    Route::put('/purchaseOrder/update/{purchase_order}', [PurchaseOrderController::class, 'update']);
    Route::delete('/purchaseOrder/destroy/{purchase_order}', [PurchaseOrderController::class, 'destroy']);
    Route::get('/purchaseOrder/setDetail/{po_id}', [PurchaseOrderController::class, 'setDetail'])->name('setDetail.detailPo');
    Route::get('/purchaseOrder/word/{po_id}', [PurchaseOrderController::class, 'word']);

});

