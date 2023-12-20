<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CollectorController;
use App\Http\Controllers\Admin\CustomerAddressController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ExpeditionController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\Log\StockProductController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PreorderBookController;
use App\Http\Controllers\Admin\PreorderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\RestockController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SupplierController;
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
    Route::get('logout', [
        AuthController::class, 'logout',
    ])->name('auth.logout');
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

    Route::name('branch.')->prefix('branch/')->group(function () {
        Route::get('/', [
            BranchController::class, 'index',
        ])->name('index');
        Route::get('/index/list', [
            BranchController::class, 'index',
        ])->name('index.list');
        Route::post('store', [
            BranchController::class, 'store',
        ])->name('store');
        Route::delete('delete/{id?}', [
            BranchController::class, 'destroy',
        ])->where('id', '[0-9]+')
            ->name('delete');
        Route::put('update/{id?}', [
            BranchController::class, 'update',
        ])->where('id', '[0-9]+')
            ->name('update');
    });

    Route::name('setting.')->prefix('setting/')->group(function () {
        Route::get('/', [
            SettingController::class, 'index',
        ])->name('index');
        Route::get('/index/list', [
            SettingController::class, 'index',
        ])->name('index.list');
        Route::delete('delete/{id?}', [
            SettingController::class, 'destroy',
        ])->where('id', '[0-9]+')
            ->name('delete');
        Route::put('update/{id?}', [
            SettingController::class, 'update',
        ])->where('id', '[0-9]+')
            ->name('update');
    });

    Route::name('supplier.')->prefix('supplier/')->group(function () {
        Route::get('/', [
            SupplierController::class, 'index',
        ])->name('index');
        Route::get('/index/list', [
            SupplierController::class, 'index',
        ])->name('index.list');
        Route::get('/detail/{id?}', [
            SupplierController::class, 'show',
        ])->where('id', '[0-9]+')
            ->name('show');
        Route::post('store', [
            SupplierController::class, 'store',
        ])->name('store');
        Route::delete('delete/{id?}', [
            SupplierController::class, 'destroy',
        ])->where('id', '[0-9]+')
            ->name('delete');
        Route::put('update/{id?}', [
            SupplierController::class, 'update',
        ])->where('id', '[0-9]+')
            ->name('update');
    });

    Route::name('collector.')->prefix('collector/')->group(function () {
        Route::get('/', [
            CollectorController::class, 'index',
        ])->name('index');
        Route::get('/index/list', [
            CollectorController::class, 'index',
        ])->name('index.list');
        Route::get('/detail/{id?}', [
            CollectorController::class, 'show',
        ])->where('id', '[0-9]+')
            ->name('show');
        Route::post('store', [
            CollectorController::class, 'store',
        ])->name('store');
        Route::delete('delete/{id?}', [
            CollectorController::class, 'destroy',
        ])->where('id', '[0-9]+')
            ->name('delete');
        Route::put('update/{id?}', [
            CollectorController::class, 'update',
        ])->where('id', '[0-9]+')
            ->name('update');
    });

    Route::name('customer.')->prefix('customer/')->group(function () {
        Route::get('/', [
            CustomerController::class, 'index',
        ])->name('index');
        Route::get('/index/list', [
            CustomerController::class, 'index',
        ])->name('index.list');
        Route::get('/detail/{id?}', [
            CustomerController::class, 'show',
        ])->where('id', '[0-9]+')
            ->name('show');
        Route::post('store', [
            CustomerController::class, 'store',
        ])->name('store');
        Route::delete('delete/{id?}', [
            CustomerController::class, 'destroy',
        ])->where('id', '[0-9]+')
            ->name('delete');
        Route::put('update/{id?}', [
            CustomerController::class, 'update',
        ])->where('id', '[0-9]+')
            ->name('update');

        Route::name('customer_address.')->prefix('{customerId}/customer-address')->group(function () {
            Route::get('/', [
                CustomerAddressController::class, 'index',
            ])->name('index');
            Route::get('/index/list', [
                CustomerAddressController::class, 'index',
            ])->name('index.list');
            Route::get('/detail/{id?}', [
                CustomerAddressController::class, 'show',
            ])->where('id', '[0-9]+')
                ->name('show');
            Route::post('store', [
                CustomerAddressController::class, 'store',
            ])->name('store');
            Route::delete('delete/{id?}', [
                CustomerAddressController::class, 'destroy',
            ])->where('id', '[0-9]+')
                ->name('delete');
            Route::put('update/{id?}', [
                CustomerAddressController::class, 'update',
            ])->where('id', '[0-9]+')
                ->name('update');
        });
    });

    Route::name('expedition.')->prefix('expedition/')->group(function () {
        Route::get('/', [
            ExpeditionController::class, 'index',
        ])->name('index');
        Route::get('/index/list', [
            ExpeditionController::class, 'index',
        ])->name('index.list');
        Route::get('/detail/{id?}', [
            ExpeditionController::class, 'show',
        ])->where('id', '[0-9]+')
            ->name('show');
        Route::post('store', [
            ExpeditionController::class, 'store',
        ])->name('store');
        Route::delete('delete/{id?}', [
            ExpeditionController::class, 'destroy',
        ])->where('id', '[0-9]+')
            ->name('delete');
        Route::put('update/{id?}', [
            ExpeditionController::class, 'update',
        ])->where('id', '[0-9]+')
            ->name('update');
    });

    Route::name('category.')->prefix('category/')->group(function () {
        Route::get('/', [
            CategoryController::class, 'index',
        ])->name('index');
        Route::get('/index/list', [
            CategoryController::class, 'index',
        ])->name('index.list');
        Route::get('/detail/{id?}', [
            CategoryController::class, 'show',
        ])->where('id', '[0-9]+')
            ->name('show');
        Route::post('store', [
            CategoryController::class, 'store',
        ])->name('store');
        Route::delete('delete/{id?}', [
            CategoryController::class, 'destroy',
        ])->where('id', '[0-9]+')
            ->name('delete');
        Route::put('update/{id?}', [
            CategoryController::class, 'update',
        ])->where('id', '[0-9]+')
            ->name('update');
    });

    Route::name('product.')->prefix('product/')->group(function () {
        Route::get('/', [
            ProductController::class, 'index',
        ])->name('index');
        Route::get('/index/list', [
            ProductController::class, 'index',
        ])->name('index.list');
        Route::get('/create', [
            ProductController::class, 'create',
        ])->name('create');
        Route::get('/detail/{id?}', [
            ProductController::class, 'show',
        ])->where('id', '[0-9]+')
            ->name('show');
        Route::get('/edit/{id?}', [
            ProductController::class, 'edit',
        ])->where('id', '[0-9]+')
            ->name('edit');
        Route::post('store', [
            ProductController::class, 'store',
        ])->name('store');
        Route::delete('delete/{id?}', [
            ProductController::class, 'destroy',
        ])->where('id', '[0-9]+')
            ->name('delete');
        Route::put('update/{id?}', [
            ProductController::class, 'update',
        ])->where('id', '[0-9]+')
            ->name('update');
    });

    Route::name('restock.')->prefix('restock/')->group(function () {
        Route::get('/', [
            RestockController::class, 'index',
        ])->name('index');
        Route::get('/index/list', [
            RestockController::class, 'index',
        ])->name('index.list');
        Route::get('/create', [
            RestockController::class, 'create',
        ])->name('create');
        Route::get('/detail/{id?}', [
            RestockController::class, 'show',
        ])->where('id', '[0-9]+')
            ->name('show');
        Route::get('/edit/{id?}', [
            RestockController::class, 'edit',
        ])->where('id', '[0-9]+')
            ->name('edit');
        Route::post('store', [
            RestockController::class, 'store',
        ])->name('store');
        Route::delete('delete/{id?}', [
            RestockController::class, 'destroy',
        ])->where('id', '[0-9]+')
            ->name('delete');
        Route::put('update/{id?}', [
            RestockController::class, 'update',
        ])->where('id', '[0-9]+')
            ->name('update');
    });

    Route::name('preorder.')->prefix('preorder/')->group(function () {
        Route::get('/', [
            PreorderController::class, 'index',
        ])->name('index');
        Route::get('/index/list', [
            PreorderController::class, 'index',
        ])->name('index.list');
        Route::get('/create', [
            PreorderController::class, 'create',
        ])->name('create');
        Route::get('/detail/{id?}', [
            PreorderController::class, 'show',
        ])->where('id', '[0-9]+')
            ->name('show');
        Route::get('/detail-json/{id?}', [
            PreorderController::class, 'detail',
        ])->where('id', '[0-9]+')
            ->name('detail');
        Route::get('/edit/{id?}', [
            PreorderController::class, 'edit',
        ])->where('id', '[0-9]+')
            ->name('edit');
        Route::post('store', [
            PreorderController::class, 'store',
        ])->name('store');
        Route::delete('delete/{id?}', [
            PreorderController::class, 'destroy',
        ])->where('id', '[0-9]+')
            ->name('delete');
        Route::put('update/{id?}', [
            PreorderController::class, 'update',
        ])->where('id', '[0-9]+')
            ->name('update');
        Route::put('update-discount/{id?}', [
            PreorderController::class, 'update_discount',
        ])->where('id', '[0-9]+')
            ->name('update_discount');
        Route::put('update-status/{id?}', [
            PreorderController::class, 'update_status',
        ])->where('id', '[0-9]+')
            ->name('update_status');
        Route::get('track/{id?}', [
            PreorderController::class, 'track',
        ])->where('id', '[0-9]+')
            ->name('track');
    });

    Route::name('preorder_book.')->prefix('preorder-book/')->group(function () {
        Route::get('/', [
            PreorderBookController::class, 'index',
        ])->name('index');
        Route::get('/index/list', [
            PreorderBookController::class, 'index',
        ])->name('index.list');
        Route::get('/export', [
            PreorderBookController::class, 'export',
        ])->name('export');
    });

    Route::name('log.')->prefix('log')->group(function () {
        Route::name('stock_product.')->prefix('stock-product/')->group(function () {
            Route::get('/', [
                StockProductController::class, 'index',
            ])->name('index');
            Route::get('/index/list', [
                StockProductController::class, 'index',
            ])->name('index.list');
        });
    });

    Route::name('file.')->prefix('file/')->group(function () {
        Route::post('upload', [
            FileController::class, 'upload',
        ])->name('upload');
        Route::delete('delete/{id?}', [
            FileController::class, 'destroy',
        ])->where('id', '[0-9]+')
            ->name('delete');
    });

    Route::name('ajax.')->prefix('ajax')->group(function () {
        Route::name('region.')->prefix('region')->group(function () {
            Route::get('list', [
                RegionController::class,
                'list',
            ])->name('list');
            Route::get('province', [
                RegionController::class,
                'province',
            ])->name('province');
        });
        Route::get('category', [
            CategoryController::class,
            'ajax_list_category',
        ])->name('category.list');
        Route::get('branch', [
            BranchController::class,
            'ajax_list_branch',
        ])->name('branch.list');
        Route::get('product', [
            ProductController::class,
            'ajax_list_product',
        ])->name('product.list');
        Route::get('customer', [
            CustomerController::class,
            'ajax_list_customer',
        ])->name('customer.list');
        Route::get('collector', [
            CollectorController::class,
            'ajax_list_collector',
        ])->name('collector.list');
        Route::get('expedition', [
            ExpeditionController::class,
            'ajax_list_expedition',
        ])->name('expedition.list');
    });
});
