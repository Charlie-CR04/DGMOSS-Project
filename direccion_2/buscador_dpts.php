<?php
// buscador_dpts.php

include(__DIR__ . '/../sign-in/conexion.php');

// Sanitizamos la búsqueda
$busqueda = trim($_GET['q'] ?? '');

if (empty($busqueda)) {
    echo "<p>No se ingresó ningún término de búsqueda.</p>";
    exit;
}

$id_direccion = 2; // Dirección de DPTS

// Consulta segura con LIKE
$sql = "SELECT titulo, descripcion, url 
        FROM documentos 
        WHERE id_direccion = ? 
          AND estado = 'Activo' 
          AND titulo LIKE ?";

$stmt = $conexion->prepare($sql);
$like = "%$busqueda%";
$stmt->bind_param("is", $id_direccion, $like);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0): ?>
    <?php while ($doc = $result->fetch_assoc()): ?>
        <div class="resultado-item mb-3 p-3 border rounded shadow-sm">
            <h5><?= htmlspecialchars($doc['titulo']) ?></h5>
            <p><?= htmlspecialchars($doc['descripcion']) ?></p>
            <a href="<?= htmlspecialchars($doc['url']) ?>" target="_blank" class="btn btn-sm btn-primary">
                Ver Documento
            </a>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No se encontraron documentos con ese nombre.</p>
<?php endif;

$stmt->close();
?>