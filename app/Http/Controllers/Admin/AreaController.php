<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Area\AreaStoreUpdateRequest;
use App\Http\Resources\Admin\Area\AreaResource;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Area::query();

            if ($q = $request->input('search.value')) {
                $query->where(function ($qArea) use ($q) {
                    $qArea->whereLike('name', $q);
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
            }

            $total = (clone $query)->count();
            $areas = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return AreaResource::collection($areas)->additional([
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
            ]);
        }

        return view('admin.area.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $area = Area::find($id);

        if (is_null($area)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return new AreaResource($area);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AreaStoreUpdateRequest $request)
    {
        try {
            $area = new Area();
            $area->fill([
                'name' => $request->validated('area_name'),
                'target' => $request->validated('area_target'),
            ]);
            $area->save();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AreaStoreUpdateRequest $request, string $id)
    {
        $area = Area::find($id);

        if (is_null($area)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            $area->fill([
                'name' => $request->validated('area_name'),
                'target' => $request->validated('area_target'),
            ]);
            $area->save();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $area = Area::find($id);

        if (is_null($area)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            $area->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }

    public function ajax_list_area(Request $request)
    {
        $params = request()->all();

        $query = Area::select('id', 'name as text');

        $q = array_key_exists('query', $params) ? $params['query'] : (array_key_exists('q', $params) ? $params['q'] : '');
        if ($q) {
            $query->whereLike('name', $q);
        }

        $list = $query->limit(20)->get()->toArray();

        return response()->json([
            'items' => $list,
            'count' => count($list),
        ]);
    }
}