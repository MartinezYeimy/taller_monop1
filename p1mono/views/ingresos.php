<?php
require_once("../models/drivers/conexDB.php");
require_once("../models/entities/report.php");
require_once("../models/entities/ingreso.php");

$db = new Database();
$conn = $db->connect();

$reportModel = new Report($conn);
$ingresoModel = new Ingreso($conn);
$reportes = $reportModel->getAllReports();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<h2>Ingresos mensuales</h2>
<a href="form_ingreso.php">➕ Nuevo ingreso</a>
<table border="1" cellpadding="8">
    <tr>
        <th>Mes</th>
        <th>Año</th>
        <th>Ingreso</th>
        <th>Opciones</th>
    </tr>
    <?php foreach ($reportes as $reporte): 
        $ingreso = $ingresoModel->getIngresoByReport($reporte['id']);
        if ($ingreso): ?>
        <tr>
            <td><?= $reporte['month'] ?></td>
            <td><?= $reporte['year'] ?></td>
            <td>$<?= number_format($ingreso['value'], 2, ',', '.') ?></td>
            <td>
               <a href="form_ingresos_edit.php?month=<?= $reporte['month'] ?>&year=<?= $reporte['year'] ?>&edit=1">✏️ Editar</a>
            </td>
        </tr>
        
    <?php endif; endforeach; ?>
</table>
<a href="../index.php">🔙 Volver</a>
</body>
</html>


