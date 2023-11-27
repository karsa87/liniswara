<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Expedition\ExpeditionStoreUpdateRequest;
use App\Http\Resources\Admin\Expedition\ExpeditionResource;
use App\Models\Expedition;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ExpeditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Expedition::with([
                'logo',
            ])
                ->offset($request->get('start', 0))
                ->limit($request->get('length', 10));

            if ($q = $request->input('search.value')) {
                $query->where(function ($qExpedition) use ($q) {
                    $qExpedition->whereLike('name', $q);
                });
            }

            $expeditions = $query->get();

            $totalAll = Expedition::count();

            return ExpeditionResource::collection($expeditions)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $expeditions->count(),
            ]);
        }

        return view('admin.expedition.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpeditionStoreUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $expedition = new Expedition();
            $expedition->fill([
                'name' => $request->input('expedition_name'),
                'logo_id' => $request->input('expedition_logo_id'),
            ]);

            $expedition->save();

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
    public function show(string $id)
    {
        $expedition = Expedition::find($id);

        if (is_null($expedition)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return new ExpeditionResource($expedition);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpeditionStoreUpdateRequest $request, string $id)
    {
        $expedition = Expedition::find($id);

        if (is_null($expedition)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        DB::beginTransaction();
        try {
            $expedition->fill([
                'name' => $request->input('expedition_name'),
                'logo_id' => $request->input('expedition_logo_id'),
            ]);

            $expedition->save();

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
    public function destroy(string $id)
    {
        $expedition = Expedition::wiht('logo')->find($id);

        if (is_null($expedition)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            if ($expedition->logo->delete()) {
                $expedition->delete();
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }
}
