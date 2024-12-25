<?php

namespace App\Services;

use App\Models\Area;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

class SchoolService
{
    /**
     * Get top ranking of agen by total amount transaction
     *
     * @param  int  $limit limit to get ranking
     *
     * **/
    public function rankingByArea(
        $areaId,
        $marketing = null,
    ): Collection {
        $area = Area::with([
            'schools' => function ($qSchool) use ($marketing) {
                $qSchool->withSum([
                    'preorders as preorders_total' => function ($qPreorder) use ($marketing) {
                        if ($marketing) {
                            $qPreorder->where('marketing', $marketing);
                        }
                    },
                ], 'total_amount')
                    ->withCount([
                        'preorders' => function ($qPreorder) use ($marketing) {
                            if ($marketing) {
                                $qPreorder->where('marketing', $marketing);
                            }
                        },
                    ])
                    ->orderBy('preorders_total', 'DESC');
            },
        ])->whereId($areaId)->first();

        return $area->schools;
    }

    /**
     * Get top ranking of agen by total amount transaction
     *
     * @param  int  $limit limit to get ranking
     *
     * **/
    public function rankingByCustomer(
        $customerId,
        $marketing = null,
    ): Collection {
        $customer = Customer::with([
            'schools' => function ($qSchool) use ($marketing) {
                $qSchool->withSum([
                    'preorders as preorders_total' => function ($qPreorder) use ($marketing) {
                        if ($marketing) {
                            $qPreorder->where('marketing', $marketing);
                        }
                    },
                ], 'total_amount')
                    ->withCount([
                        'preorders' => function ($qPreorder) use ($marketing) {
                            if ($marketing) {
                                $qPreorder->where('marketing', $marketing);
                            }
                        },
                    ])
                    ->orderBy('preorders_total', 'DESC');
            },
        ])->whereId($customerId)->first();

        return $customer->schools;
    }
}
