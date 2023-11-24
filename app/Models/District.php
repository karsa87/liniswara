<?php

namespace App\Models;

use App\Models\Scopes\ScopeLike;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory, ScopeLike;

    protected $guarded = [];

    /**
     * The data that belong to the city.
     */
    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    /**
     * The vilages that belong to the village.
     */
    public function villages(): HasMany
    {
        return $this->hasMany(Village::class, 'district_id');
    }
}
