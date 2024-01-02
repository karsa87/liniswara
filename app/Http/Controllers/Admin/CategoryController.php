<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\CategoryStoreUpdateRequest;
use App\Http\Resources\Admin\Category\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Category::with([
                'image',
                'parent',
            ]);

            if ($q = $request->input('search.value')) {
                $query->where(function ($qCategory) use ($q) {
                    $qCategory->whereLike('name', $q);
                });
            }

            $totalAll = (clone $query)->count();

            $categorys = $query->offset($request->get('start', 0))
                ->limit($request->get('length', 10))
                ->get();

            return CategoryResource::collection($categorys)->additional([
                'recordsTotal' => $totalAll,
                'recordsFiltered' => $totalAll,
            ]);
        }

        return view(
            'admin.category.index',
            [
                'categories' => Category::select('id', 'name')->whereNull('parent_category_id')->get()->pluck('name', 'id'),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreUpdateRequest $request)
    {
        DB::beginTransaction();
        try {
            $category = new Category();
            $category->fill([
                'name' => $request->input('category_name'),
                'slug' => $request->input('slug'),
                'image_id' => $request->input('category_image_id'),
                'parent_category_id' => $request->input('category_parent_id'),
            ]);

            $category->save();

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
        $category = Category::with([
            'image',
            'parent',
        ])->find($id);

        if (is_null($category)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryStoreUpdateRequest $request, string $id)
    {
        $category = Category::find($id);

        if (is_null($category)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        DB::beginTransaction();
        try {
            $category->fill([
                'name' => $request->input('category_name'),
                'slug' => $request->input('slug'),
                'image_id' => $request->input('category_image_id'),
                'parent_category_id' => $request->input('category_parent_id'),
            ]);

            $category->save();

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
        $category = Category::with('image')->find($id);

        if (is_null($category)) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_NOT_FOUND],
            ]);
        }

        try {
            if ($category->image) {
                $category->image->delete();
            }
            $category->delete();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'exception_message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json();
    }

    public function ajax_list_category(Request $request)
    {
        $params = request()->all();

        $query = Category::select('id', 'name as text');

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
