<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\School\SchoolStoreUpdateRequest;
use App\Http\Resources\Admin\School\SchoolResource;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = School::query();

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

            $total = (clone $query)->count();
            $schools = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return SchoolResource::collection($schools)->additional([
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
            ]);
        }

        return view('admin.school.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SchoolStoreUpdateRequest $request)
    {
        try {
            $school = new School();
            $school->fill([
                'name' => $request->validated('school_name'),
            ]);
            $school->save();
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
    public function update(SchoolStoreUpdateRequest $request, string $id)
    {
        $school = School::find($id);

        if (is_null($school)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            $school->fill([
                'name' => $request->validated('school_name'),
            ]);
            $school->save();
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
        $school = School::find($id);

        if (is_null($school)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            $school->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }

    public function ajax_list_school(Request $request)
    {
        $params = request()->all();

        $query = School::query();

        $q = array_key_exists('query', $params) ? $params['query'] : (array_key_exists('q', $params) ? $params['q'] : '');
        if ($q) {
            $query->whereLike('name', $q);
        }

        $list = [];
        foreach ($query->limit(20)->get() as $school) {
            $list[] = [
                'id' => $school->id,
                'text' => $school->name,
            ];
        }

        return response()->json([
            'items' => $list,
            'count' => count($list),
        ]);
    }
}
