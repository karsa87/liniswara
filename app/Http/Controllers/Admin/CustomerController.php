<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Template\CustomerImportExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Customer\CustomerStoreUpdateRequest;
use App\Http\Resources\Admin\Customer\CustomerListResource;
use App\Http\Resources\Admin\Customer\CustomerResource;
use App\Jobs\Import\CustomerImportJob;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\File;
use App\Models\Import;
use App\Models\School;
use App\Models\User;
use App\Utils\Phone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with([
                'customer',
                'customer.areas',
                'customer.schools',
                'customer.address.province:id,name',
                'customer.address.regency:id,name',
                'customer.address.district:id,name',
                'customer.address.village:id,name',
            ])
                ->has('customer');

            if ($q = $request->input('search.value')) {
                $query->where(function ($qUser) use ($q) {
                    $qUser->where(function ($qUser1) use ($q) {
                        $qUser1->whereLike('name', $q)
                            ->orWhereLike('company', $q)
                            ->orWhereLike('phone_number', $q)
                            ->orWhereLike('email', $q);
                    });
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

            $customers = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return CustomerListResource::collection($customers)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $totalAll,
            ]);
        }

        $schools = School::all()->pluck('name', 'id');

        return view('admin.customer.index', [
            'schools' => $schools,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerStoreUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $schools = collect($request->get('customer_schools'))->filter();

            $user = new User();
            $user->fill([
                'name' => $request->input('customer_name'),
                'email' => $request->input('customer_email'),
                'company' => $request->input('customer_company'),
                'phone_number' => Phone::normalize($request->input('customer_phone_number')),
                'password' => $request->input('customer_password'),
            ]);

            if ($user->save()) {
                $customer = new Customer();
                $customer->fill([
                    'type' => $request->input('customer_type'),
                    'user_id' => $user->id,
                    'target' => $schools->sum(),
                    'marketing' => $request->input('customer_marketing'),
                ]);

                if (
                    $customer->save()
                    && $request->input('customer_address')
                ) {
                    $address = new CustomerAddress();
                    $address->fill([
                        'customer_id' => $customer->id,
                        'name' => $user->name,
                        'address' => $request->input('customer_address'),
                        'province_id' => $request->input('customer_province_id'),
                        'regency_id' => $request->input('customer_regency_id'),
                        'district_id' => $request->input('customer_district_id'),
                        'village_id' => $request->input('customer_village_id'),
                        'is_default' => true,
                    ]);
                    $address->save();

                    $areas = $request->input('customer_area_id');
                    if ($areas) {
                        $customer->areas()->sync(array_unique($areas));
                    }

                    $schools = $schools->map(function ($target) {
                        return [
                            'target' => $target,
                        ];
                    });
                    if ($schools) {
                        $customer->schools()->sync($schools);
                    }
                }
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
    public function show(string $id)
    {
        $customer = Customer::with([
            'areas',
            'schools',
            'address.province:id,name',
            'address.regency:id,name',
            'address.district:id,name',
            'address.village:id,name',
        ])->find($id);

        if (is_null($customer)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return new CustomerResource($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerStoreUpdateRequest $request, string $id)
    {
        $customer = Customer::find($id);

        if (is_null($customer)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        DB::beginTransaction();
        try {
            $schools = collect($request->get('customer_schools'))->filter();

            $user = $customer->user;
            $user->fill([
                'name' => $request->input('customer_name'),
                'email' => $request->input('customer_email'),
                'company' => $request->input('customer_company'),
                'phone_number' => Phone::normalize($request->input('customer_phone_number')),
            ]);

            if (! is_null($request->input('customer_password'))) {
                $user->password = $request->input('customer_password');
            }

            if ($user->save()) {
                $customer->fill([
                    'type' => $request->input('customer_type'),
                    'user_id' => $user->id,
                    'target' => $schools->sum(),
                    'marketing' => $request->input('customer_marketing'),
                ]);

                if (
                    $customer->save()
                ) {
                    if ($request->input('customer_address')) {
                        $customer->loadMissing('address');
                        $address = $customer->address;
                        if (is_null($address)) {
                            $address = new CustomerAddress();
                            $address->name = $user->name;
                        }

                        $address->fill([
                            'customer_id' => $customer->id,
                            'address' => $request->input('customer_address'),
                            'province_id' => $request->input('customer_province_id'),
                            'regency_id' => $request->input('customer_regency_id'),
                            'district_id' => $request->input('customer_district_id'),
                            'village_id' => $request->input('customer_village_id'),
                            'is_default' => true,
                        ]);
                        $address->save();
                    }

                    $areas = $request->input('customer_area_id');
                    if ($areas) {
                        $customer->areas()->sync(array_unique($areas));
                    }

                    $schools = $schools->map(function ($target) {
                        return [
                            'target' => $target,
                        ];
                    });
                    if ($schools) {
                        $customer->schools()->sync($schools);
                    }
                }
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
    public function destroy(string $id)
    {
        $customer = Customer::find($id);

        if (is_null($customer)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            if ($customer->user->delete()) {
                $customer->delete();
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }

    public function ajax_list_customer(Request $request)
    {
        $params = $request->all();
        $query = Customer::with(
            [
                'user:id,name',
                'addresses' => function ($qAddress) {
                    $qAddress->orderBy('is_default', 'ASC');
                },
                'addresses.province:id,name',
                'addresses.regency:id,name',
                'addresses.district:id,name',
                'addresses.village:id,name',
            ]
        );

        $q = array_key_exists('query', $params) ? $params['query'] : (array_key_exists('q', $params) ? $params['q'] : '');
        if ($q) {
            $query->whereHas('user', function ($qUser) use ($q) {
                $qUser->whereLike('name', $q);
            });
        }

        $customers = $query->limit(20)->get();
        $list = [];
        foreach ($customers as $customer) {
            $addresses = [];
            foreach ($customer->addresses as $address) {
                $addresses[] = [
                    'id' => $address->id,
                    'text' => $address->summary_address,
                ];
            }
            $areas = [];
            foreach ($customer->areas as $area) {
                $areas[] = [
                    'id' => $area->id,
                    'text' => $area->name,
                ];
            }

            $list[] = [
                'id' => $customer->id,
                'text' => optional($customer->user)->name,
                'marketing' => $customer->marketing,
                'addresses' => $addresses,
                'areas' => $areas,
            ];
        }

        return response()->json([
            'items' => $list,
            'count' => count($list),
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function export_template_import()
    {
        return Excel::download(new CustomerImportExport(), 'Template Import Pelanggan.xlsx');
    }

    /**
     * Display a listing of the resource.
     */
    public function import(Request $request)
    {
        $request->validate([
            'customer_file' => [
                'required',
                'numeric',
                Rule::exists((new File())->getTable(), 'id'),
            ],
        ]);

        $now = Carbon::now();
        $import = Import::create([
            'name' => sprintf(
                'Pelanggan - %s%s%s',
                $now->format('d'),
                $now->format('m'),
                $now->format('y'),
            ),
            'file_id' => $request->customer_file,
            'user_id' => auth()->user()->id,
        ]);

        CustomerImportJob::dispatch($import);
    }
}
