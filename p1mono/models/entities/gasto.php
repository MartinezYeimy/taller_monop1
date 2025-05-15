<?php
class Gasto {
    private $conn;
    private $table = "bills";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function insert($value, $idCategory, $idReport) {
        $query = "INSERT INTO " . $this->table . " (value, idCategory, idReport) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("dii", $value, $idCategory, $idReport);
        return $stmt->execute();
    }

    public function update($id, $value, $idCategory) {
        $query = "UPDATE " . $this->table . " SET value = ?, idCategory = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("dii", $value, $idCategory, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getAll() {
        $query = "SELECT bills.id, bills.value, bills.idReport, bills.idCategory, 
                  reports.month, reports.year, categories.name AS category 
                  FROM bills 
                  INNER JOIN reports ON bills.idReport = reports.id 
                  INNER JOIN categories ON bills.idCategory = categories.id 
                  ORDER BY reports.year DESC, reports.month";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAllByReport($idReport) {
        $query = "SELECT bills.id, bills.value, bills.idCategory, categories.name AS category
                  FROM bills 
                  INNER JOIN categories ON bills.idCategory = categories.id
                  WHERE bills.idReport = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $idReport);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
