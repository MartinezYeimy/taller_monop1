<?php
class Categoria {
    private $conn;
    private $table = "categories";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todas las categorías
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY name ASC";
        $result = $this->conn->query($query);

        $categorias = [];
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }

        return $categorias;
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function insert($name, $percentage) {
        $query = "INSERT INTO " . $this->table . " (name, percentage) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sd", $name, $percentage);
        return $stmt->execute();
    }

    public function update($id, $name, $percentage) {
        $query = "UPDATE " . $this->table . " SET name = ?, percentage = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sdi", $name, $percentage, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function isUsed($id) {
        $query = "SELECT COUNT(*) FROM bills WHERE idCategory = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Error en prepare(): " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result(); 

        $count = 0;
        $stmt->bind_result($count);
        $stmt->fetch();

        return $count > 0;
    }
}
?>
