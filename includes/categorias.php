<?php

function getCategorias(mysqli $db, int $id_direccion): array {
    $sql = "SELECT DISTINCT c.id_categoria, c.nombre_categoria 
            FROM categorias c
            JOIN documentos d ON d.id_categoria = c.id_categoria
            JOIN direcciones dir ON dir.id_direccion = d.id_direccion
            WHERE dir.id_direccion = ?
            ORDER BY c.nombre_categoria";

    //Preparar la consulta
    $st = $db->prepare($sql);
    $st->bind_param("i", $id_direccion);
    $st->execute();

    //Obtener resultados
    $result = $st->get_result();
    $cats = $result->fetch_all(MYSQLI_ASSOC);

    $st->close();

    return $cats;
}

