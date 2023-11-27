<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Collector\CollectorStoreUpdateRequest;
use App\Http\Resources\Admin\Collector\CollectorResource;
use App\Models\Collector;
use App\Utils\Phone;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CollectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Collector::with([
                'province:id,name',
                'regency:id,name',
                'district:id,name',
                'village:id,name',
            ])
                ->offset($request->get('start', 0))
                ->limit($request->get('length', 10));

            if ($q = $request->input('search.value')) {
                $query->where(function ($qCollector) use ($q) {
                    $qCollector->whereLike('name', $q)
                        ->orWhereLike('company', $q)
                        ->orWhereLike('phone', $q)
                        ->orWhereLike('email', $q);
                });
            }

            if ($provinceId = $request->input('search.province_id')) {
                $query->where('province_id', $provinceId);
            }

            if ($regencyId = $request->input('search.regency_id')) {
                $query->where('regency_id', $regencyId);
            }

            if ($districtId = $request->input('search.district_id')) {
                $query->where('district_id', $districtId);
            }

            if ($villageId = $request->input('search.village_id')) {
                $query->where('village_id', $villageId);
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

            $collectors = $query->get();

            $totalAll = Collector::count();

            return CollectorResource::collection($collectors)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $collectors->count(),
            ]);
        }

        return view('admin.collector.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CollectorStoreUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $collector = new Collector();
            $collector->fill([
                'name' => $request->input('collector_name'),
                'email' => $request->input('collector_email'),
                'company' => $request->input('collector_company'),
                'phone_number' => Phone::normalize($request->input('collector_phone_number')),
                'address' => $request->input('collector_address'),
                'footer' => $request->input('collector_footer'),
                'billing_notes' => $request->input('collector_billing_notes'),
                'npwp' => $request->input('collector_npwp'),
                'gst' => $request->input('collector_gst'),
                'province_id' => $request->input('collector_province_id'),
                'regency_id' => $request->input('collector_regency_id'),
                'district_id' => $request->input('collector_district_id'),
                'village_id' => $request->input('collector_village_id'),
                'signin_file_id' => $request->input('collector_signin_file_id'),
            ]);

            $collector->save();

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
        $collector = Collector::with('signin_file')->find($id);

        if (is_null($collector)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return new CollectorResource($collector);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CollectorStoreUpdateRequest $request, string $id)
    {
        $collector = Collector::find($id);

        if (is_null($collector)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        DB::beginTransaction();
        try {
            $collector->fill([
                'name' => $request->input('collector_name'),
                'email' => $request->input('collector_email'),
                'company' => $request->input('collector_company'),
                'phone_number' => Phone::normalize($request->input('collector_phone_number')),
                'address' => $request->input('collector_address'),
                'footer' => $request->input('collector_footer'),
                'billing_notes' => $request->input('collector_billing_notes'),
                'npwp' => $request->input('collector_npwp'),
                'gst' => $request->input('collector_gst'),
                'province_id' => $request->input('collector_province_id'),
                'regency_id' => $request->input('collector_regency_id'),
                'district_id' => $request->input('collector_district_id'),
                'village_id' => $request->input('collector_village_id'),
                'signin_file_id' => $request->input('collector_signin_file_id'),
            ]);

            $collector->save();

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
        $collector = Collector::with('signin_file')->find($id);

        if (is_null($collector)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            if ($collector->signin_file) {
                $collector->signin_file->delete();
            }
            $collector->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }
}
