<?php
namespace App\Config;

use mysqli;

class Database{
    private $db_host = "localhost";
    private $db_user = "root";
    private $db_pass = "";
    private $db_name = "daar_ul_uloom_alkhizra"; // Specify the database name

    private $mysqli = null;
    private $result = array();
    private $conn = false;

    public function __construct()
    {
        // Attempt to establish a database connection
        if (!$this->conn) {
            $this->mysqli = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
            $this->conn = true; // Ensure connection status remains false on failure

            // Check for connection errors
            if ($this->mysqli->connect_error) {
                $this->result[] = "Connection Error: " . $this->mysqli->connect_error;
                return false;
            } else {
                $this->conn = true;
            }

            $this->conn = true;
        }
    }

    // founction to insert into the database
    public function insert($table, $params = array())
    {
        if ($this->tableExists($table)) {
            $fields = implode(', ', array_keys($params));
            $values = implode("', '", $params);
            $sql = "INSERT INTO $table ($fields) VALUES ('$values')";
            if ($this->mysqli->query($sql)) {
                array_push($this->result, $this->mysqli->insert_id);
                return true;
            } else {
                array_push($this->result, "Error: " . $this->mysqli->error);
                return false;
            }
        } else {
            return false;
        }
    }
    // fontion to update row in database
    public function update($table, $params = array(), $where = null)
    {
        if ($this->tableExists($table)) {
            $args = array();
            foreach ($params as $key => $value) {
                $args[] = "$key = '$value'";
            }
            $sql = "UPDATE $table SET " . implode(', ', $args);
            if ($where != null) {
                $sql .= " WHERE $where";
            }
            if ($this->mysqli->query($sql)) {
                array_push($this->result, $this->mysqli->affected_rows);
                return true; // The data has been updated
            } else {
                array_push($this->result, "Error: " . $this->mysqli->error);
            }
        }
    }

    // function to delete table row in database
    public function delete($table, $where = null)
    {
        if ($this->tableExists($table)) {
            $sql = "DELETE FROM $table";
            if ($where != null) {
                $sql .= "WHERE $where";
            }
            if ($this->mysqli->query($sql)) {
                array_push($this->result, $this->mysqli->affected_rows);
                return true;
            } else {
                array_push($this->result, "Error: " . $this->mysqli->error);
                return false; // The data has not been deleted
            }
        }
    }
    // fonction to select from the database
    public function select($table, $rows = "*", $join = null, $where = null, $orderBy = null, $limit = null)
    {
        if ($this->tableExists($table)) {
            $sql = "SELECT $rows FROM $table";
            if ($join != null) {
                $sql .= " JOIN $join";
            }
            if ($where != null) {
                $sql .= " WHERE $where";
            }
            if ($orderBy != null) {
                $sql .= " ORDER BY $orderBy";
            }
            if ($limit != null) {
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                } else {
                    $page = 1;
                }
                $start = ($page - 1) * $limit;
                $sql .= " LIMIT $start,$limit";
            }
            $query = $this->mysqli->query($sql);
            if ($query) {
                $this->result = $query->fetch_all(MYSQLI_ASSOC);
                return true;
            } else {
                array_push($this->result, $this->mysqli->error);
                return false;
            }
        }
    }
    // function to pagination
    public function pagination($table, $join = null, $where = null, $limit = null)
    {
        if ($this->tableExists($table)) {
            if ($limit != null) {
                $sql = "SELECT COUNT(*) FROM $table";
                if ($join != null) {
                    $sql .= " JOIN $join";
                }
                if ($where != null) {
                    $sql .= " WHERE $where";
                }
                $query = $this->mysqli->query($sql);
                $total = $query->fetch_row()[0];
                $pages = ceil($total / $limit);
                $url = basename($_SERVER['PHP_SELF']);
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                } else {
                    $page = 1;
                }
                $cls = "class=''";
                $pagination = '<div class="pagination">';
                if($page>1){
                    $pagination .= $pagination .= "<a  href='$url?page=".($page-1)."'>Prev</a>";
                }
                if ($total > $limit) {
                    for ($i = 1; $i <= $pages; $i++) {
                        if ($i == $page) {
                            $cls = "class='active'";
                        }
                        $pagination .= "<a $cls href='$url?page=$i'>$i</a>";
                    }
                }
                if($total>$page){
                    $pagination .= $pagination .= "<a  href='$url?page=".($page+1)."'>Next</a>";
                }
                $pagination .= '</div>';
                return $pagination;
            }
        }
    }
    // function to sql query run
    public function sql($sql)
    {
        $query = $this->mysqli->query($sql);
        if ($query) {
            $this->result = $query->fetch_all(MYSQLI_ASSOC);
            return true;
        } else {
            array_push($this->result, $this->mysqli->error);
            return false;
        }
    }

    public function getResult()
    {
        return $this->result;
    }

    // table exists
    private function tableExists($table)
    {
        $sql = "SHOW TABLES FROM $this->db_name LIKE '$table'";
        $tableInDb = $this->mysqli->query($sql);
        if ($tableInDb->num_rows > 0) {
            return true;
        } else {
            array_push($this->result, $table . ": table dose not exist in this database.");
            return false;
        }
    }

    public function close()
    {
        if ($this->conn) {
            $this->mysqli->close();
            $this->conn = false;
        }
    }
}