<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if (
            $user->password
            && $user->password != $user->getOriginal('password')
        ) {
            $user->password = \Hash::make($user->password);
        } else {
            unset($user->password);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if (
            $user->password
            && $user->password != $user->getOriginal('password')
        ) {
            $user->password = \Hash::make($user->password);
        } else {
            unset($user->password);
        }
    }
}
