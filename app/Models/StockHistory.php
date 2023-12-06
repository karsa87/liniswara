<?php

namespace App\Models;

use App\Models\Scopes\ScopeLike;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockHistory extends Model
{
    use HasFactory, ScopeLike, SoftDeletes;

    protected $guarded = [];

    protected $table = 'stock_histories';

    /**
     * The data that belong to the file.
     */
    public function from(): BelongsTo
    {
        return $this->morphTo();
    }

    /**
     * The data that belong to the category.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
