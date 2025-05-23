<?php
require_once("../models/drivers/conexDB.php");
require_once("../models/entities/report.php");
require_once("../models/entities/ingreso.php");

$db = new Database();
$conn = $db->connect();

$reportModel = new Report($conn);
$ingresoModel = new Ingreso($conn);

$month = $_GET['month'] ?? "";
$year = $_GET['year'] ?? "";
$isEdit = ($month && $year && isset($_GET['edit']) && $_GET['edit'] === '1');

$valorActual = "";

if ($isEdit) {
    $idReport = $reportModel->getReportId($month, intval($year));
    $ingreso = $idReport ? $ingresoModel->getIngresoByReport($idReport) : null;
    $valorActual = $ingreso ? $ingreso['value'] : '';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $isEdit ? 'Editar ingreso' : 'Registrar ingreso' ?></title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<div class="contenedor">
    <h2><?= $isEdit ? '✏️ Editar ingreso' : '➕ Registrar ingreso' ?></h2>

    <form method="POST" action="<?= $isEdit ? '../views/actions/updateIngreso.php' : '../views/actions/saveIngreso.php' ?>">
        <label>Mes:</label>
        <select name="month" required>
            <option value="">-- Selecciona un mes --</option>
            <?php
            $meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
            foreach ($meses as $mes) {
                $selected = ($mes === $selectedMonth) ? 'selected' : '';
                echo "<option value=\"$mes\" $selected>$mes</option>";
            }
            ?>
        </select>
        
        <label>Año:</label>
        <input type="number" name="year" value="<?= htmlspecialchars($year) ?>" <?= $isEdit ? 'readonly' : '' ?> required><br>

        <label>Valor del ingreso:</label>
        <input type="number" name="value" step="0.01" min="0.01" value="<?= htmlspecialchars($valorActual) ?>" required><br><br>

        <input type="submit" value="<?= $isEdit ? 'Actualizar' : 'Registrar' ?>">
    </form>

    <a href="ingresos.php">🔙 Volver</a>
</div>
</body>
</html>
