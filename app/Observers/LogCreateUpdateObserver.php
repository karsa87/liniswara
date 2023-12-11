<?php

namespace App\Observers;

class LogCreateUpdateObserver
{
    /**
     * Handle the Models "created" event.
     * set created_by with ID from user login
     *
     * @param  App\Models  $object
     * @return void
     */
    public function creating($object)
    {
        if ($user = auth()->user()) {
            $object->created_by = $user->id;
            $object->updated_by = null;
        }
    }

    /**
     * Handle the Models "updated" event.
     * set updated_by with ID from user login
     *
     * @param  App\Models  $object
     * @return void
     */
    public function updating($object)
    {
        if ($user = auth()->user()) {
            $object->updated_by = $user->id;
        }
    }
}
