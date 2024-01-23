<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PreorderBookExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PreorderBook\PreorderBookResource;
use App\Models\PreorderDetail;
use App\Models\Product;
use App\Services\TrackExpeditionService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PreorderBookController extends Controller
{
    public function __construct(
        private TrackExpeditionService $trackExpeditionService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Product::addSelect([
                // Key is the alias, value is the sub-select
                'stock_need' => PreorderDetail::query()
                    // You can use eloquent methods here
                    ->selectRaw('(sum(IFNULL(qty, 0)) - sum(IFNULL(qty_order, 0)))')
                    ->whereColumn('product_id', 'products.id')
                    ->whereRaw('qty != qty_order'),
                'total_stock_need' => PreorderDetail::query()
                    // You can use eloquent methods here
                    ->selectRaw('((sum(IFNULL(qty, 0)) - sum(IFNULL(qty_order, 0))) - IFNULL(products.stock, 0))')
                    ->whereColumn('product_id', 'products.id')
                    ->whereRaw('qty != qty_order'),
                'total_stock_more' => PreorderDetail::query()
                    // You can use eloquent methods here
                    ->selectRaw('(IFNULL(products.stock, 0) - (sum(IFNULL(qty, 0)) - sum(IFNULL(qty_order, 0))))')
                    ->whereColumn('product_id', 'products.id')
                    ->whereRaw('qty != qty_order'),
            ])->has('preorder_details')
                ->havingRaw('stock_need > 0');

            if ($q = $request->input('search.value')) {
                $query->where(function ($qProduct) use ($q) {
                    $qProduct->whereLike('name', $q)
                        ->orWhereLike('code', $q);
                });
            }

            if (is_numeric($request->input('order.0.column'))) {
                $column = $request->input('order.0.column');
                $columnData = $request->input("columns.$column.data");
                $sorting = $request->input('order.0.dir');

                if ($sorting == 'desc') {
                    $query->orderBy($columnData, 'DESC');
                } else {
                    $query->orderBy($columnData, 'ASC');
                }
            } else {
                $query->orderBy('total_stock_need', 'DESC');
            }

            $totalAll = (clone $query)->count();
            $preorders = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return PreorderBookResource::collection($preorders)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $totalAll,
            ]);
        }

        return view(
            'admin.preorder_book.index'
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function export(Request $request)
    {
        $query = Product::addSelect([
            // Key is the alias, value is the sub-select
            'stock_need' => PreorderDetail::query()
                // You can use eloquent methods here
                ->selectRaw('(sum(IFNULL(qty, 0)) - sum(IFNULL(qty_order, 0)))')
                ->whereColumn('product_id', 'products.id')
                ->whereRaw('qty != qty_order'),
            'total_stock_need' => PreorderDetail::query()
                // You can use eloquent methods here
                ->selectRaw('((sum(IFNULL(qty, 0)) - sum(IFNULL(qty_order, 0))) - IFNULL(products.stock, 0))')
                ->whereColumn('product_id', 'products.id')
                ->whereRaw('qty != qty_order'),
            'total_stock_more' => PreorderDetail::query()
                // You can use eloquent methods here
                ->selectRaw('(IFNULL(products.stock, 0) - (sum(IFNULL(qty, 0)) - sum(IFNULL(qty_order, 0))))')
                ->whereColumn('product_id', 'products.id')
                ->whereRaw('qty != qty_order'),
        ])->orderBy('total_stock_need', 'DESC')
            ->havingRaw('stock_need > 0')
            ->has('preorder_details');

        if ($request->product_id) {
            $query->where('id', $request->product_id);
        }

        $preorders = $query->get();

        $now = date('d-m-Y-H-i');

        return Excel::download(new PreorderBookExport($preorders), "Preorder Book - $now.xlsx");
    }
}
