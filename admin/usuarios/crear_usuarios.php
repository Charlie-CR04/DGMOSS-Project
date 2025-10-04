<?php
require __DIR__ . '/../../includes/auth.php';
requireAdmin();
require __DIR__ . '/../../includes/conexion.php';

$csrf = ensureCsrfToken();
$query = "SELECT id_direccion, nombre_direccion FROM direcciones ORDER BY id_direccion";
$dirs = $conexion->query($query)->fetch_all(MYSQLI_ASSOC);

$error = "";
if($_SERVER['REQUEST_METHOD']==='POST') {
    verifyCsrfOrDie();


    $nombre = trim($_POST['nombre_usuario'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $pwd = trim($_POST['contraseña'] ?? '');
    $rol = $_POST['rol'] ?? 'editor';
    $id_direccion = trim($_POST['id_direccion']) ? (int)$_POST['id_direccion'] : null;

    if($nombre === '' || $correo === '' || $pwd === ''){
        $error = "Nombre, correo y contraseña son obligatorios";
    } elseif(strlen($pwd) < 8){
      $error = "La contraseña debe tener al menos 8 caracteres";  
    } elseif(!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "Correo inválido";
    } elseif($rol==='editor' && empty($id_direccion)){
        $error = "Para un editor debes seleccionar una dirección";
    } else {
        //Correo
        $st = $conexion->prepare("SELECT 1 FROM usuarios WHERE correo=? LIMIT 1");
        $st->bind_param("s", $correo);
        $st->execute();
        $exists = $st->get_result()->num_rows > 0;
        $st->close();

        if($exists) {
            $error = "Ya existe un usuario con ese correo";
        } else {
            $hash = password_hash($pwd, PASSWORD_DEFAULT);
            if($rol === 'admin'){
                $st = $conexion->prepare("INSERT INTO usuarios(nombre_usuario, correo, contraseña, rol, id_direccion)
                                            VALUES (?,?,?,?, NULL)");
                $st->bind_param("ssss", $nombre, $correo, $hash, $rol);
            } else {
                $st = $conexion->prepare("INSERT INTO usuarios(nombre_usuario, correo, contraseña, rol, id_direccion)
                                            VALUES (?,?,?,?,?)");
                $st->bind_param("ssssi", $nombre, $correo, $hash, $rol, $id_direccion);
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
  <meta charset="utf-8">
  <title>Crear usuario</title>
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
    <link rel="stylesheet" href="/dgmoss-project/assets/css/crear-usuarios.css">
    <script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
<body>
<?php include(__DIR__ . '/../navbar_panel.php'); ?>
<div class="container mt-4">
  <h2 class="text-center">Nuevo usuario</h2>
  
  <?php if ($error): ?>
    <div class="alert alert-danger">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <form method="post">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">

    <div class="form-group">
      <label>Nombre</label>
      <input name="nombre_usuario" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Correo</label>
      <input name="correo" type="email" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Contraseña</label>
      <div class="input-group">
        <input id="passwordField" name="contraseña" type="password" class="form-control" required minlength="8" title="La contraseña debe tener al menos 8 caracteres">
        <div class="input-group-append">
          <button type="button" class="btn btn-outline-secondary" id="togglePassword">
              <i class="bi bi-eye-slash-fill" id="eyeIcon"></i>
          </button>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label>Rol</label>
      <select name="rol" class="form-control" id="rolSelect">
        <option value="admin">Admin</option>
        <option value="editor">Editor</option>
      </select>
    </div>

    <div class="form-group" id="dirWrap">
      <label>Dirección (solo editores)</label>
      <select name="id_direccion" class="form-control">
        <option value="">-- Selecciona --</option>
        <?php foreach ($dirs as $d): ?>
          <option value="<?= (int)$d['id_direccion'] ?>"><?= htmlspecialchars($d['nombre_direccion']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <button class="btn btn-success">Crear</button>
    <a class="btn btn-secondary" href="/dgmoss-project/admin/usuarios/usuarios.php">Cancelar</a>

  </form>
</div>
<script src="/dgmoss-project/assets/js/form-usuarios.js"></script>
<script src="/dgmoss-project/assets/js/show-password.js"></script>
</body>
</html>
