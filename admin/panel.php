<?php
    session_start(); // 1. Iniciar sesión o recupera una sesión activa
    // 2. Si no hay un usuario logueado (no existe $_SESSION['nombre']), redirige al login
    if(!isset($_SESSION['nombre_usuario'])){ 
        header("Location: /dgmoss-project/sign-in/Index_login.php"); // 3. Redirige si no hay sesión
        exit(); // 4. Detiene el script por seguridad
    }
    include(__DIR__ . '/../sign-in/conexion.php'); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="/dgmoss-project/assets/css/panel.css">
        <!-- GOB -->
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
    <script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
</head>
<body>
    <?php include(__DIR__ . '/../admin/navbar_panel.php');?>
    <main class="panel-direcciones-container mt-5">
        <h1 class="titulo-panel">Panel de Administración</h1>
        <p class="text-center mb-5">Selecciona una opción:</p>
        <div class="container seccion-admin">
            <div class="row">
                <div class="col-6 tarjet">
                    <a href="/dgmoss-project/admin/usuarios.php" class="text-decoration-none text-black">
                        <img src="/dgmoss-project/assets/img/banners/2.png" alt="Panel usuarios" class="img-fluid">
                        <h5 class="text-center mt-5">Usuarios</h5>
                    </a>
                </div>
                <div class="col-6 tarjet" >
                    <a href="/dgmoss-project/admin/direcciones.php" class="text-decoration-none text-black">
                        <img src="/dgmoss-project/assets/img/banners/1.png" alt="Panel de direcciones" class="img-fluid">
                        <h5 class="text-center mt-5">Direcciones</h5>
                    </a>
                </div>
            </div>
        </div>
    </main>

</body>
</html>