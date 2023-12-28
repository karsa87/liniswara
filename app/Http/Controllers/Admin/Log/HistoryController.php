<?php

namespace App\Http\Controllers\Admin\Log;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Log\HistoryResource;
use App\Models\LogHistory;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = LogHistory::with([
                'user',
            ])
                ->orderBy('created_at', 'DESC');

            if ($q = $request->input('search.user_id')) {
                $query->where('user_id', $q);
            }

            if ($q = $request->input('search.table')) {
                $query->where('table', $q);
            }

            if ($q = $request->input('search.action')) {
                $query->where('transaction_type', $q);
            }

            $totalAll = (clone $query)->count();
            $preorders = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return HistoryResource::collection($preorders)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $preorders->count(),
            ]);
        }

        return view(
            'admin.log.histories'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $log = LogHistory::find($id);

        if (is_null($log)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return new HistoryResource($log);
    }
}
