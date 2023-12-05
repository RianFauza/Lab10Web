<?php

class Database {
    protected $host;
    protected $user;
    protected $password;
    protected $db_name;
    protected $conn;

    public function __construct() {
        $this->getConfig();
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->db_name);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    private function getConfig() {
        include_once("config.php");
        $this->host = $config['host'];
        $this->user = $config['user'];
        $this->password = $config['password'];
        $this->db_name = $config['db_name'];
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }

    public function get($table, $where = null) {
        if ($where) {
            $where = " WHERE " . $where;
        }
        $sql = "SELECT * FROM " . $table . $where;
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            return null;
        }
    }

    public function insert($table, $data) {
        if (is_array($data)) {
            $columns = implode(",", array_keys($data));
            $values = "'" . implode("','", array_values($data)) . "'";
        }
        $sql = "INSERT INTO " . $table . " (" . $columns . ") VALUES (" . $values . ")";
        $result = $this->conn->query($sql);
        if ($result === true) {
            return true;
        } else {
            return false;
        }
    }

    public function update($table, $data, $where) {
        if (is_array($data)) {
            $update_value = "";
            foreach ($data as $key => $val) {
                $update_value .= $key . "='" . $val . "',";
            }
            $update_value = rtrim($update_value, ",");
        }
        $sql = "UPDATE " . $table . " SET " . $update_value . " WHERE " . $where;
        $result = $this->conn->query($sql);
        if ($result === true) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($table, $filter) {
        $sql = "DELETE FROM " . $table . " " . $filter;
        $result = $this->conn->query($sql);
        if ($result === true) {
            return true;
        } else {
            return false;
        }
    }
}
?>