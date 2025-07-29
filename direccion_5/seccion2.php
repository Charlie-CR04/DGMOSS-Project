<?php
    //1. Conectar a la base de datos
    include(__DIR__ . '/../sign-in/conexion.php');

    //2. Escribir la consulta SQL 
    $sql = "SELECT c.id_categoria, c.nombre_categoria, d.titulo, d.url
            FROM categorias c
            JOIN documentos d ON c.id_categoria = d.id_categoria
            WHERE d.id_direccion = ? AND d.estado = 'Activo'
            ORDER BY c.nombre_categoria, d.titulo";

    //3. Preparamos la consulta 
    $stmt = $conexion->prepare($sql);

    //4. Definimos el id de la dirección y enlazamos el parámetro
    $id_direccion = 5;
    $stmt->bind_param("i",$id_direccion);

    //5. Ejecutamos la consulta
    $stmt->execute();

    //6. Obtenemos resultados
    $resultado = $stmt->get_result();
                        
    //7. Organizamos los resultados
    $categorias = []; //Creamos un arreglo vacío donde iremos guardando la información organizada por categoría

    while($row = $resultado->fetch_assoc()){ //Esta funcion convierte cada fila en un arreglo con cada parametro: id_categoria,nombre_categoria,titulo,url
        $id_categoria = $row['id_categoria'];
        $nombre_categoria = $row['nombre_categoria'];
        $titulo_documento = $row['titulo'];
        $url = $row['url'];

        //Verificamos si esa categoría ya fue registrada en el arreglo $categorias, sino la agregamos.
        if(!isset($categorias[$id_categoria])){
            //Creamos un nuevo bloque para esa categoría, esta tendrá nombre y una lista vacía de documentos que se llenarán despúes
            $categorias[$id_categoria] = [
            'nombre' => $nombre_categoria,
            'documentos' => []
            ];
        }
        
        //Añadimos el documento actual(título + url) al arreglo de documentos de esa categoría
        $categorias[$id_categoria]['documentos'][] = [
        'titulo' => $row['titulo'],
        'url' => $row['url']
        ];
    }
    //Cerramos la consulta para liberar recursos
    $stmt->close();
?>

<section class="section-publicaciones">
    <h3 class="text-center">Documentos y Publicaciones</h3>
    <div class="container">

        <div class="filtros-container">
            <!--    Dropdowns   -->
            <?php foreach ($categorias as $categoria): ?>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
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