<?php

namespace App\Models;

use App\Models\Scopes\ScopeLike;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, ScopeLike, SoftDeletes;

    protected $guarded = [];

    protected $with = [
        'user',
        'address.province:id,name',
        'address.regency:id,name',
        'address.district:id,name',
        'address.village:id,name',
    ];

    /**
     * The addresses that has many of the address.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class, 'customer_id');
    }

    /**
     * The relation to addres to get default address.
     */
    public function address(): HasOne
    {
        return $this->hasOne(CustomerAddress::class, 'customer_id')->where('is_default', true)->orderBy('id', 'DESC')->limit(1);
    }

    /**
     * The user that belong to the user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
