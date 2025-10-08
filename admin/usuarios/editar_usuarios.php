<?php
require __DIR__ . '/../../includes/auth.php';
requireAdmin();
require __DIR__ . '/../../includes/conexion.php';

$id_usuario = (int)($_GET['id_usuario'] ?? 0);

//Obtener los datos del usuario al editar
$st = $conexion->prepare("SELECT * FROM usuarios WHERE id_usuario=?");
$st->bind_param("i", $id_usuario);
$st->execute();
$usuario = $st->get_result()->fetch_assoc();
$st->close();

if(!$usuario) {
    header('Location: /dgmoss-project/admin/usuarios/usuarios.php');
    exit;
}

//Obtener las direcciones para el dropdown
$dirs = $conexion->query("SELECT id_direccion, nombre_direccion FROM direcciones ORDER BY nombre_direccion")->fetch_all(MYSQLI_ASSOC);

$csrf = ensureCsrfToken();
$error = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    verifyCsrfOrDie();

    $nombre = trim($_POST['nombre_usuario'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $pwd = trim($_POST['contraseña'] ?? '');
    $rol = $_POST['rol'] ?? 'editor';
    $id_direccion = trim($_POST['id_direccion'] ?? '') ? (int)$_POST['id_direccion'] : null;

    if($rol === 'admin'){
        $id_direccion = null;
    }

    if($rol !== 'admin'){
        $query_admin = "SELECT COUNT(*) AS total FROM usuarios WHERE rol='admin'";
        $countAdmins = $conexion->query($query_admin)->fetch_assoc()['total'] ?? 0;
        
        if((int)$countAdmins === 1 && $usuario['rol'] === 'admin'){
            $error = "No puedes cambiar el rol del último administrador";
        }
    }

    if($nombre === '' || $correo === ''){
        $error = "El nombre y el correo son obligatorios";
    } elseif(!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "Correo inválido";
    } elseif ($rol === 'editor' && empty($id_direccion)) {
        $error = "Para un editor, debes seleccionar una dirección";
    } 
    
        if($error === "") {
        //Verificar si el correo ya existe en otro usuario
        $st = $conexion->prepare("SELECT 1 FROM usuarios WHERE correo=? AND id_usuario<>? LIMIT 1");
        $st->bind_param("si",$correo ,$id_usuario);
        $st->execute();
        $exists = $st->get_result()->num_rows > 0;
        $st->close();

        if($exists){
            $error = "Ya existe un usuario con ese correo";
        } else {
            //Actualizar usuario en la BD
            if($pwd != ''){
                $hash = password_hash($pwd, PASSWORD_DEFAULT);
                $st = $conexion->prepare("UPDATE usuarios 
                                        SET nombre_usuario=?, correo=?, contraseña=?, rol=?, id_direccion=?
                                        WHERE id_usuario=?");
                $st->bind_param("ssssii", $nombre, $correo, $hash, $rol, $id_direccion,$id_usuario);
            } else {
                $st = $conexion->prepare("UPDATE usuarios 
                                        SET nombre_usuario=?, correo=?, rol=?, id_direccion=?
                                        WHERE id_usuario=?");
                $st->bind_param("sssii", $nombre, $correo, $rol,$id_direccion , $id_usuario);
            }
            $st->execute();
            $st->close();

            header('Location: /dgmoss-project/admin/usuarios/usuarios.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
    <link rel="stylesheet" href="/dgmoss-project/assets/css/crear-usuarios.css">
</head>
<body>
    <?php include(__DIR__ . '/../navbar_panel.php'); ?>

    <div class="container mt-4">
        <h2 class="text-center">Editar Usuario</h2>

        <?php if($error): ?> 
            <div class="alert alert-danger">
                <?= htmlspecialchars($error)?>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre_usuario" class="form-control" value="<?= htmlspecialchars($usuario['nombre_usuario']) ?>" required>
            </div>

            <div class="form-group">
                <label>Correo</label>
                <input name="correo" type="email" class="form-control" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Contraseña (dejar vacío para no cambiar)</label>
                <div class="password-container">
                    <input id="passwordField" name="contraseña" type="password" class="form-control" minlength="8" title="La contraseña debe tener al menos 8 caracteres">
                    <button type="button" class="toggle-password" id="togglePassword">
                        <i class="bi bi-eye-slash-fill" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label>Rol</label>
                <select name="rol" class="form-control" id="rolSelect">
                    <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="editor" <?= $usuario['rol'] === 'editor' ? 'selected' : '' ?>>Editor</option>
                </select>
            </div>

            <div class="form-group" id="dirWrap">
                <label>Dirección (Solo para editores)</label>
                <select name="id_direccion" class="form-control">
                    <option value="">-- Selecciona --</option>
                    <?php foreach($dirs as $dir): ?>
                        <option value="<?= (int)$dir['id_direccion'] ?>" <?= $dir['id_direccion'] == $usuario['id_direccion'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dir['nombre_direccion']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button class="btn btn-success">Guardar cambios</button>
            <a class="btn btn-secondary" href="/dgmoss-project/admin/usuarios/usuarios.php">Cancelar</a>
        </form>
    </div>
    <script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
    <script src="/dgmoss-project/assets/js/form-usuarios.js"></script>
    <script src="/dgmoss-project/assets/js/show-password.js"></script>
</body>
</html>