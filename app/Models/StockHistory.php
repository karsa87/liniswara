<?php

namespace App\Models;

use App\Models\Scopes\ScopeLike;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockHistory extends Model
{
    use HasFactory, ScopeLike;

    protected $guarded = [];

    protected $connection = 'mysql_log';

    protected $table = 'stock_histories';

    /**
     * The data that belong to the file.
     */
    public function from(): BelongsTo
    {
        return $this->setConnection(config('database.default'))->morphTo();
    }

    /**
     * The data that belong to the category.
     */
    public function product(): BelongsTo
    {
        return $this->setConnection(config('database.default'))->belongsTo(Product::class, 'product_id');
    }

    /**
     * The data that belong to the user.
     */
    public function user(): BelongsTo
    {
        return $this->setConnection(config('database.default'))->belongsTo(User::class, 'user_id');
    }
}
