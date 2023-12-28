<?php

namespace App\Models;

use App\Models\Scopes\LogCreateUpdateTrait;
use App\Models\Scopes\ScopeLike;
use App\Models\Traits\LogHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restock extends Model
{
    use HasFactory, LogCreateUpdateTrait, LogHistoryTrait, ScopeLike, SoftDeletes;

    protected $guarded = [];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(RestockDetail::class, 'restock_id');
    }
}
