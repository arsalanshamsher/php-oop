<?php

namespace App\Models;

use App\Config\Database;

class Model
{
    protected static $table;
    protected $attributes = [];
    protected static $query = [];
    public function __construct($data = [])
    {
        $this->attributes = $data;
    }

    // ✅ Get all records
    public static function all()
    {
        $db = new Database();
        $result = $db->select(static::$table);
        // dd($result);
        return array_map(fn($row) => new static($row), $result);
    }

    // ✅ Find by ID
    public static function find($id)
    {
        $db = new Database();
        $result = $db->first(static::$table, ['id' => $id]);

        return $result ? new static($result) : null;
    }

    // ✅ Where with Multiple Conditions (Chaining Support)
    public static function where($column, $operator, $value = null)
    {
        
        if (!isset(static::$table)) {
            throw new \Exception("Table not defined in model");
        }

        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        static::$query[] = [$column, $operator, $value];

        return new static;
    }

    // ✅ Get all matching records
    public function get()
    {
        $db = new Database();
        $conditions = [];
        $params = [];

        foreach (static::$query as $condition) {
            [$column, $operator, $value] = $condition;
            $conditions[$column] = $value;
        }

        // ✅ Fix: Pass array as `$where`
        $data = $db->select(static::$table, "*", $conditions);

        static::$query = []; // Query reset karna zaroori hai

        return array_map(fn($row) => new static($row), $data);
    }


    // ✅ Get First Record
    public function first()
    {
        $results = $this->get();
        return (!empty($results) && is_array($results)) ? reset($results) : null;
    }


    // ✅ Save (Insert or Update)
    public function save()
    {
        $db = new Database();

        if (isset($this->attributes['id'])) {
            return $db->update(static::$table, $this->attributes, ['id' => $this->attributes['id']]);
        } else {
            $insertId = $db->insert(static::$table, $this->attributes);
            $this->attributes['id'] = $insertId;
            return $insertId;
        }
    }

    // ✅ Delete Record
    public function delete()
    {
        if (!isset($this->attributes['id'])) {
            throw new \Exception("No ID set for deletion");
        }

        $db = new Database();
        return $db->delete(static::$table, ['id' => $this->attributes['id']]);
    }

    // ✅ Dynamic Set & Get
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }
}
