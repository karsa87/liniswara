<?php

namespace App\Models;

use App\Models\Scopes\ScopeLike;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use ScopeLike, SoftDeletes;

    protected $guarded = [];

    public function preorders()
    {
        return $this->hasMany(Preorder::class, 'area_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'area_id');
    }

    /**
     * The province that belong to the province.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    /**
     * The city that belong to the city.
     */
    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    /**
     * The district that belong to the district.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    /**
     * The village that belong to the village.
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'village_id');
    }
}
