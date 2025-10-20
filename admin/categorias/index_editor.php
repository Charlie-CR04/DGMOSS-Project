<?php
require __DIR__ . '/../../includes/auth.php';
requireEditorOrAdmin(); 
require __DIR__ . '/../../includes/conexion.php';

$csrf = ensureCsrfToken();

// Obtener la dirección del editor desde la sesión
$id_direccion = (int)($_SESSION['id_direccion'] ?? 0);

// Bloqueo de dirección 4
if (isset($_SESSION['id_direccion']) && (int)$_SESSION['id_direccion'] === 4) {
    header("Location: /dgmoss/admin/panel.php");
    exit;
}// Bloqueo de dirección 4
if (isset($_SESSION['id_direccion']) && (int)$_SESSION['id_direccion'] === 4) {
    header("Location: /dgmoss/admin/panel.php");
    exit;
}

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// 🔹 Consulta actualizada — ahora directamente desde la tabla categorias
$sql = "SELECT c.id_categoria, c.nombre_categoria, dir.nombre_direccion
        FROM categorias c
        INNER JOIN direcciones dir ON dir.id_direccion = c.id_direccion
        WHERE c.id_direccion = ?
        ORDER BY c.nombre_categoria
        LIMIT ? OFFSET ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("iii", $id_direccion, $limit, $offset);
$stmt->execute();
$categorias = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// 🔹 Total de categorías para la paginación
$total_sql = "SELECT COUNT(*) as total FROM categorias WHERE id_direccion = ?";
$stmt_total = $conexion->prepare($total_sql);
$stmt_total->bind_param("i", $id_direccion);
$stmt_total->execute();
$total_categorias = $stmt_total->get_result()->fetch_assoc()['total'];
$stmt_total->close();

$total_pages = ceil($total_categorias / $limit);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Categorías</title>
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
    <link rel="stylesheet" href="/dgmoss/assets/css/formulario-direcciones.css">
    <?php include(__DIR__ . '/../../admin/navbar_panel.php'); ?>
</head>
<body>
<div class="container">

    <div class="mb-3">
        <a class="btn btn-secondary btn-sm active" href="/dgmoss/admin/panel.php">
            <i class="bi bi-arrow-left"></i> Regresar
        </a>
    </div>

    <h3 class="mb-3 text-center">Categorías</h3>

    <div class="mb-3">
        <a class="btn btn-danger btn-sm active" href="/dgmoss/admin/categorias/crear_categoria.php">
            <i class="bi bi-plus-circle"></i> Crear categoría
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">Categoría</th>
                    <th class="text-center">Dirección</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $cat): ?>
                    <tr>
                        <td class="text-center"><?= htmlspecialchars($cat['nombre_categoria']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($cat['nombre_direccion']) ?></td>
                        <td class="text-center">
                            <a class="btn btn-danger btn-sm active" href="/dgmoss/admin/categorias/editar_categoria.php?id_categoria=<?= (int)$cat['id_categoria'] ?>">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>
                            <form action="/dgmoss/admin/categorias/eliminar_categoria.php" method="post" class="d-inline" onsubmit="return confirm('¿Eliminar esta categoría?');">
                                <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                                <input type="hidden" name="id_categoria" value="<?= (int)$cat['id_categoria'] ?>">
                                <button class="btn btn-primary btn-sm active">
                                    <i class="bi bi-trash-fill"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($categorias)): ?>
                    <tr><td colspan="3" class="text-center">No hay categorías disponibles</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <ul class="pagination">
        <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Anterior">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <li class="page-item <?= $page == $total_pages || $total_categorias == 0 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Siguiente">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>

</div>

<script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
<script src="/dgmoss/assets/js/formulario.js"></script>
</body>
</html>
