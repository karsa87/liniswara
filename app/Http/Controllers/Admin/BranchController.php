<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Branch\BranchStoreUpdateRequest;
use App\Http\Resources\Admin\Branch\BranchResource;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Branch::query();

            if ($q = $request->input('search.value')) {
                $query->where(function ($qBranch) use ($q) {
                    $qBranch->whereLike('name', $q);
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
            $branchs = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return BranchResource::collection($branchs)->additional([
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
            ]);
        }

        return view('admin.branch.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BranchStoreUpdateRequest $request)
    {
        try {
            $branch = new Branch();
            $branch->fill([
                'name' => $request->validated('branch_name'),
            ]);
            $branch->save();
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
    public function update(BranchStoreUpdateRequest $request, string $id)
    {
        $branch = Branch::find($id);

        if (is_null($branch)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            $branch->fill([
                'name' => $request->validated('branch_name'),
            ]);
            $branch->save();
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
        $branch = Branch::find($id);

        if (is_null($branch)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            $branch->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }

    public function ajax_list_branch(Request $request)
    {
        $params = request()->all();

        $query = Branch::select('id', 'name as text');

        $q = array_key_exists('query', $params) ? $params['query'] : (array_key_exists('q', $params) ? $params['q'] : '');
        if ($q) {
            $query->whereLike('name', $q);
        }

        $list = $query->limit(20)->get()->toArray();

        return response()->json([
            'items' => $list,
            'count' => count($list),
        ]);
    }
}
