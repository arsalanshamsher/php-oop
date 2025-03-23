<?php

namespace App\Models;

use App\Config\Database;

class Model extends Database
{
    protected static $table;
    protected $data = []; // Yeh define karo

    // Fetch all data
    public static function all()
    {
        if (!isset(static::$table)) {
            throw new \Exception("Table not defined in model");
        }

        $sql = new Database();
        return $sql->select(static::$table);
    }

    // Find data by ID
    public static function find($id)
    {
        if (!isset(static::$table)) {
            throw new \Exception("Table not defined in model");
        }

        $sql = new Database();
        $result = $sql->select(static::$table, "*", "id = ?", [$id]);
        return $result ? $result[0] : null;
    }

    public function get()
    {
        return $this->data ?? [];
    }

    // Where condition
    public static function where($column, $operator, $value = null)
    {
        if (!isset(static::$table)) {
            throw new \Exception("Table not defined in model");
        }

        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $sql = new Database();
        $data = $sql->select(static::$table, "*", "$column $operator ?", null, null, [$value]);

        // Instance create karo aur data set karo
        $instance = new static();
        $instance->data = $data; 
        return $instance;
    }

    // Insert data
    public static function create($data)
    {
        if (!isset(static::$table)) {
            throw new \Exception("Table not defined in model");
        }

        $sql = new Database();
        return $sql->insert(static::$table, $data);
    }
}
