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

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_schools', 'school_id', 'customer_id')->withPivot('target');
    }

    public function preorders()
    {
        return $this->hasMany(Preorder::class, 'school_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'school_id');
    }
}
