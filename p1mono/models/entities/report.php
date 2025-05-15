<?php
class Report {
    private $conn;
    private $table = "reports";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getReportId($month, $year) {
        $query = "SELECT id FROM " . $this->table . " WHERE month = ? AND year = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Error en prepare(): " . $this->conn->error);
        }

        $stmt->bind_param("si", $month, $year);
        $stmt->execute();
        $stmt->store_result();

        $id = null;
        $stmt->bind_result($id);

        if ($stmt->fetch()) {
            return $id;
        } else {
            return null;
        }
    }

    public function createReport($month, $year) {
        $query = "INSERT INTO " . $this->table . " (month, year) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            die("Error al preparar la consulta: " . $this->conn->error);
        }

        $stmt->bind_param("si", $month, $year);
        return $stmt->execute();
    }

    public function getAllReports() {
        $query = "SELECT id, month, year FROM " . $this->table . " ORDER BY year DESC, id DESC";
        $result = $this->conn->query($query);

        $reports = [];
        while ($row = $result->fetch_assoc()) {
            $reports[] = $row;
        }
        return $reports;
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAniosDisponibles() {
        $query = "SELECT DISTINCT year FROM " . $this->table . " ORDER BY year DESC";
        $result = $this->conn->query($query);

        $anios = [];
        while ($row = $result->fetch_assoc()) {
            $anios[] = $row['year'];
        }
        return $anios;
    }
}
?>
