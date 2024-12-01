<?php

namespace App\Models;

use App\Models\Scopes\ScopeLike;
use App\Models\Traits\LogHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnOrderDetail extends Model
{
    use HasFactory, LogHistoryTrait, ScopeLike, SoftDeletes;

    protected $guarded = [];

    /**
     * The data that belong to the file.
     */
    public function order_detail(): BelongsTo
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id');
    }

    /**
     * The data that belong to the file.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
