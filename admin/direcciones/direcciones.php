<?php
    require __DIR__ . '/../../includes/auth.php';
    requireAdmin();
    require __DIR__ . '/../../includes/conexion.php';
    require __DIR__ . '/../../includes/direcciones_config.php';

    $csrf = ensureCsrfToken();

    //Direcciones para el selector
    $direcciones = $conexion->query("SELECT id_direccion, nombre_direccion 
                              FROM direcciones 
                              ORDER BY id_direccion")->fetch_all(MYSQLI_ASSOC);
                              
    $direcciones_map = array_column($direcciones, 'nombre_direccion', 'id_direccion');
    
    $id_direccion = (int)($_GET['id_direccion'] ?? ($direcciones[0]['id_direccion'] ?? 0));
    $cfg = getConfigDireccion($conexion, $id_direccion);

    $limit = 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $sql = "SELECT d.id_documento, d.titulo, d.descripcion, d.url, d.estado, d.destacado, d.destacado_home,
            c.nombre_categoria AS categoria
            FROM documentos d
            LEFT JOIN categorias c ON c.id_categoria = d.id_categoria
            WHERE d.id_direccion = ?
            ORDER BY d.titulo
            LIMIT ? OFFSET ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iii", $id_direccion, $limit, $offset);
    $stmt->execute();
    $documentos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    //Obtener el total de documentos para obtener el número de páginas
    $total_docs_sql = "SELECT COUNT(*) as total FROM documentos WHERE id_direccion = ?";
    $stmt_total = $conexion->prepare($total_docs_sql);
    $stmt_total->bind_param("i", $id_direccion);
    $stmt_total->execute();
    $total_docs_result = $stmt_total->get_result()->fetch_assoc();
    $total_docs = $total_docs_result['total'];
    $stmt_total->close();

    //Calcular el número de páginas
    $total_pages = ceil($total_docs/$limit);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de direcciones</title>
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
    <link rel="stylesheet" href="/dgmoss-project/assets/css/formulario-direcciones.css">
    <?php include(__DIR__ . '/../../admin/navbar_panel.php');?>
</head>
<body>
    <div class="container">

    <div class="mb-3">
        <a class="btn btn-secondary btn-sm active" href="/dgmoss-project/admin/panel.php">
            <i class="bi bi-arrow-left"></i> Regresar
        </a>
    </div>

    <h3 class="mb-3 text-center">Documentos - <?= htmlspecialchars($direcciones_map[$id_direccion] ?? '') ?></h3>

    <form class="form-container" method="get">
        <div class="col-auto">
            <select name="id_direccion" class="form-control" id="direccion-select">
                <option class="text-center" value="" disabled selected>--- Selecciona una opción --- </option>
                <?php foreach($direcciones as $direccion): ?>
                    <option value="<?= (int)$direccion['id_direccion'] ?>" <?= $direccion['id_direccion'] === $id_direccion ? 'selected' : '' ?>>
                        <?= htmlspecialchars($direccion['nombre_direccion']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-auto">
            <button id="search-button" class="btn btn-secondary btn-sm active" disabled>
                <i class="bi bi-search"></i>
            </button>
        </div>

        <div>
            <?php if ($cfg['permite_docs'] === '1'): ?>
                <a href="/dgmoss-project/admin/direcciones/crear_documento.php?id_direccion=<?= $id_direccion ?>" class="btn btn-danger btn-sm active">
                    <i class="bi bi-file-earmark-plus-fill"></i> Crear documento
                </a>
            <?php endif; ?>
        </div>
    </form>

        <?php if($cfg['permite_docs'] === '0'): ?>
            <div class="alert alert-warning" role="alert">
                Esta dirección no permite documentos
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">Título</th>
                        <th class="text-center">Categoría</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Home</th>
                        <th class="text-center">Destacado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($documentos as $doc): ?>
                        <tr>
                            <td><?= htmlspecialchars($doc['titulo']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($doc['categoria']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($doc['estado']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($doc['destacado_home'] === '1' ? 'Si':'No') ?></td>
                            <td class="text-center"><?= htmlspecialchars($doc['destacado'] === '1' ? 'Si':'No') ?></td>
                            <td class="text-center">
                                <a class="btn btn-danger btn-sm active mb-3" 
                                    href="/dgmoss-project/admin/direcciones/editar_documento.php?id_documento=<?= (int)$doc['id_documento'] ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="/dgmoss-project/admin/direcciones/eliminar_documento.php" method="post" class="d-inline"
                                    onsubmit="return confirm('¿Eliminar documento?');">
                                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                                    <input type="hidden" name="id_documento" value="<?= (int)$doc['id_documento'] ?>">
                                    <button class="btn btn-primary btn-sm active"><i class="bi bi-trash-fill"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($documentos)): ?>
                        <tr><td colspan="6" class="text-center">Sin documentos</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <ul class="pagination">
            <!-- Botón de "Anterior" -->
            <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?id_direccion=<?= $id_direccion ?>&page=<?= $page - 1 ?>" aria-label="Anterior">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <!-- Páginas -->
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?id_direccion=<?= $id_direccion ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <!-- Botón de "Siguiente" -->
            <li class="page-item <?= $page == $total_pages || $total_docs == 0 ? 'disabled' : '' ?>">
                <a class="page-link" href="?id_direccion=<?= $id_direccion ?>&page=<?= $page + 1 ?>" aria-label="Siguiente">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </div>

    <script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
    <script src="/dgmoss-project/assets/js/formulario.js"></script>
</body>
</html>