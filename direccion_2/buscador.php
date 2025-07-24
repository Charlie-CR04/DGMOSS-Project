<?php
    require_once(__DIR__ . "/../sign-in/conexion.php");

    $termino2 = $_GET['q'] ?? '';
    $termino2 = trim($termino2);

    if($termino2 === '') {
        exit;
    }

    $id_direccion2 = 2; //Dirección de Politicas 

    $sql2 ="SELECT titulo, descripcion, url
            FROM documentos
            WHERE id_direccion = ?
            AND estado = 'Activo'
            AND titulo LIKE CONCAT('%', ?, '%')
            ORDER BY fecha_publicacion DESC";
    
    $stmt = $conexion->prepare($sql2);
    $stmt->bind_param("is",$id_direccion2,$termino2);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0): ?>
    <?php while ($doc = $resultado->fetch_assoc()): ?>
        <div class="resultado-item mb-3 p-3 border rounded shadow-sm">
            <h5> <?= htmlspecialchars($doc['titulo']) ?> </h5>
            <p> <?= htmlspecialchars($doc['descripcion']) ?> </p>
            <a href=" <?= htmlspecialchars($doc['url']) ?>" target="_blank" class="btn btn-sm btn-primary">
                Ver Documento
            </a>
        </div>
    <?php endwhile; ?>
    <?php else: ?>
        <p>No se encontraron documentos con ese nombre.</p>
    <?php endif;

    $stmt->close();
    ?>