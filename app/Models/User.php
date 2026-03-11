<?php

namespace App\Models;

use App\Core\Database\Model;

class User extends Model
{
    public static $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    /**
     * Get the primary role of the user
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Get the roles assigned to the user
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    /**
     * Check if the user has a specific role
     */
    public function hasRole(string $roleName): bool
    {
        $roles = $this->roles()->get();
        foreach ($roles as $role) {
            if ($role->name === strtolower($roleName)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if the user has a specific permission
     */
    public function hasPermission(string $permissionName): bool
    {
        $roles = $this->roles()->get();
        foreach ($roles as $role) {
            if ($role->status !== 'active') continue;
            
            $permissions = $role->permissions()->pluck('name');
            if (in_array($permissionName, $permissions)) {
                return true;
            }
        }
        return false;
    }
}
