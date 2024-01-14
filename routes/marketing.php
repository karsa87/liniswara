<?php

use App\Http\Controllers\Marketing\DashboardController;
use App\Http\Controllers\Marketing\PaymentController;
use App\Http\Controllers\Marketing\StockController;
use App\Http\Controllers\Marketing\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/dashboard', [
    DashboardController::class, 'index',
])->name('dashboard');

Route::name('payment.')->prefix('payment/')->group(function () {
    Route::get('transaction', [
        PaymentController::class, 'transaction',
    ])->name('transaction');
    Route::get('agent', [
        PaymentController::class, 'agent',
    ])->name('agent');
    Route::get('agent/{id?}', [
        PaymentController::class, 'detail_agent',
    ])->name('detail_agent');
});

Route::name('transaction.')->prefix('transaction/')->group(function () {
    Route::get('/', [
        TransactionController::class, 'index',
    ])->name('index');
    Route::get('{id?}', [
        TransactionController::class, 'detail',
    ])->name('detail');
});

Route::name('stock.')->prefix('stock/')->group(function () {
    Route::get('/', [
        StockController::class, 'index',
    ])->name('index');
    Route::post('store', [
        StockController::class, 'storeStock',
    ])->name('store');
    Route::get('download/excel', [
        StockController::class, 'downloadExcel',
    ])->name('download.excel');
});
