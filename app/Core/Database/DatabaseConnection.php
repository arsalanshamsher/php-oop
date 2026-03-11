<?php

namespace App\Core\Database;

use PDO;
use PDOException;

class DatabaseConnection
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
            if (!$host) {
                throw new \Exception("Database Host is not configured. Please check your .env file.");
            }

            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            $msg = $e->getMessage();
            if (strpos($msg, '2002') !== false) {
                $msg .= " (This error indicates PHP is trying to use a local socket. Please ensure DB_HOST is correct in your .env and that the .env file is uploaded to the server.)";
            }
            throw new \Exception("Database Connection Error: " . $msg);
        } catch (\Exception $e) {
            throw new \Exception("Database Configuration Error: " . $e->getMessage());
        }
    }

    public function getPdo()
    {
        return $this->pdo;
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

    public function select($table, $columns = "*", $where = [], $orderBy = "", $limit = null, $offset = null)
    {
        $sql = "SELECT $columns FROM $table";
        $params = [];

        if (!empty($where)) {
            $clauses = [];
            foreach ($where as $key => $value) {
                if (is_array($value) && count($value) === 3) {
                    // format: ['column', 'operator', 'value']
                    [$col, $op, $val] = $value;
                    $op = strtoupper($op);
                    
                    if (in_array($op, ['IN', 'NOT IN']) && is_array($val)) {
                        $placeholders = implode(', ', array_fill(0, count($val), '?'));
                        $clauses[] = "$col $op ($placeholders)";
                        foreach ($val as $v) $params[] = $v;
                    } else {
                        $clauses[] = "$col $op ?";
                        $params[] = $val;
                    }
                } else {
                    // format: 'column' => 'value'
                    $clauses[] = "$key = ?";
                    $params[] = $value;
                }
            }
            $sql .= " WHERE " . implode(" AND ", $clauses);
        }

        if (!empty($orderBy)) {
            $sql .= " ORDER BY $orderBy";
        }

        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
            if ($offset !== null) {
                $sql .= " OFFSET " . (int)$offset;
            }
        }

        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->execute($params);

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
