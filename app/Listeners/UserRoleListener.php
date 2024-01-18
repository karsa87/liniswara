<?php

namespace App\Listeners;

use App\Enums\Preorder\MarketingEnum;
use App\Events\UserLogin;
use App\Models\Permission;
use App\Models\Role;

class UserRoleListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserLogin $event): void
    {
        $user = $event->user;

        $roles_id = $user->roles->pluck('id')->toArray();
        //USER MENU
        $permissions = [];
        if ($user->isDeveloper()) {
            $permissions = Permission::all();
        } else {
            $role = Role::with('permissions')->whereIn('id', $roles_id)->get();
            $permissions = $role->pluck('permissions')->collapse()->unique('id');
        }

        \Artisan::call('view:clear');
        //SAVE SESSION
        \Session::put('user-permission', $permissions->pluck('name', 'key'));
        \Session::put(config('session.app.selected_marketing_tim'), MarketingEnum::TEAM_A());
        \Session::save();
    }
}
