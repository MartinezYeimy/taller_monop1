<?php
require_once("../../models/drivers/conexDB.php");
require_once("../../models/entities/categoria.php");

$db = new Database();
$conn = $db->connect();
$model = new Categoria($conn);

$id = $_POST["id"] ?? null;
$name = trim($_POST["name"]);
$percentage = floatval($_POST["percentage"]);

if ($percentage <= 0 || $percentage > 100) {
    die("❌ El porcentaje debe ser mayor que 0 y menor o igual a 100.");
}

if ($id) {
    if ($model->isUsed($id)) {
        die("⚠️ No se puede modificar esta categoría porque está en uso.");
    }
    $model->update($id, $name, $percentage);
} else {
    $model->insert($name, $percentage);
}

header("Location: ../categorias.php");
