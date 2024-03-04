<?php

use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CollectorController;
use App\Http\Controllers\Admin\CustomerAddressController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExpeditionController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\Log\HistoryController;
use App\Http\Controllers\Admin\Log\ImportController;
use App\Http\Controllers\Admin\Log\StockProductController;
use App\Http\Controllers\Admin\OrderArsipController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderSentController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PreorderBookController;
use App\Http\Controllers\Admin\PreorderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\RestockController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\InvoiceController;
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

    Route::get('/po-invoice/{encrypt}', [
        InvoiceController::class, 'po_invoice',
    ])->name('customer.po_invoice');

    Route::get('/po-order/{encrypt}', [
        InvoiceController::class, 'po_order',
    ])->name('customer.po_order');

    Route::get('/po-order/{encrypt}/track', [
        InvoiceController::class, 'po_order_track',
    ])->name('customer.po_order.track');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [
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
    Route::get('/dashboard/widget/sales', [
        DashboardController::class, 'widget_sales',
    ])->name('dashboard.widget.sales');
    Route::get('/dashboard/widget/product', [
        DashboardController::class, 'widget_product',
    ])->name('dashboard.widget.product');

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

    Route::name('area.')->prefix('area/')->group(function () {
        Route::get('/', [
            AreaController::class, 'index',
        ])->name('index');
        Route::get('/index/list', [
            AreaController::class, 'index',
        ])->name('index.list');
        Route::get('/detail/{id?}', [
            AreaController::class, 'show',
        ])->where('id', '[0-9]+')
            ->name('show');
        Route::post('store', [
            AreaController::class, 'store',
        ])->name('store');
        Route::delete('delete/{id?}', [
            AreaController::class, 'destroy',
        ])->where('id', '[0-9]+')
            ->name('delete');
        Route::put('update/{id?}', [
            AreaController::class, 'update',
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
        Route::get('/export/template-import', [
            CustomerController::class, 'export_template_import',
        ])->name('export.template_import');
        Route::post('/import', [
            CustomerController::class, 'import',
        ])->name('import');

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
        Route::get('/export', [
            ProductController::class, 'export',
        ])->name('export');
        Route::get('/export/template-import', [
            ProductController::class, 'export_template_import',
        ])->name('export.template_import');
        Route::post('/import', [
            ProductController::class, 'import',
        ])->name('import');
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
        Route::get('/export', [
            PreorderController::class, 'export',
        ])->name('export');

        // START PRINT REPORT
        Route::name('print.')->prefix('print')->group(function () {
            Route::get('purchase-order/{id?}', [
                PreorderController::class, 'purchase_order',
            ])->where('id', '[0-9]+')
                ->name('purchase_order');
            Route::get('faktur/{id?}', [
                PreorderController::class, 'faktur',
            ])->where('id', '[0-9]+')
                ->name('faktur');
        });
    });

    Route::name('order.')->prefix('order/')->group(function () {
        Route::get('/', [
            OrderController::class, 'index',
        ])->name('index');
        Route::get('/index/list', [
            OrderController::class, 'index',
        ])->name('index.list');
        Route::get('/create/{preorder_id?}', [
            OrderController::class, 'create',
        ])->name('create');
        Route::get('/detail/{id?}', [
            OrderController::class, 'show',
        ])->where('id', '[0-9]+')
            ->name('show');
        Route::get('/detail-json/{id?}', [
            OrderController::class, 'detail',
        ])->where('id', '[0-9]+')
            ->name('detail');
        Route::get('/edit/{id?}', [
            OrderController::class, 'edit',
        ])->where('id', '[0-9]+')
            ->name('edit');
        Route::post('/store/{preorder_id?}', [
            OrderController::class, 'store',
        ])->name('store');
        Route::delete('delete/{id?}', [
            OrderController::class, 'destroy',
        ])->where('id', '[0-9]+')
            ->name('delete');
        Route::put('update/{id?}', [
            OrderController::class, 'update',
        ])->where('id', '[0-9]+')
            ->name('update');
        Route::put('update-discount/{id?}', [
            OrderController::class, 'update_discount',
        ])->where('id', '[0-9]+')
            ->name('update_discount');
        Route::put('update-status/{id?}', [
            OrderController::class, 'update_status',
        ])->where('id', '[0-9]+')
            ->name('update_status');
        Route::get('track/{id?}', [
            OrderController::class, 'track',
        ])->where('id', '[0-9]+')
            ->name('track');
        Route::get('/export', [
            OrderController::class, 'export',
        ])->name('export');

        // START PRINT REPORT
        Route::name('print.')->prefix('print')->group(function () {
            Route::get('purchase-order/{id?}', [
                OrderController::class, 'purchase_order',
            ])->where('id', '[0-9]+')
                ->name('purchase_order');
            Route::get('faktur/{id?}', [
                OrderController::class, 'faktur',
            ])->where('id', '[0-9]+')
                ->name('faktur');
            Route::get('address/{id?}', [
                OrderController::class, 'address',
            ])->where('id', '[0-9]+')
                ->name('address');
            Route::get('sent-document/{id?}', [
                OrderController::class, 'sent_document',
            ])->where('id', '[0-9]+')
                ->name('sent_document');
        });
    });

    Route::name('order_sent.')->prefix('order-sent/')->group(function () {
        Route::get('/', [
            OrderSentController::class, 'index',
        ])->name('index');
        Route::get('/index/list', [
            OrderSentController::class, 'index',
        ])->name('index.list');
    });

    Route::name('order_arsip.')->prefix('order-arsip/')->group(function () {
        Route::get('/', [
            OrderArsipController::class, 'index',
        ])->name('index');
        Route::get('/index/list', [
            OrderArsipController::class, 'index',
        ])->name('index.list');
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

    Route::name('profile.')->prefix('profile/')->group(function () {
        Route::get('/', [
            ProfileController::class, 'index',
        ])->name('index');
        Route::put('update', [
            ProfileController::class, 'update',
        ])->name('update');
        Route::put('update/email', [
            ProfileController::class, 'update_email',
        ])->name('update.email');
        Route::put('update/password', [
            ProfileController::class, 'update_password',
        ])->name('update.password');
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
        Route::name('history.')->prefix('history/')->group(function () {
            Route::get('/', [
                HistoryController::class, 'index',
            ])->name('index');
            Route::get('/index/list', [
                HistoryController::class, 'index',
            ])->name('index.list');
            Route::get('/detail/{id?}', [
                HistoryController::class, 'show',
            ])->where('id', '[0-9]+')
                ->name('show');
        });
        Route::name('import.')->prefix('import/')->group(function () {
            Route::get('/', [
                ImportController::class, 'index',
            ])->name('index');
            Route::get('/index/list', [
                ImportController::class, 'index',
            ])->name('index.list');
            Route::get('/detail/{id?}', [
                ImportController::class, 'show',
            ])->where('id', '[0-9]+')
                ->name('show');
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
            Route::get('regency', [
                RegionController::class,
                'ajax_list_regency',
            ])->name('regency.list');
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
        Route::get('user', [
            UserController::class,
            'ajax_list_user',
        ])->name('user.list');
        Route::get('area', [
            AreaController::class,
            'ajax_list_area',
        ])->name('area.list');
    });
});
