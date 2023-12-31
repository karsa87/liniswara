<?php

namespace App\Http\Controllers\Admin\Log;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Log\ImportResource;
use App\Models\Import;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Import::with([
                'user',
                'logs',
            ])
                ->orderBy('created_at', 'DESC');

            if ($q = $request->input('search.user_id')) {
                $query->where('user_id', $q);
            }

            if ($q = $request->input('search.action')) {
                $query->where('status', $q);
            }

            $totalAll = (clone $query)->count();
            $imports = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return ImportResource::collection($imports)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $imports->count(),
            ]);
        }

        return view(
            'admin.log.import'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $log = Import::find($id);

        if (is_null($log)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return new ImportResource($log);
    }
}
