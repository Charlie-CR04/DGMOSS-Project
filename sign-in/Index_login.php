<?php
    session_start();

    if (isset($_SESSION['nombre_usuario'])) {
        header("Location: /dgmoss-project/admin/panel.php");
        exit();
    }

    include(__DIR__ . "/../sign-in/login_back.php");
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

        <form method="POST" autocomplete="off" class="formulario">
            <h2 class="text-center mb-5 mt-0">Iniciar Sesión</h2>
            <!--Usuario-->
            <div class="item-sesion">
                <label for="inputEmail" class="form-label">Correo electrónico</label>
                <input type="mail" class="form-control" name="correo" id="inputEmail" autocomplete="off" placeholder="Ejemplo: email@salud.com">
            </div>
            <!--Contraseña-->
            <div class="item-sesion">
                <label for="inputPassword" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="contraseña" id="inputPassword" required minlength="8">
            </div>
            <!--Iniciar-->
            <button type="submit" name="submit" class="btn btn-primary w-100">Ingresar</button>
            <!-- Mostrar errores -->
            <?php if(!empty($_error)) : ?>
                <div class="alert alert-danger mt-5"><?= htmlspecialchars($_error) ?></div>
            <?php endif; ?>

        </form>
    </div>


</body>
</html>