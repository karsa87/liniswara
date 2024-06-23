<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Components\Region\ListResource;
use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $query = Village::with('district.regency.province');

        if ($q = $request->input('search.value')) {
            $query->where(function ($qSupplier) use ($q) {
                $qSupplier->whereLike('name', $q)
                    ->orWhereHas('district', function ($qDistrict) use ($q) {
                        $qDistrict->whereLike('name', $q);
                    });
                // ->orWhereHas('district.regency', function ($qRegency) use ($q) {
                //     $qRegency->whereLike('name', $q);
                // })->orWhereHas('district.regency.province', function ($qProvince) use ($q) {
                //     $qProvince->whereLike('name', $q);
                // });
            });
        }

        $qNotLimit = clone $query;
        $villages = $query->offset($request->get('start', 0))->limit($request->get('length', 10))->get();
        $count = Village::count();
        $countFilter = $qNotLimit->count();

        return ListResource::collection($villages)->additional([
            'recordsTotal' => $count,
            'recordsFiltered' => $countFilter,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function province(Request $request)
    {
        $query = Province::select('id', 'name as text')->orderBy('name');

        $q = $request->get('query') ? $request->get('query') : ($request->get('q') ? $request->get('q') : '');
        if ($q) {
            $query->whereLike('name', $q);
        }

        $lists = $query->limit(20)->get()->toArray();

        return response()->json([
            'items' => $lists,
            'count' => count($lists),
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function regency(Request $request)
    {
        $query = Regency::select('id', 'name as text')->orderBy('name');

        $q = $request->get('query') ? $request->get('query') : ($request->get('q') ? $request->get('q') : '');
        if ($q) {
            $query->whereLike('name', $q);
        }

        if ($request->get('province_id')) {
            $query->where('province_id', $request->get('province_id'));
        }

        $lists = $query->limit(20)->get()->toArray();

        return response()->json([
            'items' => $lists,
            'count' => count($lists),
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function district(Request $request)
    {
        $query = District::select('id', 'name as text')->orderBy('name');

        $q = $request->get('query') ? $request->get('query') : ($request->get('q') ? $request->get('q') : '');
        if ($q) {
            $query->whereLike('name', $q);
        }

        if ($request->get('regency_id')) {
            $query->where('regency_id', $request->get('regency_id'));
        }

        $lists = $query->limit(20)->get()->toArray();

        return response()->json([
            'items' => $lists,
            'count' => count($lists),
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function village(Request $request)
    {
        $query = Village::select('id', 'name as text')->orderBy('name');

        $q = $request->get('query') ? $request->get('query') : ($request->get('q') ? $request->get('q') : '');
        if ($q) {
            $query->whereLike('name', $q);
        }

        if ($request->get('district_id')) {
            $query->where('district_id', $request->get('district_id'));
        }

        $lists = $query->limit(20)->get()->toArray();

        return response()->json([
            'items' => $lists,
            'count' => count($lists),
        ]);
    }
}
