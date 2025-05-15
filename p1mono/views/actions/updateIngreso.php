<?php
require_once("../../models/drivers/conexDB.php");
require_once("../../models/entities/report.php");
require_once("../../models/entities/ingreso.php");

$db = new Database();
$conn = $db->connect();
$reportModel = new Report($conn);
$ingresoModel = new Ingreso($conn);

$month = $_POST["month"];
$year = intval($_POST["year"]);
$value = floatval($_POST["value"]);

if ($value <= 0) {
    die("❌ El ingreso no puede ser negativo.");
}

$idReport = $reportModel->getReportId($month, $year);
if (!$idReport) {
    die("❌ No se encontró el ingreso a actualizar.");
}

$ingreso = $ingresoModel->getIngresoByReport($idReport);

if (!$ingreso) {
    die("❌ No existe ingreso para actualizar en ese mes.");
}

$ingresoModel->updateIngreso($ingreso["id"], $value);
header("Location: ../ingresos.php?msg=actualizado");
