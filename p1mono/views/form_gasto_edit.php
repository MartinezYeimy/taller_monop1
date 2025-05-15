<?php
require_once("../models/drivers/conexDB.php");
require_once("../models/entities/report.php");
require_once("../models/entities/categoria.php");
require_once("../models/entities/gasto.php");

$db = new Database();
$conn = $db->connect();

$reportModel = new Report($conn);
$categoriaModel = new Categoria($conn);
$gastoModel = new Gasto($conn);

$id = $_GET['id'] ?? null;
if (!$id) {
    die("❌ Gasto no especificado.");
}

$gasto = $gastoModel->getById($id);
$report = $reportModel->getById($gasto['idReport']);
$categorias = $categoriaModel->getAll();

$month = $report['month'];
$year = $report['year'];
$value = $gasto['value'];
$idCategory = $gasto['idCategory'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h2>✏️ Editar gasto</h2>

<form method="POST" action="../controllers/gastosController.php">
    <input type="hidden" name="id" value="<?= $id ?>">

    <label>Mes:</label>
    <input type="text" name="month" value="<?= $month ?>" readonly><br>

    <label>Año:</label>
    <input type="number" name="year" value="<?= $year ?>" readonly><br>

    <label>Categoría:</label>
    <select name="idCategory" required>
        <?php foreach ($categorias as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= $idCategory == $cat['id'] ? 'selected' : '' ?>>
                <?= $cat['name'] ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <label>Valor:</label>
    <input type="number" name="value" step="0.01" min="0.01" value="<?= $value ?>" required><br><br>

    <input type="submit" value="Actualizar">
</form>

<a href="gastos.php">🔙 Volver</a>
</body>
</html>
