<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Writer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'writers';

    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_writers', 'writer_id', 'product_id');
    }
}
