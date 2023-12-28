<?php

namespace App\Models;

use App\Enums\LogHistoryEnum;
use App\Enums\SourceLogEnum;
use App\Models\Scopes\ScopeLike;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogHistory extends Model
{
    use HasFactory, ScopeLike;

    protected $guarded = [];

    protected $connection = 'mysql_log';

    protected $table = 'log_histories';

    protected $casts = [
        'data_before' => 'array',
        'data_after' => 'array',
        'data_change' => 'array',
    ];

    /**
     * The data that belong to the file.
     */
    public function record(): BelongsTo
    {
        return $this->setConnection(config('database.default'))->morphTo();
    }

    /**
     * The data that belong to the user.
     */
    public function user(): BelongsTo
    {
        return $this->setConnection(config('database.default'))->belongsTo(User::class, 'user_id');
    }

    /*************
    * SCOPE
    ***************/
    public function scopeTransactionType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    /**
     * Get the full address.
     */
    protected function source(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                try {
                    $source = SourceLogEnum::fromValue($attributes['table']);

                    return $source->getLabel();
                } catch (\Throwable $th) {
                    return '';
                }
            },
        );
    }

    /**
     * Get the full address.
     */
    protected function typeLabel(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                try {
                    return LogHistoryEnum::MAP_LABEL[$attributes['transaction_type']];
                } catch (\Throwable $th) {
                    return '';
                }
            },
        );
    }
}
