<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
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
