<?php 
    //1. Iniciar sesión para tener acceso a las variables de sesión
    session_start();

    //2. Eliminar todas las variables de sesión
    session_unset();

    //3. Destruir completamente la sesión
    session_destroy();

    //4. Redirigir al inicio del sitio
    header("Location: /dgmoss-project/Index.php");
    exit(); //Detiene cualquier ejecución adicional 
?>