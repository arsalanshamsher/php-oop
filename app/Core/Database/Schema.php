<?php

namespace App\Core\Database;

use App\Core\Database\DatabaseConnection;
use PDO;

class Schema
{
    protected static $pdo;

    protected static function getPdo()
    {
        if (!self::$pdo) {
            $db = new DatabaseConnection();
            self::$pdo = $db->getPdo();
        }
        return self::$pdo;
    }

    public static function create($table, callable $callback)
    {
        $blueprint = new Blueprint($table, self::getPdo(), false);
        $callback($blueprint);
        $blueprint->execute();
    }

    public static function table($table, callable $callback)
    {
        $blueprint = new Blueprint($table, self::getPdo(), true);
        $callback($blueprint);
        $blueprint->execute();
    }


    public static function dropIfExists($table)
    {
        $sql = "DROP TABLE IF EXISTS $table";
        self::getPdo()->exec($sql);
    }

    public static function hasTable($table)
    {
        $sql = "SHOW TABLES LIKE '$table'";
        $stmt = self::getPdo()->query($sql);
        return $stmt->rowCount() > 0;
    }

    public static function drop($table)
    {
        $sql = "DROP TABLE $table";
        self::getPdo()->exec($sql);
    }

    public static function rename($from, $to)
    {
        $sql = "RENAME TABLE `$from` TO `$to`";
        self::getPdo()->exec($sql);
    }
}
