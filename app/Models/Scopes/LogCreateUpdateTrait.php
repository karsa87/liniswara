<?php

namespace App\Models\Scopes;

use App\Models\User;
use App\Observers\LogCreateUpdateObserver;

trait LogCreateUpdateTrait
{
    public static function bootLogCreateUpdateTrait()
    {
        static::observe(new LogCreateUpdateObserver);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }
}
