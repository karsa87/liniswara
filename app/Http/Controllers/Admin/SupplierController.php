<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Supplier\SupplierStoreUpdateRequest;
use App\Http\Resources\Admin\Supplier\SupplierResource;
use App\Models\Supplier;
use App\Utils\Phone;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Supplier::with([
                'province:id,name',
                'regency:id,name',
                'district:id,name',
                'village:id,name',
            ]);

            if ($q = $request->input('search.value')) {
                $query->where(function ($qSupplier) use ($q) {
                    $qSupplier->whereLike('name', $q)
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

            $totalAll = (clone $query)->count();
            $suppliers = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return SupplierResource::collection($suppliers)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $totalAll,
            ]);
        }

        return view('admin.supplier.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierStoreUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $supplier = new Supplier();
            $supplier->fill([
                'name' => $request->input('supplier_name'),
                'email' => $request->input('supplier_email'),
                'company' => $request->input('supplier_company'),
                'phone' => Phone::normalize($request->input('supplier_phone_number')),
                'address' => $request->input('supplier_address'),
                'province_id' => $request->input('supplier_province_id'),
                'regency_id' => $request->input('supplier_regency_id'),
                'district_id' => $request->input('supplier_district_id'),
                'village_id' => $request->input('supplier_village_id'),
            ]);

            $supplier->save();

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
        $supplier = Supplier::find($id);

        if (is_null($supplier)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return new SupplierResource($supplier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierStoreUpdateRequest $request, string $id)
    {
        $supplier = Supplier::find($id);

        if (is_null($supplier)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        DB::beginTransaction();
        try {
            $supplier->fill([
                'name' => $request->input('supplier_name'),
                'email' => $request->input('supplier_email'),
                'company' => $request->input('supplier_company'),
                'phone' => Phone::normalize($request->input('supplier_phone_number')),
                'address' => $request->input('supplier_address'),
                'province_id' => $request->input('supplier_province_id'),
                'regency_id' => $request->input('supplier_regency_id'),
                'district_id' => $request->input('supplier_district_id'),
                'village_id' => $request->input('supplier_village_id'),
            ]);

            $supplier->save();

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
        $supplier = Supplier::find($id);

        if (is_null($supplier)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            $supplier->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }
}
