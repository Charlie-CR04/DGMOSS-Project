<?php
session_start();
if (isset($_SESSION['nombre_usuario'])) {
    header("Location: /dgmoss-project/admin/panel.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="/dgmoss-project/assets/css/sign-in.css">
        <!-- GOB -->
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
    <script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
</head>
<body>
    <?php 
        include(__DIR__ . "/../includes/header_dir.php");
    ?>
    
    <div class="sign-in">

        <?php  include(__DIR__ . "/../sign-in/login.php");?>

        <h2 class="text-center mb-4">Iniciar Sesión</h2>

        <!-- Mostrar errores -->
         <?php if(!empty($_error)) : ?>
            <div class="alert alert-danger"><?= $_error ?></div>
        <?php endif; ?>

        <form method="POST" autocomplete="off" class="formulario">
            <!--Usuario-->
            <div class="item-sesion">
                <label for="inputEmail" class="form-label">Correo Electrónico</label>
                <input type="text" class="form-control" name="correo" id="inputEmail" autocomplete="off" placeholder="Ejemplo:email@example.com">
            </div>
            <!--Contraseña-->
            <div class="item-sesion">
                <label for="inputPassword" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="contraseña" id="inputPassword" autocomplete="off" placeholder="Ingresa tu contraseña">
            </div>
            <!--Iniciar-->
            <button type="submit" name="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>
    </div>


</body>
</html>