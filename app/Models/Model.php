<?php
namespace App\Models;
use App\Config\Database;

class Model extends Database{
    protected static $table;

    // Static method to fetch all data
    public static function all() {
        $table = static::$table; // Access the table property
        $sql = new Database();
        $sql->select($table);
        return $sql->getResult();
    }
}
