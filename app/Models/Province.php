<?php

namespace App\Models;

use App\Models\Scopes\ScopeLike;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    use HasFactory, ScopeLike;

    protected $guarded = [];

    /**
     * The cities that belong to the city.
     */
    public function regencies(): HasMany
    {
        return $this->hasMany(Regency::class, 'province_id');
    }
}
