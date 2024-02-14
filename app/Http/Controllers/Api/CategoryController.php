<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Category\ListRequest;
use App\Http\Resources\Api\Category\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListRequest $request)
    {
        $query = Category::with([
            'image',
            'parent',
        ]);

        if ($q = $request->input('q')) {
            $query->where(function ($qCategory) use ($q) {
                $qCategory->whereLike('name', $q);
            });
        }

        $categorys = $query->get();

        return CategoryResource::collection($categorys);
    }
}
