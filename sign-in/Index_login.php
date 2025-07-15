<?php
session_start();
if (isset($_SESSION['nombre'])) {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/dgmoss-project/assets/css/style.css">
    <link rel="stylesheet" href="/dgmoss-project/assets/css/style_dir.css">
    <link rel="stylesheet" href="/dgmoss-project/assets/css/sign-in.css">
</head>
<body>
    <?php
        include(__DIR__ . "/../direcciones/header_dir.php");
    ?>
    
    <div class="sign-in">

    <?php  include(__DIR__ . "/../sign-in/login.php");?>

        <h2 class="text-center mb-4">Iniciar Sesión</h2>

        <!-- Mostrar errores -->
         <?php if(!empty($_error)) : ?>
            <div class="alert alert-danger"><?= $_error ?></div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
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

    <?php include(__DIR__ . "/../includes/footer.php"); ?>
</script>


</body>
</html>