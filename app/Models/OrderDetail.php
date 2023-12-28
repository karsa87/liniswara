<?php

namespace App\Models;

use App\Models\Scopes\ScopeLike;
use App\Models\Traits\LogHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use HasFactory, LogHistoryTrait, ScopeLike, SoftDeletes;

    protected $guarded = [];

    /**
     * The data that belong to the file.
     */
    public function preorder_detail(): BelongsTo
    {
        return $this->belongsTo(PreorderDetail::class, 'preorder_detail_id');
    }

    /**
     * The data that belong to the file.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * The data that belong to the file.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
