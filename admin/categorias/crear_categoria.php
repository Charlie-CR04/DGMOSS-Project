<?php
require __DIR__ . '/../../includes/auth.php';
requireEditorOrAdmin();
require __DIR__ . '/../../includes/conexion.php';

$csrf = ensureCsrfToken();
$error = "";

// Si es admin, puede elegir dirección
$is_admin = ($_SESSION['rol'] === 'admin');

// Si es editor, su dirección viene de la sesión
$id_direccion_editor = (int)($_SESSION['id_direccion'] ?? 0);

// Bloqueo de dirección 4
if (isset($_SESSION['id_direccion']) && (int)$_SESSION['id_direccion'] === 4) {
    header("Location: /dgmoss/admin/panel.php");
    exit;
}

// Obtener direcciones (solo para admin)
$direcciones = [];
if ($is_admin) {
    $sql = "SELECT id_direccion, nombre_direccion 
            FROM direcciones 
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
        // Validar que no exista la categoría con el mismo nombre y dirección
        $check = $conexion->prepare("SELECT 1 FROM categorias WHERE nombre_categoria = ? AND id_direccion = ? LIMIT 1");
        $check->bind_param("si", $nombre_categoria, $id_direccion);
        $check->execute();
        $exists = $check->get_result()->num_rows > 0;
        $check->close();

        if ($exists) {
            $error = "Ya existe una categoría con ese nombre en la misma dirección.";
        } else {
            // Insertar nueva categoría
            $stmt = $conexion->prepare("INSERT INTO categorias (nombre_categoria, id_direccion) VALUES (?, ?)");
            $stmt->bind_param("si", $nombre_categoria, $id_direccion);
            $stmt->execute();
            $stmt->close();

            // Redirigir según el rol
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
    <title>Crear Categoría</title>
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
    <link rel="stylesheet" href="/dgmoss/assets/css/formulario-direcciones.css">
    <?php include(__DIR__ . '/../navbar_panel.php'); ?>
</head>
<body>
<div class="container mt-4">
    <div class="mb-3">
        <a class="btn btn-secondary btn-sm active" href="<?= $is_admin ? '/dgmoss/admin/categorias/index_admin.php' : '/dgmoss/admin/categorias/index_editor.php' ?>">
            <i class="bi bi-arrow-left"></i> Regresar
        </a>
    </div>

    <h2 class="text-center">Crear Nueva Categoría</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="form-container">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">

        <div class="form-group">
            <label for="nombre_categoria">Nombre de la categoría</label>
            <input type="text" name="nombre_categoria" id="nombre_categoria" class="form-control" required>
        </div>

        <?php if ($is_admin): ?>
            <div class="form-group">
                <label for="id_direccion">Dirección</label>
                <select name="id_direccion" id="id_direccion" class="form-control" required>
                    <option value="">-- Selecciona una dirección --</option>
                    <?php foreach ($direcciones as $dir): ?>
                        <option value="<?= (int)$dir['id_direccion'] ?>">
                            <?= htmlspecialchars($dir['nombre_direccion']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php else: ?>
            <input type="hidden" name="id_direccion" value="<?= $id_direccion_editor ?>">
        <?php endif; ?>

        <button class="btn btn-success mt-3">Guardar</button>
    </form>
</div>

<script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
</body>
</html>
