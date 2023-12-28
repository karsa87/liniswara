<?php

namespace App\Models\Traits;

use App\Models\LogHistory;
use App\Observers\LogHistoryObserver;

trait LogHistoryTrait
{
    private $skip_log = false;

    public static function bootLogHistoryTrait()
    {
        static::observe(new LogHistoryObserver);
    }

    public function defaultName()
    {
        $name = $this->name;
        $name = $name ?: $this->no_transaction;
        $name = $name ?: sprintf('%s with id: %s', $this->getTable(), $this->id);

        return $name;
    }

    public function skipLog()
    {
        $this->skip_log = true;

        return $this;
    }

    public function getSkipLog()
    {
        return $this->skip_log;
    }

    /**
     * Get all of the owning commentable models.
     */
    public function logHistory()
    {
        return $this->setConnection('mysql_log')->morphMany(LogHistory::class, 'record');
    }
}
