<?php
    //1. Conectar a la base de datos
    include(__DIR__ . '/../includes/conexion.php');

    //2. Escribir la consulta SQL 
    $sql = "SELECT c.id_categoria, c.nombre_categoria, d.titulo, d.url
            FROM categorias c
            JOIN documentos d 
              ON c.id_categoria = d.id_categoria
             AND c.id_direccion = d.id_direccion
            WHERE c.id_direccion = ? 
              AND d.estado = 'Activo'
            ORDER BY c.nombre_categoria, d.titulo";

    //3. Preparamos la consulta 
    $stmt = $conexion->prepare($sql);

    //4. Definimos el id de la dirección y enlazamos el parámetro
    $id_direccion = 5;
    $stmt->bind_param("i", $id_direccion);

    //5. Ejecutamos la consulta
    $stmt->execute();

    //6. Obtenemos resultados
    $resultado = $stmt->get_result();

    //7. Organizamos los resultados
    $categorias = [];

    while($row = $resultado->fetch_assoc()) {
        $id_categoria = $row['id_categoria'];
        $nombre_categoria = $row['nombre_categoria'];

        if(!isset($categorias[$id_categoria])) {
            $categorias[$id_categoria] = [
                'nombre' => $nombre_categoria,
                'documentos' => []
            ];
        }

        $categorias[$id_categoria]['documentos'][] = [
            'titulo' => $row['titulo'],
            'url' => $row['url']
        ];
    }

    $stmt->close();
?>

<section class="section-publicaciones">
    <h3 class="text-center">Documentos y Publicaciones</h3>
    <div class="container">

        <div class="filtros-container">
            <!--    Dropdowns   -->
            <?php foreach ($categorias as $categoria): ?>
                <div class="dropdown">
                    <button class="btn btn-secondary w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <?= htmlspecialchars($categoria['nombre']) ?>
                    </button>
                    <ul class="dropdown-menu">
                        <?php foreach($categoria['documentos'] as $doc): ?>
                            <li>
                                <a class="dropdown-item" href="<?= htmlspecialchars($doc['url']) ?>" target="_blank">
                                    <?= htmlspecialchars($doc['titulo']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>

            <!--       Buscador     -->
            <input type="text" id="input-busqueda-dsd" class="form-control" placeholder="Buscar documento">
        </div>
        <!--    Resultados  -->
        <div id="resultados-busqueda-dsd"></div>
    </div>
</section>