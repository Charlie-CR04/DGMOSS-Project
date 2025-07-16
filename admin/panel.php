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
    <title>Formulario</title>
    <link rel="stylesheet" href="/dgmoss-project/assets/css/panel.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <?php include(__DIR__ . '/../admin/navbar_panel.php'); // 6. Incluye barra de navegación ?>
</head>
<body>

    <main class="panel-direcciones-container">
        <h1 class="titulo-panel">Selecciona una dirección para administrar documentos</h1>
        <div class="grid-direcciones">
            <?php 
                // 7. Consulta SQL para obtener direcciones
                $sql = "SELECT id_direccion, nombre_direccion FROM direcciones";
                $result = $conexion->query($sql);
                
                // 8. Manejo básico de errores en consulta
                if ($result === false) {
                    die("Error al cargar las direcciones. Intenta más tarde.");
                }
            ?>
            <?php if($result): // 9. Si la consulta fue exitosa ?>
                <?php if($result->num_rows > 0): // 10. Si hay resultados ?>
                    <?php while($row = $result->fetch_assoc()): // 11. Iterar sobre cada fila ?>
                        <a href="documentos.php?dir=<?php echo $row['id_direccion']; ?>" class="card-dir">
                            <i class="fas fa-folder icon-dir"></i>
                            <!-- 12. Escapar output para prevenir XSS -->
                            <span><?php echo htmlspecialchars($row['nombre_direccion']); ?></span>
                        </a>
                    <?php endwhile; ?>
                <?php else: // 13. Si no hay resultados ?>
                    <p>No hay direcciones disponibles en este momento</p>
                <?php endif; ?>
            <?php else: // 14. Si hubo error en la consulta ?>
                <p>Error al ejecutar la consulta</p>
            <?php endif; ?>
        </div>
    </main>
    
    <?php 
        // 15. Cerrar conexión a BD (liberar recursos)
        $conexion->close(); 
    ?>
</body>
</html>