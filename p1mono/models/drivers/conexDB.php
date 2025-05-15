<?php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbName = "proyecto_1_db";
    public $conn;

    public function connect() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbName);

        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
?>
