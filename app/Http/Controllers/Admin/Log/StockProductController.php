<?php

namespace App\Http\Controllers\Admin\Log;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Log\StockProductResource;
use App\Models\StockHistory;
use Illuminate\Http\Request;

class StockProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = StockHistory::with([
                'product',
            ])
                ->has('product')
                ->orderBy('created_at', 'DESC')
                ->offset($request->get('start', 0))
                ->limit($request->get('length', 10));

            if ($q = $request->input('search.value')) {
                $query->where(function ($q2) use ($q) {
                    $q2->whereHas('product', function ($qProduct) use ($q) {
                        $qProduct->whereLike('name', $q);
                    });
                });
            }

            if ($q = $request->input('search.product_id')) {
                $query->where('product_id', $q);
            }

            $preorders = $query->get();
            $totalAll = StockHistory::has('product')->count();

            return StockProductResource::collection($preorders)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $preorders->count(),
            ]);
        }

        return view(
            'admin.log.stock_product'
        );
    }
}
