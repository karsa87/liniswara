<?php

namespace App\Models;

use App\Models\Scopes\LogCreateUpdateTrait;
use App\Models\Scopes\ScopeLike;
use App\Models\Traits\LogHistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, LogCreateUpdateTrait, LogHistoryTrait, ScopeLike, SoftDeletes;

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
    public function shipping(): HasOne
    {
        return $this->hasOne(OrderShipping::class, 'order_id');
    }

    /**
     * The data that belong to the file.
     */
    public function collector(): BelongsTo
    {
        return $this->belongsTo(Collector::class, 'collector_id');
    }

    /**
     * The data that belong to the file.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    /**
     * The data that belong to the file.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id')->withTrashed();
    }

    /**
     * The data that belong to the file.
     */
    public function customer_address(): BelongsTo
    {
        return $this->belongsTo(CustomerAddress::class, 'customer_address_id');
    }

    /**
     * The data that belong to the file.
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    /**
     * The data that belong to the file.
     */
    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public static function nextNoInvoice($preorderId = null)
    {
        try {
            $next_code = 'No Invoice Preorder';
            $preorder = Preorder::find($preorderId);

            $totalRecord = self::where('preorder_id', $preorderId)->count();
            if (! is_null($preorder)) {

                return sprintf(
                    '%s (%s)',
                    $preorder->invoice_number,
                    ($totalRecord + 1)
                );
            }
        } catch (\Throwable $th) {
        }

        return $next_code;
    }
}
