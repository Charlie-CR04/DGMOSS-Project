<?php
require __DIR__ . '/../../includes/auth.php';
requireEditorOrAdmin();
require __DIR__ . '/../../includes/conexion.php';

$csrf = ensureCsrfToken();
$error = "";

// ID de categoría
$id_categoria = (int)($_GET['id_categoria'] ?? 0);

// Obtener la categoría
$query = "SELECT c.id_categoria, c.nombre_categoria, c.id_direccion, d.nombre_direccion
        FROM categorias c
        INNER JOIN direcciones d ON c.id_direccion = d.id_direccion
        WHERE c.id_direccion != 1 AND c.id_direccion != 4
        AND c.id_categoria = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_categoria);
$stmt->execute();
$categoria = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$categoria) {
    header("Location: /dgmoss/admin/categorias/index_admin.php");
    exit;
}

// Verificar rol
$is_admin = ($_SESSION['rol'] === 'admin');
$id_direccion_editor = (int)($_SESSION['id_direccion'] ?? 0);

// Obtener direcciones (solo para admin)
$direcciones = [];
if ($is_admin) {
    $sql = "SELECT id_direccion, nombre_direccion FROM direcciones 
    WHERE id_direccion != 1 AND id_direccion != 4
    ORDER BY nombre_direccion";
    $direcciones = $conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfOrDie();

    $nombre_categoria = trim($_POST['nombre_categoria'] ?? '');
    $id_direccion = $is_admin ? (int)($_POST['id_direccion'] ?? 0) : $id_direccion_editor;

    if ($nombre_categoria === '') {
        $error = "El nombre de la categoría es obligatorio.";
    } elseif ($id_direccion === 0) {
        $error = "Debes seleccionar una dirección válida.";
    } else {
        // Verificar duplicados
        $check = $conexion->prepare("
            SELECT 1 FROM categorias 
            WHERE nombre_categoria = ? AND id_direccion = ? AND id_categoria <> ?
            LIMIT 1
        ");
        $check->bind_param("sii", $nombre_categoria, $id_direccion, $id_categoria);
        $check->execute();
        $exists = $check->get_result()->num_rows > 0;
        $check->close();

        if ($exists) {
            $error = "Ya existe una categoría con ese nombre en la misma dirección.";
        } else {
            // Actualizar
            $stmt = $conexion->prepare("
                UPDATE categorias 
                SET nombre_categoria = ?, id_direccion = ? 
                WHERE id_categoria = ?
            ");
            $stmt->bind_param("sii", $nombre_categoria, $id_direccion, $id_categoria);
            $stmt->execute();
            $stmt->close();

            // Redirigir
            if ($is_admin) {
                header("Location: /dgmoss/admin/categorias/index_admin.php");
            } else {
                header("Location: /dgmoss/admin/categorias/index_editor.php");
            }
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoría</title>
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
    <link rel="stylesheet" href="/dgmoss/assets/css/crear-usuarios.css">
    <?php include(__DIR__ . '/../navbar_panel.php'); ?>
</head>
<body>
<div class="container mt-4">
    <div class="mb-3">
        <a class="btn btn-secondary btn-sm active" href="<?= $is_admin ? '/dgmoss/admin/categorias/index_admin.php' : '/dgmoss/admin/categorias/index_editor.php' ?>">
            <i class="bi bi-arrow-left"></i> Regresar
        </a>
    </div>

    <h2 class="text-center">Editar Categoría</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="form-container">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">

        <div class="form-group">
            <label for="nombre_categoria">Nombre de la categoría</label>
            <input type="text" name="nombre_categoria" id="nombre_categoria" 
                   class="form-control" 
                   value="<?= htmlspecialchars($categoria['nombre_categoria']) ?>" required>
        </div>

        <?php if ($is_admin): ?>
            <div class="form-group">
                <label for="id_direccion">Dirección</label>
                <select name="id_direccion" id="id_direccion" class="form-control" required>
                    <?php foreach ($direcciones as $dir): ?>
                        <option value="<?= (int)$dir['id_direccion'] ?>" <?= $dir['id_direccion'] == $categoria['id_direccion'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dir['nombre_direccion']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php else: ?>
            <div class="form-group">
                <label>Dirección</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($categoria['nombre_direccion']) ?>" readonly>
                <input type="hidden" name="id_direccion" value="<?= $id_direccion_editor ?>">
            </div>
        <?php endif; ?>

        <button class="btn btn-success mt-3">Guardar cambios</button>
    </form>
</div>

<script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
</body>
</html>
