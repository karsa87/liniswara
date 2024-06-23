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

Route::get('/dashboard/widget/preorder', [
    DashboardController::class, 'widget_preorder',
])->name('dashboard.widget.preorder');
Route::get('/dashboard/widget/order', [
    DashboardController::class, 'widget_order',
])->name('dashboard.widget.order');
Route::get('/dashboard/widget/zone', [
    DashboardController::class, 'widget_zone',
])->name('dashboard.widget.zone');
Route::get('/dashboard/widget/transaction-by-marketing', [
    DashboardController::class, 'widget_transaction_by_marketing',
])->name('dashboard.widget.transaction_by_marketing');
Route::get('/dashboard/widget/transaction-all', [
    DashboardController::class, 'widget_transaction_all',
])->name('dashboard.widget.transaction_all');

Route::get('/switch-marketing/{marketing?}', [
    DashboardController::class, 'switch_marketing',
])->name('switch_marketing');

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
    Route::get('agent/transaction-rank-regency/{id?}', [
        PaymentController::class, 'transaction_rank_regency',
    ])->name('trasaction_rank_regency');
    Route::get('region', [
        PaymentController::class, 'region',
    ])->name('region');
    Route::get('region/transaction-rank-agent/{id?}', [
        PaymentController::class, 'transaction_rank_agent',
    ])->name('transaction_rank_agent');
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
    Route::get('clear', [
        StockController::class, 'clear',
    ])->name('clear');
    Route::get('download/excel', [
        StockController::class, 'downloadExcel',
    ])->name('download.excel');
});
