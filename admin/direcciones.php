<?php
    session_start(); // 1. Iniciar sesión o recupera una sesión activa
    // 2. Si no hay un usuario logueado (no existe $_SESSION['nombre']), redirige al login
    if(!isset($_SESSION['nombre_usuario'])){ 
        header("Location: /dgmoss-project/sign-in/Index_login.php"); // 3. Redirige si no hay sesión
        exit(); // 4. Detiene el script por seguridad
    }
    include(__DIR__ . '/../sign-in/conexion.php'); // 5. Incluye archivo de conexión a BD
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de direcciones</title>
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
    <script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
    <?php include(__DIR__ . '/../admin/navbar_panel.php');?>
</head>
<body>
    
</body>
</html>