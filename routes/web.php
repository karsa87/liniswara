<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
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

Route::middleware(['web'])->group(function () {
    Route::get('login', [
        AuthController::class, 'login',
    ])->name('auth.login');
    Route::post('submit-login', [
        AuthController::class, 'submit_login',
    ])->name('auth.submit.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('admin.layouts.admin');
    })->name('dashboard');

    Route::name('permission.')->prefix('permission/')->group(function () {
        Route::get('/', [
            PermissionController::class, 'index',
        ])->name('index');
        Route::get('/index/list', [
            PermissionController::class, 'index',
        ])->name('index.list');
        Route::post('store', [
            PermissionController::class, 'store',
        ])->name('store');
        Route::delete('delete/{id?}', [
            PermissionController::class, 'destroy',
        ])->where('id', '[0-9]+')
            ->name('delete');
        Route::put('update/{id?}', [
            PermissionController::class, 'update',
        ])->where('id', '[0-9]+')
            ->name('update');
    });

    Route::name('role.')->prefix('role/')->group(function () {
        Route::get('/', [
            RoleController::class, 'index',
        ])->name('index');
        Route::get('/detail/{id?}', [
            RoleController::class, 'show',
        ])->where('id', '[0-9]+')
            ->name('show');
        Route::post('store', [
            RoleController::class, 'store',
        ])->name('store');
        Route::delete('delete/{id?}', [
            RoleController::class, 'destroy',
        ])->where('id', '[0-9]+')
            ->name('delete');
        Route::put('update/{id?}', [
            RoleController::class, 'update',
        ])->where('id', '[0-9]+')
            ->name('update');
    });

    Route::name('user.')->prefix('user/')->group(function () {
        Route::get('/', [
            UserController::class, 'index',
        ])->name('index');
        Route::get('/index/list', [
            UserController::class, 'index',
        ])->name('index.list');
        Route::get('/detail/{id?}', [
            UserController::class, 'show',
        ])->where('id', '[0-9]+')
            ->name('show');
        Route::post('store', [
            UserController::class, 'store',
        ])->name('store');
        Route::delete('delete/{id?}', [
            UserController::class, 'destroy',
        ])->where('id', '[0-9]+')
            ->name('delete');
        Route::put('update/{id?}', [
            UserController::class, 'update',
        ])->where('id', '[0-9]+')
            ->name('update');
    });
});
