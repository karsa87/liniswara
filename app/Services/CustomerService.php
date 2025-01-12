<?php

namespace App\Services;

use App\Models\Area;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

class CustomerService
{
    /**
     * Get top ranking of agen by total amount transaction
     *
     * @param  int  $limit limit to get ranking
     *
     * **/
    public function rankingAgents(
        $limit = null,
        $marketing = null,
    ): Collection {
        $query = Customer::with([
            'user:id,name,email,phone_number,profile_photo_id',
            'user.profile_photo',
        ])
            ->withCount('preorders')
            ->withSum([
                'preorders' => function ($qOrder) use ($marketing) {
                    if ($marketing) {
                        $qOrder->where('marketing', $marketing);
                    }
                },
            ], 'total_amount')
            ->havingRaw('preorders_sum_total_amount > 0')
            ->orderBy('preorders_sum_total_amount', 'DESC');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Get top ranking of agen by total amount transaction
     *
     * @param  int  $limit limit to get ranking
     *
     * **/
    public function rankingSpecificAgent(
        $id,
        $marketing = null,
        $params = []
    ): Collection {
        $area = Area::with([
            'customer.user:id,name',
            'customer.address:id,customer_id,name',
            'customer' => function ($qCustomer) use ($marketing, $params) {
                $qCustomer->withSum([
                    'preorders as preorders_total' => function ($qPreorder) use ($marketing, $params) {
                        if ($marketing) {
                            $qPreorder->where('marketing', $marketing);
                        }

                        if (array_key_exists('school_id', $params)) {
                            $qPreorder->where('school_id', $params['school_id']);
                        }
                    },
                ], 'total_amount')
                    ->withCount([
                        'preorders' => function ($qPreorder) use ($marketing, $params) {
                            if ($marketing) {
                                $qPreorder->where('marketing', $marketing);
                            }

                            if (array_key_exists('school_id', $params)) {
                                $qPreorder->where('school_id', $params['school_id']);
                            }
                        },
                    ])
                    ->orderBy('preorders_total', 'DESC');
            },
        ])->whereId($id)->first();

        return $area->customer;
    }
}
