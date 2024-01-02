<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permission\PermissionStoreUpdateRequest;
use App\Http\Resources\Admin\Permission\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Permission::with('roles:id,name');

            if ($q = $request->input('search.value')) {
                $query->where(function ($qPermission) use ($q) {
                    $qPermission->whereLike('name', $q);
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
            $permissions = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return PermissionResource::collection($permissions)->additional([
                'recordsTotal' => $total,
                'recordsFiltered' => $permissions->count(),
            ]);
        }

        return view('admin.permission.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionStoreUpdateRequest $request)
    {
        try {
            $permission = new Permission();
            $permission->fill([
                'name' => $request->validated('permission_name'),
                'key' => $request->validated('key'),
            ]);
            $permission->save();
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
    public function update(PermissionStoreUpdateRequest $request, string $id)
    {
        $permission = Permission::find($id);

        if (is_null($permission)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            $permission->fill([
                'name' => $request->validated('permission_name'),
                'key' => $request->validated('key'),
            ]);
            $permission->save();
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
        $permission = Permission::find($id);

        if (is_null($permission)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            $permission->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }
}
