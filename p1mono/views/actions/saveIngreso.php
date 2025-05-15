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

if ($idReport) {
    $ingreso = $ingresoModel->getIngresoByReport($idReport);
    if ($ingreso) {
        die("❌ Ya hay un ingreso registrado para este mes y año");
    }
} else {
    $reportModel->createReport($month, $year);
    $idReport = $reportModel->getReportId($month, $year);
}

$ingresoModel->insertIngreso($value, $idReport);
header("Location: ../ingresos.php?msg=registrado");
