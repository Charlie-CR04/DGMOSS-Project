<?php
require __DIR__ . '/../../includes/auth.php';
requireEditorOrAdmin();
require __DIR__ . '/../../includes/conexion.php';
require __DIR__ . '/../../includes/direcciones_config.php';
require __DIR__ . '/../../includes/categorias.php';

$id_documento = (int)($_GET['id_documento'] ?? 0);
$st = $conexion->prepare("SELECT * FROM documentos WHERE id_documento=?");
$st->bind_param("i", $id_documento);
$st->execute();
$doc = $st->get_result()->fetch_assoc();
$st->close();

if (!$doc) {
    header('Location: /dgmoss/admin/panel.php');
    exit;
}

$id_direccion = (int)$doc['id_direccion'];
$cfg = getConfigDireccion($conexion, $id_direccion);
$cats = getCategorias($conexion, $id_direccion);

// CSRF
$csrf = ensureCsrfToken();
$err = "";

// Procesamiento del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfOrDie();

    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $estado = $_POST['estado'] ?? 'Activo';
    $id_categoria = (int)($_POST['id_categoria'] ?? 0);
    $destacado = isset($_POST['destacado']) ? '1' : '0';
    $home = isset($_POST['destacado_home']) ? '1' : '0';
    $url_externa = trim($_POST['url_externa'] ?? '');
    $upload_dir = __DIR__ . '/../../uploads/';

    $tiene_archivo = isset($_FILES['url']) && $_FILES['url']['error'] === 0;
    $tiene_enlace = $url_externa !== '';

    // Validaciones
    if ($titulo === '' || $descripcion === '' || $estado === '' || $id_categoria === 0) {
        $err = "Título, descripción y categoría son obligatorios.";
    } elseif (!$tiene_archivo && !$tiene_enlace && empty($doc['url'])) {
        $err = "Debes subir un archivo o ingresar un enlace.";
    } elseif ($tiene_archivo && $tiene_enlace) {
        $err = "Solo puedes elegir uno: archivo o enlace, no ambos.";
    }

    // Lógica para manejar el campo "Mostrar en Home"
    if ($err === "" && $home === '1') {
        $st = $conexion->prepare("UPDATE documentos SET destacado_home = '0' WHERE id_direccion = ? AND id_documento <> ?");
        $st->bind_param("ii", $id_direccion, $id_documento);
        $st->execute();
        $st->close();
    }

    // Límite de documentos destacados
    if ($err === "" && $destacado === '1' && $cfg['show_all_docs'] !== '1') {
        $sql = "SELECT COUNT(*) c FROM documentos WHERE id_direccion=? AND destacado = '1' AND id_documento<>?";
        $st = $conexion->prepare($sql);
        $st->bind_param("ii", $id_direccion, $id_documento);
        $st->execute();
        $c = $st->get_result()->fetch_assoc()['c'] ?? 0;
        $st->close();
        if ((int)$c >= (int)$cfg['max_destacados']) {
            $err = "Máximo {$cfg['max_destacados']} documentos destacados en esta dirección.";
        }
    }

    // Procesar archivo o enlace
    if ($err === "") {
        if ($tiene_archivo) {
            $pdf_name = basename($_FILES['url']['name']);
            $pdf_path = $upload_dir . $pdf_name;

            if (move_uploaded_file($_FILES['url']['tmp_name'], $pdf_path)) {
                $url = '/dgmoss/uploads/' . $pdf_name;
            } else {
                $err = "Hubo un problema al subir el archivo.";
            }
        } elseif ($tiene_enlace) {
            $url = $url_externa;
        } else {
            $url = $doc['url']; // Mantiene la URL anterior si no se cambió
        }
    }

    // Imagen destacada
    if ($err === "") {
        if (isset($_FILES['imagen_destacada']) && $_FILES['imagen_destacada']['error'] == 0) {
            $img_name = basename($_FILES['imagen_destacada']['name']);
            $img_path = $upload_dir . $img_name;

            if (move_uploaded_file($_FILES['imagen_destacada']['tmp_name'], $img_path)) {
                $imagen_destacada = '/dgmoss/uploads/' . $img_name;
            } else {
                $err = "Hubo un problema al subir la imagen destacada.";
            }
        } else {
            $imagen_destacada = $doc['imagen_destacada'];
        }
    }

    // Guardar cambios si no hay errores
    if ($err === "") {
        $sql = "UPDATE documentos 
                SET titulo=?, descripcion=?, url=?, estado=?, actualizacion=NOW(),
                id_categoria=?, destacado_home=?, destacado=?, imagen_destacada=?
                WHERE id_documento=?";
        $st = $conexion->prepare($sql);
        $st->bind_param("ssssisssi", $titulo, $descripcion, $url, $estado, $id_categoria, $home, $destacado, $imagen_destacada, $id_documento);
        $st->execute();
        $st->close();

        header('Location: /dgmoss/admin/direcciones/direcciones.php?id_direccion=' . $id_direccion);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar documento</title>
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
    <link rel="stylesheet" href="/dgmoss/assets/css/crear-documentos.css">
</head>
<body>
    <?php include(__DIR__ . '/../navbar_panel.php'); ?>
    <div class="container mt-4">
        <h2 class="text-center">Editar documento</h2>

        <?php if ($err): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
        <?php endif; ?>

        <div class="alert alert-info">
            Última modificación: <?= date('d/m/Y H:i:s', strtotime($doc['actualizacion'])) ?>
        </div>

        <form method="post" action="editar_documento.php?id_documento=<?= $id_documento ?>" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">

            <div class="form-group">
                <label>Título</label>
                <input name="titulo" class="form-control" value="<?= htmlspecialchars($doc['titulo']) ?>" required>
            </div>

            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3"><?= htmlspecialchars($doc['descripcion']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Archivo (PDF/DOC/XLS) o Enlace externo</label>

                <?php if (!empty($doc['url'])): ?>
                    <div class="mb-2">
                        <small class="d-block">Documento o enlace actual:</small>
                        <a href="<?= htmlspecialchars($doc['url']) ?>" target="_blank">
                            <?= htmlspecialchars($doc['url']) ?>
                        </a>
                    </div>
                <?php endif; ?>

                <input type="file" name="url" class="form-control mb-2" accept=".pdf, .doc, .docx, .xls, .xlsx">
                <input type="url" name="url_externa" class="form-control" placeholder="https://ejemplo.com/documento" pattern="https?://.+" title="Debe comenzar con http:// o https://">
                <small class="text-muted">Solo uno de los dos es obligatorio (archivo o enlace).</small>
            </div>

            <div class="form-group">
                <label>Imagen destacada</label>
                <?php if (!empty($doc['imagen_destacada'])): ?>
                    <div style="text-align:center; margin-top:10px;">
                        <small class="d-block mb-2">Imagen actual:</small>
                        <img src="<?= htmlspecialchars($doc['imagen_destacada']) ?>" alt="Imagen destacada" style="max-width:600px; margin-bottom:20px;">
                    </div>
                <?php endif; ?>
                <input name="imagen_destacada" class="form-control" type="file" accept=".jpg, .jpeg, .png">
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="estado" class="form-control">
                    <option <?= $doc['estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
                    <option <?= $doc['estado'] === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>

            <div class="form-group">
                <label>Categoría</label>
                <select name="id_categoria" class="form-control">
                    <?php foreach ($cats as $c): ?>
                        <option value="<?= (int)$c['id_categoria'] ?>" <?= $c['id_categoria'] == $doc['id_categoria'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nombre_categoria']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if ($id_direccion != 4 && $cfg['show_all_docs'] !== '1'): ?>
                <div class="form-group">
                    <label><input type="checkbox" name="destacado" value="1" <?= $doc['destacado'] === '1' ? 'checked' : '' ?>> Destacado</label>
                    <small class="text-muted d-block">Máximo <?= (int)$cfg['max_destacados'] ?> documentos destacados.</small>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label><input type="checkbox" name="destacado_home" value="1" <?= $doc['destacado_home'] === '1' ? 'checked' : '' ?>> Mostrar en home</label>
            </div>

            <button class="btn btn-success">Guardar cambios</button>
            <a class="btn btn-secondary active" href="/dgmoss/admin/direcciones/direcciones.php?id_direccion=<?= $doc['id_direccion'] ?>">Cancelar</a>
        </form>
    </div>

    <script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
</body>
</html>
