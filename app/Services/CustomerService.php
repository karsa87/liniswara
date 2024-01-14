<?php

namespace App\Services;

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
        $limit = null
    ): Collection {
        $query = Customer::with([
            'user:id,name,email,phone_number,profile_photo_id',
            'user.profile_photo',
        ])
            ->withCount('preorders')
            ->withSum('preorders', 'total_amount')
            ->havingRaw('preorders_sum_total_amount > 0')
            ->orderBy('preorders_sum_total_amount', 'DESC');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }
}
