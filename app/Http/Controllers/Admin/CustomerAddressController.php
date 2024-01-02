<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Customer\CustomerAddressStoreUpdateRequest;
use App\Http\Resources\Admin\Customer\CustomerAddressResource;
use App\Models\CustomerAddress;
use App\Utils\Phone;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CustomerAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $customerId)
    {
        if ($request->ajax()) {
            $query = CustomerAddress::with([
                'province:id,name',
                'regency:id,name',
                'district:id,name',
                'village:id,name',
            ])
                ->whereCustomerId($customerId)
                ->orderBy('is_default', 'DESC');

            if ($q = $request->input('search.value')) {
                $query->where(function ($qCustomerAddress) use ($q) {
                    $qCustomerAddress->whereLike('name', $q)
                        ->orWhereLike('phone_number', $q)
                        ->orWhereLike('address', $q);
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

            $customerAddresses = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return CustomerAddressResource::collection($customerAddresses)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $customerAddresses->count(),
            ]);
        }

        return view('admin.customer_address.index', [
            'customerId' => $customerId,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerAddressStoreUpdateRequest $request, $customerId)
    {
        DB::beginTransaction();
        try {
            $customerAddress = new CustomerAddress();
            $customerAddress->fill([
                'customer_id' => $customerId,
                'name' => $request->input('customer_address_name'),
                'phone_number' => Phone::normalize($request->input('customer_address_phone_number')),
                'address' => $request->input('customer_address_address'),
                'province_id' => $request->input('customer_address_province_id'),
                'regency_id' => $request->input('customer_address_regency_id'),
                'district_id' => $request->input('customer_address_district_id'),
                'village_id' => $request->input('customer_address_village_id'),
                'is_default' => $request->input('customer_address_is_default'),
            ]);

            if (
                $customerAddress->save()
                && $customerAddress->is_default
            ) {
                CustomerAddress::whereCustomerId($customerId)->where('id', '!=', $customerAddress->id)->update([
                    'is_default' => false,
                ]);
            }

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
    public function show($customerId, string $id)
    {
        $customerAddress = CustomerAddress::find($id);

        if (is_null($customerAddress)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return new CustomerAddressResource($customerAddress);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerAddressStoreUpdateRequest $request, $customerId, string $id)
    {
        $customerAddress = CustomerAddress::find($id);

        if (is_null($customerAddress)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        DB::beginTransaction();
        try {
            $customerAddress->fill([
                'customer_id' => $request->input('customer_address_customer_id'),
                'name' => $request->input('customer_address_name'),
                'phone_number' => Phone::normalize($request->input('customer_address_phone_number')),
                'address' => $request->input('customer_address_address'),
                'province_id' => $request->input('customer_address_province_id'),
                'regency_id' => $request->input('customer_address_regency_id'),
                'district_id' => $request->input('customer_address_district_id'),
                'village_id' => $request->input('customer_address_village_id'),
            ]);

            if (
                $customerAddress->save()
                && $customerAddress->is_default
            ) {
                CustomerAddress::whereCustomerId($customerId)->where('id', '!=', $customerAddress->id)->update([
                    'is_default' => false,
                ]);
            }

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
    public function destroy($customerId, string $id)
    {
        $customerAddress = CustomerAddress::find($id);

        if (is_null($customerAddress)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ], Response::HTTP_NOT_FOUND);
        }

        if ($customerAddress->is_default) {
            return response()->json([
                'message' => 'Tidak bisa hapus karena ini adalah alamat default',
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $customerAddress->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }
}
