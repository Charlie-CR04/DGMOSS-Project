<?php
    require __DIR__ . '/../../includes/auth.php';
    requireAdmin();
    require __DIR__ . '/../../includes/conexion.php';
    require __DIR__ . '/../../includes/direcciones_config.php';

    $csrf = ensureCsrfToken();

    //Direcciones para el selector
    $dirs = $conexion->query("SELECT id_direccion, nombre_direccion FROM direcciones ORDER BY id_direccion")->fetch_all(MYSQLI_ASSOC);
    
    $id_direccion = (int)($_GET['id_direccion'] ?? ($dirs[0]['id_direccion'] ?? 0));
    $cfg = getConfigDireccion($conexion, $id_direccion);

    $sql = "SELECT d.id_documento, d.titulo, d.descripcion, d.url, d.estado, d.destacado, d.destacado_home,
            c.nombre_categoria AS categoria
            FROM documentos d
            LEFT JOIN categorias c ON c.id_categoria = d.id_categoria
            WHERE d.id_direccion = ?
            ORDER BY d.actualizacion DESC, d.fecha_publicacion DESC";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_direccion);
    $stmt->execute();
    $docs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de direcciones</title>
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
    <script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
    <?php include(__DIR__ . '/../../admin/navbar_panel.php');?>
</head>
<body>
    <div class="container mt-4">
        <h3 class="mb-3 ">Documentos - <?= htmlspecialchars($dirs[array_search($id_direccion, array_column($dirs,'$id_direccion'))]['nombre_direccion'] ?? '') ?></h3>

        <form class="row g-2" method="get">
            <div class="col-auto">
                <select name="id_direccion" class="form-control">
                    <?php foreach($dirs as $d): ?>
                        <option value="<?= (int)$d['id_direccion'] ?>" <?= $d['id_direccion']===$id_direccion ? 'selected':'' ?>>
                            <?= htmlspecialchars($d['nombre_direccion']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary">Cambiar</button>
            </div>
        </form>
    </div>
</body>
</html>