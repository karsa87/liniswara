<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * Get the full url.
     */
    protected function fullUrl(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                if ($attributes['url']) {
                    return $attributes['url'];
                }

                if ($attributes['path']) {
                    return Storage::url($attributes['path']);
                }

                return null;
            },
        );
    }
}
