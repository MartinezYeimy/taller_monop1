<?php
require_once("../../models/drivers/conexDB.php");
require_once("../../models/entities/categoria.php");

$db = new Database();
$conn = $db->connect();
$model = new Categoria($conn);

$id = $_GET["id"] ?? null;

if ($id && !$model->isUsed($id)) {
    $model->delete($id);
} else {
    die("❌ No se puede eliminar una categoría que ya está en uso.");
}

header("Location: ../categorias.php");
