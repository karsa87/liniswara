<?php

namespace App\Models;

use App\Models\Scopes\ScopeLike;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreorderDetail extends Model
{
    use HasFactory, ScopeLike, SoftDeletes;

    protected $guarded = [];

    /**
     * The data that belong to the file.
     */
    public function preorder(): BelongsTo
    {
        return $this->belongsTo(Preorder::class, 'preorder_id');
    }

    /**
     * The data that belong to the file.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
