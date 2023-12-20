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

Route::get('/', [
    DashboardController::class, 'index',
])->name('dashboard');

Route::get('payment/transaction', [
    PaymentController::class, 'transaction',
])->name('payment.transaction');
Route::get('payment/agent', [
    PaymentController::class, 'agent',
])->name('payment.agent');
Route::get('payment/agent/{id?}', [
    PaymentController::class, 'detail_agent',
])->name('payment.detail_agent');

Route::get('transaction', [
    TransactionController::class, 'index',
])->name('transaction.index');
Route::get('transaction/{id?}', [
    TransactionController::class, 'detail',
])->name('transaction.detail');

Route::get('stock', [
    StockController::class, 'index',
])->name('stock.index');
