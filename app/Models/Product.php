<?php

namespace App\Models;

use App\Enums\ProductDiscountTypeEnum;
use App\Models\Scopes\ScopeLike;
use App\Utils\Util;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, ScopeLike, SoftDeletes;

    protected $guarded = [];

    /**
     * The data that belong to the file.
     */
    public function thumbnail(): BelongsTo
    {
        return $this->belongsTo(File::class, 'thumbnail_id');
    }

    /**
     * The data that belong to the category.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'product_categories',
            'product_id',
            'category_id',
        );
    }

    /**
     * The data that belong to the stock history.
     */
    public function stock_histories(): HasMany
    {
        return $this->hasMany(StockHistory::class, 'product_id');
    }

    /**
     * Get the price after discount.
     */
    protected function priceAfterDiscount(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $price = $attributes['price'];
                if ($attributes['discount_type'] == ProductDiscountTypeEnum::DISCOUNT_PERCENTAGE) {
                    $discountPrice = $price * ($attributes['discount_percentage'] / 100);
                    $price -= $discountPrice;
                }

                if ($attributes['discount_type'] == ProductDiscountTypeEnum::DISCOUNT_PRICE) {
                    $price -= $attributes['discount_price'];
                }

                return $price;
            },
        );
    }

    /**
     * Get the discount.
     */
    protected function discount(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $discountPrice = 0;
                if ($attributes['discount_type'] == ProductDiscountTypeEnum::DISCOUNT_PERCENTAGE) {
                    $discountPrice = $attributes['price'] * ($attributes['discount_percentage'] / 100);
                }

                if ($attributes['discount_type'] == ProductDiscountTypeEnum::DISCOUNT_PRICE) {
                    $discountPrice = $attributes['discount_price'];
                }

                return $discountPrice;
            },
        );
    }

    /**
     * Get the discount description.
     */
    protected function discountDescription(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $discountDescription = '';
                if ($attributes['discount_type'] == ProductDiscountTypeEnum::DISCOUNT_PERCENTAGE) {
                    $discountDescription = sprintf('Diskon %.0f%%', $attributes['discount_percentage']);
                }

                if ($attributes['discount_type'] == ProductDiscountTypeEnum::DISCOUNT_PRICE) {
                    $discountDescription = sprintf('Diskon Rp. %s', Util::format_currency($attributes['discount_price']));
                }

                return $discountDescription;
            },
        );
    }

    /**
     * Get the discount.
     */
    protected function discountZone2(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $discountPrice = 0;
                if ($attributes['discount_type'] == ProductDiscountTypeEnum::DISCOUNT_PERCENTAGE) {
                    $discountPrice = $attributes['price_zone_2'] * ($attributes['discount_percentage'] / 100);
                }

                if ($attributes['discount_type'] == ProductDiscountTypeEnum::DISCOUNT_PRICE) {
                    $discountPrice = $attributes['discount_price'];
                }

                return $discountPrice;
            },
        );
    }
}
