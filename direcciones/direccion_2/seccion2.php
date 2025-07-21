<?php
include(__DIR__ . '/../../sign-in/conexion.php');

$sql2 = "SELECT c.id_categoria, c.nombre_categoria, d.titulo, d.url
         FROM categorias c
         JOIN documentos d ON c.id_categoria = d.id_categoria
         WHERE id_direccion = ? AND d.estado = 'Activo'
         ORDER BY c.nombre_categoria, d.titulo";

$stmt2 = $conexion->prepare($sql2);
$id_direccion2 = 2;
$stmt2->bind_param("i", $id_direccion2);
$stmt2->execute();
$resultado2 = $stmt2->get_result();

$categorias2 = [];

while ($row2 = $resultado2->fetch_assoc()) {
    $id_categoria2 = $row2['id_categoria'];
    $nombre_categoria2 = $row2['nombre_categoria'];
    
    // Si la categoría no ha sido registrada aún
    if (!isset($categorias2[$id_categoria2])) {
        $categorias2[$id_categoria2] = [
            'nombre' => $nombre_categoria2,
            'documentos' => []
        ];
    }

    // Agregamos el documento a esa categoría
    $categorias2[$id_categoria2]['documentos'][] = [
        'titulo' => $row2['titulo'],
        'url' => $row2['url']
    ];
}

$stmt2->close();
?>


<section class="section-dpts-publicaciones">
    <h3 class="text-center">Documentos y Publicaciones</h3>
    <div class="container">

        <div class="filtros-dpts-container">
            <!--    Dropdowns   -->
            <?php foreach($categorias2 as $categoria2): ?>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <?= htmlspecialchars($categoria2['nombre']) ?>
                    </button>
                    <ul class="dropdown-menu">
                        <?php foreach($categoria2['documentos'] as $doc2): ?>
                            <li>
                                <a class="dropdown-item" href="<?= htmlspecialchars($doc2['url']) ?>" target="_blank">
                                    <?= htmlspecialchars($doc2['titulo']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>

            <!--    Buscador    -->
            <input type="text" id="input-busqueda-dpts" class="form-control" placeholder="Buscar documento...">
        </div>

        <div id="resultados-busqueda-dpts"></div>
    </div>
</section>