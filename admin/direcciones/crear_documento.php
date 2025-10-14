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
    $url_externa = trim($_POST['url_externa'] ?? '');
    $upload_dir = __DIR__ . '/../../uploads/';

    // Validaciones básicas
    if ($titulo === '' || $descripcion === '' || $estado === '' || $id_categoria === 0) {
        $err = "Título, descripción y categoría son obligatorios.";
    }

    // Validación: archivo o enlace (uno obligatorio, no ambos)
    $tiene_archivo = isset($_FILES['url']) && $_FILES['url']['error'] === 0;
    $tiene_enlace = $url_externa !== '';

    if (!$tiene_archivo && !$tiene_enlace) {
        $err = "Debes subir un archivo o ingresar un enlace.";
    } elseif ($tiene_archivo && $tiene_enlace) {
        $err = "Solo puedes elegir uno: archivo o enlace, no ambos.";
    }

    // Si no hay errores hasta aquí, continuamos
    if ($err === "") {
        // Determinar la URL final (archivo o enlace)
        if ($tiene_archivo) {
            $pdf_name = basename($_FILES['url']['name']);
            $pdf_path = $upload_dir . $pdf_name;

            if (move_uploaded_file($_FILES['url']['tmp_name'], $pdf_path)) {
                $url = '/dgmoss-project/uploads/' . $pdf_name;
            } else {
                $err = "Hubo un problema al subir el archivo.";
            }
        } elseif ($tiene_enlace) {
            $url = $url_externa;
        }

        // Imagen destacada
        $imagen_destacada = '';
        if ($err === "" && isset($_FILES['imagen_destacada']) && $_FILES['imagen_destacada']['error'] === 0) {
            $img_name = basename($_FILES['imagen_destacada']['name']);
            $img_path = $upload_dir . $img_name;

            if (move_uploaded_file($_FILES['imagen_destacada']['tmp_name'], $img_path)) {
                $imagen_destacada = '/dgmoss-project/uploads/' . $img_name;
            } else {
                $err = "Hubo un problema al subir la imagen destacada.";
            }
        }

        // 🔹 Validar límite de documentos destacados (solo si aplica)
        if ($err === "" && $destacado === '1' && $cfg['show_all_docs'] !== '1') {
            $sql = "SELECT COUNT(*) AS c FROM documentos WHERE id_direccion = ? AND destacado = '1'";
            $st = $conexion->prepare($sql);
            $st->bind_param("i", $id_direccion);
            $st->execute();
            $c = $st->get_result()->fetch_assoc()['c'] ?? 0;
            $st->close();

            if ((int)$c >= (int)$cfg['max_destacados']) {
                $err = "Máximo {$cfg['max_destacados']} documentos destacados en esta dirección.";
            }
        }

        // Guardar el documento si no hay errores
        if ($err === "") {
            $insertar = "INSERT INTO documentos (titulo, descripcion, url, estado, fecha_publicacion, actualizacion, 
                    id_direccion, id_categoria, destacado_home, destacado, imagen_destacada)
                    VALUES (?, ?, ?, ?, NOW(), NOW(), ?, ?, ?, ?, ?)";
            $st = $conexion->prepare($insertar);
            $st->bind_param(
                "ssssiisss",
                $titulo,
                $descripcion,
                $url,
                $estado,
                $id_direccion,
                $id_categoria,
                $home,
                $destacado,
                $imagen_destacada
            );
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
            <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
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
                <textarea name="descripcion" class="form-control" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label>Selecciona el archivo (PDF/DOC/XLS) o pega un enlace</label>
                <input type="file" name="url" class="form-control mb-2" accept=".pdf, .doc, .docx, .xls, .xlsx">
                <input type="url" name="url_externa" class="form-control" placeholder="https://ejemplo.com/documento" pattern="https?://.+" title="Debe comenzar con http:// o https://">
                <small class="text-muted">Solo uno de los dos es obligatorio (archivo o enlace).</small>
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

            <?php if ($id_direccion != 4): ?>
                <div class="form-group">
                    <label><input type="checkbox" name="destacado" value="1"> Destacado</label>
                    <small class="text-muted d-block">Máximo <?= (int)$cfg['max_destacados'] ?> destacados.</small>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label><input type="checkbox" name="destacado_home" value="1"> Mostrar en Home</label>
                <small class="text-muted d-block">Solo 1 documento por dirección.</small>
            </div>

            <button class="btn btn-success">Guardar</button>
            <a href="/dgmoss-project/admin/direcciones/direcciones.php?id_direccion=<?= $id_direccion ?>" class="btn btn-secondary active">Cancelar</a>
        </form>
    </div>

    <script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
</body>
</html>
