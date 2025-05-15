<?php
require_once("../models/drivers/conexDB.php");
require_once("../models/entities/gasto.php");
require_once("../models/entities/report.php");

$db = new Database();
$conn = $db->connect();

$gastoModel = new Gasto($conn);
$reportModel = new Report($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"] ?? null;
    $value = floatval($_POST["value"]);
    $idCategory = intval($_POST["idCategory"]);

    if ($id) {

        $gastoModel->update($id, $value, $idCategory);
    } else {
        
        $month = $_POST["month"];
        $year = intval($_POST["year"]);
        $idReport = $reportModel->getReportId($month, $year);

        if (!$idReport) {
            $reportModel->createReport($month, $year);
            $idReport = $reportModel->getReportId($month, $year);
        }

        $gastoModel->insert($value, $idCategory, $idReport);
    }

    header("Location: ../views/gastos.php");
}
?>
