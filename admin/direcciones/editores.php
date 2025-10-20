<?php
require __DIR__ . '/../../includes/auth.php';
requireEditorOrAdmin(); // Solo admin o editor
require __DIR__ . '/../../includes/conexion.php';
require __DIR__ . '/../../includes/direcciones_config.php';

// Verificar rol y obtener dirección
$isAdmin = (($_SESSION['rol'] ?? '') === 'admin');
if($isAdmin){
    header('Location: /dgmoss/admin/panel.php');
    exit;
}

// Dirección del editor
$id_direccion = (int)($_SESSION['id_direccion'] ?? 0);
$cfg = getConfigDireccion($conexion, $id_direccion);
$csrf = ensureCsrfToken();

// Obtener documentos de la dirección del editor
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

// Total documentos para paginación
$total_docs_sql = "SELECT COUNT(*) as total FROM documentos WHERE id_direccion = ?";
$stmt_total = $conexion->prepare($total_docs_sql);
$stmt_total->bind_param("i", $id_direccion);
$stmt_total->execute();
$total_docs_result = $stmt_total->get_result()->fetch_assoc();
$total_docs = $total_docs_result['total'];
$stmt_total->close();
$total_pages = ceil($total_docs / $limit);

// Nombre de la dirección
$direccion_name = $conexion->query("SELECT nombre_direccion FROM direcciones WHERE id_direccion = $id_direccion")
    ->fetch_assoc()['nombre_direccion'] ?? 'Desconocida';
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel del Editor</title>
<link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
<link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
<link rel="stylesheet" href="/dgmoss/assets/css/formulario-direcciones.css">
<?php include(__DIR__ . '/../../admin/navbar_panel.php'); ?>
</head>
<body>
<div class="container mt-5">

    <h2 class="text-center mb-4">Panel de Documentos - <?= htmlspecialchars($direccion_name) ?></h2>

    <?php if($cfg['permite_docs'] === '1'): ?>
        <a href="/dgmoss/admin/direcciones/crear_documento.php?id_direccion=<?= $id_direccion ?>" class="btn btn-danger btn-sm mb-3 active">
            <i class="bi bi-file-earmark-plus-fill"></i> Crear documento
        </a>
    <?php else: ?>
        <div class="alert alert-warning">No tienes permisos para crear documentos en esta dirección.</div>
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
                        <td class="text-center"><?= $doc['destacado_home'] === '1' ? 'Si' : 'No' ?></td>
                        <td class="text-center"><?= $doc['destacado'] === '1' ? 'Si' : 'No' ?></td>
                        <td class="text-center">
                            <a class="btn btn-danger btn-sm mb-1 active" 
                               href="/dgmoss/admin/direcciones/editar_documento.php?id_documento=<?= $doc['id_documento'] ?>&id_direccion=<?= $id_direccion ?>">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="/dgmoss/admin/direcciones/eliminar_documento.php" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar documento?');">
                                <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                                <input type="hidden" name="id_documento" value="<?= (int)$doc['id_documento'] ?>">
                                <button class="btn btn-primary btn-sm active">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
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
        <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Anterior">&laquo;</a>
        </li>
        <?php for($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
        <li class="page-item <?= $page == $total_pages || $total_docs == 0 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Siguiente">&raquo;</a>
        </li>
    </ul>

</div>

<script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
<script src="/dgmoss/assets/js/formulario.js"></script>
</body>
</html>
