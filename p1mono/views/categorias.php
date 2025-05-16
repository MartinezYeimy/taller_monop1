<?php
require_once("../models/drivers/conexDB.php");
require_once("../models/entities/categoria.php");

$db = new Database();
$conn = $db->connect();
$categoriaModel = new Categoria($conn);
$categorias = $categoriaModel->getAll();
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
<h2>📂 Categorías de Gasto</h2>

<form method="POST" action="actions/saveCategoria.php">
    <label>Nombre:</label>
    <input type="text" name="name" required>

    <label>Porcentaje (%):</label>
    <input type="number" name="percentage" step="0.01" min="0.01" max="100" required>

    <input type="submit" value="Agregar categoría">
</form>

<table border="1" cellpadding="8">
    <tr>
        <th>Nombre</th>
        <th>Porcentaje</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($categorias as $cat): 
        $enUso = $categoriaModel->isUsed($cat['id']); ?>
        <tr>
            <td><?= htmlspecialchars($cat['name']) ?></td>
            <td><?= $cat['percentage'] ?>%</td>
            <td>
                <?php if (!$enUso): ?>
                    <a href="form_categoria.php?id=<?= $cat['id'] ?>">✏️ Editar</a> |
                    <a href="actions/deleteCategoria.php?id=<?= $cat['id'] ?>" onclick="return confirm('¿Eliminar esta categoría?');">🗑️ Eliminar</a>
                <?php else: ?>
                    <span>🔒 En uso</span>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<a href="../index.php">🏠 Volver al inicio</a>
</body>
</html>
