<?php
require_once("../models/drivers/conexDB.php");
require_once("../models/entities/ingreso.php");
require_once("../models/entities/report.php");

$db = new Database();
$conn = $db->connect();

$reportModel = new Report($conn);
$ingresoModel = new Ingreso($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $month = $_POST["month"];
    $year = intval($_POST["year"]);
    $value = floatval($_POST["value"]);


    if ($value <= 0) {
        echo "❌ El valor del ingreso no puede ser menor a 0";
        exit;
    }

    $idReport = $reportModel->getReportId($month, $year);

    if (!$idReport) {
   
    $reportModel->createReport($month, $year);
    $idReport = $reportModel->getReportId($month, $year);
    $ingresoModel->insertIngreso($value, $idReport);
    header("Location: ../views/ingresos.php?msg=registrado");
} else {
    
    $ingreso = $ingresoModel->getIngresoByReport($idReport);
    if ($ingreso) {
       
        die("<p>❌ Ya hay un ingreso registrado en este mes y año.</p>
             <p><a href='../views/ingresos.php'>🔙 Volver</a></p>");
    } else {
        
        $ingresoModel->insertIngreso($value, $idReport);
        header("Location: ../views/ingresos.php?msg=registrado");
    }
}

}

