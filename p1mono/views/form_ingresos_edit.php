<?php
require_once("../models/drivers/conexDB.php");
require_once("../models/entities/report.php");
require_once("../models/entities/categoria.php");
require_once("../models/entities/ingreso.php");

$db = new Database();
$conn = $db->connect();

$reportModel = new Report($conn);
$ingresoModel = new Ingreso($conn);

$month = $_GET['month'] ?? "";
$year = $_GET['year'] ?? "";
$isEdit = ($month && $year && isset($_GET['edit']) && $_GET['edit'] === '1');



if ($isEdit) {
    $idReport = $reportModel->getReportId($month, intval($year));
    $ingreso = $idReport ? $ingresoModel->getIngresoByReport($idReport) : null;
    $valorActual = 'value';
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar ingreso</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>
<div class="contenedor">
    <h2>✏️ Editar ingreso</h2>

    <form method="POST" action="<?= $isEdit ? '../views/actions/updateIngreso.php' : '../views/actions/saveIngreso.php' ?>">
        <input type="hidden" name="id" value="<?= $id ?>">

        <label>Mes:</label>
        <input type="text" name="month" value="<?= $month ?>" readonly><br>


        <label>Año:</label>
        <input type="text" name="year" value="<?= $year ?>" readonly><br>
       
        <label>Valor:</label>
    <input type="number" name="value" step="0.01" min="0.01" value="<?= $value ?>" required><br><br>

        <input type="submit" value="<?= $isEdit ? 'Actualizar' : 'Registrar' ?>">
    </form>

    <a href="ingresos.php">🔙 Volver</a>
</div>
</body>
</html>
