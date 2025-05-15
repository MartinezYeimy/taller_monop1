<?php
require_once("../../models/drivers/conexDB.php");
require_once("../../models/entities/gasto.php");

$db = new Database();
$conn = $db->connect();

$gastoModel = new Gasto($conn);
$id = $_GET["id"] ?? null;

if ($id) {
    $gastoModel->delete($id);
}

header("Location: ../gastos.php");
?>
