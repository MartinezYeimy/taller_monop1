<?php
class Ingreso {
    private $conn;
    private $table = "income";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getIngresoByReport($idReport) {
        $query = "SELECT id, value FROM " . $this->table . " WHERE idReport = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $idReport);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function insertIngreso($value, $idReport) {
        if ($value < 0) return false;
        $query = "INSERT INTO " . $this->table . " (value, idReport) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("di", $value, $idReport);
        return $stmt->execute();
    }

    public function updateIngreso($id, $value) {
        if ($value < 0) return false;
        $query = "UPDATE " . $this->table . " SET value = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("di", $value, $id);
        return $stmt->execute();
    }
}

