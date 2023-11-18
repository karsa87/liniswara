<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\RoleStoreUpdateRequest;
use App\Http\Resources\Admin\Role\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::with([
            'permissions:id,name',
            'users:id,name,email,created_at',
        ])->get();

        $permissions = Permission::all();

        return view('admin.role.index', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleStoreUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $role = new Role();
            $role->fill([
                'name' => $request->validated('role_name'),
                'slug' => $request->validated('slug'),
                'description' => $request->validated('role_description'),
            ]);

            if (
                $role->save()
                && $request->get('permissions')
            ) {
                $permissions = Permission::select('id', 'key')->whereIn('key', array_keys($request->get('permissions')))->get();
                $role->permissions()->sync($permissions->pluck('id')->toArray());
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
        $role = Role::find($id);

        if (is_null($role)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return new RoleResource($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleStoreUpdateRequest $request, string $id)
    {
        $role = Role::find($id);

        if (is_null($role)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        DB::beginTransaction();
        try {
            $role->fill([
                'name' => $request->validated('role_name'),
                'slug' => $request->validated('slug'),
                'description' => $request->validated('role_description'),
            ]);

            if (
                $role->save()
                && $request->get('permissions')
            ) {
                $permissions = Permission::select('id', 'key')->whereIn('key', array_keys($request->get('permissions')))->get();
                $role->permissions()->sync($permissions->pluck('id')->toArray());
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
        $role = Role::find($id);

        if (is_null($role)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            $role->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }
}
