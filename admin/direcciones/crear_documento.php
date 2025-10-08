<?php
require __DIR__ . '/../../includes/auth.php';
requireEditorOrAdmin();
require __DIR__ . '/../../includes/conexion.php';
require __DIR__ . '/../../includes/direcciones_config.php';
require __DIR__ . '/../../includes/categorias.php';


// ID de dirección
$id_direccion = (int)($_GET['id_direccion'] ?? 0);
$cfg = getConfigDireccion($conexion, $id_direccion);

// Verificar si la dirección permite documentos
if ($cfg['permite_docs'] !== '1') {
    header('Location: /dgmoss-project/admin/direcciones/direcciones.php?id_direccion=' . $id_direccion);
    exit;
}

// CSRF
$csrf = ensureCsrfToken();

// Categorías
$cats = getCategorias($conexion, $id_direccion);

$err = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfOrDie();

    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion']);
    $estado = $_POST['estado'] ?? 'Activo';
    $id_categoria = (int)($_POST['id_categoria'] ?? 0);
    $destacado = isset($_POST['destacado']) ? '1' : '0';
    $home = isset($_POST['destacado_home']) ? '1' : '0';
    $img = trim($_POST['imagen_destacada'] ?? '');

    // Validación básica
    if ($titulo === '' || $descripcion === '' || $estado === '' || $id_categoria === 0) {
        $err = "Título, descripción y categoría son obligatorios.";
    }

    // Validación de archivo
    if ($_FILES['url']['error'] !== 0) {
        $err = "El archivo PDF es obligatorio.";
    } elseif ($_FILES['imagen_destacada']['error'] !== 0) {
        $err = "La imagen destacada es opcional, pero si la subes, debe ser válida.";
    }

    // Guardar los archivos
    if ($err === "") {
        // Ruta para los archivos
        $upload_dir = __DIR__ . '/../../uploads/';
        $pdf_name = basename($_FILES['url']['name']);
        $pdf_path = $upload_dir . $pdf_name;

        // Mover el archivo PDF al directorio adecuado
        if (move_uploaded_file($_FILES['url']['tmp_name'], $pdf_path)) {
            $url = '/dgmoss-project/uploads/' . $pdf_name;
        } else {
            $err = "Hubo un problema al subir el archivo PDF.";
        }

        // Imagen destacada
        $imagen_destacada = '';
        if (isset($_FILES['imagen_destacada']) && $_FILES['imagen_destacada']['error'] === 0) {
            $img_name = basename($_FILES['imagen_destacada']['name']);
            $img_path = $upload_dir . $img_name;

            // Mover la imagen destacada al directorio adecuado
            if (move_uploaded_file($_FILES['imagen_destacada']['tmp_name'], $img_path)) {
                $imagen_destacada = '/dgmoss-project/uploads/' . $img_name;
            } else {
                $err = "Hubo un problema al subir la imagen destacada.";
            }
        }

        // Si no hay errores, guardar el documento en la base de datos
        if ($err === "") {
            $insertar = "INSERT INTO documentos (titulo, descripcion, url, estado, fecha_publicacion, actualizacion, 
                    id_direccion, id_categoria, destacado_home, destacado, imagen_destacada)
                    VALUES (?, ?, ?, ?, NOW(), NOW(), ?, ?, ?, ?, ?)";
            $st = $conexion->prepare($insertar);
            $st->bind_param("ssssiisss", $titulo, $descripcion, $url, $estado, $id_direccion, $id_categoria, $home, $destacado, $imagen_destacada);
            $st->execute();
            $st->close();
            header('Location: /dgmoss-project/admin/direcciones/direcciones.php?id_direccion=' . $id_direccion);
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
    <title>Crear documento</title>
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
    <link rel="stylesheet" href="/dgmoss-project/assets/css/crear-documentos.css">
</head>
<body>
    <?php include(__DIR__ . '/../navbar_panel.php'); ?>
    <div class="container mt-4">
        <h2 class="text-center">Nuevo documento</h2>

        <?php if ($err): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($err) ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
            <input type="hidden" name="id_direccion" value="<?= (int)$id_direccion ?>">

            <div class="form-group">
                <label>Título</label>
                <input name="titulo" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Selecciona el archivo (PDF/Enlace)</label>
                <input type="file" name="url" class="form-control" accept=".pdf, .doc, .docx, .xls,.xlsx" required>
            </div>
            <div class="form-group">
                <label>Imagen destacada</label>
                <input type="file" name="imagen_destacada" class="form-control" accept=".jpg, .jpeg, .png">
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="estado" class="form-control">
                    <option>Activo</option>
                    <option>Inactivo</option>
                </select>
            </div>
            <div class="form-group">
                <label>Categoría</label>
                <select name="id_categoria" class="form-control">
                    <?php foreach ($cats as $c): ?>
                        <option value="<?= (int)$c['id_categoria'] ?>"><?= htmlspecialchars($c['nombre_categoria']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label><input type="checkbox" name="destacado" value="1">Destacado</label>
                <small class="text-muted d-block">Máximo <?= (int)$cfg['max_destacados'] ?> destacados.</small>
            </div>

            <div class="form-group">
                <label><input type="checkbox" name="destacado_home" value="1">Mostrar en Home</label>
                <small class="text-muted d-block">Solo 1 documento por dirección.</small>
            </div>

            <button class="btn btn-success">Guardar</button>
            <a href="/dgmoss-project/admin/direcciones/direcciones.php?id_direccion=<?= $id_direccion ?>" class="btn btn-secondary active">Cancelar</a>
        </form>
    </div>

    <script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
</body>
</html>
