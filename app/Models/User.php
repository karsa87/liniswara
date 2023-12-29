<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Scopes\ScopeLike;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, ScopeLike, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'user_roles',
            'user_id',
            'role_id',
        )->withoutGlobalScope('exclude_developer');
    }

    /**
     * The roles that belong to the user.
     */
    public function role(): BelongsToMany
    {
        return $this->roles()->latest();
    }

    /**
     * The roles that belong to the user.
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'user_id');
    }

    /**
     * The data that belong to the file.
     */
    public function profile_photo(): BelongsTo
    {
        return $this->belongsTo(File::class, 'profile_photo_id');
    }

    /**
     * The roles that belong to the user.
     */
    public function isDeveloper()
    {
        return $this->roles->firstWhere('slug', 'developer') ? true : false;
    }

    public function hasPermission($findPermissions)
    {
        $has = false;
        $permissions = \Session::get('user-permission');
        $findPermissions = is_array($findPermissions) ? $findPermissions : (json_decode($findPermissions, true) ?: $findPermissions);
        if (is_array($findPermissions)) {
            foreach ($findPermissions as $p) {
                if ($permissions->has($p)) {
                    $has = true;
                }
            }
        } elseif (is_string($findPermissions) && $permissions->has($findPermissions)) {
            $has = true;
        }

        return $has;
    }
}
