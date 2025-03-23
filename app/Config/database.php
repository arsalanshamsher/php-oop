<?php

namespace App\Config;

use mysqli;

class Database
{
    private $db_host = "localhost";
    private $db_user = "root";
    private $db_pass = "";
    private $db_name = "daar_ul_uloom_alkhizra";
    private $mysqli = null;
    private $result = array();
    private $conn = false;

    public function __construct()
    {
        $this->mysqli = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);

        if ($this->mysqli->connect_error) {
            die("Connection Error: " . $this->mysqli->connect_error);
        }
        $this->conn = true;
    }

    public function insert($table, $params = array())
    {
        if ($this->tableExists($table)) {
            $fields = implode(", ", array_keys($params));
            $placeholders = implode(", ", array_fill(0, count($params), "?"));
            $types = str_repeat("s", count($params));
            $values = array_values($params);

            $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param($types, ...$values);

            if ($stmt->execute()) {
                return $stmt->insert_id;
            } else {
                return "Error: " . $stmt->error;
            }
        }
        return false;
    }

    public function update($table, $params = array(), $where)
    {
        if ($this->tableExists($table)) {
            $set_clause = implode(" = ?, ", array_keys($params)) . " = ?";
            $values = array_values($params);
            $types = str_repeat("s", count($params));

            $sql = "UPDATE $table SET $set_clause WHERE $where";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param($types, ...$values);

            return $stmt->execute() ? true : "Error: " . $stmt->error;
        }
        return false;
    }

    public function delete($table, $where)
    {
        if ($this->tableExists($table)) {
            $sql = "DELETE FROM $table WHERE $where";
            return $this->mysqli->query($sql) ? true : "Error: " . $this->mysqli->error;
        }
        return false;
    }

    public function select($table, $rows = "*", $where = null, $orderBy = null, $limit = null, $params = [])
    {
        if ($this->tableExists($table)) {
            $sql = "SELECT $rows FROM $table";

            if ($where) {
                $sql .= " WHERE $where";
            }

            if ($orderBy) {
                $sql .= " ORDER BY $orderBy";
            }

            if ($limit) {
                $sql .= " LIMIT $limit";
            }

            $stmt = $this->mysqli->prepare($sql);

            if (!empty($params)) {
                $types = str_repeat("s", count($params));
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        }
        return false;
    }


    public function pagination($table, $limit)
    {
        if ($this->tableExists($table)) {
            $sql = "SELECT COUNT(*) AS total FROM $table";
            $query = $this->mysqli->query($sql);
            $total = $query->fetch_assoc()['total'];
            $pages = ceil($total / $limit);

            $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $pagination = "<div class='pagination'>";
            if ($current_page > 1) {
                $pagination .= "<a href='?page=" . ($current_page - 1) . "'>Prev</a>";
            }
            for ($i = 1; $i <= $pages; $i++) {
                $active = ($i == $current_page) ? "class='active'" : "";
                $pagination .= "<a $active href='?page=$i'>$i</a>";
            }
            if ($current_page < $pages) {
                $pagination .= "<a href='?page=" . ($current_page + 1) . "'>Next</a>";
            }
            $pagination .= "</div>";
            return $pagination;
        }
    }

    private function tableExists($table)
    {
        $sql = "SHOW TABLES LIKE '$table'";
        $query = $this->mysqli->query($sql);
        return ($query->num_rows > 0) ? true : "Error: Table does not exist";
    }

    public function close()
    {
        if ($this->conn) {
            $this->mysqli->close();
            $this->conn = false;
        }
    }

    public function getRecord($table, $conditions = [])
    {
        if ($this->tableExists($table)) {
            $sql = "SELECT * FROM $table";
            $params = [];
            $types = '';

            if (!empty($conditions)) {
                $sql .= " WHERE ";
                $clauses = [];

                foreach ($conditions as $key => $value) {
                    $clauses[] = "$key = ?";
                    $params[] = $value;
                    $types .= 's'; // Assuming all values are strings, modify as needed
                }

                $sql .= implode(" AND ", $clauses);
            }

            $stmt = $this->mysqli->prepare($sql);

            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return false;
    }
}
