<?php
require __DIR__ . '/../../includes/auth.php';
requireAdmin();
require __DIR__ . '/../../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("Método no permitido");
}

verifyCsrfOrDie();

$id_usuario = (int)($_POST['id_usuario'] ?? 0);

if ($id_usuario <= 0) {
    http_response_code(400);
    exit("ID inválido");
}

// Buscar el usuario a eliminar
$st = $conexion->prepare("SELECT rol FROM usuarios WHERE id_usuario=?");
$st->bind_param("i", $id_usuario);
$st->execute();
$user = $st->get_result()->fetch_assoc();
$st->close();

if (!$user) {
    http_response_code(404);
    exit("Usuario no encontrado");
}

// Si es un admin, verificar que no sea el último
if ($user['rol'] === 'admin') {
    $totalAdmins = $conexion->query("SELECT COUNT(*) AS total FROM usuarios WHERE rol='admin'")->fetch_assoc()['total'] ?? 0;

    if ((int)$totalAdmins === 1) {
        // Es el último admin
        header("Location: /dgmoss-project/admin/usuarios/usuarios.php?error=" . urlencode("No puedes eliminar al último administrador del sistema."));
        exit;
    }
}

// Si no es el último admin, eliminar
$st = $conexion->prepare("DELETE FROM usuarios WHERE id_usuario=?");
$st->bind_param("i", $id_usuario);
$st->execute();
$st->close();

header('Location: /dgmoss-project/admin/usuarios/usuarios.php?success=' . urldecode("Usuario eliminado correctamente."));
exit;
?>