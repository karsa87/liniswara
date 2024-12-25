<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Area\AreaSchoolStoreUpdateRequest;
use App\Http\Resources\Admin\Area\AreaSchoolResource;
use App\Models\Area;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AreaSchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $areaId)
    {
        if ($request->ajax()) {
            $query = School::with([
                'areas' => function ($qArea) use ($areaId) {
                    $qArea->where('area_id', $areaId);
                },
            ])->whereHas('areas', function ($qArea) use ($areaId) {
                $qArea->where('area_id', $areaId);
            });

            if ($q = $request->input('search.value')) {
                $query->where(function ($qSchool) use ($q) {
                    $qSchool->whereLike('name', $q);
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

            $totalAll = (clone $query)->count();

            $areaSchools = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return AreaSchoolResource::collection($areaSchools)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $totalAll,
            ]);
        }

        return view('admin.area_school.index', [
            'areaId' => $areaId,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AreaSchoolStoreUpdateRequest $request, $areaId)
    {
        DB::beginTransaction();
        try {
            $area = Area::find($areaId);
            if (! $area) {
                return response()->json([
                    'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                ], Response::HTTP_NOT_FOUND);
            }

            $area->schools()->detach([$request->input('area_school_id')]);
            $area->schools()->syncWithoutDetaching([
                $request->input('area_school_id') => [
                    'target' => $request->input('area_school_target'),
                ],
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($areaId, string $id)
    {
        $school = School::with([
            'areas' => function ($qArea) use ($areaId) {
                $qArea->with('user')->where('area_id', $areaId);
            },
        ])->find($id);

        if (is_null($school)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return new AreaSchoolResource($school);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AreaSchoolStoreUpdateRequest $request, $areaId, string $id)
    {
        DB::beginTransaction();
        try {
            $area = Area::find($areaId);
            if (! $area) {
                return response()->json([
                    'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                ], Response::HTTP_NOT_FOUND);
            }

            $area->schools()->detach([$id]);
            $area->schools()->syncWithoutDetaching([
                $id => [
                    'target' => $request->input('area_school_target'),
                ],
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

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
    public function destroy($areaId, string $id)
    {
        $area = Area::find($areaId);
        if (! $area) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $area->schools()->detach([$id]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }
}
