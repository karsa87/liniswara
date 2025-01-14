<?php

namespace App\Models;

use App\Enums\Preorder\StatusPaymentEnum;
use App\Models\Scopes\ScopeLike;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    /**
     * The user that belong to the preorders.
     */
    public function preorders(): HasMany
    {
        return $this->hasMany(Preorder::class, 'customer_id');
    }

    /**
     * The user that belong to the preorders paid.
     */
    public function paid_preorders(): HasMany
    {
        return $this->hasMany(Preorder::class, 'customer_id')->where('status_payment', StatusPaymentEnum::PAID);
    }

    /**
     * The relation to area.
     */
    public function areas(): BelongsToMany
    {
        return $this->belongsToMany(
            Area::class,
            'customer_area',
            'customer_id',
            'area_id'
        );
    }

    /**
     * The relation to school.
     */
    public function schools(): BelongsToMany
    {
        return $this->belongsToMany(
            School::class,
            'customer_schools',
            'customer_id',
            'school_id'
        )->withPivot('target');
    }
}
