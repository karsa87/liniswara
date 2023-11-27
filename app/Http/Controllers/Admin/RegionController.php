<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Components\Region\ListResource;
use App\Models\Province;
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
            'recordsFiltered' => $countFilter,
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
}
