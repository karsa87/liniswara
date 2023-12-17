<?php

namespace App\Models;

use App\Models\Scopes\LogCreateUpdateTrait;
use App\Models\Scopes\ScopeLike;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Preorder extends Model
{
    use HasFactory, LogCreateUpdateTrait, ScopeLike, SoftDeletes;

    protected $guarded = [];

    /**
     * The data that belong to the file.
     */
    public function shipping(): HasOne
    {
        return $this->hasOne(PreorderShipping::class, 'preorder_id');
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
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * The data that belong to the file.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
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
    public function details(): HasMany
    {
        return $this->hasMany(PreorderDetail::class, 'preorder_id');
    }

    public static function nextNoInvoice()
    {
        try {
            $record = self::orderBy('id', 'DESC')->first();
            if ($record) {
                preg_match('/[0-9]*$/', $record->invoice_number, $last_no);

                // reset every year
                if ($record->created_at->format('Y') !== date('Y')) {
                    $last_no = [0];
                }

                $last_no = count($last_no) > 0 ? $last_no[0] : 0;

                $next_code = $record->trashed() ? $last_no : (((int) $last_no) + 1);
            } else {
                $next_code = 1;
            }
        } catch (\Throwable $th) {
            $next_code = 1;
        }

        $now = Carbon::now();

        return sprintf(
            'INV/%02d%02d%s/%04d',
            $now->format('d'),
            $now->format('m'),
            $now->format('y'),
            $next_code
        );
    }
}
