<?php

namespace App\Models;

use App\Models\Scopes\ScopeLike;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Village extends Model
{
    use HasFactory, ScopeLike;

    protected $guarded = [];

    /**
     * The data that belong to the district.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
