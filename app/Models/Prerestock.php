<?php

namespace App\Models;

use App\Models\Scopes\LogCreateUpdateTrait;
use App\Models\Scopes\ScopeLike;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prerestock extends Model
{
    use HasFactory, LogCreateUpdateTrait, ScopeLike, SoftDeletes;

    protected $guarded = [];

    public function restocks(): HasMany
    {
        return $this->hasMany(Restock::class, 'prerestock_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(PrerestockDetail::class, 'prerestock_id');
    }
}
