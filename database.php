<?php

require_once("config.php");

class Database {

    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PWD;
    private $dbname = DB_NAME;

    private $connection;
    private $error;
    private $stmt;
    private $dbconnected = false;

    public function __construct() {
        // Set MySQLi connection
        $this->connection = new mysqli($this->host, $this->user, $this->pass, $this->dbname);

        if ($this->connection->connect_error) {
            $this->error = "Connection failed: " . $this->connection->connect_error . PHP_EOL;
            $this->dbconnected = false;
        } else {
            $this->dbconnected = true;
        }
    }

    public function getError() {
        return $this->error;
    }

    public function isConnected() {
        return $this->dbconnected;
    }

    // Prepared statement with query
    public function query($query) {
        $this->stmt = $this->connection->prepare($query);
        if ($this->stmt === false) {
            $this->error = "Prepare failed: " . $this->connection->error . PHP_EOL;
        }
    }

    // Execute the prepared statement
    public function execute() {
        if ($this->stmt === false) {
            return false;
        }
        return $this->stmt->execute();
    }

    // Get result set as array of objects
    public function resultSet() {
        if ($this->execute()) {
            $result = $this->stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    // Get record row count
    public function rowCount() {
        if ($this->stmt === false) {
            return 0;
        }
        $this->execute();
        $result = $this->stmt->get_result();
        return $result->num_rows;
    }

    // Get single record as object
    public function single() {
        if ($this->execute()) {
            $result = $this->stmt->get_result();
            return $result->fetch_object();
        }
        return null;
    }

    // Bind values
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = 'i';
                    break;
                case is_bool($value):
                    $type = 'b';
                    break;
                case is_null($value):
                    $type = 'n';
                    break;
                default:
                    $type = 's';
            }
        }
        $this->stmt->bind_param($type, $value);
    }
}

?>
