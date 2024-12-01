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

class ReturnOrder extends Model
{
    use HasFactory, LogCreateUpdateTrait, LogHistoryTrait, ScopeLike, SoftDeletes;

    protected $guarded = [];

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
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    /**
     * The data that belong to the file.
     */
    public function details(): HasMany
    {
        return $this->hasMany(ReturnOrderDetail::class, 'return_order_id');
    }

    public static function nextNoReturn($orderId = null)
    {
        try {
            $next_code = 'No Invoice Order';
            $order = Order::find($orderId);

            if (! is_null($order)) {
                $totalRecord = self::where('order_id', $orderId)->count();

                $invoiceNumber = str($order->invoice_number)->explode(' ')[0];

                return sprintf(
                    '%s (%s)',
                    $invoiceNumber,
                    ($totalRecord + 1)
                );
            }
        } catch (\Throwable $th) {

        }

        return $next_code;
    }
}
