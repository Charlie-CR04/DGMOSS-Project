<?php
//Autenticación 
require __DIR__ . '/../../includes/auth.php';
requireAdmin();
require __DIR__ . '/../../includes/conexion.php';
require __DIR__ . '/../../includes/direcciones_config.php';

//Validaciones de entrada
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(405);
    exit("Método no permitido");
}
verifyCsrfOrDie();

//Tomamos el id del documento
$id_documento = (int)($_POST['id_documento'] ?? 0);
if($id_documento <= 0){
    http_response_code(400);
    exit("ID del documento es inválido");
}

//Helper: de ruta pública a ruta física del disco (XAMPP/Windows friendly)
function webPathToFs(?string $path): ?string {
    if (!$path) return null;
    $path = trim($path);

    // Si es URL remota, no se puede borrar del disco
    if (preg_match('#^https?://#i', $path)) return null;

    // Si ya viene como ruta absoluta de Windows (C:\... o D:/...)
    if (preg_match('#^[A-Za-z]:[\\\\/]#', $path)) return $path;

    // Si es ruta pública (/dgmoss-project/uploads/archivo.pdf)
    if (strpos($path, '/') === 0) {
        // DOCUMENT_ROOT suele ser C:\xampp\htdocs
        $docroot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/\\');
        // Unimos respetando separadores de Windows
        return $docroot . str_replace('/', DIRECTORY_SEPARATOR, $path);
    }

    // Si es algo tipo 'uploads/archivo.pdf' → asumimos relativo al docroot
    $docroot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/\\');
    return $docroot . DIRECTORY_SEPARATOR . ltrim($path, '/\\');
}

$conexion->begin_transaction();

try {
    // 5) Cargar documento para saber rutas y dirección
    $st = $conexion->prepare("SELECT id_direccion, url, imagen_destacada FROM documentos WHERE id_documento=?");
    $st->bind_param("i", $id_documento);
    $st->execute();
    $doc = $st->get_result()->fetch_assoc();
    $st->close();

    if (!$doc) {
        $conexion->rollback();
        http_response_code(404);
        exit('Documento no encontrado');
    }

    $id_direccion = (int)($doc['id_direccion'] ?? 0);

    // 6) Intentar borrar archivos del disco (no es fatal si falla)
    $pdfs = webPathToFs($doc['url'] ?? '');
    $img = webPathToFs($doc['imagen_destacada'] ?? '');

    foreach([$pdfs, $img] as $fsPath){
        if($fsPath && is_file($fsPath)){
            // si falla, seguimos; no rompemos toda la operación
            @unlink($fsPath);
        }
    }
    // 7) Borrar el registro en BD (borrado duro)
        $st = $conexion->prepare("DELETE FROM documentos WHERE id_documento=?");
        $st->bind_param("i", $id_documento);
        $st->execute();
        $st->close();

    $conexion->commit();

    // 8) Redirigir de vuelta al listado de esa dirección
    header('Location: /dgmoss-project/admin/direcciones/direcciones.php?id_direccion=' . $id_direccion);
    exit;

} catch(Throwable $e) {
    // 9) Cualquier fallo → revertimos
    $conexion->rollback();
    error_log($e->getMessage());

    http_response_code(500);
    exit('Error eliminando el documento');
}