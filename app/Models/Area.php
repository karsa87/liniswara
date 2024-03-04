<?php

namespace App\Models;

use App\Models\Scopes\ScopeLike;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use ScopeLike, SoftDeletes;

    protected $guarded = [];

    public function preorders()
    {
        return $this->hasMany(Preorder::class, 'area_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'area_id');
    }
}
