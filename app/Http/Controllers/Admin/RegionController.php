<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Components\Region\ListResource;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // DB::enableQueryLog();
        $qNotLimit = clone $query;
        $villages = $query->offset($request->get('start', 0))->limit($request->get('length', 10))->get();
        // dd(DB::getQueryLog());
        $count = Village::count();
        $countFilter = $qNotLimit->count();

        return ListResource::collection($villages)->additional([
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function province(Request $request)
    {
        return response()->json(
            Province::select('name', 'id')->get()
        );
    }

    public function ajax_list_regency(Request $request)
    {
        $params = request()->all();

        $query = Regency::with([
            'province',
            // 'districts',
        ]);

        $q = array_key_exists('query', $params) ? $params['query'] : (array_key_exists('q', $params) ? $params['q'] : '');
        if ($q) {
            $query->where(function ($qRegency) use ($q) {
                $qRegency->whereLike('name', $q);
                // ->orWhereHas('districts', function ($qDistrict) use ($q) {
                //     $qDistrict->whereLike('name', $q);
                // });
            });
        }

        $regencies = [];
        foreach ($query->limit(50)->get() as $regency) {
            $regencies[] = [
                'id' => $regency->id,
                'text' => $regency->name,
            ];
        }

        return response()->json([
            'items' => $regencies,
            'count' => count($regencies),
        ]);
    }
}
