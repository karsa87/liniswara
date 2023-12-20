<?php

namespace App\Models;

use App\Models\Scopes\ScopeLike;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends Model
{
    use HasFactory, ScopeLike;

    protected $table = 'customer_address';

    protected $guarded = [];

    /**
     * The province that belong to the province.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    /**
     * The city that belong to the city.
     */
    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    /**
     * The district that belong to the district.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    /**
     * The village that belong to the village.
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'village_id');
    }

    /**
     * The customer that belong to the customer.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Get the full address.
     */
    protected function fullAddress(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $address = $attributes['address'];
                if ($this->village) {
                    $address .= ' Desa '.$this->village->name;
                }

                if ($this->district) {
                    $address .= ', Kec. '.$this->district->name;
                }

                if ($this->regency) {
                    $address .= ', Kota/Kab '.$this->regency->name;
                }

                if ($this->province) {
                    $address .= ' - '.$this->province->name;
                }

                return $address;
            },
        );
    }

    /**
     * Get the full address.
     */
    protected function summaryAddress(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $address = $attributes['name'];

                if ($this->regency) {
                    $address .= ' - '.$this->regency->name;
                }

                if ($this->province) {
                    $address .= ' - '.$this->province->name;
                }

                return $address;
            },
        );
    }
}
