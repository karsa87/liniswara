<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Customer\CustomerSchoolStoreUpdateRequest;
use App\Http\Resources\Admin\Customer\CustomerSchoolResource;
use App\Models\Customer;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CustomerSchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $customerId)
    {
        if ($request->ajax()) {
            $query = School::with([
                'customers' => function ($qCustomer) use ($customerId) {
                    $qCustomer->with('user')->where('customer_id', $customerId);
                },
            ])->whereHas('customers', function ($qCustomer) use ($customerId) {
                $qCustomer->where('customer_id', $customerId);
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

            $customerSchools = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return CustomerSchoolResource::collection($customerSchools)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $totalAll,
            ]);
        }

        return view('admin.customer_school.index', [
            'customerId' => $customerId,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerSchoolStoreUpdateRequest $request, $customerId)
    {
        DB::beginTransaction();
        try {
            $customer = Customer::find($customerId);
            if (! $customer) {
                return response()->json([
                    'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                ], Response::HTTP_NOT_FOUND);
            }

            $customer->schools()->detach([$request->input('customer_school_id')]);
            $customer->schools()->syncWithoutDetaching([
                $request->input('customer_school_id') => [
                    'target' => $request->input('customer_school_target'),
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
    public function show($customerId, string $id)
    {
        $school = School::with([
            'customers' => function ($qCustomer) use ($customerId) {
                $qCustomer->with('user')->where('customer_id', $customerId);
            },
        ])->find($id);

        if (is_null($school)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return new CustomerSchoolResource($school);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerSchoolStoreUpdateRequest $request, $customerId, string $id)
    {
        DB::beginTransaction();
        try {
            $customer = Customer::find($customerId);
            if (! $customer) {
                return response()->json([
                    'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
                ], Response::HTTP_NOT_FOUND);
            }

            $customer->schools()->detach([$id]);
            $customer->schools()->syncWithoutDetaching([
                $id => [
                    'target' => $request->input('customer_school_target'),
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
    public function destroy($customerId, string $id)
    {
        $customer = Customer::find($customerId);
        if (! $customer) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $customer->schools()->detach([$id]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }
}
