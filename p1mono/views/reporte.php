<?php
require_once("../models/drivers/conexDB.php");
require_once("../models/entities/report.php");
require_once("../models/entities/ingreso.php");
require_once("../models/entities/gasto.php");
require_once("../models/entities/categoria.php");

$db = new Database();
$conn = $db->connect();

$reportModel = new Report($conn);
$ingresoModel = new Ingreso($conn);
$gastoModel = new Gasto($conn);
$categoriaModel = new Categoria($conn);

$anios = $reportModel->getAniosDisponibles();

$selectedMonth = $_POST['month'] ?? null;
$selectedYear = $_POST['year'] ?? null;
$resultado = [];
$mensajeNoDatos = false;

if ($selectedMonth && $selectedYear) {
    $idReport = $reportModel->getReportId($selectedMonth, intval($selectedYear));
    $ingreso = $idReport ? $ingresoModel->getIngresoByReport($idReport) : null;
    $gastos = $idReport ? $gastoModel->getAllByReport($idReport) : [];

    if (!$ingreso && empty($gastos)) {
        $mensajeNoDatos = true;
    } else {
       
        $gastosPorCategoria = [];
        foreach ($gastos as $gasto) {
            $cat = $gasto['category'];
            if (!isset($gastosPorCategoria[$cat])) {
                $gastosPorCategoria[$cat] = 0;
            }
            $gastosPorCategoria[$cat] += $gasto['value'];
        }

        $resultado = [
            'idReport' => $idReport,
            'ingreso' => $ingreso['value'] ?? 0,
            'gastos' => $gastos,
            'gastosPorCategoria' => $gastosPorCategoria
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<h2>📊 Reporte mensual</h2>

<form method="POST">
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
    <select name="year" required>
        <option value="">-- Selecciona un año --</option>
        <?php foreach ($anios as $anio): ?>
            <option value="<?= $anio ?>" <?= ($anio == $selectedYear) ? 'selected' : '' ?>>
                <?= $anio ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="submit" value="Generar reporte">
    <a href="../index.php">🔙 Volver</a>
</form>

<?php if ($mensajeNoDatos): ?>
    <p>❌ No hay datos registrados para <?= $selectedMonth . " " . $selectedYear ?>.</p>
<?php elseif ($resultado): ?>
    <hr>
    <h3>Resumen: <?= $selectedMonth . " " . $selectedYear ?></h3>
    <p><strong>Ingreso registrado:</strong> $<?= number_format($resultado['ingreso'], 2, ',', '.') ?></p>

    <h4>📌 Gastos por categoría</h4>
    <ul>
        <?php
        $categorias = $categoriaModel->getAll();
        foreach ($categorias as $cat):
            $nombre = $cat['name'];
            $limite = $cat['percentage'];
            $gastado = $resultado['gastosPorCategoria'][$nombre] ?? 0;
            $maxPermitido = $resultado['ingreso'] * ($limite / 100);
            $exceso = $gastado > $maxPermitido;
        ?>
            <li>
                <?= $nombre ?>:
                $<?= number_format($gastado, 2, ',', '.') ?> /
                Máximo permitido: $<?= number_format($maxPermitido, 2, ',', '.') ?>
                <?= $exceso ? "<span>⚠️ Excedido</span>" : "✅ Dentro del límite" ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h4>💰 Ahorro</h4>
    <?php
    $totalGastos = array_sum(array_column($resultado['gastos'], 'value'));
    $ingreso = $resultado['ingreso'];
    $ahorro = $ingreso - $totalGastos;
    $porcentajeAhorro = $ingreso > 0 ? ($ahorro / $ingreso) * 100 : 0;
    ?>

    <p>Total de gastos: $<?= number_format($totalGastos, 2, ',', '.') ?></p>
    <p>Ahorro: $<?= number_format($ahorro, 2, ',', '.') ?> (<?= round($porcentajeAhorro, 2) ?>%)</p>

    <?php if ($porcentajeAhorro < 10): ?>
        <p><strong>⚠️ Ahorro insuficiente. Deberías ahorrar al menos el 10%.</strong></p>
    <?php else: ?>
        <p>✅ Buen ahorro.</p>
    <?php endif; ?>
<?php endif; ?>
</body>
</html>
