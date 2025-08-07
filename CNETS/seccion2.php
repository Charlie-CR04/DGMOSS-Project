<?php 
    include(__DIR__ . '/../sign-in/conexion.php');

    $sql = "SELECT c.id_categoria, c.nombre_categoria, d.titulo, d.url  
            FROM categorias c
            JOIN documentos d ON c.id_categoria = d.id_categoria
            WHERE id_direccion = ? AND d.estado = 'Activo'
            ORDER BY c.nombre_categoria, d.titulo";

    $stmt = $conexion->prepare($sql);
    $id_direccion = 6;
    $stmt->bind_param("i",$id_direccion);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $categorias = [];

    while($row = $resultado->fetch_assoc()){
        $id_categoria = $row['id_categoria'];
        $nombre_categoria = $row['nombre_categoria'];

        if(!isset($categorias[$id_categoria])){
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
?>

<section class="section-publicaciones mb-5">
    <h3 class="text-center">Menú de documentos</h3>
    <div class="container">

        <div class="filtros-container">
            <?php foreach($categorias as $categoria): ?>
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
        </div>
    </div>
</section>