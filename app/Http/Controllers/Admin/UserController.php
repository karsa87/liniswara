<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserStoreUpdateRequest;
use App\Http\Resources\Admin\User\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Utils\Phone;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with('role:id,name')
                ->offset($request->get('start', 0))
                ->limit($request->get('length', 10));

            if ($q = $request->input('search.value')) {
                $query->where(function ($qUser) use ($q) {
                    $qUser->whereLike('name', $q);
                });
            }

            if ($roleId = $request->input('search.role_id')) {
                $query->whereHas('roles', function ($qRole) use ($roleId) {
                    $qRole->whereId($roleId);
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

            $users = $query->get();

            $totalAll = User::count();

            return UserResource::collection($users)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $totalAll,
            ]);
        }

        $roles = Role::select('id', 'name', 'description')->orderBy('name', 'asc')->get();

        return view('admin.user.index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = new User();
            $user->fill([
                'name' => $request->input('user_name'),
                'email' => $request->input('user_email'),
                'company' => $request->input('user_company'),
                'phone_number' => Phone::normalize($request->input('user_phone_number')),
                'password' => $request->input('user_password'),
                'can_access_marketing' => $request->input('user_can_access_marketing') ?: false,
            ]);

            if (
                $user->save()
                && $request->get('user_role_id')
            ) {
                $user->roles()->sync([
                    $request->get('user_role_id'),
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
    public function show(string $id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserStoreUpdateRequest $request, string $id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        DB::beginTransaction();
        try {
            $user->fill([
                'name' => $request->input('user_name'),
                'email' => $request->input('user_email'),
                'company' => $request->input('user_company'),
                'phone_number' => Phone::normalize($request->input('user_phone_number')),
                'can_access_marketing' => $request->input('user_can_access_marketing') ?: false,
            ]);

            if (! is_null($request->input('user_password'))) {
                $user->password = $request->input('user_password');
            }

            if (
                $user->save()
                && $request->get('user_role_id')
            ) {
                $user->roles()->sync([
                    $request->get('user_role_id'),
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
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            $user->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }
}
