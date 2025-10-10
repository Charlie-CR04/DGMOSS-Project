<?php
    require __DIR__ . '/../../includes/auth.php';
    requireEditorOrAdmin(); // Verifica que el usuario sea admin o editor
    require __DIR__ . '/../../includes/conexion.php';

    $csrf = ensureCsrfToken();
    $error = "";

    // Procesar el formulario de creación de categoría
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verifyCsrfOrDie();

        $nombre_categoria = trim($_POST['nombre_categoria'] ?? '');
        $id_direccion = (int)($_POST['id_direccion'] ?? 0); // Dirección seleccionada por el usuario

        if ($nombre_categoria === '') {
            $error = "El nombre de la categoría es obligatorio";
        } else {
            // Verificar si la categoría ya existe
            $stmt = $conexion->prepare("SELECT 1 FROM categorias WHERE nombre_categoria = ?");
            $stmt->bind_param("s", $nombre_categoria);
            $stmt->execute();
            $exists = $stmt->get_result()->num_rows > 0;
            $stmt->close();

            if ($exists) {
                $error = "Ya existe una categoría con ese nombre";
            } else {
                // Insertar la nueva categoría en la base de datos
                $stmt = $conexion->prepare("INSERT INTO categorias (nombre_categoria) VALUES (?)");
                $stmt->bind_param("s", $nombre_categoria);
                $stmt->execute();
                $stmt->close();

                // Después de crear la categoría, podemos asociarla a un documento (esto será realizado en otro paso)
                // Redirigir al listado de categorías después de crear una nueva
                header('Location: /dgmoss-project/admin/categorias/index_admin.php');
                exit;
            }
        }
    }

    // Obtener las direcciones para el selector (solo administradores pueden elegir cualquier dirección)
    $direcciones = $conexion->query("SELECT id_direccion, nombre_direccion FROM direcciones ORDER BY nombre_direccion")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Categoría</title>
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
    <link rel="stylesheet" href="/dgmoss-project/assets/css/formulario-direcciones.css">
    <?php include(__DIR__ . '/../../admin/navbar_panel.php'); ?>
</head>
<body>
    <div class="container">
        <div class="mb-3">
            <a class="btn btn-secondary btn-sm active" href="/dgmoss-project/admin/categorias/index_admin.php">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>

        <h3 class="mb-3 text-center">Crear Categoría</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">

            <div class="form-group">
                <label for="nombre_categoria">Nombre de la Categoría</label>
                <input type="text" name="nombre_categoria" id="nombre_categoria" class="form-control" required>
            </div>

            <button class="btn btn-success mt-3" type="submit">Crear Categoría</button>
        </form>
    </div>

    <script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
</body>
</html>
