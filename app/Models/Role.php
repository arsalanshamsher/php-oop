<?php

namespace App\Models;

use App\Core\Database\Model;

class Role extends Model
{
    public static $table = 'roles';

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }
}
