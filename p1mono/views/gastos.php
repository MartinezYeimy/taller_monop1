<?php
require_once("../models/drivers/conexDB.php");
require_once("../models/entities/gasto.php");

$db = new Database();
$conn = $db->connect();
$gastoModel = new Gasto($conn);
$gastos = $gastoModel->getAll();
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
    
<h2>Gastos registrados</h2>
<a href="form_gasto.php">➕ Registrar gasto</a>
<table border="1" cellpadding="8">
    <tr>
        <th>Mes</th>
        <th>Año</th>
        <th>Categoría</th>
        <th>Valor</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($gastos as $gasto): ?>
        <tr>
            <td><?= $gasto['month'] ?></td>
            <td><?= $gasto['year'] ?></td>
            <td><?= $gasto['category'] ?></td>
            <td>$<?= number_format($gasto['value'], 2, ',', '.') ?></td>
            <td>
                <a href="form_gasto_edit.php?id=<?= $gasto['id'] ?>">✏️ Editar</a>
                <a href="actions/deleteGasto.php?id=<?= $gasto['id'] ?>" onclick="return confirm('¿Eliminar gasto?');">🗑️ Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="../index.php">🔙 Volver</a>
</body>
</html>
