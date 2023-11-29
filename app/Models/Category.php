<?php

namespace App\Models;

use App\Models\Scopes\ScopeLike;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use HasFactory, ScopeLike;

    protected $guarded = [];

    /**
     * The data that belong to the file.
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(File::class, 'image_id');
    }

    /**
     * The data that belong to the paren category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_category_id');
    }
}
