<?php

namespace App\Models;

use App\Config\Database;

class Model extends Database
{
    protected static $table;
    protected $attributes = []; // Data store karne ke liye private array

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

        if ($result) {
            return new static($result[0]); // Object return karo
        }

        return null;
    }

    // Set attribute dynamically
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    // Get attribute dynamically
    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    // Save data to the database
    public function save()
    {
        if (!isset(static::$table)) {
            throw new \Exception("Table not defined in model");
        }

        $sql = new Database();

        if (isset($this->attributes['id'])) {
            // Update existing record
            return $sql->update(static::$table, $this->attributes, "id = ?", [$this->attributes['id']]);
        } else {
            // Insert new record
            return $sql->insert(static::$table, $this->attributes);
        }
    }
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
        $data = $sql->select(static::$table, "*", "$column $operator ?", [$value]);

        if ($data) {
            $instance = new static();  // ✅ Object create karo
            $instance->data = $data;   // ✅ Data set karo
            return $instance;          // ✅ Object return karo
        }

        return null;
    }
    public function first()
    {
        return !empty($this->data) ? (object) $this->data[0] : null;
    }
}
