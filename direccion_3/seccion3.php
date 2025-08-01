<?php
    include(__DIR__ . '/../sign-in/conexion.php');

    $id_direccion = 3;

    $sql = "SELECT titulo, descripcion, url, imagen_destacada
            FROM documentos
            WHERE id_direccion = ? AND estado = 'Activo' AND destacado = '1'";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i",$id_direccion);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $docs_destacados = [];

    while ($row = $resultado->fetch_assoc()) {
        $docs_destacados[] = $row;
    }

    $stmt->close();
?>





<section class="publicaciones">
    <h3 class="mt-5 mb-5 text-center">Publicaciones Destacadas</h3>
    <div class="container-publicaciones-destacadas">
        <div class="row g-4 justify-content-center">
            <?php if(!empty($docs_destacados)): ?>
                <?php foreach($docs_destacados as $doc): ?>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <a href=" <?= htmlspecialchars($doc['url']) ?>" target="_blank" style="text-decoration: none; color: inherit;">
                            <div class="card h-100 shadow-sm card-destacada">
                                <img src="<?= htmlspecialchars($doc['imagen_destacada']) ?>" alt="Imagen destacada" class="card-img-top"> 
                                <div class="card-body">
                                    <h5 class="card-title text-center"> <?= htmlspecialchars($doc['titulo']) ?> </h5>
                                    <p class="card-text"> <?= htmlspecialchars($doc['descripcion']) ?> </p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay publicaciones destacadas por el momento. </p>
            <?php endif; ?>
        </div>
    </div>
</section>