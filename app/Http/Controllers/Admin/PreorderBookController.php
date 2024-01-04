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
                    ->selectRaw('(sum(qty) - sum(qty_order))')
                    ->whereColumn('product_id', 'products.id')
                    ->whereRaw('qty != qty_order')
                    ->groupBy('product_id'),
            ])
                ->havingRaw('stock < stock_need');

            if ($q = $request->input('search.value')) {
                $query->where(function ($qProduct) use ($q) {
                    $qProduct->whereLike('name', $q)
                        ->orWhereLike('code', $q);
                });
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
                ->selectRaw('(sum(qty) - sum(qty_order))')
                ->whereColumn('product_id', 'products.id')
                ->whereRaw('qty != qty_order')
                ->groupBy('product_id'),
        ])
            ->havingRaw('stock < stock_need');

        if ($request->product_id) {
            $query->where('id', $request->product_id);
        }

        $preorders = $query->get();

        return Excel::download(new PreorderBookExport($preorders), 'preorder_book.xlsx');
    }
}
