<?php
session_start();
if(!isset($_SESSION['nombre'])){
    header("Location: /dgmoss-project/sign-in/Index_login.php");
    exit();
}

require_once(__DIR__ . '/../sign-in/conexion.php');

$id_direccion = $_GET['dir'] ?? null;
if(!$id_direccion || !is_numeric($id_direccion)){
    die("ID de dirección no válido");
}

// Obtener nombre de la dirección
$sql_direccion = "SELECT nombre FROM direcciones WHERE id_direccion = ?";
$stmt = $conexion->prepare($sql_direccion);
$stmt->bind_param("i", $id_direccion);
$stmt->execute();
$resultado = $stmt->get_result();

if($resultado->num_rows !== 1){
    die("Dirección no encontrada");
}
$nombre_direccion = $resultado->fetch_assoc()['nombre'];

// Obtener documentos de esa dirección
$sql_doc = "SELECT * FROM documentos WHERE id_direccion = ?";
$stmt_doc = $conexion->prepare($sql_doc);
$stmt_doc->bind_param("i", $id_direccion);  // CORREGIDO: antes tenía mal el bind
$stmt_doc->execute();
$docs = $stmt_doc->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos - <?= htmlspecialchars($nombre_direccion) ?></title>
    <link rel="stylesheet" href="/dgmoss-project/assets/css/panel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<?php include(__DIR__ . '/../admin/navbar_panel.php'); ?>

<main class="documentos-panel-container">
    <h1 class="titulo-panel">Documentos de: <?= htmlspecialchars($nombre_direccion) ?></h1>

    <a href="agregar_documento.php?dir=<?= $id_direccion ?>" class="btn-agregar">
        <i class="fas fa-plus"></i> Agregar documento
    </a>

    <?php if ($docs->num_rows > 0): ?>
        <div class="tabla-documentos">
            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Destacado</th>
                        <th>Publicado</th>
                        <th>Actualizado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($doc = $docs->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($doc['titulo']) ?></td>
                        <td><?= htmlspecialchars($doc['categoria']) ?></td>
                        <td><?= htmlspecialchars($doc['estado']) ?></td>
                        <td><?= $doc['destacado'] ? 'Sí' : 'No' ?></td>
                        <td><?= $doc['fecha_publicacion'] ?></td>
                        <td><?= $doc['actualizacion'] ?></td>
                        <td>
                            <a href="<?= htmlspecialchars($doc['url']) ?>" target="_blank" class="btn-ver">Ver</a>
                            <a href="editar_documento.php?id=<?= $doc['id_documento'] ?>" class="btn-editar">Editar</a>
                            <a href="eliminar_documento.php?id=<?= $doc['id_documento'] ?>" class="btn-eliminar" onclick="return confirm('¿Seguro que deseas eliminar este documento?')">Eliminar</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="no-docs">No hay documentos registrados para esta dirección.</p>
    <?php endif; ?>
</main>

</body>
</html>
