<?php
    require __DIR__ . '/../../includes/auth.php';
    requireAdmin();
    require __DIR__ . '/../../includes/conexion.php';
    require __DIR__ . '/../../includes/direcciones_config.php';
    require __DIR__ . '/../../includes/categorias.php';

    //ID de dirección
    $id_direccion = (int)($_GET['id_direccion'] ?? 0);
    $cfg = getConfigDireccion($conexion, $id_direccion);

    //Verificar si la dirección permite documentos
    if($cfg['permite_docs'] !== '1'){
        header('Location: /dgmoss-project/admin/direcciones/direcciones.php?id_direccion='.$id_direccion);
        exit;
    }

    // CSRF
    $csrf = ensureCsrfToken();

    // Categorías
    $cats = getCategorias($conexion, $id_direccion);

    $err = "";
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        verifyCsrfOrDie();

        $id_direccion = (int)($_POST['id_direccion'] ?? 0);
        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion']);
        $url = trim($_POST['url']);
        $estado = $_POST['estado'] ?? 'Activo';
        $id_categoria = (int)($_POST['id_categoria'] ?? 0);
        $destacado = isset($_POST['destacado']) ? '1' : '0';
        $home = isset($_POST['destacado_home']) ? '1' : '0';
        $img = trim($_POST['imagen_destacada'] ?? '');

        //Validación básica
        if($titulo === '' || $url === ''){
            $err = "Título y URL son obligatorios";
        } elseif(!filter_var($url, FILTER_SANITIZE_URL)) {
            $err = "La URL no es válida";
        } else{
            getConfigDireccion($conexion, $id_direccion);

            //Home unico por direción
            if($home === '1') {
                $st = $conexion->prepare("UPDATE documentos
                SET destacado_home = '0'
                WHERE id_direccion=?");
                $st->bind_param("i", $id_direccion);
                $st->execute();
                $st->close();
            }

            //Límite de destacados si show_all_docs !== 1
            if($destacado === '1' && $cfg['show_all_docs'] !== '1'){
                $st = $conexion->prepare("SELECT COUNT(*) c FROM documentos WHERE id_direccion=? AND destacado='1'");
                $st->bind_param("i", $id_direccion);
                $st->execute();
                $c = $st->get_result()->fetch_assoc()['c'] ?? 0;
                $st->close();
                if((int)$c >= (int)$cfg['max_destacados']) {
                    $err = "Máximo {$cfg['max_destacados']} documentos destacados en esta dirección";
                }
            }

            //Insertar si no hay errores
            if($err === "") {
                $insertar = "INSERT INTO documentos(titulo, descripcion, url, estado, fecha_publicacion, actualizacion,
                                        id_direccion, id_categoria, destacado_home, destacado, imagen_destacada)
                                        VALUES (?,?,?,?, NOW(), NOW(), ?,?,?,?,?)";
                $st = $conexion->prepare($insertar);
                $st->bind_param("ssssiisss", $titulo, $descripcion, $url, $estado, $id_direccion, $id_categoria, $home, $destacado, $img);
                $st->execute();
                $st->close();

                header('Location: /dgmoss-project/admin/direcciones/direcciones.php?id_direccion='.$id_direccion);
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
    <link rel="stylesheet" href="/dgmoss-project/assets/css/crear-formulario.css">
</head>
<body>
    <?php include(__DIR__ . '/../navbar_panel.php'); ?>
    <div class="container mt-4">
        <h2 class="text-center">Nuevo documento</h2>

        <?php if($err): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($err) ?>
            </div>
        <?php endif; ?>

        <form class="" method="post">
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
                    <?php foreach($cats as $c): ?>
                        <option value="<?= (int)$c['id_categoria'] ?>"><?= htmlspecialchars($c['nombre_categoria']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if($cfg['show_all_docs'] !== '1'): ?>
                <div class="form-group">
                    <label><input type="checkbox" name="destacado" value="1">Destacado</label>
                    <small class="text-muted d-block">Máximo <?= (int)$cfg['max_destacados'] ?> destacados.</small>
                </div>
            <?php endif; ?>

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