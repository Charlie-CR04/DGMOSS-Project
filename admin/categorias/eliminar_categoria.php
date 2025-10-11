<?php
require __DIR__ . '/../../includes/auth.php';
requireEditorOrAdmin();
require __DIR__ . '/../../includes/conexion.php';

verifyCsrfOrDie();

$id_categoria = (int)($_POST['id_categoria'] ?? 0);
$rol = $_SESSION['rol'];
$id_direccion_editor = (int)($_SESSION['id_direccion'] ?? 0);

if ($id_categoria <= 0) {
    die("Categoría inválida.");
}

//  Verificar que la categoría exista y, si es editor, que pertenezca a su dirección
$sql = "SELECT id_categoria, id_direccion, nombre_categoria FROM categorias WHERE id_categoria = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_categoria);
$stmt->execute();
$categoria = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$categoria) {
    die("La categoría no existe.");
}

//  Si es editor, no puede eliminar categorías fuera de su dirección
if ($rol === 'editor' && $categoria['id_direccion'] !== $id_direccion_editor) {
    die("No tienes permiso para eliminar esta categoría.");
}

//  Verificar si hay documentos asociados a esta categoría
$checkDocs = $conexion->prepare("SELECT COUNT(*) AS total FROM documentos WHERE id_categoria = ?");
$checkDocs->bind_param("i", $id_categoria);
$checkDocs->execute();
$total_docs = $checkDocs->get_result()->fetch_assoc()['total'];
$checkDocs->close();

if ($total_docs > 0) {
    //  No se elimina si tiene documentos relacionados
    $_SESSION['error'] = "No se puede eliminar la categoría '{$categoria['nombre_categoria']}' porque tiene documentos asociados.";
} else {
    // 🔹 Eliminar categoría
    $delete = $conexion->prepare("DELETE FROM categorias WHERE id_categoria = ?");
    $delete->bind_param("i", $id_categoria);
    $delete->execute();
    $delete->close();

    session_start();
    $_SESSION['success'] = "Categoría eliminada correctamente.";
}

//  Redirigir según el rol
if ($rol === 'admin') {
    header("Location: /dgmoss-project/admin/categorias/index_admin.php");
} else {
    header("Location: /dgmoss-project/admin/categorias/index_editor.php");
}
exit;
?>
