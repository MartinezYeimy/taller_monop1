<?php
require_once("../models/drivers/conexDB.php");
require_once("../models/entities/categoria.php");

$db = new Database();
$conn = $db->connect();
$model = new Categoria($conn);

$id = $_GET['id'] ?? null;
$categoria = null;

if ($id) {
    $categoria = $model->getById($id);

    if (!$categoria) {
        die("❌ Categoría no encontrada.");
    }

    if ($model->isUsed($id)) {
        die("⚠️ No puedes editar una categoría que ya tiene gastos registrados.");
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $id ? 'Editar categoría' : 'Nueva categoría' ?></title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<div class="contenedor">
    <h2><?= $id ? '✏️ Editar Categoría' : '➕ Nueva Categoría' ?></h2>

    <form method="POST" action="actions/saveCategoria.php">
        <?php if ($id): ?>
            <input type="hidden" name="id" value="<?= $id ?>">
        <?php endif; ?>

        <label>Nombre:</label>
        <input type="text" name="name" 
               value="<?= $categoria ? htmlspecialchars($categoria['name']) : '' ?>" required>

        <label>Porcentaje (%):</label>
        <input type="number" name="percentage" step="0.01" min="0.01" max="100"
               value="<?= $categoria ? $categoria['percentage'] : '' ?>" required>

        <input type="submit" value="<?= $id ? 'Actualizar' : 'Registrar' ?>">
    </form>

    <a href="categorias.php">🔙 Volver</a>
</div>
</body>
</html>
