<?php

namespace App\Models;

use App\Models\Scopes\ScopeLike;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Regency extends Model
{
    use HasFactory, ScopeLike;

    protected $guarded = [];

    /**
     * The data that belong to the province.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    /**
     * The districts that belong to the district.
     */
    public function districts(): HasMany
    {
        return $this->hasMany(District::class, 'city_id');
    }

    /**
     * The preorder that belong to the preorder.
     */
    public function preorders(): HasManyThrough
    {
        return $this->hasManyThrough(
            Preorder::class,
            CustomerAddress::class,
            'regency_id',
            'customer_address_id',
            'id',
            'id'
        );
    }
}
