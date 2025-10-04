<?php
require __DIR__ . '/../../includes/auth.php';
requireAdmin();
require __DIR__ . '/../../includes/conexion.php';
require __DIR__ . '/../../includes/direcciones_config.php';
require __DIR__ . '/../../includes/categorias.php';

$id_documento = (int)($_GET['id_documento'] ?? 0);
$st = $conexion->prepare("SELECT * FROM documentos WHERE id_documento=?");
$st->bind_param("i", $id_documento);
$st->execute();
$doc = $st->get_result()->fetch_assoc();
$st->close();

if(!$doc){
    header('Location: /dgmoss-project/admin/panel.php');
    exit;
}

$id_direccion = (int)$doc['id_direccion'];
$cfg = getConfigDireccion($conexion, $id_direccion);
$cats = getCategorias($conexion, $id_direccion);

// CSRF
$csrf = ensureCsrfToken();
$err = "";

// Procesamiento del formulario
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    verifyCsrfOrDie();

    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $estado = $_POST['estado'] ?? 'Activo';
    $id_categoria = (int)($_POST['id_categoria'] ?? 0);
    $destacado = isset($_POST['destacado']) ? '1' : '0';
    $home = isset($_POST['destacado_home']) ? '1' : '0';

    // Lógica para manejar el campo "Mostrar en Home"
    if($home === '1'){
        $st = $conexion->prepare("UPDATE documentos SET destacado_home = '0' WHERE id_direccion =? AND id_documento<>?");
        $st->bind_param("ii", $id_direccion, $id_documento);
        $st->execute();
        $st->close();
    }

    // Límite de documentos "Destacados"
    if($destacado === '1' && $cfg['show_all_docs'] !== '1'){
        $sql = "SELECT COUNT(*) c FROM documentos WHERE id_direccion=? AND destacado = '1' AND id_documento<>?";
        $st = $conexion->prepare($sql);
        $st->bind_param("ii", $id_direccion, $id_documento);
        $st->execute();
        $c = $st->get_result()->fetch_assoc()['c'] ?? 0;
        $st->close();
        if((int)$c >= (int)$cfg['max_destacados']){
            $err = "Máximo {$cfg['max_destacados']} documentos destacados en esta dirección";
        }
    }

    //Guardar archivos subidos
    $upload_dir = __DIR__ . '/../../uploads/';

    // Ruta del archivo PDF
    if(isset($_FILES['url']) && $_FILES['url']['error'] === 0){
        $pdf_name = basename($_FILES['url']['name']);
        $pdf_path = $upload_dir . $pdf_name;

        if(move_uploaded_file($_FILES['url']['tmp_name'], $pdf_path)){
            $url = '/dgmoss-project/uploads/' . $pdf_name;
        } else {
            $err = "Hubo un problema al subir el archivo PDF.";
        }

    } else {
        $url = $doc['url']; // Si no hay archivo nuevo, mantenemos la URL existente
    }

    // Ruta de imagen destacada
    if (isset($_FILES['imagen_destacada']) && $_FILES['imagen_destacada']['error'] == 0) {
        $img_name = basename($_FILES['imagen_destacada']['name']);
        $img_path = $upload_dir . $img_name;

        if(move_uploaded_file($_FILES['imagen_destacada']['tmp_name'], $img_path)){
            $imagen_destacada = '/dgmoss-project/uploads/' . $img_name;
        } else {
            $err = "Hubo un problema al subir la imagen destacada";
        }

    } else {
        $imagen_destacada = $doc['imagen_destacada']; // Si no hay imagen nueva, mantenemos la URL existente
    }

    // Actualizar el documento en la base de datos
    if($err === ""){
        $sql = "UPDATE documentos 
                SET titulo=?, descripcion=?, url=?, estado=?, actualizacion=NOW(),
                id_categoria=?, destacado_home=?, destacado=?, imagen_destacada=?
                WHERE id_documento=?";
        $st = $conexion->prepare($sql);
        $st->bind_param("ssssisssi", $titulo, $descripcion, $url, $estado, $id_categoria, $home, $destacado, $imagen_destacada, $id_documento); 
        $st->execute();
        $st->close();
        header('Location: /dgmoss-project/admin/direcciones/direcciones.php?id_direccion='. $id_direccion);
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
    <link rel="stylesheet" href="/dgmoss-project/assets/css/crear-documentos.css">
</head>
<body>
    <?php include(__DIR__ . '/../navbar_panel.php'); ?>
    <div class="container mt-4">
        <h2 class="text-center">Editar documento</h2>

        <?php if($err):?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($err) ?>
            </div>
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
                <label>Archivo (PDF/Enlace)</label>
                <?php if (!empty($doc['url'])): ?>
                    <div>
                        <!-- Mostrar el PDF en un iframe -->
                        <iframe src="<?= htmlspecialchars($doc['url']) ?>" width="100%" height="400px"></iframe>
                    </div>
                <?php endif; ?>
                <small class="d-block mb-2">Archivo actual: 
                    <?php if (!empty($doc['url'])): ?>
                        <a href="<?= htmlspecialchars($doc['url']) ?>" target="_blank"><?= basename($doc['url']) ?></a>
                    <?php endif; ?>
                </small>
                <input type="file" name="url" class="form-control" accept=".pdf, .doc, .docx, .xls, .xlsx">
            </div>

            <div class="form-group">
                <label>Imagen destacada (URL)</label>
                <?php if (!empty($doc['imagen_destacada'])): ?>
                    <div style="text-align: center; margin-top: 10px;">
                        <small class="d-block mb-2">Imagen actual:</small>
                        <img src="<?= htmlspecialchars($doc['imagen_destacada']) ?>" alt="Imagen destacada" style="max-width: 600px; margin-top: 10px; margin-bottom: 20px;">
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
                    <?php foreach($cats as $c): ?>
                        <option value="<?= (int)$c['id_categoria'] ?>" <?= $c['id_categoria'] == $doc['id_categoria'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nombre_categoria']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if($cfg['show_all_docs'] !== '1'): ?>
                <div class="form-group">
                    <label><input type="checkbox" name="destacado" value="1" <?= $doc['destacado'] === '1' ? 'checked' : '' ?>> Destacado</label>
                    <small class="text-muted d-block">Máximo <?= (int)$cfg['max_destacados'] ?> documentos destacados.</small>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label><input type="checkbox" name="destacado_home" value="1" <?= $doc['destacado_home'] === '1' ? 'checked' : '' ?>> Mostrar en home</label>
            </div>

            <button class="btn btn-success">Guardar cambios</button>
            <a class="btn btn-secondary active" href="/dgmoss-project/admin/direcciones/direcciones.php?id_direccion=<?= $doc['id_direccion'] ?>">Cancelar</a>
        </form>
    </div>

    <script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
</body>
</html>
