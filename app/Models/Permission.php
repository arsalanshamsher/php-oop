<?php

namespace App\Models;

use App\Core\Database\Model;

class Permission extends Model
{
    public static $table = 'permissions';

    public static function findByName($name)
    {
        return (new static)->where('name', $name)->first();
    }

    public static function findOrCreateByName($name)
    {
        $permission = self::findByName($name);
        if (!$permission) {
            $permission = new static(['name' => $name]);
            $permission->save();
        }
        return $permission;
    }

    public static function getIdsByNames(array $names)
    {
        $ids = [];
        foreach ($names as $name) {
            $ids[] = self::findOrCreateByName($name)->id;
        }
        return $ids;
    }
}
