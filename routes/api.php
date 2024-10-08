<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('client')->group(function () {
    Route::get('category', [CategoryController::class, 'index'])->name('category');
    Route::get('product', [ProductController::class, 'index'])->name('product');
    Route::get('product/{id}', [ProductController::class, 'show'])->name('product.detail');
});
