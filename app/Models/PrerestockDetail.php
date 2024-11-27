<?php

namespace App\Models;

use App\Enums\PrerestockTypeEnum;
use App\Models\Scopes\ScopeLike;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrerestockDetail extends Model
{
    use HasFactory, ScopeLike, SoftDeletes;

    protected $guarded = [];

    public function prerestock(): BelongsTo
    {
        return $this->belongsTo(Prerestock::class, 'prerestock_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function stockHistory(): MorphOne
    {
        return $this->morphOne(StockHistory::class, 'from')->latestOfMany();
    }

    public function scopeStockAdd(Builder $query)
    {
        return $query->where('type', PrerestockTypeEnum::STOCK_ADD);
    }

    public function scopeStockMinus(Builder $query)
    {
        return $query->where('type', PrerestockTypeEnum::STOCK_MINUS);
    }

    public function restockDetail(): HasMany
    {
        return $this->hasMany(RestockDetail::class, 'prerestock_detail_id');
    }

    /**
     * Check the record type is stock add.
     */
    protected function isStockAdd(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => $attributes['type'] == PrerestockTypeEnum::STOCK_ADD
        );
    }

    /**
     * Check the record type is stock add.
     */
    protected function isStockMinus(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => $attributes['type'] == PrerestockTypeEnum::STOCK_MINUS
        );
    }
}
