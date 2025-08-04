<?php
    include(__DIR__ . '/../sign-in/conexion.php');

    $sql = "SELECT d.titulo, d.descripcion, d.url, d.imagen_destacada, dir.nombre_direccion
            FROM documentos d
            JOIN direcciones dir ON d.id_direccion = dir.id_direccion
            WHERE d.destacado_home = '1' AND d.estado = 'Activo'
            ORDER BY d.titulo ASC";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $documentos = [];
    while($row = $resultado->fetch_assoc()){
        $documentos[] = $row;
    }

    $stmt->close();
?>

<section class="banner-docs py-5">
    <div class="container">
        <div class="col-12 p-4 docs-dir">
            <div class="row g-4">
                <!--    Panel Izquierdo -->
                <div class="col-md-4 d-flex flex-column justify-content-center p-4 panel-izq">
                    <h3 class="text-center mb-3">Documentos <br> DGMoSS</h3>
                    <p class="mb-4">Acceda a los documentos oficiales de cada dirección de la DGMoSS.</p>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                            Direcciones
                        </button>
                        <ul class="dropdown-menu w-150">
                            <li><a class="dropdown-item" href="/dgmoss-project/direccion_1/direccion1.php">Dirección General de Modernización <br> del Sector Salud</a></li>
                            <li><a class="dropdown-item" href="/dgmoss-project/direccion_2/direccion2.php">Dirección de Políticas de Tecnologías <br> para la Salud</a></li>
                            <li><a class="dropdown-item" href="/dgmoss-project/direccion_3/direccion3.php">Dirección de Desarrollo e Integración <br> de Medicina Basada en la Evidencia</a></li>
                            <li><a class="dropdown-item" href="/dgmoss-project/direccion_4/direccion4.php">Dirección de Gestión de Equipo <br> Médico</a></li>
                            <li><a class="dropdown-item" href="/dgmoss-project/direccion_5/direccion5.php">Dirección de Salud Digital</a></li>
                        </ul>
                    </div>
                </div>
                <!--    Panel derecho-->
                <div class="col-md-8">
                    <div id="carouselDocumentos" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner h-100">
                            <?php if(!empty($documentos)): ?>
                                <?php foreach(array_chunk($documentos,2) as $index => $grupo): ?>
                                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?> h-100">
                                        <div class="row h-100 g-3 p-3">
                                            <?php foreach($grupo as $doc): ?>
                                                <div class="col-lg-6 h-100">
                                                    <a href="<?= htmlspecialchars($doc['url']) ?>" target="_blank" class="document-card h-100 d-flex flex-column text-decoration-none text-dark">
                                                        <img src="<?= htmlspecialchars($doc['imagen_destacada']) ?>" class="img-fluid rounded mb-3" alt="<?= htmlspecialchars($doc['titulo']) ?>" style="height: 170px; object-fit:contain;">
                                                        <h6 class="text-center mb-2"> <?= htmlspecialchars($doc['titulo']) ?> </h6>
                                                    </a>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="carousel-item active">
                                    <div class="p-5 text-center">
                                        <p>No hay documentos para mostrar en este momento.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- Controles del carrusel -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselDocumentos" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselDocumentos" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>