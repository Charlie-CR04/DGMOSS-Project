<?php


function getConfigDireccion(mysqli $db,int $id_direccion): array {
    $sql = "SELECT permite_docs, max_destacados, show_all_docs
    FROM direcciones
    WHERE id_direccion = ? LIMIT 1";

    $stmt = $db->prepare($sql);
    $stmt->bind_param("i",$id_direccion);
    $stmt->execute();
    $cfg = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if(!$cfg) {
        $cfg = ['permite_docs' => '1', 'max_destacados' => 3, 'show_all_docs' => '0 '];
    }

    $cfg['permite_docs'] = (string)$cfg['permite_docs'];
    $cfg['max_destacados'] = (int)$cfg['max_destacados'];
    $cfg['show_all_docs'] = (string)$cfg['show_all_docs'];

    return $cfg;
}