<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private $pdo;
    private $stmt;

    public function __construct()
    {
        
        $host = env('DB_HOST');
        $dbname = env('DB_DATABASE', 'default_db');
        $user = env('DB_USERNAME');
        $pass = env('DB_PASSWORD');

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Database Connection Error: " . $e->getMessage());
        }
    }

    public function insert($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->stmt = $this->pdo->prepare($sql);

        return $this->stmt->execute($data) ? $this->pdo->lastInsertId() : false;
    }

    public function update($table, $data, $where)
    {
        $set = implode(", ", array_map(fn($col) => "$col = :$col", array_keys($data)));
        $whereClause = implode(" AND ", array_map(fn($col) => "$col = :$col", array_keys($where)));

        $sql = "UPDATE $table SET $set WHERE $whereClause";
        $this->stmt = $this->pdo->prepare($sql);

        return $this->stmt->execute(array_merge($data, $where));
    }

    public function delete($table, $where)
    {
        $whereClause = implode(" AND ", array_map(fn($col) => "$col = :$col", array_keys($where)));

        $sql = "DELETE FROM $table WHERE $whereClause";
        $this->stmt = $this->pdo->prepare($sql);

        return $this->stmt->execute($where);
    }

    public function select($table, $columns = "*", $where = [], $orderBy = "", $limit = null, $params = [])
    {
        $sql = "SELECT $columns FROM $table";

        if (!empty($where)) {
            // ✅ Ensure $where is an array
            if (!is_array($where)) {
                throw new \Exception("Where clause must be an array.");
            }

            $whereClause = implode(" AND ", array_map(fn($col) => "$col = ?", array_keys($where)));
            $sql .= " WHERE $whereClause";
        }

        if (!empty($orderBy)) {
            $sql .= " ORDER BY $orderBy";
        }

        if ($limit) {
            $sql .= " LIMIT $limit";
        }

        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->execute(array_values($where)); // ✅ Ensure correct array format

        return $this->stmt->fetchAll();
    }



    public function first($table, $where = [])
    {
        $result = $this->select($table, "*", $where, "", 1);
        return $result ? $result[0] : null;
    }

    public function close()
    {
        $this->pdo = null;
    }
}
