<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PreorderBookExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PreorderBook\PreorderBookResource;
use App\Models\PreorderDetail;
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
            $query = PreorderDetail::select('product_id')
                ->selectRaw('sum(qty) as total_qty')
                ->with([
                    'product',
                ])
                ->has('product')
                ->groupBy('product_id')
                ->offset($request->get('start', 0))
                ->limit($request->get('length', 10));

            if ($q = $request->input('search.value')) {
                $query->where(function ($q2) use ($q) {
                    $q2->whereHas('product', function ($qProduct) use ($q) {
                        $qProduct->whereLike('name', $q);
                    });
                });
            }

            $preorders = $query->get();
            $totalAll = PreorderDetail::select('product_id')->has('product')->groupBy('product_id')->count();

            return PreorderBookResource::collection($preorders)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $preorders->count(),
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
        $query = PreorderDetail::select('product_id')
            ->selectRaw('sum(qty) as total_qty')
            ->with([
                'product',
            ])
            ->has('product')
            ->groupBy('product_id');

        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        $preorders = $query->get();

        return Excel::download(new PreorderBookExport($preorders), 'preorder_book.xlsx');
    }
}
