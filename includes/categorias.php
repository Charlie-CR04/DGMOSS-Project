<?php

function getCategorias(mysqli $db, int $id_direccion): array {
    $sql = "SELECT id_categoria, nombre_categoria
            FROM categorias
            WHERE id_direccion = ?
            ORDER BY nombre_categoria";

    $st = $db->prepare($sql);
    $st->bind_param("i", $id_direccion);
    $st->execute();

    $result = $st->get_result();
    $cats = $result->fetch_all(MYSQLI_ASSOC);
    $st->close();

    return $cats;
}
