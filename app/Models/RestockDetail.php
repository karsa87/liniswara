<?php

namespace App\Models;

use App\Enums\RestockTypeEnum;
use App\Models\Scopes\ScopeLike;
use App\Models\Traits\LogHistoryTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestockDetail extends Model
{
    use HasFactory, LogHistoryTrait, ScopeLike, SoftDeletes;

    protected $guarded = [];

    public function restock(): BelongsTo
    {
        return $this->belongsTo(Restock::class, 'restock_id');
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
        return $query->where('type', RestockTypeEnum::STOCK_ADD);
    }

    public function scopeStockMinus(Builder $query)
    {
        return $query->where('type', RestockTypeEnum::STOCK_MINUS);
    }

    /**
     * Check the record type is stock add.
     */
    protected function isStockAdd(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => $attributes['type'] == RestockTypeEnum::STOCK_ADD
        );
    }

    /**
     * Check the record type is stock add.
     */
    protected function isStockMinus(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => $attributes['type'] == RestockTypeEnum::STOCK_MINUS
        );
    }
}
