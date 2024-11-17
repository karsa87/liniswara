<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $table = 'schools';

    protected $guarded = [];

    public function product()
    {
        return $this->belongsToMany(Product::class, 'product_schools', 'school_id', 'product_id');
    }
}
